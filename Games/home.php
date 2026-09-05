<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Shero's House</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#2f2a3d;
    --ink-soft:#7a7086;
    --paper:#fff8ef;
    --paper-glass:rgba(255,248,239,0.66);
    --paper-glass-strong:rgba(255,248,239,0.92);
    --coral:#ff8c69;
    --coral-deep:#e2603f;
    --gold:#ffbd59;
    --gold-deep:#e2963a;
    --sage:#8fcb9d;
    --sage-deep:#5fa877;
    --dusk:#8fa3d6;
    --dusk-deep:#5b71ab;
    --shadow:rgba(30,22,46,0.28);
    --accent:var(--sage);
    --accent-deep:var(--sage-deep);
    --room-top:#d9ecdf;
    --room-floor:#a9d4b6;
  }
  *{box-sizing:border-box;}
  html,body{height:100%;margin:0;overflow:hidden;}
  body{
    font-family:'Nunito',sans-serif;
    color:var(--ink);
    background:linear-gradient(160deg,#241a34,#3d2c53);
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
    position:absolute;
    inset:0;
    overflow:hidden;
    background:linear-gradient(180deg,var(--room-top) 0%,var(--room-top) 62%,var(--room-floor) 62%);
    transition:background 0.6s ease;
  }
  .stage.room-living{--room-top:#dcecd8;--room-floor:#a9d491;--accent:var(--sage);--accent-deep:var(--sage-deep);}
  .stage.room-kitchen{--room-top:#fbe7bd;--room-floor:#eec073;--accent:var(--gold);--accent-deep:var(--gold-deep);}
  .stage.room-bedroom{--room-top:#d6e0f2;--room-floor:#a9bce3;--accent:var(--dusk);--accent-deep:var(--dusk-deep);}

  .stage::before,.stage::after{
    content:"";
    position:absolute;
    border-radius:50%;
    filter:blur(70px);
    opacity:0.4;
    background:var(--accent);
    pointer-events:none;
    z-index:0;
    animation:driftGlow 9s ease-in-out infinite alternate;
  }
  .stage::before{width:280px;height:280px;top:-70px;right:-60px;}
  .stage::after{width:230px;height:230px;bottom:60px;left:-70px;animation-duration:11s;}
  @keyframes driftGlow{
    0%{transform:translate(0,0) scale(1);}
    100%{transform:translate(14px,-18px) scale(1.08);}
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
  .topbar > *{pointer-events:auto;}
  .glass{
    background:var(--paper-glass);
    backdrop-filter:blur(16px) saturate(1.3);
    -webkit-backdrop-filter:blur(16px) saturate(1.3);
    box-shadow:0 10px 30px var(--shadow);
  }
  .title-pill{
    display:flex;align-items:center;gap:9px;
    padding:11px 20px 11px 15px;
    border-radius:999px;
    font-family:'Fredoka',sans-serif;
    font-weight:600;
    font-size:1.15rem;
    color:var(--coral-deep);
    white-space:nowrap;
  }
  .title-pill .paw{display:flex;align-items:center;justify-content:center;}
  .title-pill .paw svg{width:20px;height:20px;fill:currentColor;}

  .room-pill{
    align-self:center;
    padding:9px 22px;
    border-radius:999px;
    font-family:'Fredoka',sans-serif;
    font-weight:600;
    font-size:1rem;
    color:var(--ink);
    letter-spacing:0.02em;
    transition:color 0.4s ease;
  }

  .stats-pill{
    display:flex;
    align-items:center;
    gap:18px;
    padding:11px 22px;
    border-radius:999px;
  }
  .stat{display:flex;align-items:center;gap:8px;}
  .stat-icon{display:flex;align-items:center;justify-content:center;line-height:1;}
  .stat-icon svg{width:17px;height:17px;display:block;stroke:currentColor;}
  .stat-hunger .stat-icon{color:#e6883a;}
  .stat-happy .stat-icon{color:#e2603f;}
  .stat-energy .stat-icon{color:#5b7fc4;fill:currentColor;}
  .stat-track{
    width:66px;height:9px;
    border-radius:6px;
    background:rgba(58,50,48,0.12);
    overflow:hidden;
  }
  .stat-fill{
    height:100%;
    border-radius:6px;
    transition:width 0.5s ease, background 0.5s ease;
  }
  .fill-hunger{background:linear-gradient(90deg,#f0ab5e,#e6883a);}
  .fill-happy{background:linear-gradient(90deg,#ff9d7d,#e2603f);}
  .fill-energy{background:linear-gradient(90deg,#a2c2ea,#6f95cf);}
  .stat-num{font-size:0.78rem;font-weight:800;color:var(--ink-soft);min-width:20px;text-align:right;}

  /* ---------- Doors ---------- */
  .room-door{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:56px;
    height:200px;
    border:none;
    padding:0;
    cursor:pointer;
    background:linear-gradient(160deg, var(--accent), var(--accent-deep));
    border-radius:26px 26px 6px 6px;
    box-shadow:0 12px 24px var(--shadow), inset 0 0 0 3px rgba(255,255,255,0.35);
    z-index:12;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:flex-end;
    gap:6px;
    padding-bottom:14px;
    transition:transform 0.2s ease, filter 0.15s ease, background 0.5s ease, box-shadow 0.2s ease;
    overflow:visible;
  }
  .room-door:hover{transform:translateY(-56%) scale(1.04);filter:brightness(1.08);box-shadow:0 16px 30px var(--shadow), 0 0 0 6px rgba(255,255,255,0.18), inset 0 0 0 3px rgba(255,255,255,0.4);}
  .room-door:active{transform:translateY(-52%) scale(0.99);}
  .room-door.prev{left:22px;}
  .room-door.next{right:22px;}
  .door-window{
    position:absolute;
    top:22px;left:50%;
    transform:translateX(-50%);
    width:22px;height:22px;
    border-radius:50%;
    background:rgba(234,246,255,0.8);
    box-shadow:inset 0 0 0 2px rgba(255,255,255,0.75);
  }
  .door-arrow{
    display:flex;
    align-items:center;
    justify-content:center;
    line-height:1;
  }
  .door-arrow svg{width:20px;height:20px;stroke:#fff;}
  .door-label{
    font-family:'Nunito',sans-serif;
    font-weight:800;
    font-size:0.68rem;
    text-transform:uppercase;
    letter-spacing:0.04em;
    color:rgba(255,255,255,0.92);
    text-align:center;
    max-width:70px;
  }

  /* room props */
  .prop{position:absolute;}
  .window{
    width:118px;height:92px;top:118px;left:44px;
    background:#eaf6ff;
    border:7px solid #fff;
    border-radius:10px;
    box-shadow:0 6px 16px rgba(0,0,0,0.1);
  }
  .window::before,.window::after{content:"";position:absolute;background:#fff;}
  .window::before{left:50%;top:0;bottom:0;width:6px;transform:translateX(-50%);}
  .window::after{top:50%;left:0;right:0;height:6px;transform:translateY(-50%);}
  .rug{
    width:260px;height:72px;
    left:50%;bottom:26px;
    transform:translateX(-50%);
    background:var(--accent);
    opacity:0.5;
    border-radius:50%;
  }
  .bowl{
    width:76px;height:34px;
    bottom:30px;left:60px;
    background:var(--accent-deep);
    border-radius:0 0 36px 36px;
  }
  .bowl::before{
    content:"";position:absolute;top:-9px;left:4px;right:4px;height:16px;
    background:#f3c98c;border-radius:50%;
  }
  .cabinet{
    width:130px;height:112px;
    bottom:80px;right:40px;
    background:#fff;
    border-radius:12px 12px 0 0;
    border:5px solid var(--accent);
  }
  .bed{
    width:210px;height:98px;
    bottom:30px;left:50%;
    transform:translateX(-50%);
    background:#fff;
    border-radius:24px;
    box-shadow:0 6px 0 rgba(0,0,0,0.05);
  }
  .bed::before{
    content:"";position:absolute;top:-24px;left:18px;right:18px;height:44px;
    background:var(--accent);border-radius:20px 20px 0 0;
  }

  /* ---------- Cat ---------- */
  .cat-stage{
    position:absolute;
    bottom:130px;left:50%;
    transform:translateX(-50%);
    width:320px;height:420px;
    z-index:5;
  }
  .mood-halo{
    position:absolute;
    left:50%;bottom:20px;
    transform:translateX(-50%);
    width:300px;height:300px;
    border-radius:50%;
    filter:blur(44px);
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
    width:300px;height:398px;
    cursor:pointer;
    z-index:2;
    animation:bob 2.6s ease-in-out infinite;
  }
  @keyframes bob{
    0%,100%{transform:translateX(-50%) translateY(0);}
    50%{transform:translateX(-50%) translateY(-10px);}
  }
  .cat-shadow{
    position:absolute;
    bottom:-6px;left:50%;
    transform:translateX(-50%);
    width:195px;height:30px;
    background:rgba(30,22,46,0.2);
    border-radius:50%;
    filter:blur(2px);
    z-index:0;
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
  .cat-wrap.mood-sleepy .cat-shadow{width:300px;}

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

  /* ---------- Dock (floating glass) ---------- */
  .dock{
    position:absolute;
    bottom:26px;left:50%;
    transform:translateX(-50%);
    z-index:25;
    display:flex;
    gap:14px;
    padding:14px 16px;
    border-radius:26px;
  }
  .action-btn{
    background:linear-gradient(160deg, var(--coral), var(--coral-deep));
    color:#fff;
    border:none;
    padding:12px 22px 13px;
    border-radius:20px;
    font-family:'Nunito',sans-serif;
    font-weight:800;
    font-size:0.82rem;
    letter-spacing:0.01em;
    cursor:pointer;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:6px;
    box-shadow:0 8px 18px rgba(226,96,63,0.35);
    transition:transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
  }
  .action-btn:hover{filter:brightness(1.05);transform:translateY(-2px);}
  .action-btn:active{transform:scale(0.95) translateY(0);}
  .action-btn .icon{
    width:36px;height:36px;
    border-radius:50%;
    background:rgba(255,255,255,0.24);
    display:flex;align-items:center;justify-content:center;
  }
  .action-btn .icon svg{width:19px;height:19px;stroke:#fff;fill:none;}
  .action-btn .icon svg.filled{fill:#fff;stroke:none;}

  /* ---------- Chat FAB + drawer ---------- */
  .chat-fab{
    position:absolute;
    bottom:30px;right:26px;
    z-index:35;
    width:60px;height:60px;
    border-radius:50%;
    border:none;
    cursor:pointer;
    background:linear-gradient(160deg, var(--coral-deep), var(--ink));
    color:#fff;
    box-shadow:0 12px 26px var(--shadow);
    display:flex;align-items:center;justify-content:center;
    transition:transform 0.15s ease;
  }
  .chat-fab svg{width:26px;height:26px;stroke:#fff;fill:none;}
  .chat-fab:hover{transform:scale(1.06);}
  .chat-fab:active{transform:scale(0.94);}

  .drawer-backdrop{
    position:fixed;inset:0;
    background:rgba(20,14,30,0.35);
    opacity:0;pointer-events:none;
    transition:opacity 0.35s ease;
    z-index:45;
  }
  .drawer-backdrop.open{opacity:1;pointer-events:auto;}

  .chat-drawer{
    position:fixed;
    top:0;right:0;
    height:100%;
    width:400px;
    max-width:92vw;
    background:var(--paper);
    box-shadow:-24px 0 50px var(--shadow);
    transform:translateX(100%);
    transition:transform 0.4s cubic-bezier(.16,1,.3,1);
    z-index:50;
    display:flex;
    flex-direction:column;
  }
  .chat-drawer.open{transform:translateX(0);}

  .chat-head{
    display:flex;align-items:center;justify-content:space-between;
    padding:20px 22px 16px;
    border-bottom:1px solid #f0e6da;
    font-family:'Fredoka',sans-serif;
    font-weight:600;
    font-size:1.1rem;
    color:var(--coral-deep);
  }
  .chat-close{
    border:none;background:#f3ece3;color:var(--ink);
    width:34px;height:34px;border-radius:50%;
    cursor:pointer;
    display:flex;align-items:center;justify-content:center;
  }
  .chat-close svg{width:15px;height:15px;stroke:currentColor;}

  .chat-log{
    flex:1;
    overflow-y:auto;
    padding:16px 20px;
    display:flex;
    flex-direction:column;
    gap:10px;
  }
  .msg{
    max-width:80%;
    padding:11px 16px;
    border-radius:18px;
    font-size:1rem;
    line-height:1.35;
  }
  .msg.cat{
    background:#f3ece3;
    align-self:flex-start;
    border-bottom-left-radius:5px;
  }
  .msg.user{
    background:var(--coral);
    color:#fff;
    align-self:flex-end;
    border-bottom-right-radius:5px;
  }
  .msg.typing{
    background:#f3ece3;
    align-self:flex-start;
    font-style:italic;
    color:#8a7c78;
  }
  .chat-input-row{
    display:flex;
    gap:10px;
    padding:14px 20px 20px;
    border-top:1px solid #f0e6da;
  }
  .chat-input-row input{
    flex:1;
    border:2px solid #f0e6da;
    border-radius:26px;
    padding:12px 18px;
    font-family:'Nunito',sans-serif;
    font-size:1rem;
    outline:none;
  }
  .chat-input-row input:focus{border-color:var(--coral);}
  .chat-input-row button{
    background:var(--coral-deep);
    color:#fff;
    border:none;
    width:50px;height:50px;
    border-radius:50%;
    cursor:pointer;
    display:flex;align-items:center;justify-content:center;
  }
  .chat-input-row button svg{width:20px;height:20px;fill:#fff;}

  .note{
    position:absolute;
    bottom:10px;left:16px;
    z-index:15;
    font-size:0.72rem;
    color:rgba(58,50,48,0.4);
    pointer-events:none;
  }

  /* ---------- Responsive ---------- */
  @media (max-width:720px){
    .title-pill{font-size:1rem;padding:9px 16px 9px 12px;}
    .room-pill{display:none;}
    .stats-pill{gap:12px;padding:9px 16px;}
    .stat-track{width:44px;}
    .room-door{width:46px;height:150px;}
    .room-door.prev{left:10px;}
    .room-door.next{right:10px;}
    .door-label{display:none;}
    .cat-stage{width:250px;height:330px;bottom:96px;}
    .mood-halo{width:220px;height:220px;}
    .cat-wrap{width:230px;height:305px;}
    .cat-shadow{width:150px;height:24px;}
    .cat-wrap.mood-sleepy{width:300px;height:248px;}
    .cat-wrap.mood-sleepy .cat-shadow{width:230px;}
    .dock{gap:8px;padding:10px 12px;bottom:18px;max-width:calc(100vw - 90px);overflow-x:auto;}
    .action-btn{padding:9px 14px 11px;font-size:0.74rem;}
    .action-btn .icon{width:30px;height:30px;}
    .action-btn .icon svg{width:16px;height:16px;}
    .chat-fab{width:52px;height:52px;bottom:20px;right:16px;}
    .chat-fab svg{width:22px;height:22px;}
    .chat-drawer{
      top:auto;bottom:0;left:0;right:0;
      width:100%;max-width:100%;
      height:78%;
      border-radius:26px 26px 0 0;
      box-shadow:0 -24px 50px var(--shadow);
      transform:translateY(100%);
    }
    .chat-drawer.open{transform:translateY(0);}
    .window{top:100px;width:90px;height:72px;}
  }
</style>
</head>
<body>

<div id="app">

  <div class="stage room-living" id="stage">
    <div class="prop window" id="prop-window"></div>
    <div class="prop rug" id="prop-rug"></div>
    <div class="prop bowl" id="prop-bowl"></div>
    <div class="prop cabinet" id="prop-cabinet"></div>
    <div class="prop bed" id="prop-bed"></div>

    <button class="room-door prev" id="prevRoom" aria-label="Previous room">
      <span class="door-window"></span>
      <span class="door-arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg></span>
      <span class="door-label" id="prevLabel">Bedroom</span>
    </button>
    <button class="room-door next" id="nextRoom" aria-label="Next room">
      <span class="door-window"></span>
      <span class="door-arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg></span>
      <span class="door-label" id="nextLabel">Kitchen</span>
    </button>

    <div class="cat-stage">
      <div class="mood-halo"></div>
      <div class="cat-wrap mood-neutral" id="catWrap">
        <div class="cat-shadow"></div>
        <img class="cat-pose-img cat-img" src="Assets/Character.png" alt="Whiskers the cat">
        <img class="cat-pose-img cat-img-sleep" src="Assets/Sleeping.png" alt="Whiskers sleeping">
        <img class="cat-pose-img cat-img-hungry" src="Assets/Hungry.png" alt="Whiskers is hungry">
        <img class="cat-pose-img cat-img-eating" src="Assets/Eating.png" alt="Whiskers eating">
        <img class="cat-pose-img cat-pet-frame" id="petFrame1" src="Assets/Pet.png" alt="Whiskers noticing pets">
        <img class="cat-pose-img cat-pet-frame" id="petFrame2" src="Assets/pet1.png" alt="Whiskers enjoying pets">
        <img class="cat-pose-img cat-pet-frame" id="petFrame3" src="Assets/Pet3.png" alt="Whiskers rolling over happily">
        <img class="cat-pose-img cat-play-frame" id="playFrame1" src="Assets/Playing1.png" alt="Whiskers playing">
        <img class="cat-pose-img cat-play-frame" id="playFrame2" src="Assets/Playing2.png" alt="Whiskers playing">
        <div class="bubble" id="bubble"></div>
      </div>
      <div class="particles" id="particles"></div>
    </div>
  </div>

  <div class="topbar">
    <div class="title-pill glass"><span class="paw"><svg viewBox="0 0 24 24"><circle cx="6.5" cy="7.5" r="2.1"/><circle cx="11.5" cy="5.3" r="2.1"/><circle cx="16.5" cy="7.5" r="2.1"/><circle cx="19.3" cy="12.2" r="1.9"/><path d="M12 20.2c-3.4 0-6-1.8-6-4.4 0-2.3 2.1-4 6-4s6 1.7 6 4c0 2.6-2.6 4.4-6 4.4Z"/></svg></span> Shero's House</div>
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

  <button class="chat-fab" id="chatToggle" aria-label="Talk to Whiskers"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-1 4L21 20l-4.3-1a8.4 8.4 0 0 1-3.7.9 8.5 8.5 0 1 1 8-8.4Z"/></svg></button>

  <div class="note">Stats reset on reload &middot; native replies for now, AI hookup coming later</div>

</div>

<div class="drawer-backdrop" id="drawerBackdrop"></div>
<div class="chat-drawer" id="chatPanel">
  <div class="chat-head">
    <span>Talk to Whiskers</span>
    <button class="chat-close" id="chatClose" aria-label="Close chat"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
  </div>
  <div class="chat-log" id="chatLog"></div>
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
    name: 'Whiskers'
  };

  const els = {
    stage: document.getElementById('stage'),
    roomName: document.getElementById('roomName'),
    prevLabel: document.getElementById('prevLabel'),
    nextLabel: document.getElementById('nextLabel'),
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
    chatPanel: document.getElementById('chatPanel'),
    chatClose: document.getElementById('chatClose'),
    drawerBackdrop: document.getElementById('drawerBackdrop'),
    chatLog: document.getElementById('chatLog'),
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
    props: {
      window: document.getElementById('prop-window'),
      rug: document.getElementById('prop-rug'),
      bowl: document.getElementById('prop-bowl'),
      cabinet: document.getElementById('prop-cabinet'),
      bed: document.getElementById('prop-bed'),
    }
  };

  const propsByRoom = {
    living: ['window','rug'],
    kitchen: ['window','bowl','cabinet'],
    bedroom: ['window','bed'],
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
      {icon:ICON.play, label:'Play', run: playWithCat},
      {icon:ICON.pet, label:'Pet', run: petCat},
    ],
    kitchen: [
      {icon:ICON.feed, label:'Feed', run: feedCat},
      {icon:ICON.treat, label:'Treat', run: giveTreat},
    ],
    bedroom: [
      {icon:ICON.nap, label: 'Nap', run: toggleSleep},
    ]
  };

  /* ---------------- Rendering ---------------- */
  function renderRoom(){
    const room = rooms[roomIndex];
    cancelPetAnimation();
    cancelEatAnimation();
    cancelPlayAnimation();
    els.stage.className = 'stage room-' + room;
    els.roomName.textContent = roomLabels[room];

    const prevRoom = rooms[(roomIndex - 1 + rooms.length) % rooms.length];
    const nextRoom = rooms[(roomIndex + 1) % rooms.length];
    els.prevLabel.textContent = roomLabels[prevRoom];
    els.nextLabel.textContent = roomLabels[nextRoom];

    Object.entries(els.props).forEach(([key, node])=>{
      node.style.display = propsByRoom[room].includes(key) ? 'block' : 'none';
    });

    renderDock();
  }

  function renderDock(){
    const room = rooms[roomIndex];
    els.dock.innerHTML = '';
    actionsByRoom[room].forEach(a=>{
      const btn = document.createElement('button');
      btn.className = 'action-btn';
      btn.innerHTML = `<span class="icon">${a.icon}</span><span>${a.label}</span>`;
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

  /* ---------------- Bubble ---------------- */
  let bubbleTimer = null;
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
    say(pick(["Nom nom nom!", "Yummy!", "More please? 🐟"]));
  }

  function giveTreat(){
    if (state.sleeping) { say("Zzz..."); return; }
    state.hunger = clamp(state.hunger + 10);
    state.happy = clamp(state.happy + 10);
    renderAll();
    playEatAnimation();
    spawnParticle('🥛');
    say(pick(["A treat?! Best day ever!", "Purrrr~"]));
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
    say(pick(["Wheee!", "Again, again!", "This is fun!"]));
  }

  function petCat(){
    if (state.sleeping) { say("Purrrr... (still sleeping)"); return; }
    state.happy = clamp(state.happy + 8);
    renderAll();
    spawnParticle('💕');
    say(pick(["Purrrrr...", "I love that!", "😻"]));
    playPetAnimation();
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
    } else {
      say(pick(["I'm awake!", "Good morning!"]));
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

  /* ---------------- Chat drawer ---------------- */
  function openChat(){
    els.chatPanel.classList.add('open');
    els.drawerBackdrop.classList.add('open');
    els.chatInput.focus();
  }
  function closeChat(){
    els.chatPanel.classList.remove('open');
    els.drawerBackdrop.classList.remove('open');
  }
  els.chatToggle.addEventListener('click', ()=>{
    els.chatPanel.classList.contains('open') ? closeChat() : openChat();
  });
  els.chatClose.addEventListener('click', closeChat);
  els.drawerBackdrop.addEventListener('click', closeChat);

  function addMessage(text, from){
    const div = document.createElement('div');
    div.className = 'msg ' + from;
    div.textContent = text;
    els.chatLog.appendChild(div);
    els.chatLog.scrollTop = els.chatLog.scrollHeight;
    return div;
  }

  /*
   * getCatReply(message)
   * -------------------------------------------------------------
   * NATIVE / PLACEHOLDER LOGIC — swap this out later for a real AI call.
   * Keep the function name, the "async" signature, and the return value
   * (a string) the same, so nothing else in the app needs to change.
   *
   * Example of what this will look like with an AI backend later:
   *
   *   async function getCatReply(message) {
   *     const res = await fetch('/api/cat-chat', {
   *       method: 'POST',
   *       headers: { 'Content-Type': 'application/json' },
   *       body: JSON.stringify({ message, state })
   *     });
   *     const data = await res.json();
   *     return data.reply;
   *   }
   */
  async function getCatReply(message){
    const m = message.toLowerCase();

    if (state.sleeping) return pick(["Zzz... five more minutes...", "*rolls over, still asleep*"]);

    if (/hungry|food|eat|feed/.test(m)){
      return pick(["I could go for a snack! 🍗", "Kitchen's this way, hint hint.", "Feed me and I'll love you forever."]);
    }
    if (/play|toy|game/.test(m)){
      return pick(["Yes! Let's play!", "Chase me, chase me!", "I'm always up for playtime."]);
    }
    if (/sleep|tired|nap/.test(m)){
      return pick(["A nap does sound nice...", "*yawns*", "Wake me up in the bedroom."]);
    }
    if (/love|cute|good (cat|kitty)|cutie/.test(m)){
      return pick(["Purrrrr, I love you too!", "😻 You're my favorite human.", "Aww, stop it, my fur's turning red."]);
    }
    if (/name|who are you/.test(m)){
      return `I'm ${state.name}! Nice to meet you.`;
    }
    if (/how (are|r) (you|u)/.test(m)){
      const mood = currentMood();
      if (mood === 'happy') return "I'm doing great, thanks for asking!";
      if (mood === 'sad') return "Honestly? Could use some food and cuddles.";
      return "I'm okay! Could always use a treat though.";
    }
    if (/hi|hello|hey/.test(m)){
      return pick(["Meow! Hi there!", "Hey hey! 🐾", "Hello, friend!"]);
    }

    return pick([
      "Meow?",
      "Purrrr~",
      "*tilts head*",
      "I don't quite get that, but I like your voice!",
      "Meow meow!"
    ]);
  }

  async function sendChat(){
    const text = els.chatInput.value.trim();
    if (!text) return;
    addMessage(text, 'user');
    els.chatInput.value = '';
    state.happy = clamp(state.happy + 3);
    renderStats();

    const typingEl = addMessage(state.name + ' is typing…', 'typing');
    els.chatLog.scrollTop = els.chatLog.scrollHeight;

    const reply = await getCatReply(text);

    setTimeout(()=>{
      typingEl.remove();
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
  addMessage("Meow! I'm " + state.name + ". Talk to me!", 'cat');
})();
</script>

</body>
</html>