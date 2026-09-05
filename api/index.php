<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Nova's House</title>
<link rel="icon" href="/Assets/MessageBubble.png" type="image/png">
<link rel="apple-touch-icon" href="/Assets/MessageBubble.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#3a2f2a;
    --ink-soft:#8a7a6e;
    --paper:#fff8ef;
    --paper-glass:rgba(255,248,236,0.6);
    --paper-glass-strong:rgba(255,248,239,0.92);
    --coral:#ff8c69;
    --coral-deep:#e2603f;
    --gold:#ffbd59;
    --gold-deep:#e2963a;
    --sage:#8fcb9d;
    --sage-deep:#5fa877;
    --dusk:#8fa3d6;
    --dusk-deep:#5b71ab;
    --blush:#ffb3c6;
    --blush-deep:#e4839c;
    --shadow:rgba(66,42,24,0.24);
    --glow-warm:rgba(255,189,89,0.35);
    --accent:var(--sage);
    --accent-deep:var(--sage-deep);
    --grain:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='120' height='120'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/><feColorMatrix type='saturate' values='0'/></filter><rect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/></svg>");
  }
  *{box-sizing:border-box;}
  html,body{height:100%;margin:0;overflow:hidden;}
  body{
    font-family:'Nunito',sans-serif;
    color:var(--ink);
    background:linear-gradient(160deg,#2a1e28,#4a2f30);
  }
  #app{
    position:relative;
    width:100vw;
    height:100dvh;
    overflow:hidden;
    background:var(--paper);
  }
  /* ---------- Stage (full-bleed room) ---------- */
  .stage{
    position:relative;
    position:absolute;
    inset:0;
    overflow:hidden;
    background:#241a1c; /* fallback while bg images load */
    transition:background 0.6s ease;
  }
  .stage.room-living{--accent:var(--sage);--accent-deep:var(--sage-deep);}
  .stage.room-kitchen{--accent:var(--gold);--accent-deep:var(--gold-deep);}
  .stage.room-bedroom{--accent:var(--dusk);--accent-deep:var(--dusk-deep);}
  .stage::before,.stage::after{
    content:"";
    position:absolute;
    border-radius:50%;
    filter:blur(85px);
    opacity:0.3;
    background:var(--accent);
    pointer-events:none;
    z-index:1;
    animation:driftGlow 9s ease-in-out infinite alternate;
  }
  .stage::before{width:280px;height:280px;top:-70px;right:-60px;}
  .stage::after{width:230px;height:230px;bottom:60px;left:-70px;animation-duration:11s;}
  @keyframes driftGlow{
    0%{transform:translate(0,0) scale(1);}
    100%{transform:translate(14px,-18px) scale(1.08);}
  }
  /* Warm candlelit vignette + soft paper grain laid over the whole
     room. Sits above the background art (z-index 0/1) and below the
     cat (.cat-stage is z-index 5, untouched), purely decorative and
     non-interactive, so it never affects cat placement. */
  .stage-ambience{
    position:absolute;
    inset:0;
    z-index:2;
    pointer-events:none;
  }
  .stage-ambience::before{
    content:"";
    position:absolute;
    inset:0;
    background:
      radial-gradient(ellipse 120% 90% at 50% 45%, transparent 45%, rgba(40,26,20,0.28) 100%),
      linear-gradient(180deg, rgba(255,214,160,0.10), transparent 30%, transparent 70%, rgba(30,18,14,0.22));
  }
  .stage-ambience::after{
    content:"";
    position:absolute;
    inset:0;
    background-image:var(--grain);
    background-size:120px 120px;
    opacity:0.05;
    mix-blend-mode:overlay;
  }
  .motes{position:absolute;inset:0;z-index:3;pointer-events:none;overflow:hidden;}
  .mote{
    position:absolute;
    bottom:-6%;
    width:5px;height:5px;
    border-radius:50%;
    background:radial-gradient(circle, #fff6d8 0%, rgba(255,220,150,0.6) 55%, transparent 75%);
    opacity:0;
    filter:blur(0.2px);
    animation:moteDrift linear infinite;
  }
  @keyframes moteDrift{
    0%{transform:translate(0,0) scale(0.7);opacity:0;}
    8%{opacity:0.85;}
    50%{transform:translate(var(--drift,18px),-55vh) scale(1);}
    92%{opacity:0.6;}
    100%{transform:translate(calc(var(--drift,18px) * 1.6),-108vh) scale(0.8);opacity:0;}
  }
  /* Fullscreen background, in two layers so the ENTIRE artwork is
     always visible (nothing cropped off) while still filling the
     screen edge-to-edge with no dead space:
       1) .room-bg-blur — a zoomed, blurred copy of the same image,
          set to `cover` so it fills every corner of the stage.
       2) .room-bg-main — the same image at `contain`, so the whole
          picture is shown at full size, letterboxed on top of the
          blurred fill rather than cropped.
     JS reads .room-bg-main's true rendered box (from its natural
     width/height vs the stage size) to anchor the cat precisely on
     the rug / counter / bed no matter the window's aspect ratio. */
  .room-bg-layer{
    position:absolute;
    inset:0;
    z-index:0;
    overflow:hidden;
    pointer-events:none;
  }
  .room-bg-blur{
    position:absolute;
    inset:-30px;
    width:calc(100% + 60px);
    height:calc(100% + 60px);
    object-fit:cover;
    filter:blur(38px) saturate(1.15) brightness(0.82);
    transform:scale(1.08);
  }
  .room-bg-main{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:contain;
    z-index:1;
  }
  /* ---------- Topbar (floating glass) ---------- */
  .topbar{
    position:absolute;
    top:0;left:0;right:0;
    z-index:30;
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:14px;
    flex-wrap:wrap;
    padding:18px 22px;
    pointer-events:none;
  }
  .topbar > *{pointer-events:auto;min-width:0;flex-shrink:1;}
  .title-pill,.stats-pill{overflow:hidden;}
  .glass{
    position:relative;
    background:var(--paper-glass);
    backdrop-filter:blur(16px) saturate(1.3);
    -webkit-backdrop-filter:blur(16px) saturate(1.3);
    box-shadow:0 10px 26px var(--shadow), inset 0 1px 0 rgba(255,255,255,0.55);
    isolation:isolate;
  }
  .glass::before{
    content:"";
    position:absolute;
    inset:0;
    border-radius:inherit;
    background-image:var(--grain);
    background-size:120px 120px;
    opacity:0.035;
    mix-blend-mode:multiply;
    pointer-events:none;
    z-index:-1;
  }
  .title-pill{
    display:flex;align-items:center;gap:10px;
    padding:8px 20px 8px 8px;
    border-radius:999px;
    font-family:'Fredoka',sans-serif;
    font-weight:600;
    font-size:1.1rem;
    color:var(--coral-deep);
    white-space:nowrap;
    letter-spacing:0.01em;
  }
  .title-pill .paw{
    display:flex;align-items:center;justify-content:center;
    width:32px;height:32px;
    border-radius:50%;
    background:linear-gradient(155deg, var(--gold), var(--coral));
    box-shadow:0 2px 0 var(--coral-deep), inset 0 1px 1px rgba(255,255,255,0.5);
    flex-shrink:0;
  }
  .title-pill .paw svg{width:16px;height:16px;fill:#fff;}
  .room-pill{
    align-self:center;
    display:flex;align-items:center;gap:9px;
    padding:9px 22px 9px 18px;
    border-radius:999px;
    font-family:'Fredoka',sans-serif;
    font-weight:600;
    font-size:1rem;
    color:var(--ink);
    letter-spacing:0.02em;
    transition:color 0.4s ease;
  }
  .room-pill::before{
    content:"";
    width:7px;height:7px;
    border-radius:50%;
    flex-shrink:0;
    background:var(--gold-deep);
    box-shadow:0 0 0 3px rgba(255,189,89,0.28);
    animation:statusPulse 2.4s ease-in-out infinite;
  }
  @keyframes statusPulse{
    0%,100%{box-shadow:0 0 0 3px rgba(255,189,89,0.28);}
    50%{box-shadow:0 0 0 5px rgba(255,189,89,0.12);}
  }
  .stats-pill{
    display:flex;
    align-items:center;
    gap:2px;
    padding:9px 20px;
    border-radius:999px;
  }
  .stat{
    display:flex;align-items:center;gap:8px;
    padding:0 12px;
    position:relative;
  }
  .stat + .stat::before{
    content:"";
    position:absolute;
    left:-1px;top:50%;
    transform:translateY(-50%);
    width:1px;height:20px;
    background:linear-gradient(180deg, transparent, rgba(122,90,64,0.2), transparent);
  }
  .stat-icon{
    display:flex;align-items:center;justify-content:center;
    width:25px;height:25px;
    border-radius:50%;
    flex-shrink:0;
    transition:transform 0.2s ease;
  }
  .stat-icon svg{width:14px;height:14px;display:block;stroke:currentColor;}
  .stat-hunger .stat-icon{color:#c9711f;background:rgba(230,136,58,0.16);}
  .stat-happy .stat-icon{color:#c94a2c;background:rgba(226,96,63,0.16);}
  .stat-energy .stat-icon{color:#4a68ab;fill:currentColor;background:rgba(91,127,196,0.16);}
  .stat-track{
    width:58px;height:8px;
    border-radius:999px;
    background:rgba(122,90,64,0.14);
    box-shadow:inset 0 1px 2px rgba(58,42,28,0.2);
    overflow:hidden;
    position:relative;
  }
  .stat-fill{
    height:100%;
    border-radius:999px;
    position:relative;
    overflow:hidden;
    transition:width 0.5s ease, background 0.5s ease;
  }
  .stat-fill::after{
    content:"";
    position:absolute;inset:0;
    background:linear-gradient(100deg, transparent 25%, rgba(255,255,255,0.65) 45%, transparent 65%);
    background-size:220% 100%;
    animation:statSheen 3.4s ease-in-out infinite;
  }
  @keyframes statSheen{
    0%{background-position:170% 0;}
    55%,100%{background-position:-70% 0;}
  }
  .fill-hunger{background:linear-gradient(90deg,#f0ab5e,#e6883a);box-shadow:0 0 8px rgba(230,136,58,0.45);}
  .fill-happy{background:linear-gradient(90deg,#ff9d7d,#e2603f);box-shadow:0 0 8px rgba(226,96,63,0.45);}
  .fill-energy{background:linear-gradient(90deg,#a2c2ea,#6f95cf);box-shadow:0 0 8px rgba(91,127,196,0.45);}
  .stat-num{
    font-family:'Fredoka',sans-serif;
    font-variant-numeric:tabular-nums;
    font-size:0.82rem;font-weight:600;min-width:22px;text-align:right;
  }
  .stat-hunger .stat-num{color:#c9711f;}
  .stat-happy .stat-num{color:#c94a2c;}
  .stat-energy .stat-num{color:#4a68ab;}
  /* ---------- Doors: portal nodes ---------- */
  .room-door{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:74px;
    height:74px;
    border:none;
    padding:0;
    cursor:pointer;
    background:transparent;
    z-index:12;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:transform 0.3s cubic-bezier(.2,1,.3,1);
    overflow:visible;
  }
  .room-door.prev{left:16px;}
  .room-door.next{right:16px;}
  .room-door:hover{transform:translateY(-56%) scale(1.07);}
  .room-door:active{transform:translateY(-52%) scale(0.93);}
  /* rotating neon ring */
  .door-ring{
    position:absolute;
    inset:-6px;
    border-radius:50%;
    background:conic-gradient(from 0deg, var(--accent), var(--gold), var(--coral), var(--accent));
    filter:blur(7px);
    opacity:0.34;
    animation:doorSpin 10s linear infinite;
    transition:opacity 0.25s ease, filter 0.25s ease;
    pointer-events:none;
  }
  @keyframes doorSpin{ to{ transform:rotate(360deg); } }
  .room-door:hover .door-ring{opacity:0.85;filter:blur(9px);}
  /* glass core with the destination icon */
  .door-core{
    position:relative;
    width:100%;height:100%;
    border-radius:50%;
    border:3px solid #fff;
    background:linear-gradient(160deg, var(--accent), var(--accent-deep));
    box-shadow:0 4px 0 var(--accent-deep), 0 10px 20px var(--shadow);
    display:flex;align-items:center;justify-content:center;
    animation:doorPulse 3.4s ease-in-out infinite;
    transition:box-shadow 0.2s ease, transform 0.15s ease;
  }
  @keyframes doorPulse{
    0%,100%{box-shadow:0 4px 0 var(--accent-deep), 0 10px 20px var(--shadow);}
    50%{box-shadow:0 4px 0 var(--accent-deep), 0 10px 20px var(--shadow), 0 0 0 6px rgba(255,255,255,0.28);}
  }
  .room-door:hover .door-core{transform:translateY(-2px); box-shadow:0 6px 0 var(--accent-deep), 0 14px 24px var(--shadow);}
  .room-door:active .door-core{transform:translateY(3px); box-shadow:0 1px 0 var(--accent-deep), 0 4px 10px var(--shadow);}
  .door-face-icon{display:flex;align-items:center;justify-content:center;}
  .door-face-icon svg{width:26px;height:26px;stroke:#fff;fill:none;filter:drop-shadow(0 1px 0 rgba(0,0,0,0.14));}
  .door-face-icon svg.filled{fill:#fff;stroke:none;}
  /* directional chevron badge */
  .door-chevron{
    position:absolute;
    bottom:-2px;
    width:26px;height:26px;
    border-radius:50%;
    background:linear-gradient(160deg, var(--accent), var(--accent-deep));
    box-shadow:0 4px 10px var(--shadow), 0 0 0 3px var(--paper);
    display:flex;align-items:center;justify-content:center;
  }
  .door-chevron svg{width:13px;height:13px;stroke:#fff;stroke-width:3;}
  .door-chevron-prev{left:-6px;}
  .door-chevron-next{right:-6px;}
  /* floating HUD label tag */
  .door-tag{
    position:absolute;
    top:-42px;left:50%;
    transform:translate(-50%,6px);
    padding:6px 14px 6px 18px;
    background:rgba(64,44,34,0.92);
    color:#fff;
    font-family:'Nunito',sans-serif;
    font-weight:800;
    font-size:0.66rem;
    text-transform:uppercase;
    letter-spacing:0.05em;
    white-space:nowrap;
    clip-path:polygon(8% 0,100% 0,92% 100%,0 100%);
    opacity:0;
    pointer-events:none;
    transition:opacity 0.18s ease, transform 0.18s ease;
  }
  .door-tag::after{
    content:"";
    position:absolute;bottom:-5px;left:50%;
    transform:translateX(-50%);
    width:0;height:0;
    border-left:5px solid transparent;
    border-right:5px solid transparent;
    border-top:5px solid rgba(64,44,34,0.92);
  }
  .room-door:hover .door-tag{opacity:1;transform:translate(-50%,0);}
  /* level-select rail: quick jump between rooms, game world-map style */
  .room-rail{
    position:absolute;
    left:50%;bottom:18px;
    transform:translateX(-50%);
    z-index:14;
    display:flex;
    align-items:center;
    gap:10px;
    padding:9px 15px;
    border-radius:999px;
  }
  .rail-dot{
    width:11px;height:11px;
    border-radius:50%;
    border:2px solid rgba(255,255,255,0.7);
    padding:0;
    cursor:pointer;
    background:rgba(58,50,48,0.18);
    box-shadow:inset 0 1px 2px rgba(60,38,26,0.15);
    transition:width 0.25s cubic-bezier(.34,1.56,.64,1), border-radius 0.25s ease, background 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
  }
  .rail-dot:hover{background:rgba(58,50,48,0.32);}
  .rail-dot.active{
    width:28px;
    border-radius:7px;
    border-color:#fff;
    background:linear-gradient(90deg, var(--accent), var(--accent-deep));
    box-shadow:0 2px 0 var(--accent-deep), 0 0 10px var(--accent);
    animation:railPop 0.3s cubic-bezier(.34,1.56,.64,1);
  }
  @keyframes railPop{0%{transform:scale(0.6);}70%{transform:scale(1.15);}100%{transform:scale(1);}}
  /* ---------- Cat ---------- */
  /* Position is NOT set here as fixed percentages — that broke down
     whenever the crop or window aspect ratio changed. Instead JS
     (positionCat() below) measures the actual rendered box of
     .room-bg-main and places this element's left/top/transform in
     pixels, anchored to a fixed point *within the artwork itself*
     (see ROOM_ANCHOR in the script). That keeps the cat glued to
     the rug / counter / bed on any screen size. left/top/transform
     transition smoothly when the anchor is recalculated (room
     switch, resize). */
  .cat-stage{
    position:absolute;
    left:50%;
    top:50%;
    transform:translate(-50%,-100%) scale(1);
    transform-origin:bottom center;
    width:170px;height:210px;
    z-index:5;
    transition:left 0.45s ease, top 0.45s ease, transform 0.45s ease;
  }
  .mood-halo{
    position:absolute;
    left:50%;bottom:10px;
    transform:translateX(-50%);
    width:160px;height:160px;
    border-radius:50%;
    filter:blur(30px);
    opacity:0.55;
    background:var(--accent);
    transition:background 0.6s ease;
    z-index:0;
    animation:haloPulse 3.2s ease-in-out infinite;
  }
  @keyframes haloPulse{
    0%,100%{opacity:0.4;transform:translateX(-50%) scale(1);}
    50%{opacity:0.62;transform:translateX(-50%) scale(1.08);}
  }
  .cat-wrap{
    position:absolute;
    bottom:0;left:50%;
    transform:translateX(-50%);
    transform-origin:bottom center;
    width:230px;height:268px;
    cursor:pointer;
    z-index:2;
    /* Subtle idle sway only — a tiny rotation, no vertical bounce and
       no scale/float, so the cat reads as sitting still and breathing
       rather than hopping or hovering. */
    animation:catIdle 5.2s cubic-bezier(.45,.05,.55,.95) infinite;
  }
  @keyframes catIdle{
    0%{ transform:translateX(-50%) rotate(0deg) scale(1, 1); }
    18%{ transform:translateX(-50%) rotate(-0.7deg) scale(1.006, 0.994); }
    38%{ transform:translateX(-50%) rotate(0.3deg) scale(0.997, 1.01); }
    60%{ transform:translateX(-50%) rotate(1deg) scale(1.01, 0.995); }
    82%{ transform:translateX(-50%) rotate(-0.3deg) scale(1, 1.004); }
    100%{ transform:translateX(-50%) rotate(0deg) scale(1, 1); }
  }
  @keyframes shadowIdle{
    0%,100%{ opacity:0.85; transform:translateX(-50%) scale(1); }
    38%{ opacity:0.8; transform:translateX(-50%) scale(0.985,1); }
    60%{ opacity:0.95; transform:translateX(-50%) scale(1.01,1); }
  }
  .cat-shadow{
    position:absolute;
    bottom:-4px;left:50%;
    transform:translateX(-50%);
    width:110px;height:16px;
    background:rgba(60,38,26,0.2);
    border-radius:50%;
    filter:blur(2px);
    opacity:0.9;
    z-index:0;
    animation:shadowIdle 5.2s cubic-bezier(.45,.05,.55,.95) infinite;
  }
  .cat-pose-img{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:contain;
    object-position:bottom center;
    filter:none;
    transition:filter 0.3s ease, transform 0.3s ease;
    -webkit-user-drag:none;
    user-select:none;
    pointer-events:none;
    z-index:1;
  }
  .cat-img-sleep{display:none;}
  .cat-img-hungry{display:none;}
  .cat-img-eating{display:none;}
  .cat-pet-frame{display:none;}
  .cat-pet-frame.active{display:block;}
  .cat-play-frame{display:none;}
  .cat-play-frame.active{display:block;}
  /* hungry idle pose - shown only when idle (not sleeping/petting/eating/playing) */
  .cat-wrap.hungry:not(.mood-sleepy):not(.petting):not(.eating):not(.playing) .cat-img{display:none;}
  .cat-wrap.hungry:not(.mood-sleepy):not(.petting):not(.eating):not(.playing) .cat-img-hungry{display:block;}
  /* transient activity overlays always win over base/hungry/sleep pose */
  .cat-wrap.petting .cat-img,
  .cat-wrap.petting .cat-img-sleep,
  .cat-wrap.petting .cat-img-hungry{display:none;}
  .cat-wrap.eating .cat-img,
  .cat-wrap.eating .cat-img-sleep,
  .cat-wrap.eating .cat-img-hungry{display:none;}
  .cat-wrap.eating .cat-img-eating{display:block;}
  .cat-wrap.playing .cat-img,
  .cat-wrap.playing .cat-img-sleep,
  .cat-wrap.playing .cat-img-hungry{display:none;}
  .mood-happy .mood-halo{background:var(--coral);}
  .mood-neutral .mood-halo{background:var(--accent);}
  .mood-sad .mood-halo{background:#9aa0b4;opacity:0.35;animation:none;}
  .mood-sleepy .mood-halo{background:var(--dusk);animation:haloPulse 4.5s ease-in-out infinite;}
  .cat-wrap.mood-happy .cat-img{filter:brightness(1.05) saturate(1.12);}
  .cat-wrap.mood-neutral .cat-img{filter:none;}
  .cat-wrap.mood-sad .cat-img{filter:grayscale(0.35) brightness(0.92);transform:translateY(4px);}
  .cat-wrap.mood-sleepy{animation:none; width:390px; height:322px;}
  .cat-wrap.mood-sleepy .cat-img{display:none;}
  .cat-wrap.mood-sleepy .cat-img-sleep{display:block;}
  .cat-wrap.mood-sleepy .cat-shadow{width:300px;animation:none;opacity:0.9;}
  /* speech bubble */
  .bubble{
    position:absolute;
    top:6px;left:50%;
    transform:translate(-50%,-100%) scale(0.9);
    background:#fff;
    padding:11px 17px;
    border-radius:18px;
    font-size:0.95rem;
    font-weight:700;
    max-width:280px;
    white-space:normal;
    box-shadow:0 10px 26px rgba(0,0,0,0.16);
    opacity:0;
    pointer-events:none;
    transition:opacity 0.25s, transform 0.25s;
    z-index:6;
  }
  .bubble.show{opacity:1;transform:translate(-50%,-112%) scale(1);}
  .bubble::after{
    content:"";position:absolute;bottom:-8px;left:50%;
    transform:translateX(-50%);
    width:0;height:0;
    border-left:8px solid transparent;
    border-right:8px solid transparent;
    border-top:8px solid #fff;
  }
  /* particles */
  .particles{position:absolute;inset:0;pointer-events:none;overflow:visible;z-index:7;}
  .particle{
    position:absolute;
    bottom:44%;
    font-size:1.5rem;
    animation:particleRise 1.3s ease-out forwards;
  }
  @keyframes particleRise{
    0%{transform:translate(-50%,0) scale(0.6);opacity:0;}
    15%{opacity:1;transform:translate(-50%,-10px) scale(1);}
    100%{transform:translate(-50%,-140px) scale(1.1);opacity:0;}
  }
  /* ---------- Floating Action Icons (dock) ---------- */
  .dock{
    position:absolute;
    right:190px;
    bottom:36px;
    z-index:35;
    display:flex;
    align-items:center;
    gap:14px;
    padding:0;
    background:transparent;
    box-shadow:none;
  }
  .action-btn{
    --btn-fill:var(--coral);
    --btn-deep:var(--coral-deep);
    position:relative;
    width:50px;
    height:50px;
    padding:0;
    border:3px solid #fff;
    border-radius:50%;
    background:linear-gradient(160deg, var(--btn-fill), var(--btn-deep));
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:
      0 4px 0 var(--btn-deep),
      0 8px 14px rgba(60,38,26,0.22);
    transition:transform 0.14s cubic-bezier(.34,1.56,.64,1), box-shadow 0.14s ease;
  }
  .action-btn:hover{
    transform:translateY(-3px) rotate(-4deg);
    box-shadow:
      0 7px 0 var(--btn-deep),
      0 12px 18px rgba(60,38,26,0.26);
  }
  .action-btn:active{
    transform:translateY(3px) rotate(0deg);
    box-shadow:
      0 1px 0 var(--btn-deep),
      0 3px 8px rgba(60,38,26,0.2);
  }
  .action-btn .icon{
    width:auto;
    height:auto;
    border-radius:0;
    background:transparent;
    display:flex;
    align-items:center;
    justify-content:center;
  }
  .action-btn .icon svg{
    width:22px;
    height:22px;
    stroke:#fff;
    fill:none;
    filter:drop-shadow(0 1px 0 rgba(0,0,0,0.14));
    transition:transform 0.18s ease;
  }
  .action-btn .icon svg.filled{fill:#fff;stroke:none;}
  .action-btn:hover .icon svg{transform:scale(1.1);}
  /* Tooltip */
  .action-btn::after{
    content:attr(data-label);
    position:absolute;
    bottom:calc(100% + 9px);
    left:50%;
    transform:translateX(-50%) translateY(4px);
    padding:5px 9px;
    background:rgba(64,44,34,0.92);
    color:#fff;
    border-radius:8px;
    font-family:'Nunito',sans-serif;
    font-size:0.68rem;
    font-weight:800;
    white-space:nowrap;
    opacity:0;
    pointer-events:none;
    transition:opacity 0.15s ease, transform 0.15s ease;
  }
  .action-btn:hover::after{
    opacity:1;
    transform:translateX(-50%) translateY(0);
  }
  /* ---------- Chat FAB + floating chatbot widget ---------- */
  .chat-fab{
    position:absolute;
    bottom:26px;
    right:26px;
    z-index:40;
    width:92px;
    height:122px;
    border:none;
    padding:0;
    background:transparent;
    box-shadow:none;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:transform 0.18s ease;
    filter:drop-shadow(0 10px 18px var(--shadow));
  }
  .chat-fab::before{
    content:"";
    position:absolute;
    bottom:10px;left:50%;
    transform:translateX(-50%);
    width:58%;
    height:13px;
    background:radial-gradient(ellipse at center, rgba(60,38,26,0.3), transparent 72%);
    border-radius:50%;
    animation:fabShadowPulse 3.6s ease-in-out infinite;
  }
  @keyframes fabShadowPulse{
    0%,100%{transform:translateX(-50%) scale(1);opacity:0.6;}
    50%{transform:translateX(-50%) scale(0.82);opacity:0.38;}
  }
  .chat-fab::after{
    content:"";
    position:absolute;
    left:50%;top:42%;
    transform:translate(-50%,-50%);
    width:70px;height:70px;
    border-radius:50%;
    background:radial-gradient(circle, var(--glow-warm), transparent 70%);
    filter:blur(6px);
    z-index:-1;
    animation:fabGlow 3.6s ease-in-out infinite;
  }
  @keyframes fabGlow{
    0%,100%{opacity:0.45;transform:translate(-50%,-50%) scale(0.92);}
    50%{opacity:0.85;transform:translate(-50%,-50%) scale(1.08);}
  }
  .chat-fab img{
    width:100%;
    height:100%;
    object-fit:contain;
    pointer-events:none;
    -webkit-user-drag:none;
    animation:fabFloat 3.6s ease-in-out infinite;
    position:relative;
    z-index:1;
  }
  @keyframes fabFloat{
    0%,100%{transform:translateY(0);}
    50%{transform:translateY(-6px);}
  }
  .chat-fab:hover{transform:scale(1.08);}
  .chat-fab:active{transform:scale(0.93);}
  .fab-badge{
    position:absolute;
    top:8px;right:16px;
    width:16px;height:16px;
    border-radius:50%;
    background:linear-gradient(160deg, var(--coral), var(--coral-deep));
    border:2px solid #fff;
    box-shadow:0 2px 4px rgba(60,38,26,0.35);
    animation:badgePulse 1.3s ease-in-out infinite;
  }
  @keyframes badgePulse{
    0%,100%{transform:scale(1);}
    50%{transform:scale(1.22);}
  }
  /* invisible click-catcher so clicking outside the widget closes it,
     without dimming the rest of the site like a modal/sidebar would */
  .chat-scrim{
    position:fixed;inset:0;
    background:transparent;
    opacity:0;pointer-events:none;
    z-index:44;
  }
  .chat-scrim.open{pointer-events:auto;}
  .chat-widget{
    position:absolute;
    bottom:118px;right:24px;
    z-index:45;
    width:340px;
    max-width:calc(100vw - 32px);
    height:min(64dvh, 500px);
    max-height:calc(100dvh - 148px);
    background:var(--paper);
    border-radius:24px;
    border:3px solid #fff;
    box-shadow:0 8px 0 rgba(60,38,26,0.14), 0 26px 50px var(--shadow);
    display:flex;
    flex-direction:column;
    overflow:hidden;
    transform-origin:bottom right;
    transform:translateY(14px) scale(0.92);
    opacity:0;
    pointer-events:none;
    transition:transform 0.28s cubic-bezier(.2,1,.3,1), opacity 0.22s ease;
  }
  .chat-widget.open{
    transform:translateY(0) scale(1);
    opacity:1;
    pointer-events:auto;
  }
  .chat-head{
    position:relative;
    display:flex;align-items:center;gap:12px;
    padding:16px 16px 14px;
    background:linear-gradient(135deg, var(--gold) 0%, var(--coral) 55%, var(--coral-deep) 100%);
    color:#fff;
    flex:0 0 auto;
    overflow:hidden;
  }
  .chat-head::before{
    /* soft string-lights trim along the very top edge */
    content:"";
    position:absolute;
    top:0;left:0;right:0;height:4px;
    background:radial-gradient(circle, rgba(255,246,216,0.95) 1.6px, transparent 1.8px);
    background-size:16px 4px;
    background-position:2px 0;
    opacity:0.85;
    filter:drop-shadow(0 0 3px rgba(255,230,160,0.8));
  }
  .chat-head::after{
    content:"";
    position:absolute;
    left:14px;right:14px;bottom:0;
    border-bottom:2px dotted rgba(255,255,255,0.5);
  }
  .chat-head-avatar{
    width:44px;height:44px;
    display:flex;align-items:center;justify-content:center;
    flex:0 0 auto;
  }
  .chat-head-avatar img{width:100%;height:100%;object-fit:contain;}
  .chat-head-text{flex:1;min-width:0;}
  .chat-head-name{
    font-family:'Caveat',cursive;
    font-weight:700;
    font-size:1.5rem;
    line-height:1;
    text-shadow:0 1px 0 rgba(0,0,0,0.08);
  }
  .chat-head-status{
    display:flex;align-items:center;gap:6px;
    font-size:0.74rem;
    font-weight:700;
    color:rgba(255,255,255,0.85);
    margin-top:2px;
  }
  .chat-head-status::before{
    content:"";
    width:7px;height:7px;
    border-radius:50%;
    background:#8fe0a0;
    box-shadow:0 0 0 2px rgba(255,255,255,0.3);
  }
  .chat-close{
    border:2px solid rgba(255,255,255,0.55);background:rgba(255,255,255,0.2);color:#fff;
    width:32px;height:32px;border-radius:50%;
    cursor:pointer;flex:0 0 auto;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 2px 0 rgba(0,0,0,0.15);
    transition:background 0.15s ease, transform 0.12s ease, box-shadow 0.12s ease;
  }
  .chat-close:hover{background:rgba(255,255,255,0.32);transform:translateY(-1px);box-shadow:0 3px 0 rgba(0,0,0,0.15);}
  .chat-close:active{transform:translateY(2px);box-shadow:0 0 0 rgba(0,0,0,0.15);}
  .chat-close svg{width:14px;height:14px;stroke:currentColor;}
  .chat-log{
    flex:1;
    min-height:0;
    overflow-y:auto;
    overscroll-behavior:contain;
    -webkit-overflow-scrolling:touch;
    padding:16px 16px 8px;
    display:flex;
    flex-direction:column;
    gap:9px;
    background:
      radial-gradient(circle at 18% 8%, rgba(255,189,89,0.14), transparent 42%),
      radial-gradient(circle at 88% 92%, rgba(143,203,157,0.08), transparent 40%),
      radial-gradient(rgba(122,90,64,0.06) 1px, transparent 1.4px) 0 0/18px 18px,
      var(--paper);
  }
  .chat-row{display:flex;}
  .chat-row.from-cat{justify-content:flex-start;}
  .chat-row.from-user{justify-content:flex-end;}
  .chat-row.from-system{justify-content:center;}
  .msg{
    max-width:78%;
    padding:10px 15px;
    border-radius:17px;
    font-size:0.92rem;
    line-height:1.35;
    animation:msgPop 0.22s ease;
  }
  @keyframes msgPop{
    0%{opacity:0;transform:translateY(6px) scale(0.97);}
    100%{opacity:1;transform:translateY(0) scale(1);}
  }
  .msg.cat{
    background:#fff;
    border:2px solid #f5e9da;
    border-bottom-left-radius:6px;
    box-shadow:0 3px 0 #f0e2d0;
  }
  .msg.user{
    background:linear-gradient(160deg, var(--coral), var(--coral-deep));
    color:#fff;
    border:2px solid #fff;
    border-bottom-right-radius:6px;
    box-shadow:0 3px 0 var(--coral-deep);
  }
  .msg.typing{
    background:#fff;
    border:2px solid #f5e9da;
    border-bottom-left-radius:6px;
    box-shadow:0 3px 0 #f0e2d0;
    display:flex;
    align-items:center;
    gap:4px;
    padding:13px 16px;
  }
  .typing-dot{
    width:6px;height:6px;border-radius:50%;
    background:var(--ink-soft);
    opacity:0.5;
    animation:typingBounce 1s ease-in-out infinite;
  }
  .typing-dot:nth-child(2){animation-delay:0.15s;}
  .typing-dot:nth-child(3){animation-delay:0.3s;}
  @keyframes typingBounce{
    0%,60%,100%{transform:translateY(0);opacity:0.4;}
    30%{transform:translateY(-4px);opacity:0.9;}
  }
  .msg.system{
    background:rgba(58,50,48,0.06);
    color:var(--ink-soft);
    font-weight:700;
    font-size:0.72rem;
    letter-spacing:0.01em;
    padding:6px 14px;
    border-radius:999px;
    max-width:90%;
    text-align:center;
    box-shadow:none;
  }
  .chat-quick-row{
    display:flex;
    gap:8px;
    padding:0 16px 12px;
    overflow-x:auto;
    flex:0 0 auto;
  }
  .chat-quick-btn{
    --chip:var(--sage);
    --chip-deep:var(--sage-deep);
    flex:0 0 auto;
    background:linear-gradient(160deg, var(--chip), var(--chip-deep));
    border:2px solid #fff;
    color:#fff;
    font-family:'Nunito',sans-serif;
    font-weight:800;
    font-size:0.76rem;
    padding:8px 14px;
    border-radius:999px;
    cursor:pointer;
    white-space:nowrap;
    box-shadow:0 3px 0 var(--chip-deep);
    transition:transform 0.12s cubic-bezier(.34,1.56,.64,1), box-shadow 0.12s ease;
  }
  .chat-quick-btn:nth-child(2){--chip:var(--gold);--chip-deep:var(--gold-deep);}
  .chat-quick-btn:nth-child(3){--chip:var(--dusk);--chip-deep:var(--dusk-deep);}
  .chat-quick-btn:nth-child(4){--chip:var(--coral);--chip-deep:var(--coral-deep);}
  .chat-quick-btn:hover{transform:translateY(-2px);box-shadow:0 5px 0 var(--chip-deep);}
  .chat-quick-btn:active{transform:translateY(3px);box-shadow:0 0 0 var(--chip-deep);}
  .chat-input-row{
    display:flex;
    align-items:center;
    gap:9px;
    padding:12px 14px;
    border-top:1px solid #f0e6da;
    flex:0 0 auto;
  }
  .chat-input-row input{
    flex:1;
    border:2px solid #f0e6da;
    border-radius:26px;
    padding:11px 17px;
    font-family:'Nunito',sans-serif;
    font-size:0.92rem;
    outline:none;
    min-width:0;
    background:#fffdf9;
    box-shadow:inset 0 2px 4px rgba(58,50,48,0.07);
    transition:border-color 0.15s ease, box-shadow 0.15s ease;
  }
  .chat-input-row input:focus{
    border-color:var(--coral);
    box-shadow:inset 0 2px 4px rgba(58,50,48,0.07), 0 0 0 3px rgba(255,140,105,0.2);
  }
  .chat-input-row button{
    background:linear-gradient(160deg, var(--coral), var(--coral-deep));
    color:#fff;
    border:2px solid #fff;
    width:46px;height:46px;
    border-radius:50%;
    cursor:pointer;
    flex:0 0 auto;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 3px 0 var(--coral-deep);
    transition:transform 0.12s cubic-bezier(.34,1.56,.64,1), box-shadow 0.12s ease, filter 0.12s ease;
  }
  .chat-input-row button:hover{filter:brightness(1.05);transform:translateY(-2px);box-shadow:0 5px 0 var(--coral-deep);}
  .chat-input-row button:active{transform:translateY(3px);box-shadow:0 0 0 var(--coral-deep);}
  .chat-input-row button svg{width:18px;height:18px;fill:#fff;}
  /* ---------- Responsive ---------- */
  @media (max-width:720px){
    .topbar{
      padding:calc(12px + env(safe-area-inset-top)) 12px 0;
      gap:8px;
    }
    .title-pill{
      font-size:0.92rem;
      padding:6px 14px 6px 6px;
      gap:8px;
    }
    .title-pill .paw{width:26px;height:26px;}
    .title-pill .paw svg{width:13px;height:13px;}
    .room-pill{display:none;}
    .stats-pill{
      gap:0;
      padding:7px 10px;
    }
    .stat{gap:5px;padding:0 7px;}
    .stat-icon{width:20px;height:20px;}
    .stat-track{width:32px;height:6px;}
    .stat-num{font-size:0.68rem;min-width:16px;}
    .stat-icon svg{width:11px;height:11px;}
    .room-door{width:50px;height:50px;}
    .room-door.prev{left:calc(10px + env(safe-area-inset-left));}
    .room-door.next{right:calc(10px + env(safe-area-inset-right));}
    .door-core{border-width:2px;box-shadow:0 3px 0 var(--accent-deep), 0 7px 14px var(--shadow);}
    .door-face-icon svg{width:19px;height:19px;}
    .door-chevron{width:18px;height:18px;}
    .door-chevron svg{width:9px;height:9px;}
    .door-tag{display:none;}
    /* Cat sizing/position is fully handled by positionCat() via the
       .cat-stage transform scale — no fixed-pixel overrides here,
       so mobile scales correctly instead of double-scaling. */
    /* Bottom row is split into three independent zones so nothing
       overlaps on narrow screens: action dock bottom-left, room
       dots bottom-center, chat FAB bottom-right. */
    .dock{
      left:calc(12px + env(safe-area-inset-left));
      right:auto;
      bottom:calc(16px + env(safe-area-inset-bottom));
      gap:9px;
      padding:6px;
      border-radius:999px;
    }
    .action-btn{
      width:42px;height:42px;border-width:2px;
      box-shadow:0 3px 0 var(--btn-deep), 0 6px 10px rgba(60,38,26,0.2);
    }
    .action-btn:hover{box-shadow:0 5px 0 var(--btn-deep), 0 9px 14px rgba(60,38,26,0.24);}
    .action-btn:active{box-shadow:0 1px 0 var(--btn-deep), 0 2px 6px rgba(60,38,26,0.18);}
    .action-btn .icon svg{width:18px;height:18px;}
    .action-btn::after{display:none;}
    .room-rail{
      bottom:calc(16px + env(safe-area-inset-bottom));
      gap:6px;padding:7px 12px;
    }
    .rail-dot{width:7px;height:7px;}
    .rail-dot.active{width:16px;}
    /* Chat FAB: shrink to a thumb-friendly size that doesn't compete
       with the dock/room-rail for space, and pin its bottom offset
       to a CSS var so the widget below can line up against it
       exactly instead of guessing a fixed gap. */
    .chat-fab{
      --fab-bottom: calc(14px + env(safe-area-inset-bottom));
      bottom:var(--fab-bottom);
      right:calc(10px + env(safe-area-inset-right));
      width:52px;
      height:69px;
    }
    .fab-badge{width:12px;height:12px;top:2px;right:8px;}
    /* Chat widget: anchored to the FAB's own bottom offset + its
       height + a small gap, so it always sits just above the FAB
       with no dead space and no overlap, regardless of safe-area
       insets on the device. Height is capped against the *visual*
       viewport (100dvh) minus the topbar and the FAB row, so it
       never runs off-screen top or bottom, and a min-height keeps
       it usable on short landscape screens instead of collapsing. */
    .chat-widget{
      right:calc(8px + env(safe-area-inset-right));
      left:calc(8px + env(safe-area-inset-left));
      width:auto;
      max-width:none;
      bottom:calc(14px + env(safe-area-inset-bottom) + 69px + 10px);
      height:min(60dvh, 480px);
      max-height:calc(100dvh - 96px - env(safe-area-inset-top) - env(safe-area-inset-bottom));
      min-height:280px;
      border-radius:20px;
      border-width:2px;
      box-shadow:0 5px 0 rgba(60,38,26,0.14), 0 18px 34px var(--shadow);
    }
    .chat-head{padding:12px 14px;}
    .chat-head-avatar{width:38px;height:38px;}
    .chat-head-name{font-size:0.92rem;}
    .chat-head-status{font-size:0.68rem;}
    .chat-close{width:30px;height:30px;}
    .chat-log{padding:12px 12px 6px;gap:7px;}
    .msg{font-size:0.88rem;padding:9px 13px;}
    .chat-quick-row{padding:0 12px 10px;gap:6px;}
    .chat-quick-btn{font-size:0.72rem;padding:7px 12px;box-shadow:0 2px 0 var(--chip-deep);}
    .chat-quick-btn:hover{box-shadow:0 3px 0 var(--chip-deep);}
    .chat-quick-btn:active{box-shadow:0 0 0 var(--chip-deep);}
    .chat-input-row{padding:9px 10px;}
    .chat-input-row input{padding:10px 15px;font-size:0.88rem;}
    .chat-input-row button{width:40px;height:40px;box-shadow:0 2px 0 var(--coral-deep);}
    .chat-input-row button:hover{box-shadow:0 3px 0 var(--coral-deep);}
    .chat-input-row button:active{box-shadow:0 0 0 var(--coral-deep);}
  }
  /* Short / landscape phones: the 60dvh chat height above can be too
     tall when the viewport itself is short, squeezing the log down
     to almost nothing. Cap by height rather than width here. */
  @media (max-width:720px) and (max-height:480px){
    .chat-widget{
      height:min(80dvh, 320px);
      min-height:200px;
      bottom:calc(10px + env(safe-area-inset-bottom) + 60px + 8px);
    }
    .chat-fab{width:44px;height:58px;}
    .chat-log{padding:10px 12px 4px;}
    .chat-quick-row{display:none;}
  }
  @media (max-width:380px){
    .title-pill{font-size:0.82rem;padding:5px 10px 5px 5px;}
    .title-pill .paw{width:23px;height:23px;}
    .stats-pill{gap:0;padding:6px 7px;}
    .stat{gap:3px;padding:0 5px;}
    .stat-icon{width:18px;height:18px;}
    .stat-track{width:24px;}
    .stat-num{min-width:14px;}
    .room-door{width:44px;height:44px;}
    .dock{gap:7px;padding:5px;}
    .action-btn{width:38px;height:38px;}
    .action-btn .icon svg{width:16px;height:16px;}
    .chat-fab{width:46px;height:61px;}
    .chat-widget{
      bottom:calc(14px + env(safe-area-inset-bottom) + 61px + 10px);
      min-height:240px;
    }
  }
</style>
</head>
<body>
<div id="app">
  <div class="stage room-living" id="stage">
    <div class="room-bg-layer">
      <img class="room-bg-blur" id="roomBgBlur" src="/Assets/LivingRoom.jfif" alt="" aria-hidden="true">
      <img class="room-bg-main" id="roomBgMain" src="/Assets/LivingRoom.jfif" alt="">
    </div>
    <div class="stage-ambience" aria-hidden="true"></div>
    <div class="motes" id="motes" aria-hidden="true"></div>
    <button class="room-door prev" id="prevRoom" aria-label="Previous room">
      <span class="door-ring"></span>
      <span class="door-core"><span class="door-face-icon" id="prevIcon"></span></span>
      <span class="door-chevron door-chevron-prev"><svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg></span>
      <span class="door-tag" id="prevLabel">Bedroom</span>
    </button>
    <button class="room-door next" id="nextRoom" aria-label="Next room">
      <span class="door-ring"></span>
      <span class="door-core"><span class="door-face-icon" id="nextIcon"></span></span>
      <span class="door-chevron door-chevron-next"><svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg></span>
      <span class="door-tag" id="nextLabel">Kitchen</span>
    </button>
    <div class="room-rail glass" id="roomRail"></div>
    <div class="cat-stage">
      <div class="mood-halo"></div>
      <div class="cat-wrap mood-neutral" id="catWrap">
        <div class="cat-shadow"></div>
        <img class="cat-pose-img cat-img" src="/Assets/Character.png" alt="Whiskers the cat">
        <img class="cat-pose-img cat-img-sleep" src="/Assets/Sleeping.png" alt="Whiskers sleeping">
        <img class="cat-pose-img cat-img-hungry" src="/Assets/Hungry.png" alt="Whiskers is hungry">
        <img class="cat-pose-img cat-img-eating" src="/Assets/Eating.png" alt="Whiskers eating">
        <img class="cat-pose-img cat-pet-frame" id="petFrame1" src="/Assets/Pet.png" alt="Whiskers noticing pets">
        <img class="cat-pose-img cat-pet-frame" id="petFrame2" src="/Assets/pet1.png" alt="Whiskers enjoying pets">
        <img class="cat-pose-img cat-pet-frame" id="petFrame3" src="/Assets/Pet3.png" alt="Whiskers rolling over happily">
        <img class="cat-pose-img cat-play-frame" id="playFrame1" src="/Assets/Playing1.png" alt="Whiskers playing">
        <img class="cat-pose-img cat-play-frame" id="playFrame2" src="/Assets/Playing2.png" alt="Whiskers playing">
        <div class="bubble" id="bubble"></div>
      </div>
      <div class="particles" id="particles"></div>
    </div>
  </div>
  <div class="topbar">
    <div class="title-pill glass"><span class="paw"><svg viewBox="0 0 24 24"><circle cx="6.5" cy="7.5" r="2.1"/><circle cx="11.5" cy="5.3" r="2.1"/><circle cx="16.5" cy="7.5" r="2.1"/><circle cx="19.3" cy="12.2" r="1.9"/><path d="M12 20.2c-3.4 0-6-1.8-6-4.4 0-2.3 2.1-4 6-4s6 1.7 6 4c0 2.6-2.6 4.4-6 4.4Z"/></svg></span> Nova's House</div>
    <div class="room-pill glass" id="roomName">Living Room</div>
    <div class="stats-pill glass">
      <div class="stat stat-hunger">
        <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11h18"/><path d="M4 11c0 4.4 3.6 8 8 8s8-3.6 8-8"/><path d="M8 11V9a4 4 0 0 1 8 0v2"/></svg></span>
        <div class="stat-track"><div class="stat-fill fill-hunger" id="hungerFill" style="width:80%"></div></div>
        <span class="stat-num" id="hungerVal">80</span>
      </div>
      <div class="stat stat-happy">
        <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7-4.3-9.3-8.6C1.1 8.6 2.6 5 6 5c2 0 3.4 1.1 4 2.4C10.6 6.1 12 5 14 5c3.4 0 4.9 3.6 3.3 6.4C19 15.7 12 20 12 20Z"/></svg></span>
        <div class="stat-track"><div class="stat-fill fill-happy" id="happyFill" style="width:80%"></div></div>
        <span class="stat-num" id="happyVal">80</span>
      </div>
      <div class="stat stat-energy">
        <span class="stat-icon"><svg viewBox="0 0 24 24"><path d="M13 2 4 14h6l-1 8 9-12h-6l1-8Z"/></svg></span>
        <div class="stat-track"><div class="stat-fill fill-energy" id="energyFill" style="width:80%"></div></div>
        <span class="stat-num" id="energyVal">80</span>
      </div>
    </div>
  </div>
  <div class="dock glass" id="dock"></div>
  <button class="chat-fab" id="chatToggle" aria-label="Talk to Whiskers">
    <img src="/Assets/ForMessageBubble.png" alt="">
    <span class="fab-badge" id="fabBadge" style="display:none;"></span>
  </button>
</div>
<div class="chat-scrim" id="chatScrim"></div>
<div class="chat-widget" id="chatPanel" role="dialog" aria-label="Chat with Whiskers">
  <div class="chat-head">
    <span class="chat-head-avatar"><img src="/Assets/MessageBubble.png" alt=""></span>
    <div class="chat-head-text">
      <div class="chat-head-name">Nova</div>
      <div class="chat-head-status">Online</div>
    </div>
    <button class="chat-close" id="chatClose" aria-label="Close chat"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
  </div>
  <div class="chat-log" id="chatLog"></div>
  <div class="chat-quick-row" id="chatQuickRow">
    <button class="chat-quick-btn" data-quick="How are you?">How are you?</button>
    <button class="chat-quick-btn" data-quick="Are you hungry?">Are you hungry?</button>
    <button class="chat-quick-btn" data-quick="Wanna play?">Wanna play?</button>
    <button class="chat-quick-btn" data-quick="I love you!">I love you!</button>
  </div>
  <div class="chat-input-row">
    <input type="text" id="chatInput" placeholder="Say something to Whiskers&hellip;" maxlength="120">
    <button id="chatSend" aria-label="Send"><svg viewBox="0 0 24 24"><path d="M3 11l17-8-8 17-2-7-7-2Z"/></svg></button>
  </div>
</div>
<script>
(function(){
  /* ---------------- State ---------------- */
  const HUNGER_THRESHOLD = 30;
  const rooms = ['living','kitchen','bedroom'];
  const roomLabels = {living:'Living Room', kitchen:'Kitchen', bedroom:'Bedroom'};
  let roomIndex = 0;
  const state = {
    hunger: 80,
    happy: 80,
    energy: 80,
    sleeping: false,
    name: 'Nova'
  };
  const ROOM_ICON = {
    living: '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v6h16v-6"/><path d="M4 12a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2"/><path d="M6 10V8a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/></svg>',
    kitchen: '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10h16"/><path d="M5 10a7 7 0 0 0 14 0"/><path d="M9.5 10V6.5a2.5 2.5 0 0 1 5 0V10"/></svg>',
    bedroom: '<svg viewBox="0 0 24 24" class="filled"><path d="M20 14.5A8.5 8.5 0 0 1 9.5 4 8.5 8.5 0 1 0 20 14.5Z"/></svg>',
  };
  // The full background artwork for each room. Fills the stage at
  // 100% via object-fit:cover (see .room-bg CSS above), with
  // per-room object-position keeping the couch/counter/bed in frame.
  const ROOM_BG = {
    living: '/Assets/LivingRoom.jfif',
    kitchen: '/Assets/Kitchen.jfif',
    bedroom: '/Assets/Bedroom.jfif',
  };
  // Where the cat sits, as a percentage *of the artwork itself*
  // (x/y from the image's top-left corner) — not of the screen.
  // Because the background is shown at object-fit:contain (the
  // whole image, uncropped), these percentages always line up with
  // the same spot in the picture: the pet bed on the rug, the
  // counter by the spice rack, the quilt at the foot of the bed.
  // widthFrac is how wide the cat sprite should be as a fraction of
  // the rendered image width — this makes the cat's on-screen size
  // scale naturally with the artwork instead of staying a fixed
  // pixel size that looks wrong at other window sizes.
  const ROOM_ANCHOR = {
    living:  { x: 60, y: 87, widthFrac: 0.135 },
    kitchen: { x: 36, y: 55, widthFrac: 0.135 },
    bedroom: { x: 53, y: 63, widthFrac: 0.14 },
  };
  const CAT_BASE_WIDTH = 170; // matches .cat-stage width in CSS
  const els = {
    stage: document.getElementById('stage'),
    roomBgBlur: document.getElementById('roomBgBlur'),
    roomBgMain: document.getElementById('roomBgMain'),
    catStage: document.querySelector('.cat-stage'),
    roomName: document.getElementById('roomName'),
    prevLabel: document.getElementById('prevLabel'),
    nextLabel: document.getElementById('nextLabel'),
    prevIcon: document.getElementById('prevIcon'),
    nextIcon: document.getElementById('nextIcon'),
    roomRail: document.getElementById('roomRail'),
    catWrap: document.getElementById('catWrap'),
    bubble: document.getElementById('bubble'),
    particles: document.getElementById('particles'),
    dock: document.getElementById('dock'),
    hungerVal: document.getElementById('hungerVal'),
    happyVal: document.getElementById('happyVal'),
    energyVal: document.getElementById('energyVal'),
    hungerFill: document.getElementById('hungerFill'),
    happyFill: document.getElementById('happyFill'),
    energyFill: document.getElementById('energyFill'),
    chatToggle: document.getElementById('chatToggle'),
    fabBadge: document.getElementById('fabBadge'),
    chatPanel: document.getElementById('chatPanel'),
    chatClose: document.getElementById('chatClose'),
    chatScrim: document.getElementById('chatScrim'),
    chatLog: document.getElementById('chatLog'),
    chatQuickRow: document.getElementById('chatQuickRow'),
    chatInput: document.getElementById('chatInput'),
    chatSend: document.getElementById('chatSend'),
    petFrames: [
      document.getElementById('petFrame1'),
      document.getElementById('petFrame2'),
      document.getElementById('petFrame3'),
    ],
    playFrames: [
      document.getElementById('playFrame1'),
      document.getElementById('playFrame2'),
    ],
  };
  const ICON = {
    play: '<svg viewBox="0 0 24 24" class="filled"><path d="M12 2c.6 3.7 2.3 5.9 6 6.5-3.7.6-5.4 2.8-6 6.5-.6-3.7-2.3-5.9-6-6.5 3.7-.6 5.4-2.8 6-6.5Z"/><path d="M19 15c.3 1.8 1.1 2.9 3 3.2-1.9.3-2.7 1.4-3 3.2-.3-1.8-1.1-2.9-3-3.2 1.9-.3 2.7-1.4 3-3.2Z"/></svg>',
    pet: '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 13V6a1.4 1.4 0 0 1 2.8 0v6"/><path d="M10.8 12V4.6a1.4 1.4 0 0 1 2.8 0V12"/><path d="M13.6 12V5.6a1.4 1.4 0 0 1 2.8 0V13"/><path d="M16.4 13V8.4a1.4 1.4 0 0 1 2.8 0V15c0 3.6-2.5 6.5-6.5 6.5-2.8 0-4.3-1-5.6-2.8l-2.7-4.1a1.3 1.3 0 0 1 2-1.6L8 13"/></svg>',
    feed: '<svg viewBox="0 0 24 24" class="filled"><path d="M15.4 15.4 6 20.8l-.8-.8 5.4-9.4a4.6 4.6 0 0 1-1-2.9 5 5 0 1 1 8.6 3.5 4.6 4.6 0 0 1-2.8 1Z"/></svg>',
    treat: '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11Z"/></svg>',
    nap: '<svg viewBox="0 0 24 24" class="filled"><path d="M20 14.5A8.5 8.5 0 0 1 9.5 4 8.5 8.5 0 1 0 20 14.5Z"/></svg>',
  };
  const actionsByRoom = {
    living: [
      {icon:ICON.play, label:'Play', run: playWithCat, fill:'--sage', deep:'--sage-deep'},
      {icon:ICON.pet, label:'Pet', run: petCat, fill:'--blush', deep:'--blush-deep'},
    ],
    kitchen: [
      {icon:ICON.feed, label:'Feed', run: feedCat, fill:'--gold', deep:'--gold-deep'},
      {icon:ICON.treat, label:'Treat', run: giveTreat, fill:'--coral', deep:'--coral-deep'},
    ],
    bedroom: [
      {icon:ICON.nap, label: 'Nap', run: toggleSleep, fill:'--dusk', deep:'--dusk-deep'},
    ]
  };
  /* ---------------- Rendering ---------------- */
  function renderRoom(){
    const room = rooms[roomIndex];
    cancelPetAnimation();
    cancelEatAnimation();
    cancelPlayAnimation();
    els.stage.className = 'stage room-' + room;
    els.roomBgBlur.src = ROOM_BG[room];
    els.roomBgMain.src = ROOM_BG[room];
    els.roomName.textContent = roomLabels[room];
    const prevRoom = rooms[(roomIndex - 1 + rooms.length) % rooms.length];
    const nextRoom = rooms[(roomIndex + 1) % rooms.length];
    els.prevLabel.textContent = roomLabels[prevRoom];
    els.nextLabel.textContent = roomLabels[nextRoom];
    els.prevIcon.innerHTML = ROOM_ICON[prevRoom];
    els.nextIcon.innerHTML = ROOM_ICON[nextRoom];
    renderDock();
    renderRail();
    positionCat();
  }
  /* ---------------- Cat anchoring ----------------
     Measures the actual rendered box of the (object-fit:contain)
     background image within the stage, then converts the room's
     image-relative anchor point (ROOM_ANCHOR) into real pixels so
     the cat sits exactly on the rug/counter/bed no matter the
     window size or aspect ratio. Re-run on room change, image load,
     and window resize. */
  function positionCat(){
    const img = els.roomBgMain;
    if (!img.naturalWidth || !img.naturalHeight) return; // wait for load
    const anchor = ROOM_ANCHOR[rooms[roomIndex]];
    const containerW = els.stage.clientWidth;
    const containerH = els.stage.clientHeight;
    const imgRatio = img.naturalWidth / img.naturalHeight;
    const containerRatio = containerW / containerH;
    let renderW, renderH, offsetX, offsetY;
    if (imgRatio > containerRatio){
      renderW = containerW;
      renderH = containerW / imgRatio;
      offsetX = 0;
      offsetY = (containerH - renderH) / 2;
    } else {
      renderH = containerH;
      renderW = containerH * imgRatio;
      offsetY = 0;
      offsetX = (containerW - renderW) / 2;
    }
    const px = offsetX + (anchor.x / 100) * renderW;
    const py = offsetY + (anchor.y / 100) * renderH;
    const scale = Math.max(0.35, Math.min(1.6, (anchor.widthFrac * renderW) / CAT_BASE_WIDTH));
    els.catStage.style.left = px + 'px';
    els.catStage.style.top = py + 'px';
    els.catStage.style.transform = `translate(-50%, -100%) scale(${scale})`;
  }
  let resizeRAF = null;
  window.addEventListener('resize', ()=>{
    cancelAnimationFrame(resizeRAF);
    resizeRAF = requestAnimationFrame(positionCat);
  });
  els.roomBgMain.addEventListener('load', positionCat);
  /* ---------------- Ambient dust motes ----------------
     Purely decorative floating light specks for a cozy, relaxing
     feel. Independent of the cat/room-anchoring system above. */
  const motesEl = document.getElementById('motes');
  function spawnMote(){
    if (!motesEl) return;
    const m = document.createElement('span');
    m.className = 'mote';
    const size = 3 + Math.random()*4;
    m.style.width = size + 'px';
    m.style.height = size + 'px';
    m.style.left = (Math.random()*94 + 3) + '%';
    m.style.setProperty('--drift', (Math.random()*60 - 30) + 'px');
    const dur = 10 + Math.random()*9;
    m.style.animationDuration = dur + 's';
    motesEl.appendChild(m);
    setTimeout(()=> m.remove(), dur*1000 + 200);
  }
  for (let i=0;i<7;i++){ setTimeout(spawnMote, i*900); }
  setInterval(spawnMote, 1900);
  function renderRail(){
    els.roomRail.innerHTML = '';
    rooms.forEach((r, i)=>{
      const dot = document.createElement('button');
      dot.className = 'rail-dot' + (i === roomIndex ? ' active' : '');
      dot.setAttribute('aria-label', 'Go to ' + roomLabels[r]);
      dot.addEventListener('click', ()=>{
        roomIndex = i;
        renderRoom();
      });
      els.roomRail.appendChild(dot);
    });
  }
  function renderDock(){
    const room = rooms[roomIndex];
    els.dock.innerHTML = '';
    actionsByRoom[room].forEach(a=>{
      const btn = document.createElement('button');
      btn.className = 'action-btn';
      btn.dataset.label = a.label;
      btn.setAttribute('aria-label', a.label);
      btn.innerHTML = `<span class="icon">${a.icon}</span>`;
      btn.style.setProperty('--btn-fill', `var(${a.fill})`);
      btn.style.setProperty('--btn-deep', `var(${a.deep})`);
      btn.addEventListener('click', a.run);
      els.dock.appendChild(btn);
    });
  }
  function clamp(n){ return Math.max(0, Math.min(100, n)); }
  function renderStats(){
    els.hungerVal.textContent = Math.round(state.hunger);
    els.happyVal.textContent = Math.round(state.happy);
    els.energyVal.textContent = Math.round(state.energy);
    els.hungerFill.style.width = state.hunger + '%';
    els.happyFill.style.width = state.happy + '%';
    els.energyFill.style.width = state.energy + '%';
  }
  function currentMood(){
    if (state.sleeping) return 'sleepy';
    const avg = (state.hunger + state.happy + state.energy) / 3;
    if (avg >= 65) return 'happy';
    if (avg >= 35) return 'neutral';
    return 'sad';
  }
  const MOOD_CLASSES = ['mood-happy','mood-neutral','mood-sad','mood-sleepy'];
  function renderMood(){
    const mood = currentMood();
    MOOD_CLASSES.forEach(m => els.catWrap.classList.remove(m));
    els.catWrap.classList.add('mood-' + mood);
    els.catWrap.classList.toggle('hungry', state.hunger < HUNGER_THRESHOLD);
  }
  function renderAll(){
    renderStats();
    renderMood();
  }
  /* ---------------- Bubble (over the cat) ---------------- */
  let bubbleTimer = null;
  let chatHistory = []; // [{role:'user'|'assistant', content:'...'}] — sent to cat-chat.php for context
  function say(text){
    els.bubble.textContent = text;
    els.bubble.classList.add('show');
    clearTimeout(bubbleTimer);
    bubbleTimer = setTimeout(()=> els.bubble.classList.remove('show'), 2600);
  }
  /* ---------------- Particles ---------------- */
  function spawnParticle(emoji){
    const span = document.createElement('span');
    span.className = 'particle';
    span.textContent = emoji;
    span.style.left = (42 + Math.random()*16) + '%';
    els.particles.appendChild(span);
    setTimeout(()=> span.remove(), 1400);
  }
  /* ---------------- Actions ---------------- */
  function feedCat(){
    if (state.sleeping) { say("Zzz... too sleepy to eat."); return; }
    state.hunger = clamp(state.hunger + 25);
    state.happy = clamp(state.happy + 4);
    renderAll();
    playEatAnimation();
    spawnParticle('🍗');
    const line = pick(["Nom nom nom!", "Yummy!", "More please? 🐟"]);
    say(line);
    logAction('🍗 You fed Whiskers', line);
  }
  function giveTreat(){
    if (state.sleeping) { say("Zzz..."); return; }
    state.hunger = clamp(state.hunger + 10);
    state.happy = clamp(state.happy + 10);
    renderAll();
    playEatAnimation();
    spawnParticle('🥛');
    const line = pick(["A treat?! Best day ever!", "Purrrr~"]);
    say(line);
    logAction('🥛 You gave a treat', line);
  }
  function playWithCat(){
    if (state.sleeping) { say("Let me sleep a bit more..."); return; }
    if (state.energy < 10){ say("Too tired to play right now."); return; }
    state.happy = clamp(state.happy + 18);
    state.energy = clamp(state.energy - 12);
    renderAll();
    bounceCat();
    playPlayAnimation();
    spawnParticle('✨');
    const line = pick(["Wheee!", "Again, again!", "This is fun!"]);
    say(line);
    logAction('✨ You played together', line);
  }
  function petCat(){
    if (state.sleeping) { say("Purrrr... (still sleeping)"); return; }
    state.happy = clamp(state.happy + 8);
    renderAll();
    spawnParticle('💕');
    const line = pick(["Purrrrr...", "I love that!", "😻"]);
    say(line);
    playPetAnimation();
    logAction('💕 You petted Whiskers', line);
  }
  let petAnimTimer = null;
  function cancelPetAnimation(){
    clearTimeout(petAnimTimer);
    els.petFrames.forEach(f => f.classList.remove('active'));
    els.catWrap.classList.remove('petting');
  }
  function playPetAnimation(){
    cancelPetAnimation();
    cancelEatAnimation();
    cancelPlayAnimation();
    els.catWrap.classList.add('petting');
    const sequence = [
      { frame: 0, hold: 280 },
      { frame: 1, hold: 650 },
      { frame: 2, hold: 750 },
    ];
    let step = 0;
    function showStep(){
      els.petFrames.forEach(f => f.classList.remove('active'));
      if (step < sequence.length){
        els.petFrames[sequence[step].frame].classList.add('active');
        petAnimTimer = setTimeout(()=>{ step++; showStep(); }, sequence[step].hold);
      } else {
        els.catWrap.classList.remove('petting');
      }
    }
    showStep();
  }
  let eatAnimTimer = null;
  function cancelEatAnimation(){
    clearTimeout(eatAnimTimer);
    els.catWrap.classList.remove('eating');
  }
  function playEatAnimation(){
    cancelEatAnimation();
    cancelPetAnimation();
    cancelPlayAnimation();
    els.catWrap.classList.add('eating');
    eatAnimTimer = setTimeout(()=>{
      els.catWrap.classList.remove('eating');
    }, 900);
  }
  let playAnimTimer = null;
  function cancelPlayAnimation(){
    clearTimeout(playAnimTimer);
    els.playFrames.forEach(f => f.classList.remove('active'));
    els.catWrap.classList.remove('playing');
  }
  function playPlayAnimation(){
    cancelPlayAnimation();
    cancelPetAnimation();
    cancelEatAnimation();
    els.catWrap.classList.add('playing');
    const sequence = [0, 1, 0, 1];
    let step = 0;
    function showStep(){
      els.playFrames.forEach(f => f.classList.remove('active'));
      if (step < sequence.length){
        els.playFrames[sequence[step]].classList.add('active');
        playAnimTimer = setTimeout(()=>{ step++; showStep(); }, 320);
      } else {
        els.catWrap.classList.remove('playing');
      }
    }
    showStep();
  }
  function toggleSleep(){
    state.sleeping = !state.sleeping;
    if (state.sleeping){
      cancelPetAnimation();
      cancelEatAnimation();
      cancelPlayAnimation();
      spawnParticle('💤');
      say("Goodnight...");
      logAction('💤 Whiskers went to nap', 'Goodnight...');
    } else {
      const line = pick(["I'm awake!", "Good morning!"]);
      say(line);
      logAction('☀️ Whiskers woke up', line);
    }
    renderMood();
  }
  function bounceCat(){
    els.catWrap.style.animation = 'none';
    void els.catWrap.offsetWidth;
    els.catWrap.style.animation = '';
  }
  function pick(arr){ return arr[Math.floor(Math.random()*arr.length)]; }
  /* ---------------- Stat decay loop ---------------- */
  setInterval(()=>{
    if (state.sleeping){
      state.energy = clamp(state.energy + 6);
      if (state.energy >= 100){
        state.sleeping = false;
        say("I'm fully rested!");
        logAction('☀️ Whiskers woke up', "I'm fully rested!");
      }
    } else {
      state.hunger = clamp(state.hunger - 2);
      state.energy = clamp(state.energy - 1.5);
      state.happy = clamp(state.happy - (state.hunger < 20 ? 3 : 1));
    }
    renderAll();
  }, 4000);
  /* ---------------- Room navigation ---------------- */
  document.getElementById('prevRoom').addEventListener('click', ()=>{
    roomIndex = (roomIndex - 1 + rooms.length) % rooms.length;
    renderRoom();
  });
  document.getElementById('nextRoom').addEventListener('click', ()=>{
    roomIndex = (roomIndex + 1) % rooms.length;
    renderRoom();
  });
  els.catWrap.addEventListener('click', petCat);
  /* ---------------- Chat widget ---------------- */
  function openChat(){
    els.chatPanel.classList.add('open');
    els.chatScrim.classList.add('open');
    els.fabBadge.style.display = 'none';
    els.chatInput.focus();
  }
  function closeChat(){
    els.chatPanel.classList.remove('open');
    els.chatScrim.classList.remove('open');
  }
  els.chatToggle.addEventListener('click', ()=>{
    els.chatPanel.classList.contains('open') ? closeChat() : openChat();
  });
  els.chatClose.addEventListener('click', closeChat);
  els.chatScrim.addEventListener('click', closeChat);
  els.chatQuickRow.addEventListener('click', (e)=>{
    const btn = e.target.closest('.chat-quick-btn');
    if (!btn) return;
    els.chatInput.value = btn.dataset.quick;
    sendChat();
  });
  function addMessage(text, from){
    const row = document.createElement('div');
    row.className = 'chat-row from-' + from;
    const bubble = document.createElement('div');
    bubble.className = 'msg ' + from;
    bubble.textContent = text;
    row.appendChild(bubble);
    els.chatLog.appendChild(row);
    els.chatLog.scrollTop = els.chatLog.scrollHeight;
    return bubble;
  }
  function addTyping(){
    const row = document.createElement('div');
    row.className = 'chat-row from-cat';
    const bubble = document.createElement('div');
    bubble.className = 'msg typing';
    bubble.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
    row.appendChild(bubble);
    els.chatLog.appendChild(row);
    els.chatLog.scrollTop = els.chatLog.scrollHeight;
    return row;
  }
  // Logs an in-game action (feed/play/pet/nap) into the chat as a small
  // centered system bubble, followed by Whiskers' reaction as a cat bubble.
  function logAction(actionText, reactionText){
    addMessage(actionText, 'system');
    if (reactionText) addMessage(reactionText, 'cat');
    if (!els.chatPanel.classList.contains('open')){
      els.fabBadge.style.display = 'block';
    }
  }
  /*
   * getCatReply(message)
   * -------------------------------------------------------------
   * Calls cat-chat.php, which proxies the message to Claude (see that
   * file for the backend). Sends along the cat's live stats + room so
   * replies react to the current game state, plus a short rolling
   * history for conversational context. Falls back to canned local
   * lines if the request fails, so chat never goes silent.
   */
  async function getCatReply(message){
    if (state.sleeping) return pick(["Zzz... five more minutes...", "*rolls over, still asleep*"]);
    const payload = {
      message,
      history: chatHistory,
      state: {
        name: state.name,
        room: roomLabels[rooms[roomIndex]],
        mood: currentMood(),
        hunger: Math.round(state.hunger),
        happy: Math.round(state.happy),
        energy: Math.round(state.energy),
        sleeping: state.sleeping,
      }
    };
    try{
      const res = await fetch('/api/cat-chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if (!res.ok || !data.reply) throw new Error(data.error || 'Bad response');
      return data.reply;
    } catch(err){
      console.error('Cat chat error:', err);
      return pick([
        "Meow? (I'm having trouble finding my words right now)",
        "Purrrr~ *connection hiccup, try again?*",
        "*tilts head* my brain's a little foggy right now."
      ]);
    }
  }
  async function sendChat(){
    const text = els.chatInput.value.trim();
    if (!text) return;
    addMessage(text, 'user');
    els.chatInput.value = '';
    state.happy = clamp(state.happy + 3);
    renderStats();
    const typingRow = addTyping();
    const reply = await getCatReply(text);
    chatHistory.push({ role: 'user', content: text });
    chatHistory.push({ role: 'assistant', content: reply });
    if (chatHistory.length > 10) chatHistory = chatHistory.slice(-10);
    setTimeout(()=>{
      typingRow.remove();
      addMessage(reply, 'cat');
      say(reply);
    }, 500 + Math.random()*400);
  }
  els.chatSend.addEventListener('click', sendChat);
  els.chatInput.addEventListener('keydown', e=>{
    if (e.key === 'Enter') sendChat();
  });
  /* ---------------- Init ---------------- */
  renderRoom();
  renderAll();
  positionCat();
  addMessage("Meow! I'm " + state.name + ". Talk to me!", 'cat');
})();
</script>
</body>
</html>