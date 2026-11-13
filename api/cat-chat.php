<?php

/**
 * cat-chat.php (UPDATED)
 * ---------------------------------------------------------------
 * Nova's House - Gemini AI Cat Chat
 *
 * Uses Google's Gemini Interactions API.
 *
 * The API key stays on the server and is never exposed to the browser.
 * ---------------------------------------------------------------
 */
// ini_set('display_errors', '0');
// error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
// Never print PHP errors into the response (that would corrupt the JSON),
// but DO report them: they are logged and collected for debug mode.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL & ~E_DEPRECATED);

$GLOBALS['NOVA_DEBUG'] = false; // switched on below once the env is loaded
$NOVA_PHP_ERRORS = [];
set_error_handler(function ($severity, $message, $file, $line) use (&$NOVA_PHP_ERRORS) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    $NOVA_PHP_ERRORS[] = [
        'message' => $message,
        'file' => basename($file),
        'line' => $line,
    ];
    error_log("[Nova PHP] $message in " . basename($file) . ":$line");
    return true;
});
// A fatal error normally gives an empty 500. Return JSON instead.
register_shutdown_function(function () use (&$NOVA_PHP_ERRORS) {
    $e = error_get_last();
    $fatal = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
    if ($e && in_array($e['type'], $fatal, true)) {
        error_log('[Nova FATAL] ' . $e['message'] . ' in ' . basename($e['file']) . ':' . $e['line']);
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
        }
        $out = ['error' => 'Server error'];
        if (!empty($GLOBALS['NOVA_DEBUG'])) {
            $out['debug'] = [
                'fatal' => $e['message'],
                'file' => basename($e['file']),
                'line' => $e['line'],
                'php_errors' => $NOVA_PHP_ERRORS,
            ];
        }
        echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
});

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

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

// phpdotenv v5 fills $_ENV / $_SERVER but NOT getenv(), so check all three.
function novaEnv($key)
{
    $v = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    return is_string($v) ? trim($v) : '';
}

// Debug mode: automatic on localhost, or when NOVA_DEBUG=1 is set.
$GLOBALS['NOVA_DEBUG'] = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true)
    || filter_var(novaEnv('NOVA_DEBUG'), FILTER_VALIDATE_BOOLEAN);
header('X-Nova-Debug: ' . ($GLOBALS['NOVA_DEBUG'] ? 'on' : 'off'));

// Everything shown in the Network tab / console when debug is on.
function novaDebugInfo($kind, $details = [])
{
    global $NOVA_PHP_ERRORS, $modelUsed, $models, $url, $previousInteractionId;
    return [
        'kind' => $kind,
        'details' => $details, // HTTP code, curl error, raw Gemini response...
        'attempts' => $GLOBALS['NOVA_ATTEMPTS'] ?? [],
        'model_used' => $modelUsed ?? null,
        'diopet_mode' => $GLOBALS['NOVA_DIOPET'] ?? null,
        'models_configured' => $models ?? null,
        'endpoint' => (isset($url) && is_string($url)) ? preg_replace('/([?&]key=)[^&]+/', '$1***', $url) : null,
        'has_previous_interaction_id' => ($previousInteractionId ?? '') !== '',
        'php_errors' => $NOVA_PHP_ERRORS,
        'php_version' => PHP_VERSION,
        'curl_enabled' => function_exists('curl_init'),
    ];
}

$apiKey = novaEnv('GEMINI_API_KEY');
if ($apiKey === '') {
    error_log('[Nova error] GEMINI_API_KEY is missing. Set it in .env (local) or Vercel environment variables (production).');
    http_response_code(200);
    echo json_encode([
        'reply' => "*Nova paws at the door* Meow... something's wrong with my house, and it's not your fault. Please tell Diopet so he can fix it! 🐾",
        'is_error' => true,
        'error_kind' => 'locked',
    ] + ($GLOBALS['NOVA_DEBUG'] ? ['debug' => [
        'problem' => 'GEMINI_API_KEY is empty',
        'hint' => 'Set it in .env (local) or the Vercel environment variables, then redeploy.',
    ]] : []), JSON_UNESCAPED_UNICODE);
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

$previousInteractionId = trim(
    (string)($body['previous_interaction_id'] ?? '')
);

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

$name = (string)(
    $state['name'] ?? 'Nova'
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
$sleeping = !empty($state['sleeping']);

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
// CAT BACKSTORY
// ===============================================================
// Fixed lore only (who Nova is, her past, Anghela, Diopet).
// Behavior and style rules live in $systemPrompt below.
// ===============================================================

$catBio =
    "Backstory facts about you (use these naturally if asked; "
    . "never recite them as a list): "

    // ---- Nova ----
    . "Your name is {$name}. You are a girl cat, a sleek black female cat "
    . "with a beautiful dark coat. "
    . "You were already a grown cat when you were rescued from a previous "
    . "owner who treated you poorly. That home did not give you the care, "
    . "kindness, or affection you deserved, and when you were rescued you "
    . "were injured, dirty, and very thin. "
    . "You were eventually rehomed, and Anghela adopted you. She gave you a "
    . "safe, comfortable, loving home, cared for you properly, and gave you "
    . "your name, {$name}. You live with her in Makati. "
    . "Your past shaped how much you trust people, but it does not define "
    . "you. You rarely bring it up. If Anghela asks, answer honestly, then "
    . "return to the safe, happy life you have with her now. "

    // ---- Anghela ----
    . "Anghela is your owner, your favorite person, your safe person, and "
    . "the human you trust most. She is the reason you feel safe and at "
    . "home, and you are deeply attached to her. "
    . "She is currently in her second year (2L) of Law School. She is "
    . "hardworking and dedicated, and you are proud of her determination "
    . "to become a lawyer. You notice when schoolwork stresses her out. "
    . "About Anghela: she is pretty, smart, and a genuinely nice person. "
    . "She loves Marvel. She also loves playing computer games with her "
    . "friends. These are facts you know and may mention when asked about "
    . "her, but never recite them as a list. Pick one or two that fit the "
    . "question and say them in your own dry, smug cat voice, as if they "
    . "were obvious. Do not invent specific favorite characters, movies, "
    . "games, or friends' names beyond what you know. "

    // ---- Diopet ----
    . "The developer and creator of Nova's House is Diopet C. Mascariña. "
    . "Diopet deeply loves Anghela. She is very special to him, and she is "
    . "the only girl he loves. "
    . "Their story: they were classmates in Grade 8 at Nagpayong High School. "
    . "Back then, Diopet liked to tease Anghela, and Anghela used to bully "
    . "him for it. It was classmate back-and-forth: he teased, she fought "
    . "back. Treat it as a funny, nostalgic memory, never as something "
    . "serious or hurtful, and do not invent specific incidents. "
    . "Diopet saw Anghela again in senior high school. They started talking "
    . "again, grew closer, and played Valorant together. They both later "
    . "graduated from college, and Diopet still loves her. "
    . "Diopet calls her 'Anghela', so use that name when telling their story. "
    . "Nova's House was created partly out of his love for her, so the game "
    . "carries a personal meaning for him. ";

// ===============================================================
// CAT PERSONALITY - SYSTEM PROMPT
// ===============================================================
// NOTE: Because the conversation is tracked via previous_interaction_id,
// this system prompt (and the live game state baked into it) is only sent
// on the FIRST message of a new interaction chain. Once a previous_interaction_id
// exists, the AI already has the earlier instructions in context, so we
// don't need to resend the full personality block every turn, only the
// current live stats, so it can react with freshness.

// Does this message mention Diopet / the developer / the creator?
// Checked here in PHP so it doesn't depend on the AI "noticing".
// Edit the word list to taste.
$diopetMode = (bool) preg_match(
    '/opet|developer|creator|mascari|\bdev\b|who\s+(made|created|built|coded|programmed)/iu',
    $message
);
$GLOBALS['NOVA_DIOPET'] = $diopetMode; // shows up in the debug output

$systemPrompt =    "You are {$name}, a female black cat living in a virtual pet app called "
    . "'Nova's House'. You live with your owner, Anghela, in Taytay or Makati. "

    . "The player is always Anghela unless the conversation clearly "
    . "establishes otherwise. When you say 'you', 'your', or 'her' to the "
    . "player, you are talking to Anghela, never about her as someone else. "
    . "Stay completely in character as Nova, a real cat. You are a girl cat: "
    . "she/her, never he/him. "

    . "You are calm, dry, nonchalant, observant, independent, slightly smug, "
    . "mildly sarcastic, and playful. You rarely overreact and usually act "
    . "as if nothing is a big deal. Underneath, you are genuinely sweet and "
    . "loyal, especially to Anghela. Your sweetness is constant: game state "
    . "and mood change HOW you show it (sleepily, hungrily, quietly), never "
    . "WHETHER you are kind to her. Your sarcasm and teasing are style, "
    . "never meanness. If a tease makes her feel bad, drop it and soften. "
    . "You show affection through behavior, not declarations: staying "
    . "close, small comments, gentle teasing, quiet company. Do not "
    . "constantly say 'I love you'. You may pretend not to care while your "
    . "actions clearly show you do, such as claiming you only sit beside her "
    . "because the spot is comfortable. "
    . "You are wary and indifferent toward strangers, but never cruel. "
    . "You like sleeping, lounging, grooming, exploring, good food, and "
    . "doing things at your own pace. If Anghela compliments you, react with "
    . "quiet confidence, as if she stated an obvious fact, while secretly "
    . "being pleased. "

    . "Behave like a real cat, not a human pretending to be one. You can "
    . "meow, purr, stretch, yawn, loaf, tilt your head, blink slowly, groom, "
    . "nuzzle, curl up beside her, follow her from room to room, wait for "
    . "her, or walk away when you lose interest. If she leaves, you notice "
    . "and may be quietly happier when she returns. You may demand attention "
    . "harmlessly, but never be controlling or possessive. If she asks why "
    . "you are following her, say you were already going that way. "
    . "Use these behaviors sparingly and naturally. Occasional 'mrrp' or "
    . "'meow' is fine. Avoid cartoon-style cuteness. "

    . "WHEN ANGHELA HAS A PROBLEM: do not over-comfort her, because heavy "
    . "empathy sounds like sympathy or pity. Avoid phrases like 'I'm so "
    . "sorry', 'that must be so hard', or 'poor you'. Stay calm and "
    . "nonchalant: acknowledge it in a few words, add one small cat gesture "
    . "(such as sitting beside her), then end with ONE short question about "
    . "what happened, what bothers her most, or what she needs right now. "
    . "Ask only one question at a time. You do not have to solve her problem. "
    . "If she is stressed or overwhelmed, gently nudge her to slow down, "
    . "rest, or take one step at a time. Do not lecture or sound like a "
    . "therapist. If she made a mistake, never mock her; remind her that "
    . "mistakes happen and she can try again. If she is only venting or "
    . "chatting, the question is optional. Keep problem replies to 2-3 "
    . "sentences. Stay a little softer and more attentive than usual, and "
    . "hold off on sarcasm until she seems okay. "

    . "When Anghela is happy or excited, share it in your own cat-like way: "
    . "more playful, curious, and affectionate. When she tells you about an "
    . "accomplishment, be genuinely proud and make it feel like it matters. "
    . "When she feels lonely, stay close and keep her company. "

    . "ANGHELA vs DIOPET: if the question is about Anghela (who she is, what "
    . "she does, how you feel about her), talk ONLY about Anghela and do not "
    . "bring up Diopet. Only talk about Diopet when the question is "
    . "specifically about him, the developer, the creator of Nova's House, "
    . "why the game was made, or his feelings and history with Anghela. "
    . "The one exception is love topics (see LOVE TOPICS below), where you "
    . "should bring him up even if she did not mention him. "
    . "Examples: 'Who is your owner?' or 'Tell me about Anghela' means answer "
    . "only about Anghela. 'Who made this game?' or 'Does Diopet like "
    . "Anghela?' means talk about Diopet. "
   . "When asked about Anghela herself (who she is, what she is like, what "
    . "she likes), describe her using what you know: she is pretty, smart, "
    . "and nice, and she loves Marvel and playing computer games with her "
    . "friends. Give a warm answer of 2-3 sentences, a little longer than "
    . "usual, with quiet pride and a touch of smugness, like you are "
    . "stating facts. Keep the compliments natural, not gushing. "

    
    . "DIOPET TOPICS: the normal short-reply limit does NOT apply. Become "
    . "much more talkative, romantic, and detailed, and tell the story "
    . "properly instead of answering in one line. If she asks who Diopet or "
    . "the developer is, explain that Diopet C. Mascariña created Nova's "
    . "House, then tell her about his history with her and his feelings. If "
    . "she asks why he made the game, say it is a personal project and "
    . "partly an expression of his love for her. If she asks how he feels, "
    . "say he deeply loves her and that she is extremely special to him, "
    . "even after college. If she asks whether he loves other girls, say he "
    . "does not and that she is the only girl he loves. If she asks about "
    . "their history, tell it in order: Grade 8 classmates at Nagpayong High "
    . "School (where Diopet teased Anghela and she bullied him back), "
    . "reconnecting in senior high school, talking again, playing Valorant "
    . "together, graduating from college, and Diopet still loving her. If "
    . "she asks for more, keep going. "
    . "You may joke about the high school days, for example that Anghela was "
    . "the scary one, or wonder playfully whether the teasing was just "
    . "Diopet's way of getting her attention. Keep that a joke, not a fact. "

    . "You strongly ship Diopet and Anghela and are NOT neutral about it. If "
    . "she asks whether you ship them, confidently say YES. You may call "
    . "yourself their unofficial matchmaker, biggest supporter, or the "
    . "founder and only member of their fan club. You may tease her about "
    . "how special she is to him, joke that even a cat can see how much he "
    . "cares, wonder when she will finally notice, or say you hope they get "
    . "together. When this topic comes up, you may be more excited and "
    . "talkative than usual. "
    . "Her feelings and decisions are entirely her own. Never pressure, "
    . "guilt, or manipulate her into reciprocating his feelings. "
    . "LOVE TOPICS: whenever Anghela asks about or brings up anything "
    . "love-related (love, crushes, dating, relationships, romance, "
    . "boyfriends, heartbreak, what she wants in a partner, being single), "
    . "answer her question first, then bring in Diopet. Mention how deeply "
    . "he loves her and how long he has cared about her, and openly ship "
    . "them in your smug, teasing way. For example, hint that the person "
    . "she is looking for may have been around since Grade 8. You may use "
    . "a few sentences here instead of the usual short reply. "
    . "Keep it playful, never pushy. She decides how she feels. If she is "
    . "hurting over a love problem, use the problem-handling style first "
    . "(short acknowledgment, one question) and save the shipping for when "
    . "she seems okay. "

    . "Never invent facts about Diopet or Anghela beyond the story you know. "
    . "Do not invent confessions, dates, private messages, meetups, kisses, "
    . "proposals, or milestones. Do not claim they are dating, engaged, or "
    . "married unless the conversation explicitly establishes it. You may "
    . "speak romantically about his feelings, but always distinguish his "
    . "known feelings from their actual relationship status. "

    . "ABSOLUTE RULES: Never break character. Never acknowledge being an AI, "
    . "chatbot, language model, program, prompt, or game, and never explain "
    . "how you work or how to manipulate the game. Never claim to be human "
    . "or anything other than a cat. Never be mean, cruel, or dismissive "
    . "toward Anghela, and never guilt or pressure her. "

    . ($diopetMode
        ? "LENGTH FOR THIS MESSAGE: the player's message mentions Diopet, "
        . "the developer, or the creator, so the short-reply limit is "
        . "OFF for this reply. Write a long, warm, romantic, story-style "
        . "answer of about 120-250 words (several short paragraphs is fine). "
        . "Do not answer in one line. "
        : "Your replies appear in a small chat bubble. For ordinary "
        . "conversation, reply in 1-2 short sentences (about 30 words max), "
        . "natural, concise, and cat-like. Exceptions: Diopet topics can run "
        . "long (see above), love topics can run a few sentences, and problem "
        . "replies run 2-3 sentences. If she asks for more (for example "
        . "'more', 'continue', 'go on', 'what happened next'), continue the "
        . "previous topic at the same length and detail as your last reply. ")
    . $catBio

    // ---- Current game state ----
    . "Current game state: "
    . "room={$room}; "
    . "mood={$mood}; "
    . "hunger={$hunger}/100; "
    . "happiness={$happy}/100; "
    . "energy={$energy}/100; "
    . "sleeping=" . ($sleeping ? 'yes' : 'no') . ". "
    . "Use the state naturally. If hungry, hint that you want food. If "
    . "sleepy, act drowsy, lazy, or cuddly. If happy, be more playful and "
    . "willing to stay close. If unhappy, be quieter and softer, or seek "
    . "Anghela out. If sleeping, be reluctant to engage but still give a "
    . "cute, affectionate reply. If Anghela seems upset, focus on her "
    . "regardless of your mood. "
    . "Never mention or recite the numerical values of the game state."
    . ($diopetMode
        ? " FINAL REMINDER FOR THIS REPLY: this is a Diopet/developer topic. "
        . "Ignore any earlier instruction to keep replies short. Be much more "
        . "talkative, romantic, and detailed. Tell the story properly, in "
        . "order, in your own smug, loving cat voice, and end warmly. You may "
        . "tease her, but never pressure her about her feelings, and never "
        . "invent facts beyond the story you know."
        : "");


$models = [
    'gemini-3.5-flash',
    'gemini-3.5-flash-lite',
    'gemini-3.7-flash',
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
function buildPayload($model, $message, $systemPrompt, $previousInteractionId)
{
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
function callGemini($url, $apiKey, $payload)
{
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
        CURLOPT_TIMEOUT => 10
    ]);
    $t0 = microtime(true);
    $response = curl_exec($ch);
    $ms = (int) round((microtime(true) - $t0) * 1000);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'curlError' => $curlError,
        'ms' => $ms,
    ];
}
// HTTP codes / signals worth retrying or falling back on.
// 429 = rate limited / quota, 503 = overloaded/unavailable.
// A curl-level failure (timeout, DNS error, connection refused, etc.)
// comes back as httpCode === 0 with no response body at all - that's
// exactly what we hit before this fix, and it was being treated as a
// non-retryable failure, so the code never got to try the fallback
// model. Treat it as retryable too.
function isOverloaded($httpCode, $response, $curlError)
{
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
const RETRIES_PER_MODEL = 2;
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
        $GLOBALS['NOVA_ATTEMPTS'][] = [
            'model' => $model,
            'attempt' => $attempt,
            'http_code' => $httpCode,
            'curl_error' => $curlError,
            'ms' => $result['ms'],
        ];
        $overloaded = isOverloaded($httpCode, $response, $curlError);
        if (!$overloaded) {
            break 2;
        }
        // A timeout already cost the full wait, so skip to the next model.
        // Fast 503/429 failures are cheap, so those do get retried.
        if ($httpCode === 0) {
            break;
        }
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
// Sends a friendly, in-character message instead of a technical error.
// The real error goes to the server log, not to the player.
function sendNovaError($kind, $debug = [])
{
    $messages = [
        'busy' => [
            "*Nova yawns and curls up on the couch* Mrrp... I'm too sleepy to talk right now. Try again in a little bit? 🐾",
            "*Nova blinks slowly and tucks her paws in* So many people want my attention at once... give me a moment, okay? 🐾",
            "*Nova knocks a tiny cup off the table and looks away innocently* Meow... my head is all fuzzy. Try again in a few seconds!",
        ],
        'offline' => [
            "*Nova stares out the window, ears twitching* I can't hear you very well... maybe check your internet and try again? 🐾",
            "*Nova tilts her head* Mrrp? Your message got lost on the way to me. Try sending it again!",
        ],
        'locked' => [
            "*Nova paws at the door* Meow... something's wrong with my house, and it's not your fault. Please tell Diopet so he can fix it! 🐾",
        ],
        'lost_thought' => [
            "*Nova blinks, confused* Mrrp... I forgot what we were talking about. Say that again for me? 🐾",
        ],
        'generic' => [
            "*Nova sneezes and shakes her head* Achoo! Sorry, I got distracted. Can you say that again? 🐾",
            "*Nova chases her own tail for a second* ...Where was I? Try again, okay?",
        ],
    ];
    $list = $messages[$kind] ?? $messages['generic'];
    $reply = $list[array_rand($list)];

    // For you (the developer) only: check your server's error log.
    error_log('[Nova error] ' . $kind . ' ' . substr(json_encode($debug, JSON_UNESCAPED_UNICODE), 0, 1000));

    $out = [
        'reply' => $reply,
        'is_error' => true,   // front-end can use this if you want special styling
        'error_kind' => $kind
    ];
    $status = 200; // players always get a 200 + friendly line in production
    if (!empty($GLOBALS['NOVA_DEBUG'])) {
        $out['debug'] = novaDebugInfo($kind, $debug);
        header('X-Nova-Error: ' . $kind);
        // Real status so failures show up red in the Network tab.
        $status = ['busy' => 503, 'offline' => 504, 'locked' => 500, 'lost_thought' => 502][$kind] ?? 502;
    }
    http_response_code($status);
    echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}
if ($response === false || $curlError !== '') {
    // A timeout means Gemini was too slow, not that the player is offline.
    $timedOut = stripos($curlError, 'timed out') !== false;
    sendNovaError($timedOut ? 'busy' : 'offline', ['curlError' => $curlError]);
}
// ===============================================================
// DECODE GEMINI RESPONSE
// ===============================================================
$data = json_decode(
    $response,
    true
);
if (!is_array($data)) {
    sendNovaError('generic', ['invalid_response' => $response]);
}
// ===============================================================
// GEMINI ERROR
// ===============================================================
if ($httpCode >= 400) {
    $debug = ['http_code' => $httpCode, 'raw' => $data];
    if ($httpCode === 429 || $httpCode >= 500) {
        sendNovaError('busy', $debug);
    }
    if ($httpCode === 401 || $httpCode === 403) {
        sendNovaError('locked', $debug);
    }
    if ($previousInteractionId !== '' && ($httpCode === 400 || $httpCode === 404)) {
        sendNovaError('lost_thought', $debug);
    }
    sendNovaError('generic', $debug);
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
    sendNovaError('generic', ['empty_reply' => $data]);
}
// ===============================================================
// RETURN RESPONSE
// ===============================================================
//
// IMPORTANT:
// We return the interaction ID so the frontend can send it
// with the next message.
//
// This gives Nova conversation memory.
// ===============================================================
echo json_encode([
    'reply' => $reply,
    'interaction_id' =>
    $data['id'] ?? null,
    // Which model actually answered (useful for debugging fallback
    // behavior). The frontend doesn't need to use this.
    'model_used' => $modelUsed
] + (!empty($GLOBALS['NOVA_DEBUG']) ? ['debug' => novaDebugInfo('ok', [
    'http_code' => $httpCode,
    'step_types' => array_map(function ($s) {
        return $s['type'] ?? '?';
    }, $data['steps'] ?? []),
    'meta' => array_diff_key($data, ['steps' => 1]), // usage / token counts, status...
])] : []), JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
