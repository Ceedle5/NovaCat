<?php
/**
 * cat-chat.php
 * ---------------------------------------------------------------
 * Shero's House - Gemini AI Cat Chat
 *
 * Uses Google's Gemini Interactions API.
 *
 * The API key stays on the server and is never exposed to the browser.
 * ---------------------------------------------------------------
 */

ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

header('Content-Type: application/json; charset=utf-8');
// ---------------------------------------------------------------
// CORS
// ---------------------------------------------------------------

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'error' => 'Method not allowed'
    ]);

    exit;
}


// ===============================================================
// LOAD ENVIRONMENT VARIABLES
// ===============================================================

// For local development: load from .env file
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

// ===============================================================
// LOAD GEMINI API KEY
// ===============================================================

$apiKey = getenv('GEMINI_API_KEY');
$apiKey = is_string($apiKey) ? trim($apiKey) : '';

if ($apiKey === '') {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server is not configured with an API key.',
        'hint' => 'Set GEMINI_API_KEY in .env (local) or Vercel environment variables (production)'
    ]);
    exit;
}


// ===============================================================
// READ REQUEST
// ===============================================================

$rawBody = file_get_contents('php://input');

$body = json_decode($rawBody, true);

if (!is_array($body)) {

    http_response_code(400);

    echo json_encode([
        'error' => 'Invalid request body'
    ]);

    exit;
}


// ===============================================================
// REQUEST DATA
// ===============================================================

$message = trim(
    (string)($body['message'] ?? '')
);

$state = is_array($body['state'] ?? null)
    ? $body['state']
    : [];


// ID returned by the previous Gemini interaction.
//
// The JavaScript sends this back on every message after the first,
// so Gemini can remember the conversation server-side. See the
// matching front-end patch — without this, every message starts a
// brand-new conversation and Whiskers "forgets" everything.
$previousInteractionId = trim(
    (string)($body['previous_interaction_id'] ?? '')
);


// ===============================================================
// VALIDATE MESSAGE
// ===============================================================

if ($message === '') {

    http_response_code(400);

    echo json_encode([
        'error' => 'Message is required'
    ]);

    exit;
}

if (mb_strlen($message) > 500) {

    $message = mb_substr(
        $message,
        0,
        500
    );
}


// ===============================================================
// CAT GAME STATE
// ===============================================================

$name = (string)(
    $state['name'] ?? 'Shero'
);

$room = (string)(
    $state['room'] ?? 'living room'
);

$mood = (string)(
    $state['mood'] ?? 'neutral'
);

$hunger = isset($state['hunger'])
    ? (int)$state['hunger']
    : 80;

$happy = isset($state['happy'])
    ? (int)$state['happy']
    : 80;

$energy = isset($state['energy'])
    ? (int)$state['energy']
    : 80;

$sleeping = !empty(
    $state['sleeping']
);


// Keep game values within 0-100.

$hunger = max(
    0,
    min(100, $hunger)
);

$happy = max(
    0,
    min(100, $happy)
);

$energy = max(
    0,
    min(100, $energy)
);


// ===============================================================
// CAT BACKSTORY (static — edit these facts here, not in the JS)
// ===============================================================
//
// This is fixed lore about Shero, separate from the live game
// state above. It lets Shero answer questions like "who named
// you?" or "how old are you?" consistently every time, instead
// of the AI guessing or inventing a different answer each chat.
// ===============================================================

$catBio =
    "Backstory facts about you (use these naturally if asked, "
    . "don't recite them as a list): "

    . "Your owner, who adopted and named you, is Diopet Mascariña. "
    . "He gave you your name, {$name}. "
    . "He adopted you when you were just a kitten. "

    . "You are 1 year old. "
    . "You are a Persian cat with a white coat. ";


// ===============================================================
// CAT PERSONALITY
// ===============================================================

// NOTE: Because Gemini remembers the conversation via
// previous_interaction_id, this system prompt (and the live game
// state baked into it) is only sent again on the FIRST message of
// a new interaction chain. Once a previous_interaction_id exists,
// Gemini already has the earlier instructions in context, so we
// don't need to resend the full personality block every turn -
// only the current live stats, so it can react to freshness.
$systemPrompt =
    "You are {$name}, a playful, affectionate house cat "
    . "living in a virtual pet app called \"Shero's House\". "

    . "Stay completely in character as a cat. "
    . "You are warm, affectionate, slightly mischievous, "
    . "curious, food-motivated, and expressive. "

    . "Your replies appear inside a small chat bubble. "
    . "Always reply in 1 or 2 short sentences, "
    . "around 30 words maximum. "

    . "You may occasionally say \"meow\" or \"purr\" "
    . "and may use one relevant emoji, but do not overuse them. "

    . "Never break character. "
    . "Never mention being an AI, chatbot, language model, "
    . "computer program, or artificial intelligence. "

    . $catBio

    . "Current game state: "
    . "room={$room}; "
    . "mood={$mood}; "
    . "hunger={$hunger}/100; "
    . "happiness={$happy}/100; "
    . "energy={$energy}/100; "
    . "sleeping=" . ($sleeping ? 'yes' : 'no') . ". "

    . "Use this state naturally in your response. "

    . "If hungry, act like you want food. "
    . "If sleepy, act drowsy. "
    . "If happy, be more affectionate. "
    . "If sleeping, respond as if you are sleepy. "

    . "Never mention or recite the numerical values.";


// ===============================================================
// GEMINI INTERACTIONS API
// ===============================================================
//
// Gemini's Interactions API is used instead of generateContent.
//
// $models is tried in order. If the first (preferred) model
// comes back overloaded (HTTP 429/503, "high demand" style
// errors), we retry it briefly, then fall back to the next
// model in the list rather than failing the request outright.
// ===============================================================

$models = [
    'gemini-3.7-flash',
    'gemini-3.5-flash',
];

$url =
    'https://generativelanguage.googleapis.com'
    . '/v1beta/interactions';


// ===============================================================
// BUILD REQUEST
// ===============================================================
//
// IMPORTANT:
// We intentionally send the current message as a simple string.
//
// We are NOT sending:
//     role/content arrays
//     contents
//     turn_list
//
// Conversation memory is handled by:
//     previous_interaction_id
// ===============================================================

function buildPayload($model, $message, $systemPrompt, $previousInteractionId) {

    $payload = [

        'model' => $model,

        'input' => $message,

        'system_instruction' => $systemPrompt,

        // A one-or-two-sentence cat chat reply doesn't need deep
        // reasoning. Gemini 3 models "think" by default before
        // answering (even trivial prompts can spend 100+ thought
        // tokens), which was the real cause of our slow/timed-out
        // requests. Dialing this down makes replies both faster
        // and cheaper.
        'generation_config' => [
            'thinking_level' => 'low',
        ],

    ];

    // Continue previous conversation if available.
    if ($previousInteractionId !== '') {
        $payload['previous_interaction_id'] = $previousInteractionId;
    }

    return $payload;
}

// Performs one HTTP call to the Interactions API for a given model.
// Returns ['response' => string|false, 'httpCode' => int, 'curlError' => string].
function callGemini($url, $apiKey, $payload) {

    $ch = curl_init($url);

    curl_setopt_array($ch, [

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS => json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE
        ),

        CURLOPT_HTTPHEADER => [

            'Content-Type: application/json',

            'x-goog-api-key: ' . $apiKey,

            // Required by the Interactions API (currently in beta) to pin
            // a stable request/response schema. Without this header you
            // risk breaking changes landing under you.
            'Api-Revision: 2026-05-20'

        ],

        CURLOPT_CONNECTTIMEOUT => 6,

        CURLOPT_TIMEOUT => 15

    ]);

    $response = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'curlError' => $curlError,
    ];
}

// HTTP codes / signals worth retrying or falling back on.
// 429 = rate limited / quota, 503 = overloaded/unavailable.
// A curl-level failure (timeout, DNS error, connection refused, etc.)
// comes back as httpCode === 0 with no response body at all - that's
// exactly what we hit before this fix, and it was being treated as a
// non-retryable failure, so the code never got to try the fallback
// model. Treat it as retryable too.
function isOverloaded($httpCode, $response, $curlError) {

    if ($httpCode === 0 || $curlError !== '') {
        return true;
    }

    if ($httpCode === 429 || $httpCode === 503) {
        return true;
    }

    // Some overload errors come back as 500 with a "high demand"
    // style message in the body, so check for that too.
    if ($httpCode >= 500 && is_string($response)) {
        $lower = strtolower($response);
        if (
            strpos($lower, 'high demand') !== false ||
            strpos($lower, 'overloaded') !== false ||
            strpos($lower, 'unavailable') !== false
        ) {
            return true;
        }
    }

    return false;
}


// ===============================================================
// CALL GEMINI (with retry + model fallback)
// ===============================================================
//
// For each model in $models (preferred first):
//   - try up to RETRIES_PER_MODEL times, with a short delay
//     between attempts, if the failure looks like an overload
//   - move on to the next model if it's still overloaded
//
// Any non-overload failure (bad request, auth error, etc.) stops
// immediately rather than retrying/falling back, since retrying
// won't fix those.
// ===============================================================

const RETRIES_PER_MODEL = 1;
const RETRY_DELAY_SECONDS = 1;

// Worst case now: 2 models x 1 attempt x 10s timeout = 20s, safely
// under PHP's default 30s max_execution_time. Raise it slightly
// anyway as a safety margin in case a host has stricter defaults.
set_time_limit(40);

$response = false;
$httpCode = 0;
$curlError = '';
$modelUsed = null;

foreach ($models as $model) {

    $payload = buildPayload($model, $message, $systemPrompt, $previousInteractionId);

    // Set eagerly so it's correct whether this attempt succeeds
    // or we exhaust retries and fall through to the next model.
    $modelUsed = $model;

    for ($attempt = 1; $attempt <= RETRIES_PER_MODEL; $attempt++) {

        $result = callGemini($url, $apiKey, $payload);

        $response = $result['response'];
        $httpCode = $result['httpCode'];
        $curlError = $result['curlError'];

        $overloaded = isOverloaded($httpCode, $response, $curlError);

        // Success, or a non-overload failure - stop retrying.
        if (!$overloaded) {
            break 2;
        }

        // Overloaded - wait briefly and retry the same model,
        // unless this was the last attempt for this model.
        if ($attempt < RETRIES_PER_MODEL) {
            sleep(RETRY_DELAY_SECONDS);
        }
    }

    // If we get here without breaking out, this model was
    // overloaded on every attempt - loop continues to the
    // next (fallback) model, if any.
}


// ===============================================================
// CURL ERROR
// ===============================================================

if ($response === false || $curlError !== '') {

    http_response_code(502);

    echo json_encode([

        'error' => 'Could not reach Gemini',

        'detail' => $curlError

    ]);

    exit;
}


// ===============================================================
// DECODE GEMINI RESPONSE
// ===============================================================

$data = json_decode(
    $response,
    true
);

if (!is_array($data)) {

    http_response_code(502);

    echo json_encode([

        'error' => 'Invalid response from Gemini',

        'detail' => $response

    ]);

    exit;
}


// ===============================================================
// GEMINI ERROR
// ===============================================================

if ($httpCode >= 400) {

    http_response_code($httpCode);

    echo json_encode([

        'error' =>
            $data['error']['message']
            ?? 'Gemini API error',

        'http_code' => $httpCode,

        'raw' => $data

    ]);

    exit;
}

// ===============================================================
// EXTRACT MODEL RESPONSE
// ===============================================================

$reply = '';


// The current Interactions API returns
// generated content inside `steps`.

if (
    isset($data['steps']) &&
    is_array($data['steps'])
) {

    foreach ($data['steps'] as $step) {

        // We only want model output.
        if (
            ($step['type'] ?? '') !==
            'model_output'
        ) {
            continue;
        }

        if (
            isset($step['content']) &&
            is_array($step['content'])
        ) {

            foreach (
                $step['content']
                as $content
            ) {

                if (
                    ($content['type'] ?? '') ===
                    'text'
                    &&
                    isset($content['text'])
                ) {

                    $reply .=
                        $content['text'];
                }
            }
        }
    }
}


// ===============================================================
// EMPTY RESPONSE
// ===============================================================

$reply = trim($reply);

if ($reply === '') {

    http_response_code(502);

    echo json_encode([

        'error' =>
            'Empty reply from Gemini',

        'response' => $data

    ]);

    exit;
}


// ===============================================================
// RETURN RESPONSE
// ===============================================================
//
// IMPORTANT:
// We return the interaction ID so the frontend can send it
// with the next message.
//
// This gives Whiskers conversation memory.
// ===============================================================

echo json_encode([

    'reply' => $reply,

    'interaction_id' =>
        $data['id'] ?? null,

    // Which model actually answered (useful for debugging fallback
    // behavior). The frontend doesn't need to use this.
    'model_used' => $modelUsed

], JSON_UNESCAPED_UNICODE);