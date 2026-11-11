<!DOCTYPE html>
<html lang="en">

<head>

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Nova's House">
  <meta property="og:title" content="Nova's House">
  <meta property="og:description" content="Come hang out with Nova, a cozy little cat. Feed her, play, pet her, and chat!">
  <meta property="og:url" content="https://nova-cat.vercel.app/">
  <meta property="og:image" content="https://nova-cat.vercel.app/Assets/Character.png?v=2">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="864">
  <meta property="og:image:height" content="1156">
  <meta property="og:image:alt" content="Nova the cat standing in her cozy house">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:image" content="https://nova-cat.vercel.app/Assets/Character.png?v=2">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, interactive-widget=resizes-visual">
  <title>Nova's House</title>
  <link rel="icon" href="/Assets/MessageBubble.png" type="image/png">
  <link rel="apple-touch-icon" href="/Assets/MessageBubble.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --ink: #3a2f2a;
      --ink-soft: #8a7a6e;
      --paper: #fff8ef;
      --paper-glass: rgba(255, 248, 236, 0.6);
      --paper-glass-strong: rgba(255, 248, 239, 0.92);
      --coral: #ff8c69;
      --coral-deep: #e2603f;
      --gold: #ffbd59;
      --gold-deep: #e2963a;
      --sage: #8fcb9d;
      --sage-deep: #5fa877;
      --dusk: #8fa3d6;
      --dusk-deep: #5b71ab;
      --blush: #ffb3c6;
      --blush-deep: #e4839c;
      --shadow: rgba(66, 42, 24, 0.24);
      --glow-warm: rgba(255, 189, 89, 0.35);
      --accent: var(--sage);
      --accent-deep: var(--sage-deep);
      --grain: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='120' height='120'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/><feColorMatrix type='saturate' values='0'/></filter><rect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/></svg>");
    }

    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }

    body {
      font-family: 'Nunito', sans-serif;
      color: var(--ink);
      background: linear-gradient(160deg, #2a1e28, #4a2f30);
    }

    #app {
      position: relative;
      width: 100vw;
      height: 100dvh;
      overflow: hidden;
      background: var(--paper);
    }

    .stage {
      position: relative;
      position: absolute;
      inset: 0;
      overflow: hidden;
      background: #241a1c;
      transition: background 0.6s ease;
    }


    .stage.room-living {
      --accent: var(--sage);
      --accent-deep: var(--sage-deep);
    }

    .stage.room-kitchen {
      --accent: var(--gold);
      --accent-deep: var(--gold-deep);
    }

    .stage.room-bedroom {
      --accent: var(--dusk);
      --accent-deep: var(--dusk-deep);
    }

    .stage::before,
    .stage::after {
      content: "";
      position: absolute;
      border-radius: 50%;
      opacity: 0.3;
      background: radial-gradient(circle, var(--accent) 0%, transparent 70%);
      pointer-events: none;
      z-index: 1;
      will-change: transform;
      animation: driftGlow 9s ease-in-out infinite alternate;
    }

    .stage::before {
      width: 460px;
      height: 460px;
      top: -190px;
      right: -180px;
    }

    .stage::after {
      width: 400px;
      height: 400px;
      bottom: -30px;
      left: -190px;
      animation-duration: 11s;
    }

    @keyframes driftGlow {
      0% {
        transform: translate(0, 0) scale(1);
      }

      100% {
        transform: translate(14px, -18px) scale(1.08);
      }
    }

    .stage-ambience {
      position: absolute;
      inset: 0;
      z-index: 2;
      pointer-events: none;
    }

    .stage-ambience::before {
      content: "";
      position: absolute;
      inset: 0;
      background:
        radial-gradient(ellipse 120% 90% at 50% 45%, transparent 45%, rgba(40, 26, 20, 0.28) 100%),
        linear-gradient(180deg, rgba(255, 214, 160, 0.10), transparent 30%, transparent 70%, rgba(30, 18, 14, 0.22));
    }

    .stage-ambience::after {
      content: "";
      position: absolute;
      inset: 0;
      background-image: var(--grain);
      background-size: 120px 120px;
      opacity: 0.05;
      mix-blend-mode: overlay;
    }

    .motes {
      position: absolute;
      inset: 0;
      z-index: 3;
      pointer-events: none;
      overflow: hidden;
    }

    .mote {
      position: absolute;
      bottom: -6%;
      width: 5px;
      height: 5px;
      border-radius: 50%;
      background: radial-gradient(circle, #fff6d8 0%, rgba(255, 220, 150, 0.6) 55%, transparent 75%);
      opacity: 0;
      filter: blur(0.2px);
      animation: moteDrift linear infinite;
    }

    @keyframes moteDrift {
      0% {
        transform: translate(0, 0) scale(0.7);
        opacity: 0;
      }

      8% {
        opacity: 0.85;
      }

      50% {
        transform: translate(var(--drift, 18px), -55vh) scale(1);
      }

      92% {
        opacity: 0.6;
      }

      100% {
        transform: translate(calc(var(--drift, 18px) * 1.6), -108vh) scale(0.8);
        opacity: 0;
      }
    }

    .room-bg-layer {
      position: absolute;
      inset: 0;
      z-index: 0;
      overflow: hidden;
      pointer-events: none;
    }

    .room-bg-side {
      position: absolute;
      object-fit: cover;
      display: block;
      filter: blur(3px) saturate(1.05) brightness(0.92);
      transform: scale(1.04);
    }



    .room-bg-main {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: contain;
      z-index: 1;
      opacity: 0;
      will-change: transform, opacity;
      backface-visibility: hidden;
      transform: translateZ(0);
    }

    /* ---------- Topbar (floating glass) ---------- */
    .topbar {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      z-index: 30;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 14px;
      flex-wrap: wrap;
      padding: 18px 22px;
      pointer-events: none;
    }

    .topbar>* {
      pointer-events: auto;
      min-width: 0;
      flex-shrink: 1;
    }

    .title-pill,
    .stats-pill {
      overflow: hidden;
    }

    .glass {
      position: relative;
      background: var(--paper-glass);
      backdrop-filter: blur(16px) saturate(1.3);
      -webkit-backdrop-filter: blur(16px) saturate(1.3);
      box-shadow: 0 10px 26px var(--shadow), inset 0 1px 0 rgba(255, 255, 255, 0.55);
      isolation: isolate;
    }

    .glass::before {
      content: "";
      position: absolute;
      inset: 0;
      border-radius: inherit;
      background-image: var(--grain);
      background-size: 120px 120px;
      opacity: 0.035;
      mix-blend-mode: multiply;
      pointer-events: none;
      z-index: -1;
    }

    .title-pill {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 20px 8px 8px;
      border-radius: 999px;
      font-family: 'Fredoka', sans-serif;
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--coral-deep);
      white-space: nowrap;
      letter-spacing: 0.01em;
    }

    .title-pill .paw {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: linear-gradient(155deg, var(--gold), var(--coral));
      box-shadow: 0 2px 0 var(--coral-deep), inset 0 1px 1px rgba(255, 255, 255, 0.5);
      flex-shrink: 0;
    }

    .title-pill .paw svg {
      width: 16px;
      height: 16px;
      fill: #fff;
    }

    .room-pill {
      align-self: center;
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 9px 22px 9px 18px;
      border-radius: 999px;
      font-family: 'Fredoka', sans-serif;
      font-weight: 600;
      font-size: 1rem;
      color: var(--ink);
      letter-spacing: 0.02em;
      transition: color 0.4s ease;
    }

    .room-pill::before {
      content: "";
      width: 7px;
      height: 7px;
      border-radius: 50%;
      flex-shrink: 0;
      background: var(--gold-deep);
      box-shadow: 0 0 0 3px rgba(255, 189, 89, 0.28);
      animation: statusPulse 2.4s ease-in-out infinite;
    }

    @keyframes statusPulse {

      0%,
      100% {
        box-shadow: 0 0 0 3px rgba(255, 189, 89, 0.28);
      }

      50% {
        box-shadow: 0 0 0 5px rgba(255, 189, 89, 0.12);
      }
    }

    .stats-pill {
      display: flex;
      align-items: center;
      gap: 2px;
      padding: 9px 20px;
      border-radius: 999px;
    }

    .stat {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 0 12px;
      position: relative;
    }

    .stat+.stat::before {
      content: "";
      position: absolute;
      left: -1px;
      top: 50%;
      transform: translateY(-50%);
      width: 1px;
      height: 20px;
      background: linear-gradient(180deg, transparent, rgba(122, 90, 64, 0.2), transparent);
    }

    .stat-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 25px;
      height: 25px;
      border-radius: 50%;
      flex-shrink: 0;
      transition: transform 0.2s ease;
    }

    .stat-icon svg {
      width: 14px;
      height: 14px;
      display: block;
      stroke: currentColor;
    }

    .stat-hunger .stat-icon {
      color: #c9711f;
      background: rgba(230, 136, 58, 0.16);
    }

    .stat-happy .stat-icon {
      color: #c94a2c;
      background: rgba(226, 96, 63, 0.16);
    }

    .stat-energy .stat-icon {
      color: #4a68ab;
      fill: currentColor;
      background: rgba(91, 127, 196, 0.16);
    }

    .stat-track {
      width: 58px;
      height: 8px;
      border-radius: 999px;
      background: rgba(122, 90, 64, 0.14);
      box-shadow: inset 0 1px 2px rgba(58, 42, 28, 0.2);
      overflow: hidden;
      position: relative;
    }

    .stat-fill {
      height: 100%;
      border-radius: 999px;
      position: relative;
      overflow: hidden;
      transition: width 0.5s ease, background 0.5s ease;
    }

    .stat-fill::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(100deg, transparent 25%, rgba(255, 255, 255, 0.65) 45%, transparent 65%);
      background-size: 220% 100%;
      animation: statSheen 3.4s ease-in-out infinite;
    }

    @keyframes statSheen {
      0% {
        background-position: 170% 0;
      }

      55%,
      100% {
        background-position: -70% 0;
      }
    }

    .fill-hunger {
      background: linear-gradient(90deg, #f0ab5e, #e6883a);
      box-shadow: 0 0 8px rgba(230, 136, 58, 0.45);
    }

    .fill-happy {
      background: linear-gradient(90deg, #ff9d7d, #e2603f);
      box-shadow: 0 0 8px rgba(226, 96, 63, 0.45);
    }

    .fill-energy {
      background: linear-gradient(90deg, #a2c2ea, #6f95cf);
      box-shadow: 0 0 8px rgba(91, 127, 196, 0.45);
    }

    .stat-num {
      font-family: 'Fredoka', sans-serif;
      font-variant-numeric: tabular-nums;
      font-size: 0.82rem;
      font-weight: 600;
      min-width: 22px;
      text-align: right;
    }

    .stat-hunger .stat-num {
      color: #c9711f;
    }

    .stat-happy .stat-num {
      color: #c94a2c;
    }

    .stat-energy .stat-num {
      color: #4a68ab;
    }

    /* ---------- Doors: portal nodes ---------- */
    .room-door {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 74px;
      height: 74px;
      border: none;
      padding: 0;
      cursor: pointer;
      background: transparent;
      z-index: 12;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.3s cubic-bezier(.2, 1, .3, 1);
      overflow: visible;
    }

    .room-door.prev {
      left: 16px;
    }

    .room-door.next {
      right: 16px;
    }

    .room-door:hover {
      transform: translateY(-56%) scale(1.07);
    }

    .room-door:active {
      transform: translateY(-52%) scale(0.93);
    }

    .door-ring {
      position: absolute;
      inset: -6px;
      border-radius: 50%;
      background: conic-gradient(from 0deg, var(--accent), var(--gold), var(--coral), var(--accent));
      filter: blur(7px);
      opacity: 0.34;
      animation: doorSpin 10s linear infinite;
      transition: opacity 0.25s ease, filter 0.25s ease;
      pointer-events: none;
    }

    @keyframes doorSpin {
      to {
        transform: rotate(360deg);
      }
    }

    .room-door:hover .door-ring {
      opacity: 0.85;
      filter: blur(9px);
    }

    .door-core {
      position: relative;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      border: 3px solid #fff;
      background: linear-gradient(160deg, var(--accent), var(--accent-deep));
      box-shadow: 0 4px 0 var(--accent-deep), 0 10px 20px var(--shadow);
      display: flex;
      align-items: center;
      justify-content: center;
      animation: doorPulse 3.4s ease-in-out infinite;
      transition: box-shadow 0.2s ease, transform 0.15s ease;
    }

    @keyframes doorPulse {

      0%,
      100% {
        box-shadow: 0 4px 0 var(--accent-deep), 0 10px 20px var(--shadow);
      }

      50% {
        box-shadow: 0 4px 0 var(--accent-deep), 0 10px 20px var(--shadow), 0 0 0 6px rgba(255, 255, 255, 0.28);
      }
    }

    .room-door:hover .door-core {
      transform: translateY(-2px);
      box-shadow: 0 6px 0 var(--accent-deep), 0 14px 24px var(--shadow);
    }

    .room-door:active .door-core {
      transform: translateY(3px);
      box-shadow: 0 1px 0 var(--accent-deep), 0 4px 10px var(--shadow);
    }

    .door-face-icon {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .door-face-icon svg {
      width: 26px;
      height: 26px;
      stroke: #fff;
      fill: none;
      filter: drop-shadow(0 1px 0 rgba(0, 0, 0, 0.14));
    }

    .door-face-icon svg.filled {
      fill: #fff;
      stroke: none;
    }

    .door-chevron {
      position: absolute;
      bottom: -2px;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: linear-gradient(160deg, var(--accent), var(--accent-deep));
      box-shadow: 0 4px 10px var(--shadow), 0 0 0 3px var(--paper);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .door-chevron svg {
      width: 13px;
      height: 13px;
      stroke: #fff;
      stroke-width: 3;
    }

    .door-chevron-prev {
      left: -6px;
    }

    .door-chevron-next {
      right: -6px;
    }

    .door-tag {
      position: absolute;
      top: -42px;
      left: 50%;
      transform: translate(-50%, 6px);
      padding: 6px 14px 6px 18px;
      background: rgba(64, 44, 34, 0.92);
      color: #fff;
      font-family: 'Nunito', sans-serif;
      font-weight: 800;
      font-size: 0.66rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      white-space: nowrap;
      clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.18s ease, transform 0.18s ease;
    }

    .door-tag::after {
      content: "";
      position: absolute;
      bottom: -5px;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 0;
      border-left: 5px solid transparent;
      border-right: 5px solid transparent;
      border-top: 5px solid rgba(64, 44, 34, 0.92);
    }

    .room-door:hover .door-tag {
      opacity: 1;
      transform: translate(-50%, 0);
    }

    .room-rail {
      position: absolute;
      left: 50%;
      bottom: 18px;
      transform: translateX(-50%);
      z-index: 14;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 15px;
      border-radius: 999px;
    }

    .rail-dot {
      width: 11px;
      height: 11px;
      border-radius: 50%;
      border: 2px solid rgba(255, 255, 255, 0.7);
      padding: 0;
      cursor: pointer;
      background: rgba(58, 50, 48, 0.18);
      box-shadow: inset 0 1px 2px rgba(60, 38, 26, 0.15);
      transition: width 0.25s cubic-bezier(.34, 1.56, .64, 1), border-radius 0.25s ease, background 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .rail-dot:hover {
      background: rgba(58, 50, 48, 0.32);
    }

    .rail-dot.active {
      width: 28px;
      border-radius: 7px;
      border-color: #fff;
      background: linear-gradient(90deg, var(--accent), var(--accent-deep));
      box-shadow: 0 2px 0 var(--accent-deep), 0 0 10px var(--accent);
      animation: railPop 0.3s cubic-bezier(.34, 1.56, .64, 1);
    }

    @keyframes railPop {
      0% {
        transform: scale(0.6);
      }

      70% {
        transform: scale(1.15);
      }

      100% {
        transform: scale(1);
      }
    }

    /* ---------- Cat ---------- */
    .cat-stage {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -100%) scale(1);
      transform-origin: bottom center;
      width: 170px;
      height: 210px;
      z-index: 5;
      transition: left 0.45s cubic-bezier(.25, .46, .45, .94), top 0.45s cubic-bezier(.25, .46, .45, .94), transform 0.45s cubic-bezier(.25, .46, .45, .94);
    }

    .cat-stage.moving {
      transition: left 0.6s cubic-bezier(.34, .82, .47, 1.42), top 0.6s cubic-bezier(.34, .82, .47, 1.42), transform 0.6s cubic-bezier(.34, .82, .47, 1.42);
    }

    .mood-halo {
      position: absolute;
      left: 50%;
      bottom: 10px;
      transform: translateX(-50%);
      width: 160px;
      height: 160px;
      border-radius: 50%;
      filter: blur(30px);
      opacity: 0.55;
      background: var(--accent);
      transition: background 0.6s ease;
      z-index: 0;
      animation: haloPulse 3.2s ease-in-out infinite;
    }

    @keyframes haloPulse {

      0%,
      100% {
        opacity: 0.4;
        transform: translateX(-50%) scale(1);
      }

      50% {
        opacity: 0.62;
        transform: translateX(-50%) scale(1.08);
      }
    }

    .cat-wrap {
      position: absolute;
      bottom: 0;
      left: 80%;
      transform: translateX(-50%);
      transform-origin: bottom center;
      width: 430px;
      height: 368px;
      cursor: pointer;
      z-index: 2;
      animation: catIdle 5.2s cubic-bezier(.45, .05, .55, .95) infinite;
    }

    .cat-wrap.moving {
      animation: none;
    }

    @keyframes catIdle {
      0% {
        transform: translateX(-50%) rotate(0deg) scale(1, 1);
      }

      18% {
        transform: translateX(-50%) rotate(-0.7deg) scale(1, 1);
      }

      38% {
        transform: translateX(-50%) rotate(0.3deg) scale(1, 1);
      }

      60% {
        transform: translateX(-50%) rotate(1deg) scale(1, 1);
      }

      82% {
        transform: translateX(-50%) rotate(-0.3deg) scale(1, 1);
      }

      100% {
        transform: translateX(-50%) rotate(0deg) scale(1, 1);
      }
    }

    @keyframes catWalk {
      0% {
        transform: translateX(-50%) translateY(0) scale(1);
      }

      25% {
        transform: translateX(-50%) translateY(-8px) scale(0.98, 1.02);
      }

      50% {
        transform: translateX(-50%) translateY(0) scale(1);
      }

      75% {
        transform: translateX(-50%) translateY(-8px) scale(0.98, 1.02);
      }

      100% {
        transform: translateX(-50%) translateY(0) scale(1);
      }
    }

    @keyframes catJump {
      0% {
        transform: translateX(-50%) translateY(0) scale(1, 1);
      }

      40% {
        transform: translateX(-50%) translateY(-40px) scale(1, 1);
      }

      100% {
        transform: translateX(-50%) translateY(0) scale(1, 1);
      }
    }

    .cat-wrap.playing {
      animation: catJump 0.8s cubic-bezier(.34, 1.56, .64, 1);
    }

    .cat-wrap.bouncing {
      animation: catJump 0.6s cubic-bezier(.34, 1.56, .64, 1);
    }

    @keyframes shadowIdle {

      0%,
      100% {
        opacity: 0.85;
        transform: translateX(-50%) scale(1);
      }

      38% {
        opacity: 0.8;
        transform: translateX(-50%) scale(0.985, 1);
      }

      60% {
        opacity: 0.95;
        transform: translateX(-50%) scale(1.01, 1);
      }
    }

    .cat-shadow {
      position: absolute;
      bottom: -4px;
      left: 50%;
      transform: translateX(-50%);
      width: 110px;
      height: 16px;
      background: rgba(60, 38, 26, 0.2);
      border-radius: 50%;
      filter: blur(2px);
      opacity: 0.9;
      z-index: 0;
      animation: shadowIdle 5.2s cubic-bezier(.45, .05, .55, .95) infinite;
    }

    .cat-wrap.playing .cat-shadow,
    .cat-wrap.bouncing .cat-shadow {
      animation: none;
      opacity: 0.6;
    }

    .cat-pose-img {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      margin: 0 auto;
      width: 275px;
      height: 368px;
      max-width: none;
      object-fit: fill;
      filter: none;
      transition: filter 0.3s ease, transform 0.3s ease;
      -webkit-user-drag: none;
      user-select: none;
      pointer-events: none;
      z-index: 1;
    }

    .cat-img-sleep {
      display: none;
    }

    .cat-img-hungry {
      display: none;
    }

    .cat-img-eating {
      display: none;
    }

    .cat-pet-frame {
      display: none;
    }

    .cat-pet-frame.active {
      display: block;
    }

    .cat-play-frame {
      display: none;
    }

    .cat-play-frame.active {
      display: block;
    }

    .cat-wrap.hungry:not(.mood-sleepy):not(.petting):not(.eating):not(.playing) .cat-img {
      display: none;
    }

    .cat-wrap.hungry:not(.mood-sleepy):not(.petting):not(.eating):not(.playing) .cat-img-hungry {
      display: block;
    }

    .cat-wrap.petting .cat-img,
    .cat-wrap.petting .cat-img-sleep,
    .cat-wrap.petting .cat-img-hungry {
      display: none;
    }

    .cat-wrap.eating .cat-img,
    .cat-wrap.eating .cat-img-sleep,
    .cat-wrap.eating .cat-img-hungry {
      display: none;
    }

    .cat-wrap.eating .cat-img-eating {
      display: block;
    }

    .cat-wrap.playing .cat-img,
    .cat-wrap.playing .cat-img-sleep,
    .cat-wrap.playing .cat-img-hungry {
      display: none;
    }

    .mood-happy .mood-halo {
      background: var(--coral);
    }

    .mood-neutral .mood-halo {
      background: var(--accent);
    }

    .mood-sad .mood-halo {
      background: #9aa0b4;
      opacity: 0.35;
      animation: none;
    }

    .mood-sleepy .mood-halo {
      background: var(--dusk);
      animation: haloPulse 4.5s ease-in-out infinite;
    }

    .cat-wrap.mood-happy .cat-img {
      filter: brightness(1.05) saturate(1.12);
    }

    .cat-wrap.mood-neutral .cat-img {
      filter: none;
    }

    .cat-wrap.mood-sad .cat-img {
      filter: grayscale(0.35) brightness(0.92);
      transform: translateY(4px);
    }

    .cat-wrap.mood-sleepy {
      animation: none;
      width: 390px;
      height: 322px;
    }

    .cat-wrap.mood-sleepy .cat-img {
      display: none;
    }

    .cat-wrap.mood-sleepy .cat-img-sleep {
      display: block;
    }

    .cat-wrap.mood-sleepy .cat-shadow {
      width: 300px;
      animation: none;
      opacity: 0.9;
    }

    .bubble {
      position: absolute;
      top: 6px;
      left: 50%;
      transform: translate(-50%, -100%) scale(0.9);
      background: #fff;
      padding: 11px 17px;
      border-radius: 18px;
      font-size: 0.95rem;
      font-weight: 700;
      max-width: 280px;
      white-space: normal;
      box-shadow: 0 10px 26px rgba(0, 0, 0, 0.16);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s, transform 0.25s;
      z-index: 6;
    }

    .bubble.show {
      opacity: 1;
      transform: translate(-50%, -112%) scale(1);
    }

    .bubble::after {
      content: "";
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 0;
      border-left: 8px solid transparent;
      border-right: 8px solid transparent;
      border-top: 8px solid #fff;
    }

    .particles {
      position: absolute;
      inset: 0;
      pointer-events: none;
      overflow: visible;
      z-index: 7;
    }

    .particle {
      position: absolute;
      bottom: 44%;
      font-size: 1.5rem;
      animation: particleRise 1.3s ease-out forwards;
    }

    @keyframes particleRise {
      0% {
        transform: translate(-50%, 0) scale(0.6);
        opacity: 0;
      }

      15% {
        opacity: 1;
        transform: translate(-50%, -10px) scale(1);
      }

      100% {
        transform: translate(-50%, -140px) scale(1.1);
        opacity: 0;
      }
    }

    /* ---------- Floating Action Icons (dock) ---------- */
    .dock {
      position: absolute;
      right: 190px;
      bottom: 36px;
      z-index: 35;
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 0;
      background: transparent;
      box-shadow: none;
    }

    .action-btn {
      --btn-fill: var(--coral);
      --btn-deep: var(--coral-deep);
      position: relative;
      width: 50px;
      height: 50px;
      padding: 0;
      border: 3px solid #fff;
      border-radius: 50%;
      background: linear-gradient(160deg, var(--btn-fill), var(--btn-deep));
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow:
        0 4px 0 var(--btn-deep),
        0 8px 14px rgba(60, 38, 26, 0.22);
      transition: transform 0.14s cubic-bezier(.34, 1.56, .64, 1), box-shadow 0.14s ease;
    }

    .action-btn:hover {
      transform: translateY(-3px) rotate(-4deg);
      box-shadow:
        0 7px 0 var(--btn-deep),
        0 12px 18px rgba(60, 38, 26, 0.26);
    }

    .action-btn:active {
      transform: translateY(3px) rotate(0deg);
      box-shadow:
        0 1px 0 var(--btn-deep),
        0 3px 8px rgba(60, 38, 26, 0.2);
    }

    .action-btn .icon {
      width: auto;
      height: auto;
      border-radius: 0;
      background: transparent;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .action-btn .icon svg {
      width: 22px;
      height: 22px;
      stroke: #fff;
      fill: none;
      filter: drop-shadow(0 1px 0 rgba(0, 0, 0, 0.14));
      transition: transform 0.18s ease;
    }

    .action-btn .icon svg.filled {
      fill: #fff;
      stroke: none;
    }

    .action-btn:hover .icon svg {
      transform: scale(1.1);
    }

    .action-btn::after {
      content: attr(data-label);
      position: absolute;
      bottom: calc(100% + 9px);
      left: 50%;
      transform: translateX(-50%) translateY(4px);
      padding: 5px 9px;
      background: rgba(64, 44, 34, 0.92);
      color: #fff;
      border-radius: 8px;
      font-family: 'Nunito', sans-serif;
      font-size: 0.68rem;
      font-weight: 800;
      white-space: nowrap;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .action-btn:hover::after {
      opacity: 1;
      transform: translateX(-50%) translateY(0);
    }

    /* ---------- Chat FAB + floating chatbot widget ---------- */
    .chat-fab {
      position: absolute;
      bottom: 26px;
      right: 26px;
      z-index: 40;
      width: 92px;
      height: 122px;
      border: none;
      padding: 0;
      background: transparent;
      box-shadow: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.18s ease;
      filter: drop-shadow(0 10px 18px var(--shadow));
    }

    .chat-fab::before {
      content: "";
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      width: 58%;
      height: 13px;
      background: radial-gradient(ellipse at center, rgba(60, 38, 26, 0.3), transparent 72%);
      border-radius: 50%;
      animation: fabShadowPulse 3.6s ease-in-out infinite;
    }

    @keyframes fabShadowPulse {

      0%,
      100% {
        transform: translateX(-50%) scale(1);
        opacity: 0.6;
      }

      50% {
        transform: translateX(-50%) scale(0.82);
        opacity: 0.38;
      }
    }

    .chat-fab::after {
      content: "";
      position: absolute;
      left: 50%;
      top: 42%;
      transform: translate(-50%, -50%);
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: radial-gradient(circle, var(--glow-warm), transparent 70%);
      filter: blur(6px);
      z-index: -1;
      animation: fabGlow 3.6s ease-in-out infinite;
    }

    @keyframes fabGlow {

      0%,
      100% {
        opacity: 0.45;
        transform: translate(-50%, -50%) scale(0.92);
      }

      50% {
        opacity: 0.85;
        transform: translate(-50%, -50%) scale(1.08);
      }
    }

    .chat-fab img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      pointer-events: none;
      -webkit-user-drag: none;
      animation: fabFloat 3.6s ease-in-out infinite;
      position: relative;
      z-index: 1;
    }

    @keyframes fabFloat {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-6px);
      }
    }

    .chat-fab:hover {
      transform: scale(1.08);
    }

    .chat-fab:active {
      transform: scale(0.93);
    }




    .chat-scrim {
      position: fixed;
      inset: 0;
      background: transparent;
      opacity: 0;
      pointer-events: none;
      z-index: 44;
    }

    .chat-scrim.open {
      pointer-events: auto;
    }

    .chat-widget {
      position: absolute;
      bottom: 118px;
      right: 24px;
      z-index: 45;
      width: 340px;
      max-width: calc(100vw - 32px);
      height: min(64dvh, 500px);
      max-height: calc(100dvh - 148px);
      background: var(--paper);
      border-radius: 24px;
      border: 3px solid #fff;
      box-shadow: 0 8px 0 rgba(60, 38, 26, 0.14), 0 26px 50px var(--shadow);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      transform-origin: bottom right;
      transform: translateY(14px) scale(0.92);
      opacity: 0;
      pointer-events: none;
      transition: transform 0.28s cubic-bezier(.2, 1, .3, 1), opacity 0.22s ease;
    }

    .chat-widget.open {
      transform: translateY(0) scale(1);
      opacity: 1;
      pointer-events: auto;
    }

    .chat-head {
      position: relative;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px 16px 14px;
      background: linear-gradient(135deg, var(--gold) 0%, var(--coral) 55%, var(--coral-deep) 100%);
      color: #fff;
      flex: 0 0 auto;
      overflow: hidden;
    }

    .chat-head::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: radial-gradient(circle, rgba(255, 246, 216, 0.95) 1.6px, transparent 1.8px);
      background-size: 16px 4px;
      background-position: 2px 0;
      opacity: 0.85;
      filter: drop-shadow(0 0 3px rgba(255, 230, 160, 0.8));
    }

    .chat-head::after {
      content: "";
      position: absolute;
      left: 14px;
      right: 14px;
      bottom: 0;
      border-bottom: 2px dotted rgba(255, 255, 255, 0.5);
    }

    .chat-head-avatar {
      width: 44px;
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex: 0 0 auto;
    }

    .chat-head-avatar img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .chat-head-text {
      flex: 1;
      min-width: 0;
    }

    .chat-head-name {
      font-family: 'Caveat', cursive;
      font-weight: 700;
      font-size: 1.5rem;
      line-height: 1;
      text-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
    }

    .chat-head-status {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.74rem;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.85);
      margin-top: 2px;
    }

    .chat-head-status::before {
      content: "";
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #8fe0a0;
      box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
    }

    .chat-close {
      border: 2px solid rgba(255, 255, 255, 0.55);
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      cursor: pointer;
      flex: 0 0 auto;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 0 rgba(0, 0, 0, 0.15);
      transition: background 0.15s ease, transform 0.12s ease, box-shadow 0.12s ease;
    }

    .chat-close:hover {
      background: rgba(255, 255, 255, 0.32);
      transform: translateY(-1px);
      box-shadow: 0 3px 0 rgba(0, 0, 0, 0.15);
    }

    .chat-close:active {
      transform: translateY(2px);
      box-shadow: 0 0 0 rgba(0, 0, 0, 0.15);
    }

    .chat-close svg {
      width: 14px;
      height: 14px;
      stroke: currentColor;
    }

    .chat-log {
      flex: 1;
      min-height: 0;
      overflow-y: auto;
      overscroll-behavior: contain;
      -webkit-overflow-scrolling: touch;
      padding: 16px 16px 8px;
      display: flex;
      flex-direction: column;
      gap: 9px;
      background:
        radial-gradient(circle at 18% 8%, rgba(255, 189, 89, 0.14), transparent 42%),
        radial-gradient(circle at 88% 92%, rgba(143, 203, 157, 0.08), transparent 40%),
        radial-gradient(rgba(122, 90, 64, 0.06) 1px, transparent 1.4px) 0 0/18px 18px,
        var(--paper);
    }

    .chat-row {
      display: flex;
    }

    .chat-row.from-cat {
      justify-content: flex-start;
    }

    .chat-row.from-user {
      justify-content: flex-end;
    }

    .chat-row.from-system {
      justify-content: center;
    }

    .msg {
      max-width: 78%;
      padding: 10px 15px;
      border-radius: 17px;
      font-size: 0.92rem;
      line-height: 1.35;
      animation: msgPop 0.22s ease;
    }

    @keyframes msgPop {
      0% {
        opacity: 0;
        transform: translateY(6px) scale(0.97);
      }

      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .msg.cat {
      background: #fff;
      border: 2px solid #f5e9da;
      border-bottom-left-radius: 6px;
      box-shadow: 0 3px 0 #f0e2d0;
    }

    .msg.user {
      background: linear-gradient(160deg, var(--coral), var(--coral-deep));
      color: #fff;
      border: 2px solid #fff;
      border-bottom-right-radius: 6px;
      box-shadow: 0 3px 0 var(--coral-deep);
    }

    .msg.typing {
      background: #fff;
      border: 2px solid #f5e9da;
      border-bottom-left-radius: 6px;
      box-shadow: 0 3px 0 #f0e2d0;
      display: flex;
      align-items: center;
      gap: 4px;
      padding: 13px 16px;
    }

    .typing-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--ink-soft);
      opacity: 0.5;
      animation: typingBounce 1s ease-in-out infinite;
    }

    .typing-dot:nth-child(2) {
      animation-delay: 0.15s;
    }

    .typing-dot:nth-child(3) {
      animation-delay: 0.3s;
    }

    @keyframes typingBounce {

      0%,
      60%,
      100% {
        transform: translateY(0);
        opacity: 0.4;
      }

      30% {
        transform: translateY(-4px);
        opacity: 0.9;
      }
    }

    .msg.system {
      background: rgba(58, 50, 48, 0.06);
      color: var(--ink-soft);
      font-weight: 700;
      font-size: 0.72rem;
      letter-spacing: 0.01em;
      padding: 6px 14px;
      border-radius: 999px;
      max-width: 90%;
      text-align: center;
      box-shadow: none;
    }

    .chat-quick-row {
      display: flex;
      gap: 8px;
      padding: 0 16px 12px;
      overflow-x: auto;
      flex: 0 0 auto;
    }

    .chat-quick-btn {
      --chip: var(--sage);
      --chip-deep: var(--sage-deep);
      flex: 0 0 auto;
      background: linear-gradient(160deg, var(--chip), var(--chip-deep));
      border: 2px solid #fff;
      color: #fff;
      font-family: 'Nunito', sans-serif;
      font-weight: 800;
      font-size: 0.76rem;
      padding: 8px 14px;
      border-radius: 999px;
      cursor: pointer;
      white-space: nowrap;
      box-shadow: 0 3px 0 var(--chip-deep);
      transition: transform 0.12s cubic-bezier(.34, 1.56, .64, 1), box-shadow 0.12s ease;
    }

    .chat-quick-btn:nth-child(2) {
      --chip: var(--gold);
      --chip-deep: var(--gold-deep);
    }

    .chat-quick-btn:nth-child(3) {
      --chip: var(--dusk);
      --chip-deep: var(--dusk-deep);
    }

    .chat-quick-btn:nth-child(4) {
      --chip: var(--coral);
      --chip-deep: var(--coral-deep);
    }

    .chat-quick-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 0 var(--chip-deep);
    }

    .chat-quick-btn:active {
      transform: translateY(3px);
      box-shadow: 0 0 0 var(--chip-deep);
    }

    .chat-input-row {
      display: flex;
      align-items: center;
      gap: 9px;
      padding: 12px 14px;
      border-top: 1px solid #f0e6da;
      flex: 0 0 auto;
    }

    .chat-input-row input {
      flex: 1;
      border: 2px solid #f0e6da;
      border-radius: 26px;
      padding: 11px 17px;
      font-family: 'Nunito', sans-serif;
      font-size: 0.92rem;
      outline: none;
      min-width: 0;
      background: #fffdf9;
      box-shadow: inset 0 2px 4px rgba(58, 50, 48, 0.07);
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .chat-input-row input:focus {
      border-color: var(--coral);
      box-shadow: inset 0 2px 4px rgba(58, 50, 48, 0.07), 0 0 0 3px rgba(255, 140, 105, 0.2);
    }

    .chat-input-row button {
      background: linear-gradient(160deg, var(--coral), var(--coral-deep));
      color: #fff;
      border: 2px solid #fff;
      width: 46px;
      height: 46px;
      border-radius: 50%;
      cursor: pointer;
      flex: 0 0 auto;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 3px 0 var(--coral-deep);
      transition: transform 0.12s cubic-bezier(.34, 1.56, .64, 1), box-shadow 0.12s ease, filter 0.12s ease;
    }

    .chat-input-row button:hover {
      filter: brightness(1.05);
      transform: translateY(-2px);
      box-shadow: 0 5px 0 var(--coral-deep);
    }

    .chat-input-row button:active {
      transform: translateY(3px);
      box-shadow: 0 0 0 var(--coral-deep);
    }

    .chat-input-row button svg {
      width: 18px;
      height: 18px;
      fill: #fff;
    }

    /* ---------- Responsive ---------- */
    @media (max-width:720px) {
      .topbar {
        padding: calc(12px + env(safe-area-inset-top)) 12px 0;
        gap: 8px;
      }

      .title-pill {
        font-size: 0.92rem;
        padding: 6px 14px 6px 6px;
        gap: 8px;
      }

      .title-pill .paw {
        width: 26px;
        height: 26px;
      }

      .title-pill .paw svg {
        width: 13px;
        height: 13px;
      }

      .room-pill {
        display: none;
      }

      .stats-pill {
        gap: 0;
        padding: 7px 10px;
      }

      .stat {
        gap: 5px;
        padding: 0 7px;
      }

      .stat-icon {
        width: 20px;
        height: 20px;
      }

      .stat-track {
        width: 32px;
        height: 6px;
      }

      .stat-num {
        font-size: 0.68rem;
        min-width: 16px;
      }

      .stat-icon svg {
        width: 11px;
        height: 11px;
      }

      .room-door {
        width: 50px;
        height: 50px;
      }

      .room-door.prev {
        left: calc(10px + env(safe-area-inset-left));
      }

      .room-door.next {
        right: calc(10px + env(safe-area-inset-right));
      }

      .door-core {
        border-width: 2px;
        box-shadow: 0 3px 0 var(--accent-deep), 0 7px 14px var(--shadow);
      }

      .door-face-icon svg {
        width: 19px;
        height: 19px;
      }

      .door-chevron {
        width: 18px;
        height: 18px;
      }

      .door-chevron svg {
        width: 9px;
        height: 9px;
      }

      .door-tag {
        display: none;
      }

      .dock {
        left: calc(12px + env(safe-area-inset-left));
        right: auto;
        bottom: calc(16px + env(safe-area-inset-bottom));
        gap: 9px;
        padding: 6px;
        border-radius: 999px;
      }

      .action-btn {
        width: 42px;
        height: 42px;
        border-width: 2px;
        box-shadow: 0 3px 0 var(--btn-deep), 0 6px 10px rgba(60, 38, 26, 0.2);
      }

      .action-btn:hover {
        box-shadow: 0 5px 0 var(--btn-deep), 0 9px 14px rgba(60, 38, 26, 0.24);
      }

      .action-btn:active {
        box-shadow: 0 1px 0 var(--btn-deep), 0 2px 6px rgba(60, 38, 26, 0.18);
      }

      .action-btn .icon svg {
        width: 18px;
        height: 18px;
      }

      .action-btn::after {
        display: none;
      }

      .room-rail {
        bottom: calc(16px + env(safe-area-inset-bottom));
        gap: 6px;
        padding: 7px 12px;
      }

      .rail-dot {
        width: 7px;
        height: 7px;
      }

      .rail-dot.active {
        width: 16px;
      }

      .chat-fab {
        --fab-bottom: calc(14px + env(safe-area-inset-bottom));
        bottom: var(--fab-bottom);
        right: calc(10px + env(safe-area-inset-right));
        width: 52px;
        height: 69px;
      }

      .fab-badge {
        width: 12px;
        height: 12px;
        top: 2px;
        right: 8px;
      }

      .chat-widget {
        right: calc(8px + env(safe-area-inset-right));
        left: calc(8px + env(safe-area-inset-left));
        width: auto;
        max-width: none;
        bottom: calc(14px + env(safe-area-inset-bottom) + 69px + 10px);
        height: min(60dvh, 480px);
        max-height: calc(100dvh - 96px - env(safe-area-inset-top) - env(safe-area-inset-bottom));
        min-height: 280px;
        border-radius: 20px;
        border-width: 2px;
        box-shadow: 0 5px 0 rgba(60, 38, 26, 0.14), 0 18px 34px var(--shadow);
      }

      .chat-head {
        padding: 12px 14px;
      }

      .chat-head-avatar {
        width: 38px;
        height: 38px;
      }

      .chat-head-name {
        font-size: 0.92rem;
      }

      .chat-head-status {
        font-size: 0.68rem;
      }

      .chat-close {
        width: 30px;
        height: 30px;
      }

      .chat-log {
        padding: 12px 12px 6px;
        gap: 7px;
      }

      .msg {
        font-size: 0.88rem;
        padding: 9px 13px;
      }

      .chat-quick-row {
        padding: 0 12px 10px;
        gap: 6px;
      }

      .chat-quick-btn {
        font-size: 0.72rem;
        padding: 7px 12px;
        box-shadow: 0 2px 0 var(--chip-deep);
      }

      .chat-quick-btn:hover {
        box-shadow: 0 3px 0 var(--chip-deep);
      }

      .chat-quick-btn:active {
        box-shadow: 0 0 0 var(--chip-deep);
      }

      .chat-input-row {
        padding: 9px 10px;
      }

      .chat-input-row input {
        padding: 10px 15px;
        font-size: 0.88rem;
      }

      .chat-input-row button {
        width: 40px;
        height: 40px;
        box-shadow: 0 2px 0 var(--coral-deep);
      }

      .chat-input-row button:hover {
        box-shadow: 0 3px 0 var(--coral-deep);
      }

      .chat-input-row button:active {
        box-shadow: 0 0 0 var(--coral-deep);
      }
    }

    @media (max-width:720px) and (max-height:480px) {
      .chat-widget {
        height: min(80dvh, 320px);
        min-height: 200px;
        bottom: calc(10px + env(safe-area-inset-bottom) + 60px + 8px);
      }

      .chat-fab {
        width: 44px;
        height: 58px;
      }

      .chat-log {
        padding: 10px 12px 4px;
      }

      .chat-quick-row {
        display: none;
      }
    }

    @media (max-width:380px) {
      .title-pill {
        font-size: 0.82rem;
        padding: 5px 10px 5px 5px;
      }

      .title-pill .paw {
        width: 23px;
        height: 23px;
      }

      .stats-pill {
        gap: 0;
        padding: 6px 7px;
      }

      .stat {
        gap: 3px;
        padding: 0 5px;
      }

      .stat-icon {
        width: 18px;
        height: 18px;
      }

      .stat-track {
        width: 24px;
      }

      .stat-num {
        min-width: 14px;
      }

      .room-door {
        width: 44px;
        height: 44px;
      }

      .dock {
        gap: 7px;
        padding: 5px;
      }

      .action-btn {
        width: 38px;
        height: 38px;
      }

      .action-btn .icon svg {
        width: 16px;
        height: 16px;
      }

      .chat-fab {
        width: 46px;
        height: 61px;
      }

      .chat-widget {
        bottom: calc(14px + env(safe-area-inset-bottom) + 61px + 10px);
        min-height: 240px;
      }
    }

    /* ================= Cozy game skin: clay HUD + cat-head buttons ================= */
    :root {
      --clay-light: #b4e1bf;
      --clay-deep: #74b787;
      --clay-shadow: #4f8f64;
      --cream: #fff8ec;
      /* the cat-head button shape. Edit the two stop-color values to recolor every button */
      --cat-btn: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='g' x1='0' y1='0' x2='0' y2='1'><stop offset='0' stop-color='%23b4e1bf'/><stop offset='1' stop-color='%2374b787'/></linearGradient></defs><path d='M14 47.8 L14.5 10 Q15 5 19.5 7.5 L40 23.3 A38 38 0 0 1 60 23.3 L80.5 7.5 Q85 5 85.5 10 L86 47.8 A38 38 0 1 1 14 47.8 Z' fill='url(%23g)' stroke='%23fff8ec' stroke-width='3.5' stroke-linejoin='round'/><path d='M21 16 L33 25 L21 36 Z M79 16 L67 25 L79 36 Z' fill='%23fff8ec' opacity='.35'/><ellipse cx='50' cy='38' rx='22' ry='7' fill='%23fff' opacity='.2'/></svg>");
    }

    /* ----- Pills: title, room name, stats ----- */
    .title-pill.glass,
    .room-pill.glass,
    .stats-pill.glass {
      background: linear-gradient(180deg, var(--clay-light) 0%, var(--clay-deep) 100%);
      border: 2.5px solid var(--cream);
      box-shadow: 0 4px 0 var(--clay-shadow), 0 10px 18px rgba(60, 38, 26, 0.28), inset 0 2px 0 rgba(255, 255, 255, 0.45);
      backdrop-filter: none;
      -webkit-backdrop-filter: none;
      color: var(--cream);
      text-shadow: 0 2px 0 rgba(63, 120, 82, 0.55);
    }

    .title-pill .paw {
      background: linear-gradient(160deg, #fffdf6, #ecf5e8);
      box-shadow: 0 2px 0 var(--clay-shadow), inset 0 1px 1px rgba(255, 255, 255, 0.8);
    }

    .title-pill .paw svg {
      fill: var(--clay-deep);
    }

    .room-pill::before {
      display: none;
    }

    .room-pill.glass {
      padding: 9px 24px;
    }

    .stats-pill .stat-icon {
      background: var(--cream);
      box-shadow: 0 2px 0 rgba(63, 120, 82, 0.45);
    }

    .stats-pill .stat-track {
      background: rgba(52, 104, 70, 0.38);
      box-shadow: inset 0 2px 3px rgba(30, 70, 45, 0.4);
    }

    .stats-pill .stat-num {
      background: var(--cream);
      padding: 1px 8px;
      border-radius: 999px;
      min-width: 32px;
      text-align: center;
      text-shadow: none;
      box-shadow: 0 2px 0 rgba(63, 120, 82, 0.4);
    }

    .stats-pill .stat+.stat::before {
      background: linear-gradient(180deg, transparent, rgba(255, 248, 236, 0.6), transparent);
    }

    /* ----- Room rail ----- */
    .room-rail.glass {
      background: rgba(255, 248, 236, 0.94);
      border: 2.5px solid #fff;
      box-shadow: 0 3px 0 rgba(63, 120, 82, 0.35), 0 8px 16px rgba(60, 38, 26, 0.22);
      backdrop-filter: none;
      -webkit-backdrop-filter: none;
    }

    .rail-dot {
      background: rgba(95, 168, 119, 0.25);
      border-color: #fff;
    }

    .rail-dot:hover {
      background: rgba(95, 168, 119, 0.45);
    }

    .rail-dot.active {
      background: linear-gradient(90deg, var(--clay-light), var(--clay-deep));
      box-shadow: 0 2px 0 var(--clay-shadow);
    }

    /* ----- Room doors: cat-head buttons ----- */
    .room-door {
      width: 86px;
      height: 86px;
    }

    .door-core {
      border: 0;
      border-radius: 0;
      background: var(--cat-btn) center/100% 100% no-repeat;
      box-shadow: none;
      padding-top: 20%;
      filter: drop-shadow(0 4px 0 var(--clay-shadow)) drop-shadow(0 10px 12px rgba(60, 38, 26, 0.3));
      animation: none;
      transition: filter 0.2s ease, transform 0.15s ease;
    }

    .room-door:hover .door-core {
      box-shadow: none;
      transform: translateY(-2px);
      filter: drop-shadow(0 6px 0 var(--clay-shadow)) drop-shadow(0 14px 14px rgba(60, 38, 26, 0.32));
    }

    .room-door:active .door-core {
      box-shadow: none;
      transform: translateY(3px);
      filter: drop-shadow(0 1px 0 var(--clay-shadow)) drop-shadow(0 3px 6px rgba(60, 38, 26, 0.28));
    }

    .door-face-icon svg {
      width: 28px;
      height: 28px;
    }

    .door-ring {
      inset: -8px;
      background: radial-gradient(circle, rgba(255, 230, 160, 0.85) 0%, transparent 68%);
      filter: blur(8px);
      animation: catGlow 3.4s ease-in-out infinite;
    }

    @keyframes catGlow {

      0%,
      100% {
        opacity: 0.25;
        transform: scale(0.94);
      }

      50% {
        opacity: 0.6;
        transform: scale(1.06);
      }
    }

    .door-chevron {
      background: linear-gradient(160deg, var(--clay-light), var(--clay-deep));
      box-shadow: 0 3px 0 var(--clay-shadow), 0 0 0 3px var(--cream);
    }

    .door-chevron-prev {
      left: 2px;
      bottom: 4px;
    }

    .door-chevron-next {
      right: 2px;
      bottom: 4px;
    }

    /* ----- Action dock: cat-head buttons ----- */
    .dock.glass {
      backdrop-filter: none;
      -webkit-backdrop-filter: none;
    }

    .dock.glass::before {
      display: none;
    }

    .action-btn {
      width: 66px;
      height: 66px;
      padding: 20% 0 0;
      border: 0;
      border-radius: 0;
      background: none;
      box-shadow: none;
    }

    .action-btn::before {
      content: "";
      position: absolute;
      inset: 0;
      z-index: 0;
      background: var(--cat-btn) center/100% 100% no-repeat;
      filter: drop-shadow(0 4px 0 var(--clay-shadow)) drop-shadow(0 8px 10px rgba(60, 38, 26, 0.28));
      transition: filter 0.14s ease;
    }

    .action-btn .icon {
      position: relative;
      z-index: 1;
    }

    .action-btn:hover,
    .action-btn:active {
      box-shadow: none;
    }

    .action-btn:hover::before {
      filter: drop-shadow(0 7px 0 var(--clay-shadow)) drop-shadow(0 12px 14px rgba(60, 38, 26, 0.3));
    }

    .action-btn:active::before {
      filter: drop-shadow(0 1px 0 var(--clay-shadow)) drop-shadow(0 3px 6px rgba(60, 38, 26, 0.25));
    }

    /* ----- Chat button: cat-head with paw + "new message" bubble ----- */
    .chat-fab {
      width: 96px;
      height: 96px;
      filter: none;
    }

    .chat-fab::before,
    .chat-fab::after {
      display: none;
    }

    .fab-face {
      position: relative;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding-top: 20%;
      background: var(--cat-btn) center/100% 100% no-repeat;
      filter: drop-shadow(0 4px 0 var(--clay-shadow)) drop-shadow(0 10px 14px rgba(60, 38, 26, 0.32));
      animation: fabFloat 3.6s ease-in-out infinite;
      pointer-events: none;
    }

    .fab-face svg {
      width: 42%;
      height: 42%;
      fill: var(--cream);
      filter: drop-shadow(0 1px 0 rgba(0, 0, 0, 0.14));
    }


    @media (max-width: 720px) {
      .room-door {
        width: 64px;
        height: 64px;
      }

      .door-face-icon svg {
        width: 21px;
        height: 21px;
      }

      .action-btn {
        width: 54px;
        height: 54px;
      }

      .action-btn .icon svg {
        width: 20px;
        height: 20px;
      }

      .chat-fab {
        width: 68px;
        height: 68px;
        bottom: calc(12px + env(safe-area-inset-bottom));
        right: calc(8px + env(safe-area-inset-right));
      }

      .fab-badge {
        width: 24px;
        height: 19px;
        top: 0;
        right: -2px;
      }

      .stats-pill .stat-num {
        padding: 0 5px;
        min-width: 22px;
      }
    }

    @media (max-width: 380px) {
      .room-door {
        width: 56px;
        height: 56px;
      }

      .action-btn {
        width: 48px;
        height: 48px;
      }

      .chat-fab {
        width: 60px;
        height: 60px;
      }
    }

    /* ================= Cozy chat: knitted panel ================= */
    :root {
      /* tiny knit "V" stitches */
      --knit: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='10' height='12' viewBox='0 0 10 12'><path d='M1 1.5 L5 8.5 L9 1.5' fill='none' stroke='%236e5a3c' stroke-opacity='.16' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/><path d='M1 .5 L5 7.5 L9 .5' fill='none' stroke='%23ffffff' stroke-opacity='.55' stroke-width='.9' stroke-linecap='round' stroke-linejoin='round'/></svg>");
      /* gold embroidered paw / fish bone / cat face divider */
      --stitch-divider: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='250' height='34' viewBox='0 0 250 34'><defs><g id='e' fill='none' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'><circle cx='10' cy='17' r='.6' stroke-width='3.4'/><ellipse cx='40' cy='21' rx='8' ry='6'/><ellipse cx='31' cy='12' rx='2.6' ry='3.4'/><ellipse cx='37' cy='8' rx='2.6' ry='3.4'/><ellipse cx='43' cy='8' rx='2.6' ry='3.4'/><ellipse cx='49' cy='12' rx='2.6' ry='3.4'/><circle cx='66' cy='17' r='.6' stroke-width='3.4'/><ellipse cx='88' cy='17' rx='7' ry='5.5'/><circle cx='85.5' cy='16' r='.5' stroke-width='2.2'/><path d='M95 17 H133 M101 17 L105 10 M101 17 L105 24 M109 17 L113 10 M109 17 L113 24 M117 17 L121 10 M117 17 L121 24 M133 17 L143 9 M133 17 L143 25 M143 9 L143 25'/><circle cx='156' cy='17' r='.6' stroke-width='3.4'/><path d='M171 24 Q170 12 172 7 L178 12 Q184 10.5 190 12 L196 7 Q198 12 197 24 Q197 30 184 30 Q171 30 171 24Z'/><circle cx='178' cy='20' r='.5' stroke-width='2.2'/><circle cx='190' cy='20' r='.5' stroke-width='2.2'/><path d='M182 24 L184 25.5 L186 24 Z'/><circle cx='214' cy='17' r='.6' stroke-width='3.4'/></g></defs><use href='%23e' x='.9' y='1' stroke='%23a8813a' stroke-opacity='.35'/><use href='%23e' stroke='%23dbb862'/></svg>");
      /* paw badge on the input */
      --paw-badge: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23f6e4bd'><circle cx='6.5' cy='7.5' r='2.1'/><circle cx='11.5' cy='5.3' r='2.1'/><circle cx='16.5' cy='7.5' r='2.1'/><circle cx='19.3' cy='12.2' r='1.9'/><path d='M12 20.2c-3.4 0-6-1.8-6-4.4 0-2.3 2.1-4 6-4s6 1.7 6 4c0 2.6-2.6 4.4-6 4.4Z'/></svg>");
    }

    /* ----- Panel: cream knit with sage stripes ----- */
    .chat-widget {
      border: 5px solid #d6e3d0;
      border-radius: 28px;
      background:
        var(--knit) 0 0/10px 12px repeat,
        linear-gradient(90deg, transparent 0 9%, rgba(143, 190, 150, 0.55) 9% 23%, transparent 23% 77%, rgba(143, 190, 150, 0.55) 77% 92%, transparent 92%),
        #fbf3e1;
      box-shadow: 0 0 0 2px #a9c2a6, inset 0 0 0 2px rgba(255, 255, 255, 0.7), 0 8px 0 rgba(60, 38, 26, 0.16), 0 26px 50px var(--shadow);
    }

    .chat-log {
      background: none;
      scrollbar-width: thin;
      scrollbar-color: #b7cdb4 transparent;
    }

    /* ----- Quilted header with stitched edge ----- */
    .chat-head {
      background:
        radial-gradient(ellipse at 25% 20%, rgba(255, 255, 255, 0.55), transparent 55%),
        repeating-linear-gradient(45deg, rgba(120, 140, 105, 0.10) 0 7px, rgba(255, 255, 255, 0.10) 7px 14px),
        linear-gradient(180deg, #e9edd8 0%, #cfdac0 100%);
      color: #5b4a36;
      border-radius: 0 0 22px 22px;
      box-shadow: inset 0 -3px 0 rgba(90, 115, 85, 0.28), 0 4px 0 #b3c4a6, 0 8px 10px rgba(60, 38, 26, 0.18);
      z-index: 2;
    }

    .chat-head::before {
      top: 6px;
      left: 6px;
      right: 6px;
      bottom: 6px;
      height: auto;
      background: none;
      filter: none;
      opacity: 1;
      border: 2px dashed rgba(110, 135, 105, 0.55);
      border-radius: 16px;
    }

    .chat-head::after {
      display: none;
    }

    .chat-head-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: radial-gradient(circle at 40% 30%, #4d6a5c, #24302a);
      border: 3px solid var(--cream, #fff8ec);
      box-shadow: 0 2px 0 rgba(90, 115, 85, 0.45);
      overflow: hidden;
    }

    .chat-head-name {
      color: #5b4a36;
      font-size: 1.7rem;
      text-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    .chat-head-status {
      color: #8b8a6c;
    }

    .chat-head-status::before {
      box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.7);
    }

    .chat-close {
      background: linear-gradient(160deg, #e9eed9, #cbd7bb);
      border: 2px solid #b9c7a8;
      color: #6f7d62;
      box-shadow: 0 2px 0 rgba(90, 115, 85, 0.35);
    }

    .chat-close:hover {
      background: linear-gradient(160deg, #f1f5e4, #d5e0c6);
      box-shadow: 0 3px 0 rgba(90, 115, 85, 0.35);
    }

    /* ----- Messages ----- */
    /* Nova: fluffy cloud, script font */
    .msg.cat,
    .msg.typing {
      position: relative;
      font-family: 'Caveat', cursive;
      font-weight: 700;
      font-size: 1.12rem;
      line-height: 1.2;
      color: #4a3b2e;
      padding: 12px 20px;
      background: #d9ecf8;
      border: 0;
      border-radius: 30px 34px 32px 28px / 26px 28px 30px 24px;
      box-shadow: 0 0 0 3px #d9ecf8, 0 0 9px 5px rgba(190, 222, 242, 0.8);
    }

    .msg.cat::before,
    .msg.cat::after {
      content: "";
      position: absolute;
      z-index: -1;
      border-radius: 50%;
      background: #d9ecf8;
      box-shadow: 0 0 6px 2px rgba(190, 222, 242, 0.7);
    }

    .msg.cat::before {
      width: 30px;
      height: 26px;
      left: 12px;
      top: -9px;
    }

    .msg.cat::after {
      width: 34px;
      height: 28px;
      right: 16px;
      bottom: -9px;
    }

    /* You: stitched green felt patch */
    .msg.user {
      font-weight: 800;
      color: #fff;
      background: linear-gradient(160deg, #a9d7b4, #7cbd8d);
      border: 2px dashed rgba(255, 248, 236, 0.9);
      border-radius: 18px 18px 6px 18px;
      text-shadow: 0 1px 0 rgba(63, 120, 82, 0.5);
      box-shadow: 0 0 0 3px #7cbd8d, 0 5px 0 3px #4f8f64;
    }

    .msg.system {
      background: rgba(255, 248, 236, 0.92);
      color: #7a7d62;
      border: 1.5px dashed #b4c7ae;
      box-shadow: none;
    }

    /* ----- Embroidered divider + jelly quick replies ----- */
    .chat-quick-row {
      padding: 36px 16px 12px;
      background: var(--stitch-divider) center 4px/auto 28px repeat-x;
      scrollbar-width: thin;
      scrollbar-color: #a9c2a6 rgba(255, 255, 255, 0.5);
    }

    .chat-quick-btn {
      border: 0;
      padding: 9px 18px;
      font-size: 0.8rem;
      text-shadow: 0 1px 1px rgba(0, 0, 0, 0.25);
      background:
        linear-gradient(180deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.1) 50%, transparent 52%),
        linear-gradient(180deg, var(--chip), var(--chip-deep));
      box-shadow: inset 0 -3px 5px rgba(0, 0, 0, 0.14), inset 0 2px 3px rgba(255, 255, 255, 0.7), 0 3px 0 var(--chip-deep), 0 6px 8px rgba(60, 38, 26, 0.2);
    }

    .chat-quick-btn:hover {
      box-shadow: inset 0 -3px 5px rgba(0, 0, 0, 0.14), inset 0 2px 3px rgba(255, 255, 255, 0.7), 0 5px 0 var(--chip-deep), 0 9px 10px rgba(60, 38, 26, 0.2);
    }

    .chat-quick-btn:active {
      box-shadow: inset 0 -3px 5px rgba(0, 0, 0, 0.14), inset 0 2px 3px rgba(255, 255, 255, 0.7), 0 0 0 var(--chip-deep);
    }

    /* ----- Wooden input + compass send button ----- */
    .chat-input-row {
      position: relative;
      border-top: 0;
      padding: 14px 16px 16px;
    }

    .chat-input-row::before {
      content: "";
      position: absolute;
      left: 8px;
      top: 6px;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: var(--paw-badge) center/15px no-repeat, radial-gradient(circle at 35% 30%, #d9a463, #8a5d2e);
      border: 2px solid #7b5128;
      box-shadow: 0 2px 0 rgba(60, 38, 26, 0.3);
      z-index: 2;
    }

    .chat-input-row input {
      border: 5px solid transparent;
      border-radius: 28px;
      padding: 9px 16px;
      color: #5b4a36;
      background:
        linear-gradient(#f8efdc, #f8efdc) padding-box,
        repeating-linear-gradient(100deg, #a8743c 0 6px, #b98650 6px 10px, #97663a 10px 15px) border-box;
      box-shadow: inset 0 3px 5px rgba(90, 60, 30, 0.22), 0 0 0 1.5px #6f4a25, 0 3px 0 1.5px #5e3d1b;
    }

    .chat-input-row input::placeholder {
      color: #9a8566;
    }

    .chat-input-row input:focus {
      border-color: transparent;
      box-shadow: inset 0 3px 5px rgba(90, 60, 30, 0.22), 0 0 0 1.5px #6f4a25, 0 0 0 4px rgba(255, 200, 120, 0.55);
    }

    .chat-input-row button {
      width: 48px;
      height: 48px;
      background: radial-gradient(circle at 35% 28%, #e2b072, #b27c43 55%, #8a5d2e);
      border: 3px solid #7b5128;
      box-shadow: 0 3px 0 #5e3d1b, inset 0 0 0 3px rgba(255, 226, 170, 0.35);
    }

    .chat-input-row button:hover {
      box-shadow: 0 5px 0 #5e3d1b, inset 0 0 0 3px rgba(255, 226, 170, 0.35);
    }

    .chat-input-row button:active {
      box-shadow: 0 0 0 #5e3d1b, inset 0 0 0 3px rgba(255, 226, 170, 0.35);
    }

    .chat-input-row button svg {
      fill: #f6e4bd;
      stroke: #7b5128;
      stroke-width: 1;
      stroke-linejoin: round;
      filter: drop-shadow(0 1px 0 rgba(255, 255, 255, 0.25));
    }

    /* ----- Phones ----- */
    @media (max-width: 720px) {
      .chat-head-avatar {
        width: 40px;
        height: 40px;
      }

      .chat-head-name {
        font-size: 1.4rem;
      }

      .chat-quick-btn {
        padding: 8px 14px;
        font-size: 0.74rem;
      }

      .chat-input-row {
        padding: 12px 12px 14px;
      }

      .chat-input-row button {
        width: 42px;
        height: 42px;
      }
    }


    /* ================= Embroidered HUD ================= */
    :root {
      /* cat-head patch: satin-stitch fill, thread border, running stitch (replaces the earlier --cat-btn) */
      --cat-btn: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><pattern id='s' width='3.2' height='3.2' patternUnits='userSpaceOnUse' patternTransform='rotate(35)'><rect width='3.2' height='3.2' fill='%238cc79b'/><rect width='1.2' height='3.2' fill='%236fae84' opacity='.75'/><rect x='1.6' width='.6' height='3.2' fill='%23c4e8cd' opacity='.55'/></pattern><path id='p' d='M14 47.8 L14.5 10 Q15 5 19.5 7.5 L40 23.3 A38 38 0 0 1 60 23.3 L80.5 7.5 Q85 5 85.5 10 L86 47.8 A38 38 0 1 1 14 47.8 Z'/></defs><use href='%23p' fill='url(%23s)' stroke='%23fff4dc' stroke-width='4' stroke-linejoin='round'/><use href='%23p' fill='none' stroke='%23d9c9a6' stroke-width='4' stroke-dasharray='1.1 1.3' stroke-opacity='.5' stroke-linejoin='round'/><use href='%23p' fill='none' stroke='%23fff8ec' stroke-width='1.7' stroke-dasharray='3.2 2.6' stroke-linecap='round' transform='translate(50 57) scale(.8) translate(-50 -57)'/></svg>");
    }

    /* ----- Patches: title, room name, stats ----- */
    .title-pill.glass,
    .room-pill.glass,
    .stats-pill.glass {
      background:
        repeating-linear-gradient(115deg, rgba(255, 255, 255, 0.14) 0 1.5px, transparent 1.5px 3.5px),
        linear-gradient(180deg, #9fd3ac 0%, #78b98a 100%);
      border: 3px solid #fff4dc;
      box-shadow: 0 0 0 1.5px #cdbd95, 0 4px 0 1.5px var(--clay-shadow), 0 10px 18px rgba(60, 38, 26, 0.28);
      color: #fff8ec;
      letter-spacing: 0.03em;
      text-shadow: 0 1px 0 rgba(255, 255, 255, 0.45), 0 2px 0 #4f8f64, 0 3px 3px rgba(30, 70, 45, 0.35);
    }

    /* dashed running stitch inside each patch */
    .title-pill.glass::before,
    .room-pill.glass::before,
    .stats-pill.glass::before {
      display: block;
      position: absolute;
      inset: 4px;
      width: auto;
      height: auto;
      border: 2px dashed rgba(255, 248, 236, 0.85);
      border-radius: inherit;
      background: none;
      box-shadow: none;
      animation: none;
      opacity: 1;
      mix-blend-mode: normal;
      z-index: -1;
    }

    /* ----- Progress bars: thread guide + satin-stitch fill ----- */
    .stats-pill .stat-icon {
      outline: 1.5px dashed rgba(95, 168, 119, 0.75);
      outline-offset: -3.5px;
    }

    .stats-pill .stat-track {
      height: 10px;
      background:
        repeating-linear-gradient(90deg, rgba(255, 248, 236, 0.75) 0 3px, transparent 3px 6px) center/100% 2px no-repeat,
        rgba(52, 104, 70, 0.34);
      box-shadow: inset 0 2px 3px rgba(30, 70, 45, 0.4);
    }

    .stats-pill .fill-hunger,
    .stats-pill .fill-happy,
    .stats-pill .fill-energy {
      box-shadow: inset 0 0 0 1px rgba(255, 248, 236, 0.5);
    }

    .stats-pill .fill-hunger {
      background: repeating-linear-gradient(60deg, rgba(255, 255, 255, 0.38) 0 1.5px, transparent 1.5px 4px), linear-gradient(90deg, #f0ab5e, #e6883a);
    }

    .stats-pill .fill-happy {
      background: repeating-linear-gradient(60deg, rgba(255, 255, 255, 0.38) 0 1.5px, transparent 1.5px 4px), linear-gradient(90deg, #ff9d7d, #e2603f);
    }

    .stats-pill .fill-energy {
      background: repeating-linear-gradient(60deg, rgba(255, 255, 255, 0.38) 0 1.5px, transparent 1.5px 4px), linear-gradient(90deg, #a2c2ea, #6f95cf);
    }

    /* thread knot at the tip of each bar (replaces the shiny sheen) */
    .stats-pill .stat-fill::after {
      inset: auto;
      right: 0;
      top: 50%;
      width: 9px;
      height: 9px;
      transform: translateY(-50%);
      border-radius: 50%;
      background: radial-gradient(circle at 35% 30%, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0) 65%);
      background-size: auto;
      animation: none;
    }

    .stats-pill .stat+.stat::before {
      width: 0;
      height: 22px;
      background: none;
      border-left: 2px dashed rgba(255, 248, 236, 0.7);
    }

    .stats-pill .stat-num {
      border: 1.5px dashed rgba(95, 168, 119, 0.7);
    }

    /* ----- Rail ----- */
    .room-rail.glass {
      border: 3px solid #fff4dc;
      box-shadow: 0 0 0 1.5px #cdbd95, 0 3px 0 1.5px rgba(63, 120, 82, 0.4), 0 8px 16px rgba(60, 38, 26, 0.22);
    }

    .room-rail.glass::before {
      display: block;
      position: absolute;
      inset: 3px;
      border: 1.5px dashed rgba(95, 168, 119, 0.6);
      border-radius: inherit;
      background: none;
      opacity: 1;
      mix-blend-mode: normal;
      z-index: -1;
    }

    /* ----- Button icons + chevrons: raised thread ----- */
    .door-face-icon svg,
    .action-btn .icon svg {
      filter: drop-shadow(0 1.5px 0 rgba(47, 105, 70, 0.75));
    }

    .door-chevron {
      outline: 1.5px dashed rgba(255, 248, 236, 0.9);
      outline-offset: -3px;
    }

    /* ----- Hover labels: stitched felt tags ----- */
    .door-tag,
    .action-btn::after {
      background:
        repeating-linear-gradient(115deg, rgba(255, 255, 255, 0.12) 0 1.5px, transparent 1.5px 3.5px),
        linear-gradient(180deg, #b9955f, #8f6d46);
      color: #fff8ec;
      clip-path: none;
      border-radius: 10px;
      padding: 7px 14px;
      outline: 1.5px dashed rgba(255, 244, 220, 0.85);
      outline-offset: -3.5px;
      text-shadow: 0 1px 0 rgba(60, 38, 22, 0.7);
      box-shadow: 0 3px 0 #6d5133, 0 6px 8px rgba(60, 38, 26, 0.25);
    }

    .door-tag::after {
      border-top-color: #8f6d46;
    }

    /* ----- Phones ----- */
    @media (max-width: 720px) {
      .stats-pill .stat-track {
        height: 7px;
      }

      .stats-pill .stat-fill::after {
        width: 6px;
        height: 6px;
      }
    }

    /* ================= Wooden hanging signs + quilted stats ================= */
    :root {
      /* the two ropes above each sign */
      --ropes: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 18' preserveAspectRatio='none'><path d='M0 18 L50 1 L100 18' fill='none' stroke='%23b98e55' stroke-width='2.2' stroke-linejoin='round' vector-effect='non-scaling-stroke'/><path d='M0 18 L50 1 L100 18' fill='none' stroke='%23e7c98f' stroke-width='.8' stroke-linejoin='round' stroke-dasharray='3 3' vector-effect='non-scaling-stroke'/></svg>");
      /* leaf icon for the hunger bar */
      --leaf: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%237fb05a' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'><path d='M5 19C5 9 11 4 20 4C20 13 15 19 5 19Z' fill='%23dff0c8'/><path d='M5 19L13 11'/></svg>");
    }

    /* ----- Wooden signs: title + room name ----- */
    .title-pill.glass,
    .room-pill.glass {
      overflow: visible;
      border: 3px solid #8a5a2c;
      border-radius: 10px;
      background:
        repeating-linear-gradient(0deg, rgba(90, 55, 25, 0.10) 0 1px, transparent 1px 5px),
        repeating-linear-gradient(92deg, rgba(255, 230, 180, 0.10) 0 3px, transparent 3px 9px),
        linear-gradient(180deg, #d9a566 0%, #c18a4d 55%, #a8733a 100%);
      box-shadow: inset 0 2px 0 rgba(255, 235, 200, 0.55), inset 0 -3px 0 rgba(90, 55, 25, 0.35), 0 4px 0 #6d4620, 0 10px 14px rgba(60, 38, 26, 0.35);
      color: #fbeccb;
      text-shadow: 0 2px 0 rgba(80, 45, 15, 0.85), 0 3px 3px rgba(60, 35, 15, 0.4);
    }

    .title-pill.glass {
      padding: 8px 22px 8px 14px;
    }

    /* engraved inner frame (replaces the dashed stitch on these two) */
    .title-pill.glass::before,
    .room-pill.glass::before {
      inset: 4px;
      border: 1.5px solid rgba(90, 55, 25, 0.28);
      border-radius: 6px;
    }

    /* hanging ropes */
    .title-pill.glass::after,
    .room-pill.glass::after {
      content: "";
      position: absolute;
      left: 16%;
      right: 16%;
      bottom: calc(100% + 1px);
      height: 18px;
      background: var(--ropes) center/100% 100% no-repeat;
      pointer-events: none;
      z-index: -1;
    }

    .title-pill .paw {
      background: none;
      box-shadow: none;
    }

    .title-pill .paw svg {
      width: 20px;
      height: 20px;
      fill: #6b4423;
      filter: drop-shadow(0 1px 0 rgba(255, 230, 180, 0.55));
    }

    /* ----- Quilted stats panel ----- */
    .stats-pill.glass {
      border-radius: 16px;
      padding: 8px 14px;
      border: 3px solid #5d8f63;
      background:
        repeating-linear-gradient(45deg, rgba(255, 255, 255, 0.07) 0 1px, transparent 1px 14px),
        repeating-linear-gradient(-45deg, rgba(255, 255, 255, 0.07) 0 1px, transparent 1px 14px),
        radial-gradient(ellipse at 50% 0%, rgba(255, 255, 255, 0.22), transparent 70%),
        linear-gradient(180deg, #86b886, #6ea374);
      box-shadow: inset 0 2px 0 rgba(255, 255, 255, 0.35), inset 0 -3px 0 rgba(40, 80, 50, 0.28), 0 4px 0 #4a7a52, 0 10px 14px rgba(60, 38, 26, 0.3);
    }

    .stats-pill.glass::before {
      border: 1.5px dashed rgba(255, 248, 236, 0.55);
    }

    .stats-pill .stat+.stat::before {
      display: none;
    }

    .stats-pill .stat-happy {
      order: -1;
    }

    /* heart first, then leaf, then bolt */

    /* icons: no circle, colored outline */
    .stats-pill .stat-icon {
      width: 26px;
      height: 26px;
      background: none;
      box-shadow: none;
      outline: none;
    }

    .stats-pill .stat-icon svg {
      width: 22px;
      height: 22px;
      filter: drop-shadow(0 1px 0 rgba(30, 70, 45, 0.35));
    }

    .stats-pill .stat-happy .stat-icon svg {
      fill: #ffd6c8;
      stroke: #e2603f;
      stroke-width: 2;
    }

    .stats-pill .stat-energy .stat-icon svg {
      fill: #cfe0f7;
      stroke: #5b7fc4;
      stroke-width: 1.6;
      stroke-linejoin: round;
    }

    .stats-pill .stat-hunger .stat-icon svg {
      display: none;
    }

    .stats-pill .stat-hunger .stat-icon::before {
      content: "";
      width: 22px;
      height: 22px;
      background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><path d='M11.5 12.5 L6.5 17.5' stroke='%23c9b58f' stroke-width='3.2' stroke-linecap='round'/><path d='M11.5 12.5 L6.5 17.5' stroke='%23fff4dc' stroke-width='1.6' stroke-linecap='round'/><ellipse cx='15.5' cy='8.5' rx='6' ry='5' transform='rotate(40 15.5 8.5)' fill='%23f0a860' stroke='%23b5651f' stroke-width='1.6'/><path d='M14 5.6 Q16 4.8 17.6 6.1' fill='none' stroke='%23ffffff' stroke-opacity='.6' stroke-width='1.2' stroke-linecap='round'/><circle cx='4.8' cy='17.2' r='1.9' fill='%23fff4dc' stroke='%23c9b58f' stroke-width='1.2'/><circle cx='6.9' cy='19.3' r='1.9' fill='%23fff4dc' stroke='%23c9b58f' stroke-width='1.2'/></svg>") center/contain no-repeat;
      filter: drop-shadow(0 1px 0 rgba(30, 70, 45, 0.35));
    }

    /* bars: cream pill, glossy fill, number inside */
    .stats-pill .stat-track {
      width: 104px;
      height: 18px;
      background: #fbf3e1;
      box-shadow: inset 0 2px 3px rgba(80, 55, 30, 0.35), 0 0 0 2px rgba(50, 90, 60, 0.35);
    }

    .stats-pill .fill-hunger {
      background: linear-gradient(180deg, #ffc46b, #e8923a);
    }

    .stats-pill .fill-happy {
      background: linear-gradient(180deg, #ffab8f, #f0775a);
    }

    .stats-pill .fill-energy {
      background: linear-gradient(180deg, #a3c0f2, #5b82cf);
    }

    .stats-pill .fill-hunger,
    .stats-pill .fill-happy,
    .stats-pill .fill-energy {
      box-shadow: inset 0 3px 0 rgba(255, 255, 255, 0.45), inset 0 -2px 0 rgba(0, 0, 0, 0.12);
    }

    .stats-pill .stat-fill::after {
      display: none;
    }

    .stats-pill .stat .stat-num {
      position: relative;
      z-index: 1;
      margin-left: -38px;
      /* slides the number back over the end of the bar */
      min-width: 30px;
      padding: 0 9px 0 0;
      background: none;
      border: 0;
      box-shadow: none;
      text-align: right;
      color: #6b4a2b;
    }

    /* ----- Phones ----- */
    @media (max-width: 720px) {
      .title-pill.glass {
        padding: 6px 16px 6px 10px;
      }

      .title-pill .paw svg {
        width: 16px;
        height: 16px;
      }

      .title-pill.glass::after,
      .room-pill.glass::after {
        height: calc(12px + env(safe-area-inset-top));
      }

      .stats-pill.glass {
        padding: 7px 10px;
      }

      .stats-pill .stat {
        gap: 5px;
        padding: 0 6px;
      }

      .stats-pill .stat-icon {
        width: 20px;
        height: 20px;
      }

      .stats-pill .stat-icon svg,
      .stats-pill .stat-hunger .stat-icon::before {
        width: 17px;
        height: 17px;
      }

      .stats-pill .stat-track {
        width: 58px;
        height: 14px;
      }

      .stats-pill .stat .stat-num {
        min-width: 22px;
        margin-left: -27px;
        padding-right: 6px;
        font-size: 0.68rem;
      }
    }

    /* ================= Cozy splash screen ================= */
    .splash {
      position: fixed;
      inset: 0;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      overflow: hidden;
      background:
        var(--knit, none) 0 0/10px 12px repeat,
        linear-gradient(90deg, transparent 0 9%, rgba(143, 190, 150, 0.5) 9% 17%, transparent 17% 83%, rgba(143, 190, 150, 0.5) 83% 91%, transparent 91%),
        radial-gradient(ellipse at 50% 45%, #fff8ec 0%, #f6ead0 70%, #ead9b8 100%);
      transition: opacity 0.7s ease, transform 0.7s ease, filter 0.7s ease;
    }

    .splash::before {
      /* stitched frame */
      content: "";
      position: absolute;
      inset: 14px;
      border: 2.5px dashed rgba(110, 150, 115, 0.55);
      border-radius: 26px;
      pointer-events: none;
      z-index: 1;
    }

    .splash::after {
      /* warm vignette */
      content: "";
      position: absolute;
      inset: 0;
      box-shadow: inset 0 0 120px rgba(120, 80, 40, 0.28);
      pointer-events: none;
    }

    .splash.leaving {
      opacity: 0;
      transform: scale(1.05);
      filter: blur(8px);
      pointer-events: none;
    }

    /* fairy lights + dust motes */
    .splash-lights {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 40px;
      z-index: 1;
      pointer-events: none;
    }

    .splash-lights svg {
      display: block;
      width: 100%;
      height: 100%;
    }

    .splash-bulb {
      position: absolute;
      width: 12px;
      height: 16px;
      margin-left: -6px;
      border-radius: 50% 50% 55% 55%;
      background: var(--c, #ffd27a);
      box-shadow: 0 0 10px 3px var(--c, #ffd27a), 0 0 22px 8px rgba(255, 200, 120, 0.35);
      animation: splashTwinkle 2.4s ease-in-out infinite;
    }

    @keyframes splashTwinkle {

      0%,
      100% {
        opacity: 0.55;
        transform: scale(0.92);
      }

      50% {
        opacity: 1;
        transform: scale(1.06);
      }
    }

    .splash-motes {
      position: absolute;
      inset: 0;
      overflow: hidden;
      z-index: 1;
      pointer-events: none;
    }

    .splash-motes .mote {
      background: radial-gradient(circle, #ffd27a 0%, rgba(255, 190, 90, 0.55) 55%, transparent 75%);
    }

    /* content */
    .splash-card {
      position: relative;
      z-index: 2;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: clamp(16px, 3.5vh, 28px);
      text-align: center;
    }

    /* wooden sign */
    .splash-sign {
      position: relative;
      display: flex;
      align-items: center;
      gap: 12px;
      margin-top: 44px;
      padding: 12px 30px 12px 20px;
      font-family: 'Fredoka', sans-serif;
      font-weight: 700;
      font-size: clamp(1.5rem, 6vw, 2.2rem);
      letter-spacing: 0.03em;
      color: #fbeccb;
      text-shadow: 0 2px 0 rgba(80, 45, 15, 0.85), 0 3px 3px rgba(60, 35, 15, 0.4);
      border: 3px solid #8a5a2c;
      border-radius: 12px;
      background:
        repeating-linear-gradient(0deg, rgba(90, 55, 25, 0.10) 0 1px, transparent 1px 5px),
        repeating-linear-gradient(92deg, rgba(255, 230, 180, 0.10) 0 3px, transparent 3px 9px),
        linear-gradient(180deg, #d9a566 0%, #c18a4d 55%, #a8733a 100%);
      box-shadow: inset 0 2px 0 rgba(255, 235, 200, 0.55), inset 0 -3px 0 rgba(90, 55, 25, 0.35), 0 5px 0 #6d4620, 0 14px 18px rgba(60, 38, 26, 0.35);
      transform-origin: 50% -44px;
      animation: splashDrop 0.8s cubic-bezier(.34, 1.56, .64, 1) both, splashSway 5s ease-in-out 0.8s infinite;
    }

    .splash-sign::before {
      /* ropes */
      content: "";
      position: absolute;
      left: 16%;
      right: 16%;
      bottom: calc(100% + 1px);
      height: 44px;
      background: var(--ropes, none) center/100% 100% no-repeat;
      pointer-events: none;
    }

    .splash-paw {
      width: 28px;
      height: 28px;
      fill: #6b4423;
      filter: drop-shadow(0 1px 0 rgba(255, 230, 180, 0.55));
    }

    @keyframes splashDrop {
      0% {
        opacity: 0;
        transform: translateY(-30px) rotate(-4deg);
      }

      100% {
        opacity: 1;
        transform: translateY(0) rotate(0);
      }
    }

    @keyframes splashSway {

      0%,
      100% {
        transform: rotate(-1.4deg);
      }

      50% {
        transform: rotate(1.4deg);
      }
    }

    /* sleeping Nova on a knitted rug */
    .splash-cat {
      position: relative;
      width: min(62vw, 320px);
      animation: splashRise 0.9s 0.15s cubic-bezier(.2, 1, .3, 1) both;
    }

    .splash-cat img {
      position: relative;
      z-index: 1;
      display: block;
      width: 100%;
      height: auto;
      transform-origin: bottom center;
      filter: drop-shadow(0 8px 10px rgba(60, 38, 26, 0.3));
      animation: splashBreathe 4.2s ease-in-out infinite;
    }

    .splash-rug {
      position: absolute;
      left: 50%;
      bottom: -14px;
      width: 128%;
      height: 64px;
      transform: translateX(-50%);
      border-radius: 50%;
      background:
        var(--knit, none) 0 0/10px 12px repeat,
        radial-gradient(ellipse at 50% 40%, #b4e1bf, #8cc79b 70%);
      box-shadow: 0 0 0 4px #fff4dc, 0 0 0 6px #cdbd95, 0 10px 14px rgba(60, 38, 26, 0.3);
    }

    .splash-rug::after {
      content: "";
      position: absolute;
      inset: 8px;
      border: 2px dashed rgba(255, 248, 236, 0.85);
      border-radius: 50%;
    }

    .splash-z {
      position: absolute;
      z-index: 2;
      right: 14%;
      top: 6%;
      font-family: 'Caveat', cursive;
      font-weight: 700;
      font-size: 1.6rem;
      color: #8a7a6e;
      opacity: 0;
      animation: splashZ 3.6s ease-in-out infinite;
    }

    .splash-z.z2 {
      right: 6%;
      font-size: 2.1rem;
      animation-delay: 1.2s;
    }

    .splash-z.z3 {
      right: 20%;
      font-size: 1.3rem;
      animation-delay: 2.4s;
    }

    @keyframes splashZ {
      0% {
        opacity: 0;
        transform: translate(0, 10px) scale(0.7) rotate(-8deg);
      }

      25% {
        opacity: 0.9;
      }

      100% {
        opacity: 0;
        transform: translate(18px, -46px) scale(1.15) rotate(8deg);
      }
    }

    @keyframes splashRise {
      0% {
        opacity: 0;
        transform: translateY(18px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes splashBreathe {

      0%,
      100% {
        transform: scale(1, 1);
      }

      50% {
        transform: scale(1.012, 1.025);
      }
    }

    /* message + progress / button */
    .splash-msg {
      margin: 14px 0 0;
      min-height: 1.3em;
      font-family: 'Caveat', cursive;
      font-weight: 700;
      font-size: clamp(1.3rem, 4.5vw, 1.7rem);
      color: #6b5a45;
      text-shadow: 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    .splash-action {
      position: relative;
      width: min(280px, 72vw);
      height: 62px;
    }

    .splash-bar {
      position: absolute;
      left: 0;
      right: 0;
      top: 50%;
      height: 18px;
      transform: translateY(-50%);
      border-radius: 999px;
      background: #fbf3e1;
      box-shadow: inset 0 2px 3px rgba(80, 55, 30, 0.35), 0 0 0 3px #fff4dc, 0 0 0 4.5px #cdbd95, 0 4px 0 4.5px rgba(79, 143, 100, 0.5);
      transition: opacity 0.4s ease, transform 0.4s ease;
    }

    .splash-fill {
      position: relative;
      width: 0;
      height: 100%;
      overflow: hidden;
      border-radius: 999px;
      background: repeating-linear-gradient(60deg, rgba(255, 255, 255, 0.38) 0 1.5px, transparent 1.5px 4px), linear-gradient(180deg, #b4e1bf, #74b787);
      box-shadow: inset 0 3px 0 rgba(255, 255, 255, 0.45), inset 0 -2px 0 rgba(0, 0, 0, 0.12);
    }

    .splash-fill::after {
      /* thread knot */
      content: "";
      position: absolute;
      right: 0;
      top: 50%;
      width: 11px;
      height: 11px;
      transform: translateY(-50%);
      border-radius: 50%;
      background: radial-gradient(circle at 35% 30%, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0) 65%);
    }

    .splash.ready .splash-bar {
      opacity: 0;
      transform: translateY(-50%) scale(0.85);
      pointer-events: none;
    }

    .splash-go {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      border: 3px solid #fff4dc;
      border-radius: 999px;
      cursor: pointer;
      font-family: 'Fredoka', sans-serif;
      font-weight: 700;
      font-size: 1.15rem;
      letter-spacing: 0.03em;
      color: #fff8ec;
      text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4), 0 2px 0 #4f8f64, 0 3px 3px rgba(30, 70, 45, 0.35);
      background:
        repeating-linear-gradient(115deg, rgba(255, 255, 255, 0.14) 0 1.5px, transparent 1.5px 3.5px),
        linear-gradient(180deg, #9fd3ac, #78b98a);
      box-shadow: 0 0 0 1.5px #cdbd95, 0 5px 0 1.5px #4f8f64, 0 12px 18px rgba(60, 38, 26, 0.3);
      opacity: 0;
      transform: scale(0.8);
      pointer-events: none;
      transition: opacity 0.4s ease, transform 0.15s ease, box-shadow 0.15s ease;
    }

    .splash-go::before {
      /* running stitch */
      content: "";
      position: absolute;
      inset: 4px;
      border: 2px dashed rgba(255, 248, 236, 0.85);
      border-radius: 999px;
      pointer-events: none;
    }

    .splash-go svg {
      width: 20px;
      height: 20px;
      fill: #fff8ec;
      filter: drop-shadow(0 1px 0 rgba(47, 105, 70, 0.75));
    }

    .splash.ready .splash-go {
      opacity: 1;
      transform: scale(1);
      pointer-events: auto;
      animation: splashPop 0.6s cubic-bezier(.34, 1.56, .64, 1);
    }

    .splash.ready .splash-go:hover {
      transform: translateY(-3px);
      box-shadow: 0 0 0 1.5px #cdbd95, 0 8px 0 1.5px #4f8f64, 0 16px 20px rgba(60, 38, 26, 0.3);
    }

    .splash.ready .splash-go:active {
      transform: translateY(3px);
      box-shadow: 0 0 0 1.5px #cdbd95, 0 1px 0 1.5px #4f8f64, 0 4px 8px rgba(60, 38, 26, 0.3);
    }

    @keyframes splashPop {
      0% {
        transform: scale(0.6);
      }

      100% {
        transform: scale(1);
      }
    }

    /* short landscape screens */
    @media (max-height: 560px) {
      .splash-card {
        gap: 10px;
      }

      .splash-cat {
        width: min(34vh, 220px);
      }

      .splash-sign {
        font-size: 1.3rem;
        margin-top: 36px;
      }

      .splash-rug {
        height: 44px;
      }
    }

    @media (prefers-reduced-motion: reduce) {

      .splash *,
      .splash *::before,
      .splash *::after {
        animation: none !important;
      }

      .splash {
        transition: opacity 0.2s ease;
      }

      .splash.leaving {
        transform: none;
        filter: none;
      }
    }

    /* ================= Smooth room transitions ================= */
    .room-bg-main.is-active {
      opacity: 1;
    }

    .room-bg-main[class*="enter-"] {
      z-index: 2;
    }

    .room-bg-main.enter-next {
      animation: roomEnterNext 0.8s cubic-bezier(.22, 1, .36, 1) both;
    }

    .room-bg-main.exit-next {
      animation: roomExitNext 0.8s cubic-bezier(.22, 1, .36, 1) both;
    }

    .room-bg-main.enter-prev {
      animation: roomEnterPrev 0.8s cubic-bezier(.22, 1, .36, 1) both;
    }

    .room-bg-main.exit-prev {
      animation: roomExitPrev 0.8s cubic-bezier(.22, 1, .36, 1) both;
    }

    /* the entering room fades in on top while the old one holds, so there is no dark dip */
    @keyframes roomEnterNext {
      0% {
        opacity: 0;
        transform: translate3d(7%, 0, 0) scale(1.07);
      }

      55% {
        opacity: 1;
      }

      100% {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
      }
    }

    @keyframes roomExitNext {
      0% {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
      }

      70% {
        opacity: 1;
      }

      100% {
        opacity: 0;
        transform: translate3d(-7%, 0, 0) scale(0.96);
      }
    }

    @keyframes roomEnterPrev {
      0% {
        opacity: 0;
        transform: translate3d(-7%, 0, 0) scale(1.07);
      }

      55% {
        opacity: 1;
      }

      100% {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
      }
    }

    @keyframes roomExitPrev {
      0% {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
      }

      70% {
        opacity: 1;
      }

      100% {
        opacity: 0;
        transform: translate3d(7%, 0, 0) scale(0.96);
      }
    }

    /* warm light sweep across the screen */
    .room-veil {
      position: absolute;
      inset: 0;
      z-index: 9;
      pointer-events: none;
      opacity: 0;
      background: linear-gradient(90deg, transparent 0%, rgba(255, 226, 170, 0) 25%, rgba(255, 238, 206, 0.6) 50%, rgba(255, 226, 170, 0) 75%, transparent 100%);
      will-change: transform, opacity;
    }

    .room-veil.sweep-next {
      animation: veilNext 0.8s ease-in-out;
    }

    .room-veil.sweep-prev {
      animation: veilPrev 0.8s ease-in-out;
    }

    @keyframes veilNext {
      0% {
        opacity: 0;
        transform: translate3d(60%, 0, 0);
      }

      35% {
        opacity: 1;
      }

      100% {
        opacity: 0;
        transform: translate3d(-60%, 0, 0);
      }
    }

    @keyframes veilPrev {
      0% {
        opacity: 0;
        transform: translate3d(-60%, 0, 0);
      }

      35% {
        opacity: 1;
      }

      100% {
        opacity: 0;
        transform: translate3d(60%, 0, 0);
      }
    }

    /* cat: no sliding, just fade out / hop back in */
    .cat-stage,
    .cat-stage.moving {
      transition: opacity 0.3s ease;
      will-change: opacity;
    }

    .cat-stage.cat-away {
      opacity: 0;
    }

    /* room name pops in */
    .room-pill.pop {
      animation: pillPop 0.55s cubic-bezier(.34, 1.56, .64, 1);
    }

    @keyframes pillPop {
      0% {
        opacity: 0;
        transform: translateY(-10px) scale(0.85);
      }

      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @media (prefers-reduced-motion: reduce) {

      .room-bg-main[class*="enter-"],
      .room-bg-main[class*="exit-"] {
        animation-duration: 0.01s;
      }

      .room-veil {
        display: none;
      }
    }

    /* make the header's stitched border click-through and lift the close button above it */
    .chat-head::before,
    .chat-head::after {
      pointer-events: none;
    }

    .chat-close {
      position: relative;
      z-index: 3;
    }

    /* ---- Nova stays glued to her spot ---- */
    .cat-stage,
    .cat-stage.moving {
      transition: none !important;
    }

    .cat-stage:not(.placed) {
      visibility: hidden;
    }

    /* ---- Splash: motivational note (legal pad) ---- */
    .splash-note {
      position: relative;
      width: min(340px, 86vw);
      margin-top: 18px;
      padding: 18px 18px 12px;
      text-align: center;
      background: linear-gradient(180deg, #fffbe3, #fff3c4);
      border: 2.5px solid #fff4dc;
      border-radius: 14px;
      box-shadow: 0 0 0 1.5px #cdbd95, 0 5px 0 1.5px rgba(79, 143, 100, 0.5), 0 14px 20px rgba(60, 38, 26, 0.25);
      transform: rotate(-1.2deg);
      animation: splashNoteIn 0.8s 0.55s cubic-bezier(.34, 1.56, .64, 1) both;
    }

    .splash-note::after {
      /* running stitch inside the note */
      content: "";
      position: absolute;
      inset: 5px;
      border: 1.5px dashed rgba(110, 150, 115, 0.5);
      border-radius: 10px;
      pointer-events: none;
    }

    .splash-note-pin {
      position: absolute;
      top: -11px;
      left: 50%;
      width: 22px;
      height: 22px;
      margin-left: -11px;
      border-radius: 50%;
      border: 2px solid #fff4dc;
      background: radial-gradient(circle at 35% 30%, #b4e1bf, #74b787 60%, #4f8f64);
      box-shadow: 0 2px 0 #3d7550, 0 4px 6px rgba(60, 38, 26, 0.35);
    }

    .splash-note-label {
      display: block;
      margin-bottom: 6px;
      font-family: 'Nunito', sans-serif;
      font-weight: 800;
      font-size: 0.64rem;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: #8a7a6e;
    }

    .splash-note-text {
      /* ruled lines are drawn by the paragraph itself so they line up with the text */
      margin: 0;
      padding: 0 4px;
      min-height: 52px;
      font-family: 'Caveat', cursive;
      font-weight: 700;
      font-size: 1.3rem;
      line-height: 26px;
      color: #4a3b2e;
      background: repeating-linear-gradient(180deg, transparent 0 25px, rgba(120, 150, 125, 0.3) 25px 26px);
    }

    .splash-note-by {
      display: block;
      margin-top: 6px;
      text-align: right;
      font-family: 'Caveat', cursive;
      font-weight: 700;
      font-size: 1.1rem;
      color: #6b5a45;
    }

    @keyframes splashNoteIn {
      0% {
        opacity: 0;
        transform: translateY(16px) rotate(-6deg) scale(0.9);
      }

      100% {
        opacity: 1;
        transform: translateY(0) rotate(-1.2deg) scale(1);
      }
    }

    /* small phones: make room for the note */
    @media (max-height: 700px) {
      .splash-cat {
        width: min(48vw, 240px);
      }

      .splash-note {
        margin-top: 12px;
      }
    }

    @media (max-height: 560px) {
      .splash-note {
        margin-top: 6px;
        padding: 12px 14px 8px;
      }

      .splash-note-label {
        display: none;
      }

      .splash-note-text {
        font-size: 1.1rem;
        min-height: 26px;
      }
    }

    /* ---- Splash: side notes + never-cut-off button ---- */
    .splash {
      overflow-y: auto;
      /* if the screen is really short, you can scroll to the button */
      overflow-x: hidden;
      align-items: flex-start;
    }

    .splash-card {
      margin: auto;
      /* centers when there's room, scrolls safely when there isn't */
      padding: 8px 0 20px;
    }

    .splash-note {
      position: absolute;
      z-index: 2;
      top: 50%;
      margin: -100px 0 0;
      /* vertical centering without touching transform */
      width: clamp(190px, 19vw, 280px);
      padding: 20px 16px 14px;
      transform: rotate(var(--tilt, -4deg));
      animation: noteIn 0.9s cubic-bezier(.34, 1.56, .64, 1) both,
        noteFloat 6s ease-in-out 1.2s infinite;
    }

    .note-left {
      left: clamp(12px, 3.5vw, 70px);
      --tilt: -6deg;
      animation-delay: 0.5s, 1.4s;
    }

    .note-right {
      right: clamp(12px, 3.5vw, 70px);
      --tilt: 5deg;
      margin-top: -60px;
      /* sits a little lower than the left one, so it feels scattered */
      animation-delay: 0.75s, 2.2s;
      background: linear-gradient(180deg, #fffbe3, #fff3c4);
      /* pink paper for variety */
    }

    .note-right .splash-note-pin {
      background: radial-gradient(circle at 35% 30%, #ffc9b8, #f0775a 60%, #c9553a);
      box-shadow: 0 2px 0 #a8452e, 0 4px 6px rgba(60, 38, 26, 0.35);
    }

    .splash-note-text {
      font-size: clamp(1.15rem, 1.5vw, 1.45rem);
      min-height: 78px;
    }

    @keyframes noteIn {
      0% {
        opacity: 0;
        transform: translateY(30px) rotate(calc(var(--tilt) * 2.5)) scale(0.85);
      }

      100% {
        opacity: 1;
        transform: translateY(0) rotate(var(--tilt)) scale(1);
      }
    }

    @keyframes noteFloat {

      0%,
      100% {
        transform: translateY(0) rotate(var(--tilt));
      }

      50% {
        transform: translateY(-8px) rotate(calc(var(--tilt) + 1.5deg));
      }
    }

    /* tablets / small laptops: slightly smaller cards */
    @media (max-width: 1100px) {
      .splash-note {
        width: 170px;
      }

      .splash-note-text {
        font-size: 1.1rem;
        min-height: 70px;
      }
    }

    /* ---- Mobile: notes sit in the empty gap under the sign, no overlap ---- */
    @media (max-width: 760px) {
      .splash {
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: calc(var(--rope) + 80px) 16px 24px;
      }

      /* the card keeps sign-less content: cat, message, button */
      .splash-card {
        margin: 0 auto;
        padding: 0 0 12px;
        gap: 14px;
      }

      /* notes become normal flow items, placed ABOVE the cat */
      .splash-note,
      .note-left,
      .note-right {
        position: relative;
        order: -1;
        top: auto;
        bottom: auto;
        left: auto;
        right: auto;
        margin: 18px 0 0;
        width: min(300px, 82vw);
        padding: 18px 14px 10px;
        z-index: 3;
      }

      .note-left {
        --tilt: -3deg;
      }

      /* show only one note on phones */
      .note-right {
        display: none;
      }

      .note-left .splash-note-label {
        display: block;
        font-size: 0.58rem;
        margin-bottom: 4px;
      }

      .note-left .splash-note-text {
        font-size: 1.15rem;
        line-height: 24px;
        min-height: 48px;
        background-size: 100% 24px;
      }

      .note-left .splash-note-by {
        font-size: 1rem;
      }

      .splash-cat {
        width: min(58vw, 240px);
      }
    }

    /* very short phones: shrink things so the button always fits */
    @media (max-width: 760px) and (max-height: 700px) {
      .splash {
        padding-top: calc(var(--rope) + 64px);
      }

      .splash-note {
        margin-top: 10px;
      }

      .splash-note-text {
        font-size: 1.05rem;
        min-height: 0;
      }

      .splash-cat {
        width: min(44vw, 190px);
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .splash-note {
        animation: none !important;
        opacity: 1;
      }
    }

    @media (max-height: 800px) {
      .splash-cat {
        width: min(40vh, 260px);
      }

      .splash-card {
        gap: 12px;
      }
    }

    /* ---- Splash: sign hanging from the top, swinging ---- */
    .splash {
      --rope: clamp(46px, 9vh, 90px);
      /* rope length */
      padding-top: calc(var(--rope) + 96px);
      /* keeps the cat clear of the sign */
    }

    .splash-hanger {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      z-index: 4;
      display: flex;
      justify-content: center;
      pointer-events: none;
    }

    .splash-sign {
      margin-top: var(--rope);
      transform-origin: 50% calc(var(--rope) * -1);
      /* pivot = where the ropes meet at the top */
      animation:
        splashHangIn 1s cubic-bezier(.2, .9, .3, 1) both,
        splashSwing 4.6s ease-in-out 1s infinite;
    }

    /* long ropes up to the top edge, meeting at one point */
    .splash-sign::before {
      left: 14%;
      right: 14%;
      height: var(--rope);
    }

    /* little nail where the ropes meet */
    .splash-sign::after {
      content: "";
      position: absolute;
      left: 50%;
      bottom: calc(100% + var(--rope) - 7px);
      width: 14px;
      height: 14px;
      margin-left: -7px;
      border-radius: 50%;
      border: 2px solid #fff4dc;
      background: radial-gradient(circle at 35% 30%, #e2c28a, #8a5a2c);
      box-shadow: 0 2px 0 #6d4620, 0 3px 5px rgba(60, 38, 26, 0.35);
    }

    /* swings in from the side, then keeps swaying */
    @keyframes splashHangIn {
      0% {
        opacity: 0;
        transform: rotate(-16deg);
      }

      100% {
        opacity: 1;
        transform: rotate(0deg);
      }
    }

    @keyframes splashSwing {
      0% {
        transform: rotate(0deg);
      }

      25% {
        transform: rotate(4.5deg);
      }

      75% {
        transform: rotate(-4.5deg);
      }

      100% {
        transform: rotate(0deg);
      }
    }

    @media (max-height: 560px) {
      .splash {
        --rope: 36px;
        padding-top: calc(var(--rope) + 70px);
      }

      .splash-sign {
        margin-top: var(--rope);
        font-size: 1.2rem;
      }
    }

    /* ---- Splash: cat sits ON the rug ---- */
    .splash-cat {
      --rug-lift: 14%;
      /* raise this if she still floats, lower it if she sinks into the rug */
    }

    .splash-rug {
      bottom: var(--rug-lift);
      z-index: 0;
    }

    /* soft contact shadow where she touches the rug */
    .splash-cat::after {
      content: "";
      position: absolute;
      left: 50%;
      bottom: calc(var(--rug-lift) + 18px);
      width: 78%;
      height: 26px;
      transform: translateX(-50%);
      border-radius: 50%;
      background: radial-gradient(ellipse at center, rgba(40, 60, 45, 0.45), transparent 70%);
      filter: blur(2px);
      z-index: 0;
      pointer-events: none;
    }

    /* the cat image stays above the rug and the shadow */
    .splash-cat img {
      z-index: 1;
    }

    /* ================= 4 notes ================= */
    .splash-notes {
      position: absolute;
      inset: 0;
      z-index: 2;
      pointer-events: none;
    }

    .splash-notes .splash-note {
      position: absolute;
      margin: 0;
      width: clamp(170px, 17vw, 260px);
      animation: noteIn 0.9s cubic-bezier(.34, 1.56, .64, 1) both,
        noteFloat 6s ease-in-out 1.4s infinite;
    }

    /* left column */
    .splash-notes .n1 {
      left: clamp(12px, 3vw, 60px);
      top: 12%;
      --tilt: -6deg;
      animation-delay: .4s, 1.3s;
    }

    .splash-notes .n2 {
      left: clamp(12px, 3vw, 60px);
      top: 54%;
      --tilt: 4deg;
      animation-delay: .6s, 2.1s;
    }

    /* right column */
    .splash-notes .n3 {
      right: clamp(12px, 3vw, 60px);
      top: 18%;
      --tilt: 5deg;
      animation-delay: .8s, 1.7s;
    }

    .splash-notes .n4 {
      right: clamp(12px, 3vw, 60px);
      top: 58%;
      --tilt: -4deg;
      animation-delay: 1s, 2.5s;
    }

    /* different paper + pin color for each card */
    .splash-notes .n1 {
      background: linear-gradient(180deg, #fffbe3, #fff3c4);
    }

    .splash-notes .n2 {
      background: linear-gradient(180deg, #ffeef0, #ffdfe4);
    }

    .splash-notes .n3 {
      background: linear-gradient(180deg, #eaf8ee, #d3efdb);
    }

    .splash-notes .n4 {
      background: linear-gradient(180deg, #eef4fd, #d9e6f8);
    }

    .splash-notes .n1 .splash-note-pin {
      background: radial-gradient(circle at 35% 30%, #b4e1bf, #74b787 60%, #4f8f64);
      box-shadow: 0 2px 0 #3d7550, 0 4px 6px rgba(60, 38, 26, .35);
    }

    .splash-notes .n2 .splash-note-pin {
      background: radial-gradient(circle at 35% 30%, #ffc9b8, #f0775a 60%, #c9553a);
      box-shadow: 0 2px 0 #a8452e, 0 4px 6px rgba(60, 38, 26, .35);
    }

    .splash-notes .n3 .splash-note-pin {
      background: radial-gradient(circle at 35% 30%, #ffe9a8, #ffbd59 60%, #e2963a);
      box-shadow: 0 2px 0 #b9782a, 0 4px 6px rgba(60, 38, 26, .35);
    }

    .splash-notes .n4 .splash-note-pin {
      background: radial-gradient(circle at 35% 30%, #c6d8f5, #8fa3d6 60%, #5b71ab);
      box-shadow: 0 2px 0 #44588a, 0 4px 6px rgba(60, 38, 26, .35);
    }

    .splash-notes .splash-note-text {
      font-size: clamp(1.05rem, 1.3vw, 1.35rem);
      min-height: 0;
    }

    /* phones: 4 notes in a swipeable row above the cat */
    @media (max-width: 760px) {
      .splash-notes {
        position: relative;
        inset: auto;
        order: -1;
        display: flex;
        gap: 14px;
        width: min(100%, 380px);
        padding: 24px 4px 16px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        pointer-events: auto;
      }

      .splash-notes::-webkit-scrollbar {
        display: none;
      }

      .splash-notes .splash-note {
        position: relative;
        top: auto;
        left: auto;
        right: auto;
        flex: 0 0 76%;
        /* the next card peeks out, so it's clear you can swipe */
        width: auto;
        scroll-snap-align: center;
        padding: 18px 14px 10px;
        animation: noteIn 0.9s cubic-bezier(.34, 1.56, .64, 1) both;
      }

      .splash-notes .n1 {
        --tilt: -2deg;
      }

      .splash-notes .n2 {
        --tilt: 2deg;
      }

      .splash-notes .n3 {
        --tilt: -2deg;
      }

      .splash-notes .n4 {
        --tilt: 2deg;
      }

      .splash-notes .splash-note-label {
        font-size: 0.58rem;
      }

      .splash-notes .splash-note-text {
        font-size: 1.1rem;
        line-height: 24px;
        background-size: 100% 24px;
      }
    }

    @media (max-width: 760px) and (max-height: 700px) {
      .splash-notes {
        padding: 20px 4px 10px;
      }

      .splash-notes .splash-note-text {
        font-size: 1rem;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .splash-notes .splash-note {
        animation: none !important;
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <div class="splash" id="splash" role="dialog" aria-modal="true" aria-label="Welcome to Nova's House">
    <div class="splash-lights" aria-hidden="true"></div>
    <div class="splash-motes" aria-hidden="true"></div>

    <div class="splash-hanger">
      <div class="splash-sign">
        <svg class="splash-paw" viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="6.5" cy="7.5" r="2.1" />
          <circle cx="11.5" cy="5.3" r="2.1" />
          <circle cx="16.5" cy="7.5" r="2.1" />
          <circle cx="19.3" cy="12.2" r="1.9" />
          <path d="M12 20.2c-3.4 0-6-1.8-6-4.4 0-2.3 2.1-4 6-4s6 1.7 6 4c0 2.6-2.6 4.4-6 4.4Z" />
        </svg>
        <span>Nova's House</span>
      </div>
    </div>

    <div class="splash-card">
      <div class="splash-cat">
        <span class="splash-rug" aria-hidden="true"></span>
        <span class="splash-z" aria-hidden="true">z</span>
        <span class="splash-z z2" aria-hidden="true">Z</span>
        <span class="splash-z z3" aria-hidden="true">z</span>
        <img src="/Assets/Sleeping.png" alt="Nova is sleeping">
      </div>

      <!-- these two MUST stay inside .splash-card -->
      <p class="splash-msg" id="splashMsg">Warming up the house…</p>

      <div class="splash-action">
        <div class="splash-bar">
          <div class="splash-fill" id="splashFill"></div>
        </div>
        <button class="splash-go" id="splashGo" type="button" disabled>
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="6.5" cy="7.5" r="2.1" />
            <circle cx="11.5" cy="5.3" r="2.1" />
            <circle cx="16.5" cy="7.5" r="2.1" />
            <circle cx="19.3" cy="12.2" r="1.9" />
            <path d="M12 20.2c-3.4 0-6-1.8-6-4.4 0-2.3 2.1-4 6-4s6 1.7 6 4c0 2.6-2.6 4.4-6 4.4Z" />
          </svg>
          Wake Nova up
        </button>
      </div>
    </div><!-- end .splash-card -->

    <!-- <aside class="splash-note note-left" aria-hidden="true">
      <span class="splash-note-pin"></span>
      <small class="splash-note-label">⚖️ A note for Anghela</small>
      <p class="splash-note-text" id="splashNoteTextL"></p>
      <span class="splash-note-by">— Diopet</span>
    </aside>

    <aside class="splash-note note-right" aria-hidden="true">
      <span class="splash-note-pin"></span>
      <small class="splash-note-label">⚖️ Also, Counsel…</small>
      <p class="splash-note-text" id="splashNoteTextR"></p>
      <span class="splash-note-by">— Diopet</span>
    </aside> -->

    <div class="splash-notes" aria-hidden="true">
      <aside class="splash-note n1">
        <span class="splash-note-pin"></span>
        <small class="splash-note-label">⚖️ A note for Anghela</small>
        <p class="splash-note-text"></p>
        <span class="splash-note-by">— Diopet</span>
      </aside>

      <aside class="splash-note n2">
        <span class="splash-note-pin"></span>
        <small class="splash-note-label">⚖️ Also, for Anghela</small>
        <p class="splash-note-text"></p>
        <span class="splash-note-by">— Diopet</span>
      </aside>

      <aside class="splash-note n3">
        <span class="splash-note-pin"></span>
        <small class="splash-note-label">⚖️ Take it slow</small>
        <p class="splash-note-text"></p>
        <span class="splash-note-by">— Diopet</span>
      </aside>

      <aside class="splash-note n4">
        <span class="splash-note-pin"></span>
        <small class="splash-note-label">⚖️ Don't forget</small>
        <p class="splash-note-text"></p>
        <span class="splash-note-by">— Diopet</span>
      </aside>
    </div>


  </div><!-- end #splash -->

  <div id="app">
    <div class="stage room-living" id="stage">
      <div class="room-bg-layer">
        <img class="room-bg-side side-left" id="sideLeft" src="/Assets/AnghelaNova.jfif" alt="" aria-hidden="true">
        <img class="room-bg-side side-right" id="sideRight" src="/Assets/AnghelaNova2.jfif" alt="" aria-hidden="true">
        <img class="room-bg-main is-active" data-room="living" src="/Assets/LivingRoom.jfif" alt="Living room" decoding="async">
        <img class="room-bg-main" data-room="kitchen" src="/Assets/Kitchen.jfif" alt="Kitchen" decoding="async">
        <img class="room-bg-main" data-room="bedroom" src="/Assets/Bedroom.jfif" alt="Bedroom" decoding="async">
      </div>
      <div class="stage-ambience" aria-hidden="true"></div>
      <div class="room-veil" id="roomVeil" aria-hidden="true"></div>
      <div class="stage-ambience" aria-hidden="true"></div>
      <div class="motes" id="motes" aria-hidden="true"></div>
      <button class="room-door prev" id="prevRoom" aria-label="Previous room">
        <span class="door-ring"></span>
        <span class="door-core"><span class="door-face-icon" id="prevIcon"></span></span>
        <span class="door-chevron door-chevron-prev"><svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 6l-6 6 6 6" />
          </svg></span>
        <span class="door-tag" id="prevLabel">Bedroom</span>
      </button>
      <button class="room-door next" id="nextRoom" aria-label="Next room">
        <span class="door-ring"></span>
        <span class="door-core"><span class="door-face-icon" id="nextIcon"></span></span>
        <span class="door-chevron door-chevron-next"><svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 6l6 6-6 6" />
          </svg></span>
        <span class="door-tag" id="nextLabel">Kitchen</span>
      </button>
      <div class="room-rail glass" id="roomRail"></div>
      <div class="cat-stage">
        <div class="mood-halo"></div>
        <div class="cat-wrap mood-neutral" id="catWrap">
          <div class="cat-shadow"></div>
          <img class="cat-pose-img cat-img" src="/Assets/Character.png" alt="Nova the cat">
          <img class="cat-pose-img cat-img-sleep" src="/Assets/Sleeping.png" alt="Nova sleeping">
          <img class="cat-pose-img cat-img-hungry" src="/Assets/Hungry.png" alt="Nova is hungry">
          <img class="cat-pose-img cat-img-eating" src="/Assets/Eating.png" alt="Nova eating">
          <img class="cat-pose-img cat-pet-frame" id="petFrame1" src="/Assets/Pet.png" alt="Nova noticing pets">
          <img class="cat-pose-img cat-pet-frame" id="petFrame2" src="/Assets/pet1.png" alt="Nova enjoying pets">
          <img class="cat-pose-img cat-pet-frame" id="petFrame3" src="/Assets/Pet3.png" alt="Nova rolling over happily">
          <img class="cat-pose-img cat-play-frame" id="playFrame1" src="/Assets/Playing1.png" alt="Nova playing">
          <img class="cat-pose-img cat-play-frame" id="playFrame2" src="/Assets/Playing2.png" alt="Nova playing">
          <div class="bubble" id="bubble"></div>
        </div>
        <div class="particles" id="particles"></div>
      </div>
    </div>
    <div class="topbar">
      <div class="title-pill glass"><span class="paw"><svg viewBox="0 0 24 24">
            <circle cx="6.5" cy="7.5" r="2.1" />
            <circle cx="11.5" cy="5.3" r="2.1" />
            <circle cx="16.5" cy="7.5" r="2.1" />
            <circle cx="19.3" cy="12.2" r="1.9" />
            <path d="M12 20.2c-3.4 0-6-1.8-6-4.4 0-2.3 2.1-4 6-4s6 1.7 6 4c0 2.6-2.6 4.4-6 4.4Z" />
          </svg></span> Nova's House</div>
      <div class="room-pill glass" id="roomName">Living Room</div>
      <div class="stats-pill glass">
        <div class="stat stat-hunger">
          <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 11h18" />
              <path d="M4 11c0 4.4 3.6 8 8 8s8-3.6 8-8" />
              <path d="M8 11V9a4 4 0 0 1 8 0v2" />
            </svg></span>
          <div class="stat-track">
            <div class="stat-fill fill-hunger" id="hungerFill" style="width:80%"></div>
          </div>
          <span class="stat-num" id="hungerVal">80</span>
        </div>
        <div class="stat stat-happy">
          <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 20s-7-4.3-9.3-8.6C1.1 8.6 2.6 5 6 5c2 0 3.4 1.1 4 2.4C10.6 6.1 12 5 14 5c3.4 0 4.9 3.6 3.3 6.4C19 15.7 12 20 12 20Z" />
            </svg></span>
          <div class="stat-track">
            <div class="stat-fill fill-happy" id="happyFill" style="width:80%"></div>
          </div>
          <span class="stat-num" id="happyVal">80</span>
        </div>
        <div class="stat stat-energy">
          <span class="stat-icon"><svg viewBox="0 0 24 24">
              <path d="M13 2 4 14h6l-1 8 9-12h-6l1-8Z" />
            </svg></span>
          <div class="stat-track">
            <div class="stat-fill fill-energy" id="energyFill" style="width:80%"></div>
          </div>
          <span class="stat-num" id="energyVal">80</span>
        </div>
      </div>
    </div>
    <div class="dock glass" id="dock"></div>
    <button class="chat-fab" id="chatToggle" aria-label="Talk to Nova">
      <img src="/Assets/ForMessageBubble.png" alt="">
      <span class="fab-badge" id="fabBadge" style="display:none;"></span>
    </button>
  </div>
  <div class="chat-scrim" id="chatScrim"></div>
  <div class="chat-widget" id="chatPanel" role="dialog" aria-label="Chat with Nova">
    <div class="chat-head">
      <span class="chat-head-avatar"><img src="/Assets/MessageBubble.png" alt=""></span>
      <div class="chat-head-text">
        <div class="chat-head-name">Nova</div>
        <div class="chat-head-status">Online</div>
      </div>
      <button class="chat-close" id="chatClose" aria-label="Close chat"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round">
          <path d="M6 6l12 12M18 6 6 18" />
        </svg></button>
    </div>
    <div class="chat-log" id="chatLog"></div>
    <div class="chat-quick-row" id="chatQuickRow">
      <button class="chat-quick-btn" data-quick="How are you?">How are you?</button>
      <button class="chat-quick-btn" data-quick="Are you hungry?">Are you hungry?</button>
      <button class="chat-quick-btn" data-quick="Wanna play?">Wanna play?</button>
      <button class="chat-quick-btn" data-quick="I love you!">I love you!</button>
    </div>
    <div class="chat-input-row">
      <input type="text" id="chatInput" placeholder="Say something to Nova&hellip;" maxlength="120">
      <button id="chatSend" aria-label="Send"><svg viewBox="0 0 24 24">
          <path d="M3 11l17-8-8 17-2-7-7-2Z" />
        </svg></button>
    </div>
  </div>


  <script>
    (function() {
      /* =====================================================
         State & constants
         ===================================================== */
      const HUNGER_THRESHOLD = 30;
      const rooms = ['living', 'kitchen', 'bedroom'];
      const roomLabels = {
        living: 'Living Room',
        kitchen: 'Kitchen',
        bedroom: 'Bedroom'
      };
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
      const ROOM_BG = {
        living: '/Assets/LivingRoom.jfif',
        kitchen: '/Assets/Kitchen.jfif',
        bedroom: '/Assets/Bedroom.jfif',
      };
      const ROOM_ANCHOR = {
        living: {
          x: 60,
          y: 87,
          widthFrac: 0.135
        },
        kitchen: {
          x: 36,
          y: 55,
          widthFrac: 0.135
        },
        bedroom: {
          x: 53,
          y: 63,
          widthFrac: 0.14
        },
      };
      const CAT_BASE_WIDTH = 170;


      const REF_SELECTOR = '.cat-img'; // Character.png defines the fixed frame
      const POSE_SCALE = 368 / 1156; // how the standing pose is displayed

      const POSE_ADJUST = {
        '.cat-img': {
          s: 1,
          bw: 1,
          bh: 1,
          x: 0,
          y: 0
        },
        '.cat-img-sleep': {
          s: 1,
          bw: 1.7,
          bh: 1,
          x: 0,
          y: 0
        },
        '.cat-img-hungry': {
          s: 1,
          bw: 1,
          bh: 1,
          x: 0,
          y: 0
        },
        '.cat-img-eating': {
          s: 1,
          bw: 1,
          bh: 1,
          x: 0,
          y: 0
        },
        '#petFrame1': {
          s: 1,
          bw: 1,
          bh: 1,
          x: 0,
          y: 0
        },
        '#petFrame2': {
          s: 1,
          bw: 1,
          bh: 1,
          x: 0,
          y: 0
        },
        '#petFrame3': {
          s: 1,
          bw: 1.2,
          bh: 1,
          x: 0,
          y: 0
        },
        '#playFrame1': {
          s: 1,
          bw: 1,
          bh: 1,
          x: 0,
          y: 0
        },
        '#playFrame2': {
          s: 1,
          bw: 1,
          bh: 1,
          x: 0,
          y: 0
        }
      };
      const poseImgs = Array.from(document.querySelectorAll('.cat-pose-img'));
      const poseBoxCache = new Map();

      function getPoseAdj(img) {
        for (const sel in POSE_ADJUST) {
          if (img.matches(sel)) return POSE_ADJUST[sel];
        }
        return {
          s: 1,
          bw: 1,
          bh: 1,
          x: 0,
          y: 0
        };
      }

      function measurePose(img) {
        if (poseBoxCache.has(img)) return poseBoxCache.get(img);
        const W = img.naturalWidth,
          H = img.naturalHeight;
        let box = {
          x: 0,
          y: 0,
          w: W,
          h: H
        };
        try {
          const sc = Math.min(1, 160 / Math.max(W, H));
          const cw = Math.max(1, Math.round(W * sc)),
            ch = Math.max(1, Math.round(H * sc));
          const c = document.createElement('canvas');
          c.width = cw;
          c.height = ch;
          const ctx = c.getContext('2d', {
            willReadFrequently: true
          });
          ctx.drawImage(img, 0, 0, cw, ch);
          const d = ctx.getImageData(0, 0, cw, ch).data;
          let minX = cw,
            minY = ch,
            maxX = -1,
            maxY = -1;
          for (let y = 0; y < ch; y++) {
            for (let x = 0; x < cw; x++) {
              if (d[(y * cw + x) * 4 + 3] > 20) {
                if (x < minX) minX = x;
                if (x > maxX) maxX = x;
                if (y < minY) minY = y;
                if (y > maxY) maxY = y;
              }
            }
          }
          if (maxX >= 0) {
            box = {
              x: minX / sc,
              y: minY / sc,
              w: (maxX - minX + 1) / sc,
              h: (maxY - minY + 1) / sc
            };
          }
        } catch (e) {
          /* canvas blocked (e.g. file://): fall back to the full image box */
        }
        poseBoxCache.set(img, box);
        return box;
      }

      function layoutPoses() {
        const ready = poseImgs.filter(i => i.naturalWidth);
        const ref = ready.find(i => i.matches(REF_SELECTOR));
        if (!ref) return;

        // the fixed frame = the standing cat's visible footprint
        const refBox = measurePose(ref);
        const frameW = refBox.w * POSE_SCALE;
        const frameH = refBox.h * POSE_SCALE;
        // where the standing cat's feet and centre sit (so Nova stays in her current spot)
        const floor = (ref.naturalHeight - (refBox.y + refBox.h)) * POSE_SCALE;
        const centreX = (refBox.x + refBox.w / 2) * POSE_SCALE - (ref.naturalWidth * POSE_SCALE) / 2;

        ready.forEach(img => {
          const adj = getPoseAdj(img);
          const W = img.naturalWidth,
            H = img.naturalHeight;
          const box = measurePose(img);

          // one rule for every pose: fit the visible cat inside the same frame
          const k = Math.min((frameW * adj.bw) / box.w, (frameH * adj.bh) / box.h) * adj.s;

          img.style.width = Math.round(W * k) + 'px';
          img.style.height = Math.round(H * k) + 'px';
          img.style.left = '50%';
          img.style.right = 'auto';
          img.style.margin = '0';

          const padBottom = (H - (box.y + box.h)) * k; // empty space under the paws
          const catCentre = (box.x + box.w / 2) * k; // cat's centre inside its canvas
          img.style.bottom = Math.round(floor - padBottom + adj.y) + 'px';
          img.style.marginLeft = Math.round(centreX - catCentre + adj.x) + 'px';
        });
      }
      poseImgs.forEach(img => img.addEventListener('load', layoutPoses));
      layoutPoses();

      /* =====================================================
         Element references
         ===================================================== */
      const els = {
        stage: document.getElementById('stage'),
        sideLeft: document.getElementById('sideLeft'),
        sideRight: document.getElementById('sideRight'),
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

      /* =====================================================
         Room images (all three live in the DOM)
         ===================================================== */
      const roomImgs = {};
      document.querySelectorAll('.room-bg-main').forEach(img => {
        roomImgs[img.dataset.room] = img;
      });
      if (Object.keys(roomImgs).length < rooms.length) {
        console.error('Nova: expected one <img class="room-bg-main" data-room="…"> per room', roomImgs);
      }
      const roomVeil = document.getElementById('roomVeil');
      let transitioning = false;
      const TRANSITION_MS = 800;

      /* pre-decode every room so the first switch never hitches */
      Object.values(roomImgs).forEach(img => {
        if (img.decode) img.decode().catch(() => {});
        img.addEventListener('load', () => {
          if (img.classList.contains('is-active')) relayout();
        });
      });

      /* =====================================================
         Icons & actions
         ===================================================== */
      const ICON = {
        play: '<svg viewBox="0 0 24 24" class="filled"><path d="M12 2c.6 3.7 2.3 5.9 6 6.5-3.7.6-5.4 2.8-6 6.5-.6-3.7-2.3-5.9-6-6.5 3.7-.6 5.4-2.8 6-6.5Z"/><path d="M19 15c.3 1.8 1.1 2.9 3 3.2-1.9.3-2.7 1.4-3 3.2-.3-1.8-1.1-2.9-3-3.2 1.9-.3 2.7-1.4 3-3.2Z"/></svg>',
        pet: '<svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 13V6a1.4 1.4 0 0 1 2.8 0v6"/><path d="M10.8 12V4.6a1.4 1.4 0 0 1 2.8 0V12"/><path d="M13.6 12V5.6a1.4 1.4 0 0 1 2.8 0V13"/><path d="M16.4 13V8.4a1.4 1.4 0 0 1 2.8 0V15c0 3.6-2.5 6.5-6.5 6.5-2.8 0-4.3-1-5.6-2.8l-2.7-4.1a1.3 1.3 0 0 1 2-1.6L8 13"/></svg>',
        feed: '<svg viewBox="0 0 24 24" class="filled"><path d="M15.4 15.4 6 20.8l-.8-.8 5.4-9.4a4.6 4.6 0 0 1-1-2.9 5 5 0 1 1 8.6 3.5 4.6 4.6 0 0 1-2.8 1Z"/></svg>',
        treat: '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11Z"/></svg>',
        nap: '<svg viewBox="0 0 24 24" class="filled"><path d="M20 14.5A8.5 8.5 0 0 1 9.5 4 8.5 8.5 0 1 0 20 14.5Z"/></svg>',
      };
      const actionsByRoom = {
        living: [{
            icon: ICON.play,
            label: 'Play',
            run: playWithCat,
            fill: '--sage',
            deep: '--sage-deep'
          },
          {
            icon: ICON.pet,
            label: 'Pet',
            run: petCat,
            fill: '--blush',
            deep: '--blush-deep'
          },
        ],
        kitchen: [{
            icon: ICON.feed,
            label: 'Feed',
            run: feedCat,
            fill: '--gold',
            deep: '--gold-deep'
          },
          {
            icon: ICON.treat,
            label: 'Treat',
            run: giveTreat,
            fill: '--coral',
            deep: '--coral-deep'
          },
        ],
        bedroom: [{
          icon: ICON.nap,
          label: 'Nap',
          run: toggleSleep,
          fill: '--dusk',
          deep: '--dusk-deep'
        }, ],
      };

      /* =====================================================
         Room UI (labels, icons, dock, rail)
         ===================================================== */
      function updateRoomUI() {
        const room = rooms[roomIndex];
        els.roomName.textContent = roomLabels[room];
        els.roomName.classList.remove('pop');
        void els.roomName.offsetWidth;
        els.roomName.classList.add('pop');
        const prevRoom = rooms[(roomIndex - 1 + rooms.length) % rooms.length];
        const nextRoom = rooms[(roomIndex + 1) % rooms.length];
        els.prevLabel.textContent = roomLabels[prevRoom];
        els.nextLabel.textContent = roomLabels[nextRoom];
        els.prevIcon.innerHTML = ROOM_ICON[prevRoom];
        els.nextIcon.innerHTML = ROOM_ICON[nextRoom];
        renderDock();
        renderRail();
      }

      /* initial render (no animation) */
      function renderRoom() {
        const room = rooms[roomIndex];
        cancelPetAnimation();
        cancelEatAnimation();
        cancelPlayAnimation();
        Object.values(roomImgs).forEach(i => i.classList.remove('is-active'));
        roomImgs[room].classList.add('is-active');
        updateRoomUI();
        positionCat();
      }

      /* animated room change: dir = 1 (next) or -1 (prev) */
      function goToRoom(target, dir) {
        if (transitioning || target === roomIndex) return;
        transitioning = true;

        const d = dir > 0 ? 'next' : 'prev';
        const fromImg = roomImgs[rooms[roomIndex]];
        roomIndex = target;
        const toImg = roomImgs[rooms[roomIndex]];

        cancelPetAnimation();
        cancelEatAnimation();
        cancelPlayAnimation();

        els.catStage.classList.add('cat-away');
        els.bubble.classList.remove('show');
        updateRoomUI();

        roomVeil.classList.remove('sweep-next', 'sweep-prev');
        void roomVeil.offsetWidth;
        roomVeil.classList.add('sweep-' + d);

        fromImg.classList.add('exit-' + d);
        toImg.classList.add('is-active', 'enter-' + d);

        /* move the cat while it is invisible, so nothing slides across the screen */
        setTimeout(positionCat, 300);

        /* cat hops back in */
        setTimeout(() => {
          els.catStage.classList.remove('cat-away');
          if (!state.sleeping) {
            els.catWrap.classList.add('bouncing');
            setTimeout(() => els.catWrap.classList.remove('bouncing'), 650);
          }
          spawnParticle('✨');
        }, 520);

        /* cleanup */
        setTimeout(() => {
          fromImg.classList.remove('is-active', 'exit-' + d);
          toImg.classList.remove('enter-' + d);
          roomVeil.classList.remove('sweep-' + d);
          transitioning = false;
        }, TRANSITION_MS);
      }

      /* =====================================================
         Cat positioning
         ===================================================== */
      function positionCat() {
        const img = roomImgs[rooms[roomIndex]];
        if (!img || !img.complete || !img.naturalWidth || !img.naturalHeight) return;
        const anchor = ROOM_ANCHOR[rooms[roomIndex]];
        const containerW = els.stage.clientWidth;
        const containerH = els.stage.clientHeight;
        const imgRatio = img.naturalWidth / img.naturalHeight;
        const containerRatio = containerW / containerH;
        let renderW, renderH, offsetX, offsetY;
        if (imgRatio > containerRatio) {
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
        if (offsetX > 1) {
          Object.assign(els.sideLeft.style, {
            left: '0',
            right: 'auto',
            top: '0',
            bottom: 'auto',
            width: offsetX + 'px',
            height: '100%'
          });
          Object.assign(els.sideRight.style, {
            right: '0',
            left: 'auto',
            top: '0',
            bottom: 'auto',
            width: offsetX + 'px',
            height: '100%'
          });
        } else {
          Object.assign(els.sideLeft.style, {
            left: '0',
            right: 'auto',
            top: '0',
            bottom: 'auto',
            width: '100%',
            height: offsetY + 'px'
          });
          Object.assign(els.sideRight.style, {
            left: '0',
            right: 'auto',
            bottom: '0',
            top: 'auto',
            width: '100%',
            height: offsetY + 'px'
          });
        }
        const px = offsetX + (anchor.x / 100) * renderW;
        const py = offsetY + (anchor.y / 100) * renderH;
        const scale = Math.max(0.35, Math.min(1.6, (anchor.widthFrac * renderW) / CAT_BASE_WIDTH));
        els.catStage.style.left = px + 'px';
        els.catStage.style.top = py + 'px';
        els.catStage.style.transform = `translate(-50%, -100%) scale(${scale})`;
        els.catStage.classList.add('placed');
      }

      let relayoutRAF = null;

      function relayout() {
        cancelAnimationFrame(relayoutRAF);
        relayoutRAF = requestAnimationFrame(() => {
          if (window.scrollX || window.scrollY) window.scrollTo(0, 0); // undo focus-scroll on mobile
          positionCat();
        });
      }
      if ('ResizeObserver' in window) new ResizeObserver(relayout).observe(els.stage);
      window.addEventListener('resize', relayout);
      window.addEventListener('orientationchange', () => setTimeout(relayout, 300));
      window.addEventListener('pageshow', relayout);
      if (window.visualViewport) window.visualViewport.addEventListener('resize', relayout);
      setTimeout(() => els.catStage.classList.add('placed'), 4000); // never leave Nova hidden


      const motesEl = document.getElementById('motes');

      function spawnMote() {
        if (!motesEl) return;
        const m = document.createElement('span');
        m.className = 'mote';
        const size = 3 + Math.random() * 4;
        m.style.width = size + 'px';
        m.style.height = size + 'px';
        m.style.left = (Math.random() * 94 + 3) + '%';
        m.style.setProperty('--drift', (Math.random() * 60 - 30) + 'px');
        const dur = 10 + Math.random() * 9;
        m.style.animationDuration = dur + 's';
        motesEl.appendChild(m);
        setTimeout(() => m.remove(), dur * 1000 + 200);
      }
      for (let i = 0; i < 7; i++) setTimeout(spawnMote, i * 900);
      setInterval(spawnMote, 1900);


      function renderRail() {
        els.roomRail.innerHTML = '';
        rooms.forEach((r, i) => {
          const dot = document.createElement('button');
          dot.className = 'rail-dot' + (i === roomIndex ? ' active' : '');
          dot.setAttribute('aria-label', 'Go to ' + roomLabels[r]);
          dot.addEventListener('click', () => goToRoom(i, i > roomIndex ? 1 : -1));
          els.roomRail.appendChild(dot);
        });
      }

      function renderDock() {
        const room = rooms[roomIndex];
        els.dock.innerHTML = '';
        actionsByRoom[room].forEach(a => {
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

      function clamp(n) {
        return Math.max(0, Math.min(100, n));
      }

      function renderStats() {
        els.hungerVal.textContent = Math.round(state.hunger);
        els.happyVal.textContent = Math.round(state.happy);
        els.energyVal.textContent = Math.round(state.energy);
        els.hungerFill.style.width = state.hunger + '%';
        els.happyFill.style.width = state.happy + '%';
        els.energyFill.style.width = state.energy + '%';
      }

      function currentMood() {
        if (state.sleeping) return 'sleepy';
        const avg = (state.hunger + state.happy + state.energy) / 3;
        if (avg >= 65) return 'happy';
        if (avg >= 35) return 'neutral';
        return 'sad';
      }
      const MOOD_CLASSES = ['mood-happy', 'mood-neutral', 'mood-sad', 'mood-sleepy'];

      function renderMood() {
        const mood = currentMood();
        MOOD_CLASSES.forEach(m => els.catWrap.classList.remove(m));
        els.catWrap.classList.add('mood-' + mood);
        els.catWrap.classList.toggle('hungry', state.hunger < HUNGER_THRESHOLD);
      }

      function renderAll() {
        renderStats();
        renderMood();
      }


      let bubbleTimer = null;
      let chatHistory = [];
      let lastInteractionId = '';

      function say(text) {
        els.bubble.textContent = text;
        els.bubble.classList.add('show');
        clearTimeout(bubbleTimer);
        bubbleTimer = setTimeout(() => els.bubble.classList.remove('show'), 2600);
      }

      function spawnParticle(emoji) {
        const span = document.createElement('span');
        span.className = 'particle';
        span.textContent = emoji;
        span.style.left = (42 + Math.random() * 16) + '%';
        els.particles.appendChild(span);
        setTimeout(() => span.remove(), 1400);
      }

      function feedCat() {
        if (state.sleeping) {
          say('Zzz... too sleepy to eat.');
          return;
        }
        state.hunger = clamp(state.hunger + 25);
        state.happy = clamp(state.happy + 4);
        renderAll();
        playEatAnimation();
        spawnParticle('🍗');
        const line = pick(['Nom nom nom!', 'Yummy!', 'More please? 🐟']);
        say(line);
        logAction('🍗 You fed Nova', line);
      }

      function giveTreat() {
        if (state.sleeping) {
          say('Zzz...');
          return;
        }
        state.hunger = clamp(state.hunger + 10);
        state.happy = clamp(state.happy + 10);
        renderAll();
        playEatAnimation();
        spawnParticle('🥛');
        const line = pick(['A treat?! Best day ever!', 'Purrrr~']);
        say(line);
        logAction('🥛 You gave a treat', line);
      }

      function playWithCat() {
        if (state.sleeping) {
          say('Let me sleep a bit more...');
          return;
        }
        if (state.energy < 10) {
          say('Too tired to play right now.');
          return;
        }
        state.happy = clamp(state.happy + 18);
        state.energy = clamp(state.energy - 12);
        renderAll();
        playPlayAnimation();
        spawnParticle('✨');
        const line = pick(['Wheee!', 'Again, again!', 'This is fun!']);
        say(line);
        logAction('✨ You played together', line);
      }

      function petCat() {
        if (state.sleeping) {
          say('Purrrr... (still sleeping)');
          return;
        }
        state.happy = clamp(state.happy + 8);
        renderAll();
        spawnParticle('💕');
        const line = pick(['Purrrrr...', 'I love that!', '😻']);
        say(line);
        playPetAnimation();
        logAction('💕 You petted Nova', line);
      }


      let petAnimTimer = null;

      function cancelPetAnimation() {
        clearTimeout(petAnimTimer);
        els.petFrames.forEach(f => f.classList.remove('active'));
        els.catWrap.classList.remove('petting');
      }

      function playPetAnimation() {
        cancelPetAnimation();
        cancelEatAnimation();
        cancelPlayAnimation();
        els.catWrap.classList.add('petting');
        const sequence = [{
            frame: 0,
            hold: 280
          },
          {
            frame: 1,
            hold: 650
          },
          {
            frame: 2,
            hold: 750
          },
        ];
        let step = 0;

        function showStep() {
          els.petFrames.forEach(f => f.classList.remove('active'));
          if (step < sequence.length) {
            els.petFrames[sequence[step].frame].classList.add('active');
            petAnimTimer = setTimeout(() => {
              step++;
              showStep();
            }, sequence[step].hold);
          } else {
            els.catWrap.classList.remove('petting');
          }
        }
        showStep();
      }

      let eatAnimTimer = null;

      function cancelEatAnimation() {
        clearTimeout(eatAnimTimer);
        els.catWrap.classList.remove('eating');
      }

      function playEatAnimation() {
        cancelEatAnimation();
        cancelPetAnimation();
        cancelPlayAnimation();
        els.catWrap.classList.add('eating');
        eatAnimTimer = setTimeout(() => els.catWrap.classList.remove('eating'), 900);
      }

      let playAnimTimer = null;

      function cancelPlayAnimation() {
        clearTimeout(playAnimTimer);
        els.playFrames.forEach(f => f.classList.remove('active'));
        els.catWrap.classList.remove('playing');
        els.catStage.classList.remove('moving'); // never leave this stuck on
      }

      function playPlayAnimation() {
        cancelPlayAnimation();
        cancelPetAnimation();
        cancelEatAnimation();
        els.catWrap.classList.add('playing');
        els.catStage.classList.add('moving');

        const sequence = [0, 1, 0, 1];
        let step = 0;

        function showStep() {
          els.playFrames.forEach(f => f.classList.remove('active'));
          if (step < sequence.length) {
            els.playFrames[sequence[step]].classList.add('active');
            playAnimTimer = setTimeout(() => {
              step++;
              showStep();
            }, 320);
          } else {
            els.catWrap.classList.remove('playing');
            els.catStage.classList.remove('moving');
          }
        }
        showStep();
      }

      function toggleSleep() {
        state.sleeping = !state.sleeping;
        if (state.sleeping) {
          cancelPetAnimation();
          cancelEatAnimation();
          cancelPlayAnimation();
          spawnParticle('💤');
          say('Goodnight...');
          logAction('💤 Nova went to nap', 'Goodnight...');
        } else {
          const line = pick(["I'm awake!", 'Good morning!']);
          say(line);
          logAction('☀️ Nova woke up', line);
        }
        renderMood();
      }

      function pick(arr) {
        return arr[Math.floor(Math.random() * arr.length)];
      }

      /* =====================================================
         Stat decay loop
         ===================================================== */
      setInterval(() => {
        if (state.sleeping) {
          state.energy = clamp(state.energy + 6);
          if (state.energy >= 100) {
            state.sleeping = false;
            say("I'm fully rested!");
            logAction('☀️ Nova woke up', "I'm fully rested!");
          }
        } else {
          state.hunger = clamp(state.hunger - 2);
          state.energy = clamp(state.energy - 1.5);
          state.happy = clamp(state.happy - (state.hunger < 20 ? 3 : 1));
        }
        renderAll();
      }, 4000);

      /* =====================================================
         Room navigation
         ===================================================== */
      document.getElementById('prevRoom').addEventListener('click', () => {
        goToRoom((roomIndex - 1 + rooms.length) % rooms.length, -1);
      });
      document.getElementById('nextRoom').addEventListener('click', () => {
        goToRoom((roomIndex + 1) % rooms.length, 1);
      });
      els.catWrap.addEventListener('click', petCat);

      /* =====================================================
         Chat widget
         ===================================================== */
      function openChat() {
        els.chatPanel.classList.add('open');
        els.chatScrim.classList.add('open');
        els.fabBadge.style.display = 'none';
        els.chatInput.focus();
      }

      function closeChat() {
        els.chatPanel.classList.remove('open');
        els.chatScrim.classList.remove('open');
      }
      els.chatToggle.addEventListener('click', () => {
        els.chatPanel.classList.contains('open') ? closeChat() : openChat();
      });
      els.chatClose.addEventListener('click', closeChat);
      els.chatScrim.addEventListener('click', closeChat);
      els.chatQuickRow.addEventListener('click', e => {
        const btn = e.target.closest('.chat-quick-btn');
        if (!btn) return;
        els.chatInput.value = btn.dataset.quick;
        sendChat();
      });

      function addMessage(text, from) {
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

      function addTyping() {
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

      function logAction(actionText, reactionText) {
        addMessage(actionText, 'system');
        if (reactionText) addMessage(reactionText, 'cat');
        if (!els.chatPanel.classList.contains('open')) {
          els.fabBadge.style.display = 'block';
        }
      }

      async function getCatReply(message) {
        if (state.sleeping) return pick(['Zzz... five more minutes...', '*rolls over, still asleep*']);
        const payload = {
          message,
          previous_interaction_id: lastInteractionId,
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
        try {
          const res = await fetch('/api/cat-chat.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
          });
          const data = await res.json();
          if (data.debug) console.warn('[Nova debug]', data.debug);
          if (data.interaction_id) lastInteractionId = data.interaction_id;
          if (data.error_kind === 'lost_thought') lastInteractionId = '';
          if (!data.reply) throw new Error(data.error || 'Bad response (HTTP ' + res.status + ')');
          return data.reply;
        } catch (err) {
          console.error('Cat chat error:', err);
          return pick([
            "Meow? (I'm having trouble finding my words right now)",
            'Purrrr~ *connection hiccup, try again?*',
            "*tilts head* my brain's a little foggy right now."
          ]);
        }
      }

      async function sendChat() {
        const text = els.chatInput.value.trim();
        if (!text) return;
        addMessage(text, 'user');
        els.chatInput.value = '';
        state.happy = clamp(state.happy + 3);
        renderStats();
        const typingRow = addTyping();
        const reply = await getCatReply(text);
        chatHistory.push({
          role: 'user',
          content: text
        });
        chatHistory.push({
          role: 'assistant',
          content: reply
        });
        if (chatHistory.length > 10) chatHistory = chatHistory.slice(-10);
        setTimeout(() => {
          typingRow.remove();
          const msgEl = addMessage(reply, 'cat');
          // Long story replies: short teaser over Nova, full text stays in the chat
          say(reply.length > 110 ? reply.slice(0, 100).trim() + '…' : reply);
          if (reply.length > 160) msgEl.scrollIntoView({
            block: 'start',
            behavior: 'smooth'
          });
        }, 500 + Math.random() * 400);
      }
      els.chatSend.addEventListener('click', sendChat);
      els.chatInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') sendChat();
      });

      renderRoom();
      renderAll();
      addMessage("Meow! I'm " + state.name + ". Talk to me!", 'cat');

      (function initSplash() {
        const SPLASH_ONCE = false; // true = only show it the first time per browser tab session
        const MIN_MS = 10000; // shortest time the splash stays up
        const MAX_MS = 24000; // stop waiting for slow images after this long
        const splash = document.getElementById('splash');
        if (!splash) return;
        try {
          if (SPLASH_ONCE && sessionStorage.getItem('novaSplashSeen')) {
            splash.remove();
            return;
          }
        } catch (e) {}

        const fill = document.getElementById('splashFill');
        const msg = document.getElementById('splashMsg');
        const go = document.getElementById('splashGo');
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        const MOTIVATION_MODE = 'daily';
        // const NOTES = [
        //   "One case at a time. I've never read one, but I'm told that's how it works.",
        //   "Hard days still count. Read a little, rest a little, come back stronger.",
        //   "Small progress is still progress. Even I only nap in small increments.",
        //   "Be kind to yourself today. A rested mind learns better than a tired one.",
        //   "Your outline doesn't have to be perfect. It just has to exist.",
        //   "Every page you read is one page closer to the bar. I'm proud of you.",
        //   "It's okay to be tired. Just don't forget how far you've already come.",
        //   "Court is adjourned for today, but your future isn't. Rest well, Counsel.",
        //   "Not every day has to be a great day. A showed-up day is enough.",
        //   "The bar exam is a mountain. Today you only need to take the next step.",
        //   "You are not behind. You're building something big, and big things take time.",
        //   "If it gets heavy, come sit with me for a minute. Then back to it, Counsel.",
        //   "Even the longest case has a last page. Keep going.",
        //   "Finals are temporary. 'Atty.' is forever.",
        //   "Nobody watches you study as closely as I do. You're doing great.",
        //   "Believe in yourself the way I believe in 3 a.m. zoomies: completely.",
        //   "A messy day of studying still beats a perfect day of waiting.",
        //   "You've got the brains, the heart, and a very supportive cat. That's a winning brief.",
        //   "Rest is part of the plan, not a break from it."
        // ];
        // const noteL = document.getElementById('splashNoteTextL');
        // const noteR = document.getElementById('splashNoteTextR');
        // let ni;
        // if (MOTIVATION_MODE === 'random') {
        //   ni = Math.floor(Math.random() * NOTES.length);
        // } else {
        //   const t = new Date();
        //   ni = Math.floor(Date.UTC(t.getFullYear(), t.getMonth(), t.getDate()) / 86400000) % NOTES.length;
        // }
        // const ni2 = (ni + Math.floor(NOTES.length / 2)) % NOTES.length; // a different quote for the other side
        // if (noteL) noteL.textContent = NOTES[ni];
        // if (noteR) noteR.textContent = NOTES[ni2];

        const NOTES = [
          "Hi Anghela. Study hard, but eat something first. I'm rooting for you, always.",
          "One case at a time. You've been doing hard things for years, and this is just another one.",
          "Rest is part of the plan, Anghela. Take a break, then come back stronger.",
          "Back in Nagpayong you won every argument with me. The bar exam doesn't stand a chance.",
          "You don't have to be perfect today. You just have to keep going. I believe in you.",
          "Whatever your grades say, you're already someone worth being proud of.",
          "Drink some water, stretch a little, and then crush that reading. You've got this.",
          "Future Atty. Anghela is counting on today's you. And so am I.",
          "Hard day? Close the book for a bit and breathe. It will still be there, and so will you.",
          "I built this little house so you'd have a place to rest. Stay as long as you need.",
          "When you're tired, remember how far you've come since Grade 8. It's a long way.",
          "You make everything look harder than it is, and then you do it anyway. That's what I admire.",
          "A messy study day still counts. Showing up is the hard part, and you always do.",
          "Take it slow, Anghela. You're building something big, and big things take time.",
          "Even the longest case has a last page. You'll get there, and I'll be cheering.",
          "No pressure. Just know that someone out there thinks you're amazing, with or without the grades.",
          "Be kind to yourself today. You'd never be this hard on anyone else.",
          "I may have teased you in Grade 8, but I never doubted you. Not once.",
          "You're allowed to be tired. You're not allowed to forget how brilliant you are.",
          "A messy day of studying still beats a perfect day of waiting.",
          "Every page you read is one page closer to the bar. Keep going, Counsel.",
          "Missing you is part of the deal while you study. But I'm cheering for you from here.",
          "Sleep enough, eat well, and trust yourself. The rest will follow.",
          "You were scary even in Grade 8, and now you're going to be a scary good lawyer.",
          "Don't compare your chapter 2 to someone else's chapter 20. You're on your own path.",
          "Whatever happens today, I'm on your side. Nova is too, probably. She just won't admit it.",
          "Breathe in, breathe out, then one more page. You're doing great, Anghela.",
          "Hope today treats you gently. And if it doesn't, you're stronger than it is."
        ];

        const noteEls = splash.querySelectorAll('.splash-notes .splash-note-text');
        const pool = NOTES.slice();
        for (let i = pool.length - 1; i > 0; i--) { // shuffle
          const j = Math.floor(Math.random() * (i + 1));
          [pool[i], pool[j]] = [pool[j], pool[i]];
        }
        noteEls.forEach((el, i) => {
          el.textContent = pool[i];
        });

        /* fairy lights along a gentle wire */
        const lights = splash.querySelector('.splash-lights');
        const wireY = x => 16 + 9 * Math.sin(x * Math.PI * 6);
        const pts = [];
        for (let i = 0; i <= 80; i++) {
          const x = i / 80;
          pts.push((x * 1000).toFixed(1) + ',' + wireY(x).toFixed(1));
        }
        lights.innerHTML = '<svg viewBox="0 0 1000 40" preserveAspectRatio="none"><polyline points="' + pts.join(' ') + '" fill="none" stroke="#7a5a3c" stroke-width="2" vector-effect="non-scaling-stroke"/></svg>';
        const bulbColors = ['#ffd27a', '#ffb3a0', '#b4e1bf', '#ffe9a8', '#f7c0d3'];
        const BULBS = 12;
        for (let i = 0; i < BULBS; i++) {
          const x = (i + 0.5) / BULBS;
          const b = document.createElement('i');
          b.className = 'splash-bulb';
          b.style.left = (x * 100) + '%';
          b.style.top = (wireY(x) + 2) + 'px';
          b.style.setProperty('--c', bulbColors[i % bulbColors.length]);
          b.style.animationDelay = ((i * 0.37) % 2.4) + 's';
          lights.appendChild(b);
        }

        /* floating warm dust */
        const motes = splash.querySelector('.splash-motes');
        for (let i = 0; i < 16; i++) {
          const m = document.createElement('span');
          m.className = 'mote';
          const size = 3 + Math.random() * 5;
          m.style.width = m.style.height = size + 'px';
          m.style.left = (Math.random() * 94 + 3) + '%';
          m.style.setProperty('--drift', (Math.random() * 60 - 30) + 'px');
          const dur = 9 + Math.random() * 8;
          m.style.animationDuration = dur + 's';
          m.style.animationDelay = (-Math.random() * dur) + 's';
          motes.appendChild(m);
        }

        /* real loading progress: every image on the page + room backgrounds + fonts */
        const urls = new Set();
        Array.from(document.images).forEach(im => {
          if (im.src) urls.add(im.src);
        });
        Object.values(ROOM_BG).forEach(u => urls.add(new URL(u, location.href).href));
        const total = urls.size + 1; // +1 for fonts
        let done = 0;
        const tick = () => {
          done++;
        };
        urls.forEach(u => {
          const im = new Image();
          im.onload = im.onerror = tick;
          im.src = u;
        });
        (document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(tick, tick);

        // const lines = [
        //   [0, 'Warming up the house…'],
        //   [0.12, 'Fluffing the cushions…'],
        //   [0.25, 'Lighting the fairy lights…'],
        //   [0.38, 'Pinning up the notes…'],
        //   [0.52, 'Brewing something warm…'],
        //   [0.66, 'Smoothing the rug…'],
        //   [0.80, 'Waking Nova up…'],
        //   [0.92, 'Almost there…'],
        // ];
        const lines = [
          [0, 'Go, Anghela, go!'],
          [0.12, 'Future Atty. Anghela is loading… '],
          [0.25, 'Cutie and unstoppable, that\'s you '],
          [0.38, 'One page at a time. You\'ve got this'],
          [0.52, 'Nova is cheering from her rug 🐾'],
          [0.66, 'The bar exam has no idea what\'s coming '],
          [0.80, 'You\'re doing better than you think '],
          [0.92, 'Okay, Nova is waking up for you… '],
        ];
        const t0 = performance.now();
        let shown = 0;
        let lastLine = '';

        function frame(now) {
          const elapsed = now - t0;
          const target = elapsed > MAX_MS ? 1 : Math.min(done / total, Math.min(1, elapsed / MIN_MS));
          if (target > shown) shown = Math.min(target, shown + 0.04);
          fill.style.width = (shown * 100) + '%';
          let line = lines[0][1];
          lines.forEach(l => {
            if (shown >= l[0]) line = l[1];
          });
          if (line !== lastLine) {
            msg.textContent = line;
            lastLine = line;
          }
          if (shown >= 1) {
            msg.textContent = 'Nova is waiting for you…';
            splash.classList.add('ready');
            go.disabled = false;
            go.focus({
              preventScroll: true
            });
            return;
          }
          requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);

        function enter() {
          if (!splash.classList.contains('ready') || splash.classList.contains('leaving')) return;
          try {
            if (SPLASH_ONCE) sessionStorage.setItem('novaSplashSeen', '1');
          } catch (e) {}
          splash.classList.add('leaving');
          setTimeout(() => splash.remove(), reduce ? 250 : 900);
          setTimeout(() => {
            say('Meow! Welcome home mommy! 🐾');
            spawnParticle('💕');
            els.catWrap.classList.add('bouncing');
            setTimeout(() => els.catWrap.classList.remove('bouncing'), 700);
          }, reduce ? 150 : 500);
        }
        go.addEventListener('click', enter);
      })();
    })();
  </script>
</body>

</html>