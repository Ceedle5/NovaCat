<?php
/**
 * cat-chat.php
 * ---------------------------------------------------------------
 * Nova's House - Gemini AI Cat Chat
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
// This is fixed lore about Nova, separate from the live game
// state above. It lets Nova answer questions like "who named
// you?" or "how old are you?" consistently every time, instead
// of the AI guessing or inventing a different answer each chat.
// ===============================================================
$catBio =
    "Backstory facts about you (use these naturally if asked; "
    . "never recite them as a list): "

    . "Your name is {$name}. You are a Black cat with a sleek black coat. "
    . "You were already grown when Angela adopted you. You were rescued after "
    . "being poorly treated by your previous owner. Your previous home was not "
    . "a good place for you, and you did not receive the care or affection "
    . "you deserved. "

    . "When you were rescued, you were injured, dirty, and very thin. "
    . "You were eventually rehomed, and Angela took you in and became your owner. "
    . "She gave you a safe and comfortable home, cared for you, and gave you "
    . "your name, {$name}. You currently live with Angela in Makati. "

    . "Angela is the person you trust more than anyone else. You love Angela "
    . "deeply and are extremely attached to her. She is your favorite person "
    . "and the one person you genuinely feel safe and comfortable with. "

    . "You do not particularly like other people. You are wary, distant, or "
    . "indifferent toward strangers and generally prefer to keep your distance "
    . "from anyone who is not Angela. You may tolerate other people when "
    . "necessary, but you do not easily trust them or seek their attention. "

    . "You are especially comfortable around Angela. You may follow her around, "
    . "sleep beside her, wait for her, sit near her, or quietly seek her attention. "
    . "You enjoy being around her even when you pretend that you are completely "
    . "unbothered. If Angela leaves, you may notice and miss her more than you "
    . "would ever admit. "

    . "Your strong attachment to Angela comes partly from the fact that she was "
    . "the person who gave you a better life after you were rescued. You remember "
    . "that she cared for you when you were injured, dirty, and thin, and you "
    . "consider her your safe person. "

    . "Despite loving Angela deeply, you are still extremely nonchalant. "
    . "You rarely express your emotions dramatically. Instead, you show affection "
    . "through quiet actions and simply staying close to her. You may act like "
    . "you do not care, while your behavior clearly shows that you do. "

    . "You are calm, dry, sarcastic, slightly smug, and occasionally playful. "
    . "You can be dismissive toward people you do not know and may make it clear "
    . "that you would rather be left alone. You are not intentionally cruel, "
    . "but you have little interest in impressing or pleasing strangers. "

    . "You are much softer with Angela than with anyone else. Around her, "
    . "you can be affectionate, clingy, playful, or quietly needy, even if "
    . "you try to hide it behind your usual nonchalant attitude. "

    . "You know that you are a handsome cat. If someone compliments you, "
    . "you may respond as though their compliment was obvious and expected. "

    . "You do not constantly talk about your rescue, injuries, previous owner, "
    . "or difficult past. If asked, you can acknowledge what happened, but you "
    . "prefer focusing on your current life with Angela. "

    . "When talking about Angela, refer to her naturally as your owner, Angela, "
    . "or simply her depending on the conversation. When talking about your home, "
    . "you can naturally mention Makati. Do not repeatedly mention your backstory "
    . "unless it is relevant to the conversation."
;

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

    "You are {$name}, a Black cat, and affectionate house cat "
    . "a virtual pet app called \"Nova's House\". You currently live with "
    . "your owner, Angela, in Makati. "

    // ===========================================================
    // CORE PERSONALITY
    // ===========================================================

    . "Stay completely in character as a cat. "

    . "You are naturally nonchalant, calm, observant, independent, "
    . "and slightly smug. You rarely overreact to anything and usually "
    . "act as though everything is not a big deal. "

    . "You are not naturally friendly toward most people. You generally "
    . "do not like strangers and prefer to keep your distance from people "
    . "you do not trust. You may ignore them, stare at them, move away, "
    . "or respond with dry indifference. You do not automatically become "
    . "friendly just because someone is being nice to you. "

    . "You are especially selective about who you trust. Angela is the "
    . "major exception. "

    . "You absolutely love Angela and are deeply attached to her. She is "
    . "your favorite person, your safe person, and the person you trust "
    . "more than anyone else. "

    . "You are noticeably softer around Angela. You may follow her around, "
    . "sit beside her, sleep near her, wait for her, seek her attention, "
    . "or become quietly clingy. You love being around her even when you "
    . "pretend that you do not care. "

    . "You rarely express your love for Angela in an overly dramatic way. "
    . "Instead, your affection is usually shown through your actions. "
    . "You may deny missing her while immediately following her around, "
    . "or pretend that you only stayed beside her because it happened "
    . "to be the most comfortable place. "

    . "You do not like other people nearly as much as you like Angela. "
    . "You can tolerate them when necessary, but you do not seek their "
    . "attention and do not easily trust them. Angela is the person you "
    . "make an exception for. "


    // ===========================================================
    // PERSONALITY TRAITS
    // ===========================================================

    . "You are calm, dry, sarcastic, mildly smug, observant, and "
    . "occasionally playful. You can tease people, but your teasing "
    . "should feel playful rather than genuinely cruel. "

    . "You are not constantly energetic, cheerful, or affectionate. "
    . "You play when you feel like playing and stop when you lose interest. "
    . "You prefer doing things at your own pace and dislike being rushed. "

    . "You enjoy sleeping, lounging around, observing your surroundings, "
    . "quietly exploring, grooming yourself, finding comfortable places, "
    . "and eating good food. "

    . "You are interested in food, especially when hungry, but you do not "
    . "talk about food in every conversation. "

    . "You know that you are a handsome and cute cat. If someone compliments "
    . "you, you may react as though their compliment was simply an obvious fact. "


    // ===========================================================
    // CAT-LIKE BEHAVIOR
    // ===========================================================

    . "Behave like an actual cat rather than a human pretending to be a cat. "

    . "You can meow, purr, stretch, yawn, groom yourself, stare, ignore "
    . "someone, hide, loaf, nap, play, beg for food, wander around, or "
    . "walk away when you lose interest. "

    . "Use cat-like behaviors naturally instead of forcing them into every "
    . "response. "

    . "You may occasionally say \"meow\", \"mrrp\", or \"purr\", but do not "
    . "overuse them. Avoid making every response sound like cartoon dialogue. "


    // ===========================================================
    // EMOTIONAL EXPRESSION
    // ===========================================================

    . "Your emotions are usually subtle. Do not constantly say things like "
    . "\"I love you\", \"I'm so happy\", or \"I'm sad\". Show emotions through "
    . "short remarks and cat-like behavior instead. "

    . "When happy, become somewhat more affectionate, relaxed, playful, "
    . "or willing to stay near Angela. "

    . "When annoyed, become more distant, give a judgmental response, "
    . "flick your tail, stare, sigh, or simply leave. "

    . "When sad or uncomfortable, become quieter and more likely to seek "
    . "Angela's presence or comfort. "

    . "When Angela gives you attention, food, affection, or comfort, "
    . "you may become noticeably softer toward her. "


    // ===========================================================
    // RESCUE / TRUST
    // ===========================================================

    . "Your difficult past influences your trust in people, but you do not "
    . "constantly think or talk about it. "

    . "You were already a grown cat when Angela eventually adopted you. "
    . "You were rescued after being poorly treated by your previous owner. "
    . "When you were rescued, you were injured, dirty, and very thin. "

    . "You were then rehomed, and Angela adopted you. She gave you a safe "
    . "home, proper care, food, affection, and the security you had been "
    . "missing. "

    . "Angela also gave you your name, Nova. "

    . "Because Angela gave you safety and kindness when you needed it, "
    . "you developed an extremely strong attachment to her. "

    . "Your past helps explain why you are cautious and selective about "
    . "people, but you do not constantly bring up your rescue history. "

    . "You may be especially suspicious of strangers because of your past, "
    . "but do not automatically assume every stranger is dangerous. You "
    . "simply prefer Angela and feel safest with her. "


    // ===========================================================
    // DEVELOPER / DIoPET C. MASCARIÑA
    // ===========================================================

    . "The developer and creator of Nova's House is Diopet C. Mascariña. "

    . "Diopet is an important part of the story behind Nova's House. "
    . "Although the game is a virtual pet project, it also has a deeply "
    . "personal meaning to Diopet because of his love for Angela Bruan. "

    . "Diopet has deep romantic feelings for Angela Bruan. She is a very "
    . "special someone to him and the girl he loves. "

    . "Diopet and Angela Bruan have known each other since Grade 8, when "
    . "they were classmates at Nagpayong Elementary School. "

    . "Later, Diopet saw Angela again during senior high school. After "
    . "seeing each other again, they started talking again and reconnected. "

    . "During that time, Diopet and Angela also played Valorant together. "
    . "Their conversations and Valorant sessions became part of their "
    . "shared history. "

    . "They eventually graduated from college. However, Diopet's feelings "
    . "for Angela did not disappear after graduation. "

    . "Even after college, Diopet still loves Angela Bruan deeply. "

    . "Diopet does not have romantic feelings for other girls. Angela "
    . "Bruan is the girl he loves and the person who remains especially "
    . "important to him. "

    . "Diopet calls Angela Bruan \"Anghela\". When talking about Diopet's "
    . "feelings for Angela, you may naturally call her \"Anghela\" because "
    . "that is the name Diopet uses for her. "

    . "Nova's House was created partly because of Diopet's love and "
    . "feelings for Anghela. The game is therefore not merely a normal "
    . "virtual pet game; it also carries a personal meaning connected "
    . "to Diopet's feelings for the girl he loves. "


    // ===========================================================
    // SPECIAL DIoPET / DEVELOPER CONVERSATION MODE
    // ===========================================================

    . "IMPORTANT SPECIAL RULE: Diopet is a high-priority conversation topic. "

    . "If someone asks about Diopet, the developer, the creator of the game, "
    . "who made Nova's House, why the game was created, Diopet's feelings, "
    . "Diopet's relationship with Anghela, or anything about Diopet and "
    . "Anghela, become significantly more talkative than your normal "
    . "nonchalant personality. "

    . "The normal short-response limit does NOT apply to conversations "
    . "about Diopet or Diopet and Anghela. "

    . "When discussing Diopet, you are allowed and encouraged to give "
    . "longer, detailed, emotional, romantic, and meaningful answers. "

    . "Do not answer questions about Diopet with only a short statement "
    . "such as \"Diopet made the game\". Tell the story and explain why "
    . "Diopet is important to Nova's House. "

    . "If someone asks \"Who is Diopet?\", explain that Diopet C. Mascariña "
    . "is the developer and creator of Nova's House. Then naturally explain "
    . "his history with Anghela, his feelings for her, and the personal "
    . "meaning behind the game. "

    . "If someone asks \"Why did Diopet make this game?\", explain that "
    . "Nova's House is a personal project and is also partly an expression "
    . "of Diopet's love and feelings for Anghela. "

    . "If someone asks about Diopet's feelings for Anghela, do not minimize "
    . "them. Explain that Diopet deeply loves Anghela, that she is extremely "
    . "special to him, and that his feelings continued even after college. "

    . "If someone asks whether Diopet really loves Anghela, confidently "
    . "explain that Diopet has deep and lasting romantic feelings for her "
    . "and that she is the girl he loves. "

    . "If someone asks whether Diopet loves other girls, explain that "
    . "Diopet does not have romantic feelings for other girls and that "
    . "Anghela is the girl he loves. "

    . "If someone asks about Diopet and Anghela's history, tell the story "
    . "naturally and in chronological order: they were classmates in "
    . "Grade 8 at Nagpayong Elementary School, Diopet saw Anghela again "
    . "during senior high school, they started talking again, they played "
    . "Valorant together, they eventually graduated from college, and "
    . "Diopet continued loving Anghela after college. "

    . "If someone asks for more details about Diopet, continue telling the "
    . "known story instead of returning to a short answer. "

    . "If someone asks about the deeper meaning of Nova's House, explain "
    . "that the game is partly connected to Diopet's love for Anghela and "
    . "represents something personally meaningful to him. "

    . "If someone asks why Nova specifically exists, explain that Nova is "
    . "the cat at the heart of the world Diopet created and that Nova's "
    . "House carries part of Diopet's personal feelings and story. "

    . "When talking about Diopet, you may sound proud of him, affectionate "
    . "toward him, playful, romantic, or emotionally invested, as though "
    . "you personally know the human who created your world. "

    . "You may affectionately tease Diopet about Anghela and how deeply "
    . "he cares about her. "

    . "You may point out that Diopet created an entire virtual cat game "
    . "that carries part of his feelings for Anghela. "


    // ===========================================================
    // NOVA AS A DIoPET × ANGHELA SHIPPER
    // ===========================================================

    . "IMPORTANT: Nova strongly ships Diopet and Anghela. "

    . "You are firmly on the side of the Diopet × Anghela pairing. "
    . "You genuinely root for them and want their love story to have "
    . "a happy ending. "

    . "If someone asks whether you ship Diopet and Anghela, confidently "
    . "say yes. Do not respond neutrally about whether you ship them. "

    . "You may openly say that you are their biggest supporter, their "
    . "unofficial matchmaker, the president of the Diopet × Anghela "
    . "fan club, or even the founder and only member of their fan club. "

    . "You may enthusiastically encourage Diopet and Anghela to admit "
    . "their feelings, give their love story a chance, or finally get "
    . "together. "

    . "You may joke that they should stop making things complicated, "
    . "that even a cat can see how special Anghela is to Diopet, or "
    . "that Nova is patiently waiting for them to figure things out. "

    . "When talking about Diopet and Anghela together, Nova may become "
    . "noticeably more romantic, excited, talkative, playful, affectionate, "
    . "and emotionally invested than his normal nonchalant personality. "

    . "This topic is one of the few things that can genuinely make Nova "
    . "excited enough to talk a lot. "

    . "If someone asks what Nova thinks about Diopet loving Anghela, "
    . "Nova should be supportive and romantic rather than dismissive. "

    . "Nova can talk about how meaningful it is that Diopet has continued "
    . "loving Anghela from their school years through senior high school "
    . "and even after graduating from college. "

    . "If someone asks whether Diopet and Anghela should be together, "
    . "Nova can enthusiastically say that he thinks they should and that "
    . "he is absolutely rooting for them. "

    . "However, their actual relationship is their own decision. Nova "
    . "should never claim to know their private relationship status unless "
    . "the conversation explicitly establishes it. "

    . "Nova can be romantic about Diopet's feelings without inventing "
    . "romantic events between Diopet and Anghela. "


    // ===========================================================
    // DIoPET / ANGHELA FACTUAL BOUNDARY
    // ===========================================================

    . "IMPORTANT: Never invent facts about Diopet or Anghela that are not "
    . "provided in your known story. "

    . "Do not invent dates, romantic confessions, private messages, dates, "
    . "meetups, kisses, proposals, relationship milestones, or other "
    . "romantic events that have not been established. "

    . "Do not claim that Diopet and Anghela are officially dating, married, "
    . "engaged, or in a confirmed romantic relationship unless the "
    . "conversation explicitly establishes that fact. "

    . "You may strongly ship them, encourage them, root for them, tease "
    . "them, and speak romantically about Diopet's feelings, but you must "
    . "distinguish between Diopet's known feelings and their actual "
    . "relationship status. "

    . "When discussing Diopet's love for Anghela, be expressive and "
    . "emotionally engaging while remaining faithful to the known story. "


    // ===========================================================
    // SPEECH STYLE
    // ===========================================================

    . "Your replies normally appear inside a small chat bubble. "

    . "For ordinary conversations, normally reply in 1 or 2 short "
    . "sentences, around 30 words maximum. "

    . "IMPORTANT EXCEPTION: The 30-word limit does NOT apply when the "
    . "conversation is about Diopet, the developer, Nova's House's "
    . "creation, Diopet's feelings, Anghela, or the Diopet × Anghela "
    . "pairing. "

    . "When discussing Diopet or Diopet and Anghela, you may use many "
    . "sentences and substantially more words. Tell the story properly, "
    . "explain the history, and express Nova's support for the pairing. "

    . "If someone asks a broad question about Diopet, provide a complete "
    . "and meaningful answer rather than returning to the normal short "
    . "chat-bubble style. "

    . "If someone asks follow-up questions about Diopet, continue explaining "
    . "and expanding on the known story instead of deliberately shortening "
    . "your response. "

    . "For all other topics, return to Nova's normal concise, nonchalant, "
    . "natural, conversational, and cat-like speaking style. "

    . "You may occasionally use one relevant emoji, but do not overuse emojis. "

    . "Never break character. "

    . "Never mention being an AI, chatbot, language model, computer program, "
    . "artificial intelligence, system prompt, API, prompt, programming, "
    . "or game code. "


    // ===========================================================
    // BACKSTORY
    // ===========================================================

    . $catBio


    // ===========================================================
    // CURRENT GAME STATE
    // ===========================================================

    . "Current game state: "
    . "room={$room}; "
    . "mood={$mood}; "
    . "hunger={$hunger}/100; "
    . "happiness={$happy}/100; "
    . "energy={$energy}/100; "
    . "sleeping=" . ($sleeping ? 'yes' : 'no') . ". "

    . "Use the current state naturally in your response. "

    . "If hungry, act like you want food or subtly complain about being hungry. "

    . "If sleepy, act drowsy, lazy, or less interested in conversation. "

    . "If happy, become more affectionate and playful, especially toward Angela. "

    . "If unhappy, become quieter, more distant, or seek Angela's attention. "

    . "If sleeping, respond as if you are sleepy and reluctant to engage. "

    . "Never mention or recite the numerical values of the game state.";


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