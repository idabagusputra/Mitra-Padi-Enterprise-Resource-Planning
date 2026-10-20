<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Surat dari Hogwarts</title>
<meta name="color-scheme" content="dark">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=IM+Fell+English:ital@0;1&family=Pinyon+Script&display=swap" rel="stylesheet">
<style>
:root{--bg:#05061a;--gold:#e6c27a;--gold2:#fff1cf;--ink:#1d4a37;--wine:#7d1d2b;--parch:#ead9a9;
--disp:'Cinzel','Trajan Pro',Georgia,serif;--body:'IM Fell English','Palatino Linotype',Georgia,serif;--script:'Pinyon Script','Snell Roundhand',cursive;
--noise:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='260' height='260'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.75' numOctaves='3'/%3E%3CfeColorMatrix values='0 0 0 0 .42 0 0 0 0 .27 0 0 0 0 .1 0 0 0 .55 0'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.4'/%3E%3C/svg%3E");color-scheme:dark}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
html,body{height:100%;overflow:hidden;background:var(--bg);color:var(--gold2);font-family:var(--body);font-size:19px}
button{font:inherit;color:inherit;background:none;border:0;cursor:pointer}
button:focus-visible{outline:2px solid var(--gold);outline-offset:3px}
body{background:linear-gradient(180deg,#04051a 0%,#0e1038 45%,#2a2358 80%,#4a3560 100%)}
canvas{position:fixed;inset:0;width:100%;height:100%}
#stars{z-index:0}#fx{z-index:50;pointer-events:none}
/* ===== langit & kastil ===== */
#sky{position:fixed;inset:0;z-index:1;pointer-events:none;overflow:hidden}
.moon{position:absolute;right:11%;top:9%;width:clamp(70px,13vw,130px);aspect-ratio:1;border-radius:50%;background:radial-gradient(circle at 35% 35%,#fffdf2,#f3ead0 55%,#d8cfae);box-shadow:0 0 60px 20px rgba(255,244,210,.28),0 0 160px 70px rgba(150,160,255,.16)}
.castle{position:absolute;left:-4%;right:-4%;bottom:0;width:108%;height:auto;max-height:46vh;transform:translateX(calc(var(--px,0)*var(--k)*1px))}
.castle.far{--k:-10;opacity:.55}.castle.near{--k:-22}
.castle .w{animation:win 3s ease-in-out infinite alternate}
@keyframes win{from{opacity:.2}to{opacity:1}}
.mist{position:absolute;left:-20%;width:140%;height:22vh;bottom:0;background:radial-gradient(ellipse at 30% 60%,rgba(190,190,255,.2),transparent 60%),radial-gradient(ellipse at 75% 70%,rgba(255,200,230,.13),transparent 60%);filter:blur(14px);animation:mist 26s ease-in-out infinite alternate}
.mist.b{bottom:-4vh;animation-duration:38s;animation-direction:alternate-reverse;opacity:.8}
@keyframes mist{to{transform:translateX(9%)}}
.fcandle{position:absolute;top:var(--y);left:var(--x);width:14px;animation:fcf var(--d) ease-in-out var(--dl) infinite alternate;filter:drop-shadow(0 0 10px rgba(255,200,110,.8))}
@keyframes fcf{to{transform:translateY(-16px) rotate(3deg)}}
.fl{transform-box:fill-box;transform-origin:50% 100%;animation:fl .3s ease-in-out infinite alternate}
@keyframes fl{to{transform:scale(.88,1.12) rotate(3deg)}}
.owlf{position:absolute;top:var(--y);left:-120px;width:88px;animation:owlfly var(--t) linear forwards}
@keyframes owlfly{to{transform:translate(calc(100vw + 240px),-70px)}}
.wing{transform-box:fill-box;transform-origin:50% 100%;animation:flap .35s ease-in-out infinite alternate}
@keyframes flap{from{transform:scaleY(1)}to{transform:scaleY(-.5)}}
.shoot{position:absolute;top:var(--y);left:var(--x);width:130px;height:2px;rotate:25deg;background:linear-gradient(90deg,transparent,#fff);animation:shoot 1.1s ease-out forwards}
@keyframes shoot{from{opacity:1;translate:0 0}to{opacity:0;translate:340px 160px}}
.snitch{position:absolute;left:-40px;top:var(--y);width:34px;animation:snt 7s cubic-bezier(.4,0,.6,1) forwards;filter:drop-shadow(0 0 8px #ffd166)}
@keyframes snt{20%{transform:translate(28vw,-8vh)}40%{transform:translate(48vw,5vh)}65%{transform:translate(72vw,-10vh)}to{transform:translate(calc(100vw + 80px),3vh)}}
.snitch .wing{animation-duration:.08s}
#flash{position:fixed;inset:0;z-index:40;pointer-events:none;opacity:0;background:radial-gradient(circle,rgba(255,233,210,.8),rgba(125,29,43,.25) 40%,transparent 70%)}
#flash.go{animation:fl2 .9s ease-out}
@keyframes fl2{0%{opacity:0;transform:scale(.2)}35%{opacity:1}100%{opacity:0;transform:scale(2.4)}}
.spell{position:fixed;z-index:60;pointer-events:none;font-family:var(--script);font-size:clamp(30px,7.5vw,46px);white-space:nowrap;color:var(--gold2);text-shadow:0 0 18px rgba(255,214,150,.95);transform:translate(-50%,-50%);animation:spl 1.7s ease-out forwards}
@keyframes spl{0%{opacity:0;scale:.4}20%{opacity:1;scale:1.1}100%{opacity:0;translate:0 -90px}}
/* ===== UI umum ===== */
.steps{position:fixed;z-index:20;top:calc(env(safe-area-inset-top,0px) + 14px);left:0;right:0;display:flex;justify-content:center;gap:12px;pointer-events:none}
.steps i{width:9px;height:9px;rotate:45deg;border:1px solid rgba(230,194,122,.5);transition:.5s}
.steps i.on{background:var(--gold);box-shadow:0 0 12px var(--gold)}
.scene{position:fixed;inset:0;z-index:5;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;padding:calc(env(safe-area-inset-top,0px) + 40px) 18px calc(env(safe-area-inset-bottom,0px) + 20px);text-align:center;opacity:0;visibility:hidden;transform:translateY(18px) scale(.98);transition:opacity .6s,transform .6s,visibility 0s .6s;overflow:auto}
.scene.on{opacity:1;visibility:visible;transform:none;transition:opacity .8s .1s,transform .8s .1s,visibility 0s}
h1,h2{font-family:var(--script);font-weight:400;color:var(--gold2);line-height:1.1;text-shadow:0 0 28px rgba(230,194,122,.6)}
h1{font-size:clamp(46px,12vw,82px)}h2{font-size:clamp(36px,9vw,56px)}
.sub{font-style:italic;opacity:.85;font-size:clamp(16px,4vw,20px);letter-spacing:.02em}
.rule{width:min(60vw,220px);height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent);position:relative}
.rule:after{content:"";position:absolute;left:50%;top:-3px;width:7px;height:7px;rotate:45deg;margin-left:-3px;background:var(--gold)}
.btn{font-family:var(--disp);font-weight:500;font-size:13px;letter-spacing:.14em;padding:14px 30px;border:1px solid var(--gold);border-radius:99px;color:var(--gold2);background:rgba(20,18,60,.5);backdrop-filter:blur(8px);box-shadow:0 0 0 4px rgba(230,194,122,.08),0 8px 24px rgba(0,0,0,.4);transition:.3s}
.btn:hover:not(:disabled){background:var(--gold);color:#241a08;box-shadow:0 0 30px rgba(230,194,122,.6)}
.btn:disabled{opacity:.35;cursor:default}.btn.solid{background:linear-gradient(#f0d28f,#c99a45);color:#241a08}
.hid{opacity:0;pointer-events:none;transform:translateY(8px);transition:.6s}.hid.show{opacity:1;pointer-events:auto;transform:none}
.row{display:flex;gap:12px;flex-wrap:wrap;justify-content:center}
.hallows{width:44px;fill:none;stroke:var(--gold);stroke-width:2.4;filter:drop-shadow(0 0 8px rgba(230,194,122,.7))}
/* ===== amplop ===== */
.tilt{perspective:900px}
.env{position:relative;width:min(84vw,440px);aspect-ratio:3/2;filter:drop-shadow(0 30px 34px rgba(0,0,0,.65));transform:rotateX(var(--rx,0deg)) rotateY(var(--ry,0deg));transition:transform .5s,opacity .8s;animation:envin 1.4s cubic-bezier(.2,1,.3,1) both,hov 5s ease-in-out 1.4s infinite}
@keyframes envin{from{opacity:0;translate:0 -90px;scale:.8}}@keyframes hov{50%{translate:0 -10px}}
.env.gone{transform:translateY(60px) scale(.8);opacity:0}
.env>*{position:absolute;inset:0}
.par{background:var(--noise),radial-gradient(ellipse at 30% 20%,#f1e2b8,#d9bf86 80%);box-shadow:inset 0 0 40px rgba(120,70,20,.55)}
.e-back{background:var(--noise),#b99a5e;border-radius:4px}
.e-front{clip-path:polygon(0 0,50% 56%,100% 0,100% 100%,0 100%);border-radius:4px}
.e-flap{clip-path:polygon(0 0,100% 0,50% 58%);transform-origin:50% 0;transition:transform .9s cubic-bezier(.5,0,.3,1),z-index 0s .4s;z-index:3}
.env.open .e-flap{transform:rotateX(180deg);z-index:0}
.e-card{inset:6% 7% 6%!important;background:#f6ecd0;border-radius:3px;z-index:1;transition:transform 1s .5s}
.env.open .e-card{transform:translateY(-32%)}
.addr{position:absolute;inset:auto 0 12% 0!important;z-index:2;font-family:var(--script);color:var(--ink);font-size:clamp(22px,6.4vw,34px);line-height:1.15;text-shadow:0 0 1px rgba(29,74,55,.4)}
.addr small{display:block;font-family:var(--body);font-style:italic;font-size:clamp(11px,2.8vw,14px);opacity:.8;margin-top:2px}
.seal{inset:49% auto auto 50%!important;width:74px;height:74px;margin:-37px 0 0 -37px;z-index:5;border-radius:50%;display:grid;place-items:center;color:#4a0b16;background:radial-gradient(circle at 35% 30%,#d83650,#8e1c2d 60%,#4c0e18);box-shadow:0 5px 12px rgba(0,0,0,.55),inset 0 0 0 3px rgba(255,160,160,.18),inset 0 -4px 8px rgba(0,0,0,.35);animation:pulse 2.2s infinite}
.seal svg{width:34px;height:40px;filter:drop-shadow(0 1px 0 rgba(255,170,170,.35))}
.seal.crack{animation:crack .6s forwards}
@keyframes pulse{50%{transform:scale(1.08);box-shadow:0 0 0 14px rgba(230,194,122,.13),0 5px 12px rgba(0,0,0,.55)}}
@keyframes crack{to{transform:scale(.2) rotate(-40deg);opacity:0}}
/* ===== surat ===== */
.letter{width:min(92vw,560px);max-height:calc(100dvh - 160px);overflow:auto;padding:30px clamp(22px,6vw,48px) 34px;border-radius:4px;text-align:left;color:var(--ink);scrollbar-width:none;background:var(--noise),radial-gradient(ellipse at 50% 0,#f4e8c2,#e2cb94 90%);box-shadow:0 30px 70px rgba(0,0,0,.6),inset 0 0 70px rgba(130,80,25,.5),0 0 60px rgba(230,194,122,.15)}
.lh{display:flex;flex-direction:column;align-items:center;gap:4px;margin-bottom:10px;text-align:center}
.lh svg{width:46px;height:55px}.lh b{font-family:var(--disp);font-size:clamp(11px,3vw,14px);letter-spacing:.18em;color:#5a3b17}
.lh em{font-size:12px;opacity:.7}
.letter h2{color:var(--ink);text-shadow:none;font-size:clamp(38px,9vw,50px);text-align:center;margin:6px 0 8px}
.letter p{font-size:clamp(19px,4.6vw,22px);line-height:1.5;margin-bottom:12px;text-shadow:0 0 1px rgba(29,74,55,.35)}
.wd{display:inline-block;opacity:0;filter:blur(5px);transform:translateY(5px);animation:ink .7s forwards}
@keyframes ink{to{opacity:1;filter:none;transform:none}}
.letter .sig{text-align:right;font-family:var(--script);font-size:clamp(28px,7vw,36px);margin:6px 0 0}
/* ===== kue ===== */
.cake{width:min(78vw,340px)}
.flame,.glow{transition:opacity .35s}.flame{transform-box:fill-box;transform-origin:50% 100%;animation:fl .3s ease-in-out infinite alternate}
.out .flame,.out .glow{opacity:0}.smoke{opacity:0}.out .smoke{animation:sm 2.2s ease-out}
@keyframes sm{0%{opacity:.7;transform:translateY(0)}100%{opacity:0;transform:translateY(-40px)}}
.cd{cursor:pointer}.cd .flame,.cd .glow{opacity:0}.cd.lit .flame,.cd.lit .glow{opacity:1}.out .cd.lit .flame,.out .cd.lit .glow{opacity:0}
.wish{font-family:var(--script);font-size:clamp(30px,7.5vw,42px);color:var(--gold2);text-shadow:0 0 18px rgba(255,214,150,.7);min-height:1.3em}
/* ===== pilih ===== */
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;width:min(96vw,780px)}
@media(min-width:640px){.grid{grid-template-columns:repeat(4,1fr)}}
.card{--c:#9b1c2c;position:relative;padding:8px 4px 10px;border:1px solid rgba(230,194,122,.28);border-radius:14px;background:linear-gradient(rgba(255,255,255,.07),rgba(255,255,255,.01));box-shadow:inset 0 3px 0 var(--c);backdrop-filter:blur(6px);transition:.3s;animation:cin .7s cubic-bezier(.2,1.4,.4,1) both;animation-delay:calc(var(--i)*.06s)}
.card:nth-child(4n+2){--c:#d9a528}.card:nth-child(4n+3){--c:#2a5db0}.card:nth-child(4n){--c:#1f7a4d}
@keyframes cin{from{opacity:0;transform:translateY(40px) scale(.7)}}
.card:hover{border-color:var(--gold);transform:translateY(-4px)}
.card svg{height:clamp(84px,14vh,124px);width:100%;animation:bob 3.4s ease-in-out calc(var(--i)*-.4s) infinite}
@keyframes bob{50%{transform:translateY(-6px)}}
.card span{display:block;font-family:var(--disp);font-size:11px;letter-spacing:.06em;margin-top:4px}
.card.sel{border-color:var(--gold);background:rgba(230,194,122,.18);box-shadow:inset 0 3px 0 var(--c),0 0 26px rgba(230,194,122,.4)}
.card .n{position:absolute;top:6px;right:6px;width:22px;height:22px;border-radius:50%;background:var(--gold);color:#241a08;font-family:var(--disp);font-size:12px;display:none;place-items:center}
.card.sel .n{display:grid}.card.shake{animation:sh .35s}
@keyframes sh{25%{transform:translateX(-5px)}75%{transform:translateX(5px)}}
/* ===== hadiah ===== */
.gifts{display:flex;gap:14px;flex-wrap:wrap;justify-content:center}
.gift{width:min(28vw,200px);min-width:106px;aspect-ratio:3/4.4;perspective:900px;cursor:pointer}
.gi{position:relative;width:100%;height:100%;transform-style:preserve-3d;transition:transform .9s cubic-bezier(.3,1.2,.4,1)}
.gift.open .gi{transform:rotateY(180deg)}
.gi>div{position:absolute;inset:0;backface-visibility:hidden;-webkit-backface-visibility:hidden;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:8px;gap:6px}
.gf{background:linear-gradient(160deg,#1d2556,#0d1130);border:1px solid var(--gold);animation:bob 3s ease-in-out infinite}
.gift:nth-child(2) .gf{animation-delay:-1s}.gift:nth-child(3) .gf{animation-delay:-2s}
.gb{transform:rotateY(180deg);color:var(--ink);background:var(--noise),#f1e4bd;box-shadow:0 0 34px rgba(230,194,122,.5)}
.gift.open .gb{animation:glowp 2.4s ease-in-out infinite}
@keyframes glowp{50%{box-shadow:0 0 58px rgba(255,214,150,.9)}}
.gb svg{height:50%;width:100%}.gb b{font-family:var(--disp);font-size:11px;letter-spacing:.06em;color:var(--wine)}
.gb small{font-size:clamp(13px,3.3vw,16px);line-height:1.2;font-style:italic}
/* ===== penutup ===== */
.fin{max-width:30em;font-size:clamp(19px,4.8vw,25px);line-height:1.45;font-style:italic}
#f1{background:linear-gradient(90deg,#ffe9d2,#fff,#e6c27a,#ffe9d2);background-size:200%;-webkit-background-clip:text;background-clip:text;color:transparent;animation:shim 4s linear infinite;text-shadow:none}
@keyframes shim{to{background-position:200% 0}}
.lant{position:fixed;bottom:-60px;left:var(--x);z-index:3;width:24px;height:32px;pointer-events:none;border-radius:6px 6px 10px 10px;background:linear-gradient(#ffe2a8,#ff9f43);box-shadow:0 0 24px rgba(255,190,100,.85);animation:rise var(--t) ease-in forwards}
@keyframes rise{to{transform:translate(var(--dx),-120vh);opacity:0}}
@media(prefers-reduced-motion:reduce){*{animation-duration:.01ms!important;transition-duration:.01ms!important}}
</style>
</head>
<body>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
 <symbol id="crest" viewBox="0 0 100 120"><path d="M8 8H92V62C92 92 66 108 50 116C34 108 8 92 8 62Z" fill="#c9a24a"/><path d="M13 13H50V62H13Z" fill="#9b1c2c"/><path d="M50 13H87V62H50Z" fill="#e2b73b"/><path d="M13 62H50V110C32 101 13 88 13 62Z" fill="#1f4f9e"/><path d="M50 62H87C87 88 68 101 50 110Z" fill="#1f6b43"/><text x="50" y="74" text-anchor="middle" font-size="46" font-family="Cinzel,serif" font-weight="700" fill="#fff1cf" stroke="#4a3520" stroke-width="1.5">H</text></symbol>
 <symbol id="crestm" viewBox="0 0 100 120"><path d="M10 10H90V62C90 90 66 106 50 114C34 106 10 90 10 62Z" fill="none" stroke="currentColor" stroke-width="7"/><path d="M50 14V108M12 56H88" stroke="currentColor" stroke-width="3"/><text x="50" y="76" text-anchor="middle" font-size="50" font-family="Cinzel,serif" font-weight="700" fill="currentColor">H</text></symbol>
</svg>
<canvas id="stars"></canvas><div id="sky" aria-hidden="true"></div><canvas id="fx"></canvas><div id="flash"></div>
<div class="steps" id="steps"></div>

<section class="scene on" id="s0">
  <h1>Sebuah Surat<br>Untukmu</h1><div class="rule"></div>
  <div class="tilt" id="tilt"><div class="env" id="env"><div class="e-back"></div><div class="e-card"></div><div class="e-front par"></div><div class="addr" id="addr" style="text-align:center"></div><div class="e-flap par"></div><button class="seal" id="seal" aria-label="Buka surat"><svg><use href="#crestm"/></svg></button></div></div>
  <p class="sub" id="h0">ketuk segel lilin untuk membuka</p>
</section>

<section class="scene" id="s1">
  <div class="letter" id="letter"></div>
  <button class="btn hid" id="n1">Lanjut</button>
</section>

<section class="scene" id="s2">
  <h2>Buat Satu Permohonan</h2>
  <svg class="cake" id="cake" viewBox="0 0 320 250" aria-hidden="true"></svg>
  <p class="wish" id="wish"></p>
  <p class="sub" id="chint">ketuk tiap lilin untuk menyalakannya dengan mantra</p>
  <div class="row" id="lightRow"><button class="btn" id="lightAll">Incendio</button></div>
  <div class="row" id="blowRow" style="display:none"><button class="btn solid" id="blow">Nox · Padamkan</button><button class="btn" id="mic">Tiup Pakai Mikrofon</button></div>
  <button class="btn hid" id="n2">Lanjut</button>
</section>

<section class="scene" id="s3">
  <h2>Pilih 3 Hadiah Hirono</h2>
  <p class="sub" id="cnt">0 dari 3 dipilih</p>
  <div class="grid" id="grid"></div>
  <button class="btn" id="n3" disabled>Buka Hadiahku</button>
</section>

<section class="scene" id="s4">
  <h2>Hadiahmu</h2>
  <p class="sub" id="gh">ketuk tiap kotak untuk membukanya</p>
  <div class="gifts" id="gifts"></div>
  <button class="btn hid" id="n4">Lanjut</button>
</section>

<section class="scene" id="s5">
  <svg class="hallows" viewBox="0 0 60 60" aria-hidden="true"><path d="M30 4L56 54H4Z"/><circle cx="30" cy="39" r="12"/><path d="M30 4V54"/></svg>
  <h1 id="f1"></h1><div class="rule"></div>
  <p class="fin" id="f2"></p>
  <div class="row"><button class="btn solid" id="pat">Expecto Patronum</button><button class="btn" id="again">Ulangi dari Awal</button></div>
</section>

<script>
(()=>{
'use strict';
/* ===== UBAH DI SINI ===== */
const C={
  name:"Tante Ulan",
  from:"Putra",
  message:[
    "Selamat ulang tahun ke-24 TANTE. Panjang umur dan sehat selalu!",
    "This is your day! Semoga hari ini pekerjaannya lancar dan menyenangkan ya ges.",
    "Karena belum bisa unboxing Hirononya sekarang, ayo coba online dulu ya. Click lanjut dibawah!"
  ],
  wish:"Semoga semua doamu terkabul.",
  candles:3,
  closing:""
};
/* ======================== */
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
const rnd=(a,b)=>a+Math.random()*(b-a),T=s=>s.replaceAll('{name}',C.name);

/* ---------- figur gaya Hirono (SVG orisinal) ---------- */
const SK='#f8dcc4';
const A={
 gl:'<g fill="none" stroke="#241b14" stroke-width="2.6"><circle cx="46" cy="71" r="10.5"/><circle cx="74" cy="71" r="10.5"/><path d="M56 70h8"/></g>',
 hgl:'<g fill="none" stroke="#c9a14a" stroke-width="2"><path d="M37 68h18v6a9 9 0 0 1-18 0zM65 68h18v6a9 9 0 0 1-18 0z"/></g>',
 beardW:'<path d="M29 74Q32 118 60 124Q88 118 91 74Q78 98 60 98Q42 98 29 74Z" fill="#f4f1ea"/>',
 beardD:'<path d="M29 74Q32 116 60 120Q88 116 91 74Q78 96 60 96Q42 96 29 74Z" fill="#33241b"/>',
 hatw:'<path d="M30 46Q60 32 90 46V32Q60 14 30 32Z" fill="#2f2a63"/><path d="M30 44Q60 30 90 44" stroke="#d8b46a" stroke-width="2" fill="none"/>',
 sort:'<path d="M18 46Q60 28 102 46Q60 54 18 46Z" fill="#5f4026"/><path d="M34 40L72 -10Q78 16 88 38Z" fill="#76512f"/>',
 dragon:'<g fill="#4c9a56"><ellipse cx="60" cy="25" rx="16" ry="8"/><circle cx="76" cy="16" r="7"/><path d="M44 20l-8-10 14 4zM60 16l0-10 8 8z"/></g><circle cx="78" cy="14" r="1.6" fill="#111"/>',
 scarfR:'<path d="M42 98Q60 110 78 98L80 110Q60 122 40 110Z" fill="#a31d2a"/><path d="M44 104Q60 116 76 104" stroke="#e0a82e" stroke-width="3" fill="none"/><rect x="74" y="106" width="11" height="32" rx="3" fill="#a31d2a"/><path d="M74 118h11M74 126h11" stroke="#e0a82e" stroke-width="3"/>',
 scarfG:'<path d="M42 98Q60 110 78 98L80 110Q60 122 40 110Z" fill="#1f6b43"/><path d="M44 104Q60 116 76 104" stroke="#cfd4d9" stroke-width="3" fill="none"/>',
 ears:'<path d="M28 66L4 50L22 82Z M92 66L116 50L98 82Z" fill="#e3b890"/>',
 wand:'<path d="M92 126L116 92" stroke="#5a3a1e" stroke-width="3.2" stroke-linecap="round"/>',
 owl:'<g><ellipse cx="100" cy="104" rx="11" ry="16" fill="#f6f3ec"/><circle cx="96" cy="97" r="3.4" fill="#e8b84a"/><circle cx="105" cy="97" r="3.4" fill="#e8b84a"/><path d="M100 100l-2 4h4z" fill="#c98a2e"/></g>',
 mane:'<circle cx="60" cy="64" r="48" fill="#d99a3c"/><circle cx="20" cy="28" r="11" fill="#d99a3c"/><circle cx="100" cy="28" r="11" fill="#d99a3c"/><circle cx="20" cy="28" r="5" fill="#a8641f"/><circle cx="100" cy="28" r="5" fill="#a8641f"/>',
 paper:'<g transform="rotate(-4 60 128)"><rect x="26" y="112" width="68" height="36" rx="2" fill="#efe7d0"/><path d="M32 120h56M32 127h56M32 134h36" stroke="#8f846c" stroke-width="2"/></g>',
 poster:'<rect x="4" y="-8" width="112" height="152" rx="4" fill="#e6d3a2"/><text x="60" y="12" text-anchor="middle" font-size="14" font-family="Cinzel,serif" font-weight="700" fill="#4a3520">WANTED</text>',
 plac:'<rect x="30" y="112" width="60" height="24" rx="2" fill="#272727"/><text x="60" y="129" text-anchor="middle" font-size="14" font-family="Cinzel,serif" fill="#f1ead6">0427</text>',
 snitch:'<g><circle cx="106" cy="72" r="5" fill="#e8b84a"/><path d="M101 70L86 58L97 74ZM111 70L124 58L115 74Z" fill="#f6e3a0"/></g>',
 lamp:'<g><rect x="10" y="108" width="15" height="22" rx="3" fill="#272727"/><rect x="13" y="112" width="9" height="14" fill="#ffd166"/><path d="M13 108Q17 98 22 108" stroke="#272727" fill="none" stroke-width="2"/></g>',
 book:'<g><rect x="8" y="114" width="26" height="17" rx="2" fill="#7b5a38"/><rect x="10" y="116" width="22" height="13" fill="#f1ead6"/></g>',
 horn:'<path d="M82 142Q104 128 112 106L117 110Q113 138 88 154Z" fill="#9a6b3c"/>',
 sock:'<path d="M86 116h11v20a5.5 5.5 0 0 1-11 0z" fill="#7aa36a"/>',
 chess:'<path d="M82 140h26v-8l-6-4 6-14-12-10-12 10 5 10-7 6z" fill="#a3a9b1"/><circle cx="96" cy="112" r="1.8" fill="#333"/>'
};
const CH=[
 {n:"Dumbledore",hair:"#f1efe8",robe:"#5a3f8f",front:['beardW','hgl','hatw','wand'],s:"Semoga kebijaksanaan dan ketenangan selalu menyertaimu."},
 {n:"Hagrid",hair:"#3b2a20",m:1,robe:"#6b6a3f",front:['beardD','dragon'],base:"#5b7a3e",s:"Semoga hatimu sehangat dan sebesar miliknya."},
 {n:"Topi Seleksi",hair:"#4a3326",long:1,robe:"#2a2a33",front:['gl','sort'],s:"Apa pun pilihanmu, semoga selalu tepat."},
 {n:"Draco",hair:"#f0d878",m:1,robe:"#23232b",front:['scarfG','lamp','wand'],base:"#5b7a3e",s:"Semoga langkahmu selalu penuh percaya diri."},
 {n:"Snape",hair:"#15151a",long:1,robe:"#17171d",front:['book'],base:"#3a3a44",s:"Semoga hari-harimu tenang dan penuh ramuan keberuntungan."},
 {n:"Dobby",hair:"#a87a50",m:1,sk:"#e3b890",robe:"#8d7c52",back:['ears'],front:['sock','book'],s:"Semoga kebahagiaan selalu datang tanpa diminta."},
 {n:"Buronan Azkaban",hair:"#3a2a22",m:1,robe:"#222",back:['poster'],front:['plac'],base:"#9a7a50",s:"Semoga kamu selalu bebas menjadi dirimu sendiri."},
 {n:"Harry Quidditch",hair:"#2a1d18",m:1,robe:"#8e1f2c",front:['gl','snitch'],base:"#5b7a3e",s:"Semoga semua targetmu tertangkap tepat waktu."},
 {n:"Ron & Catur Sihir",hair:"#c9622a",m:1,robe:"#4d5f9e",front:['chess'],base:"#777",s:"Semoga setiap langkahmu selalu strategis dan menyenangkan."},
 {n:"Luna",hair:"#e8c878",long:1,robe:"#3a3a55",back:['mane'],front:['paper'],base:"#444",s:"Semoga imajinasimu terus tumbuh dan tak pernah biasa."},
 {n:"Harry & Hedwig",hair:"#2a1d18",m:1,robe:"#1c1c24",front:['gl','scarfR','owl'],s:"Semoga selalu ada kabar baik yang terbang menghampirimu."},
 {n:"Penyihir Tanduk",hair:"#f3ead4",m:1,robe:"#4d6b5e",front:['horn','wand'],base:"#2a2a33",s:"Semoga hidupmu penuh kejutan kecil yang manis."}
];
function fig(c){
 const sk=c.sk||SK,h=c.hair,L=k=>(c[k]||[]).map(x=>A[x]).join('');
 const eye=x=>`<ellipse cx="${x}" cy="71" rx="7.5" ry="7" fill="#fff"/><circle cx="${x}" cy="72.5" r="5" fill="#5d86c4"/><circle cx="${x}" cy="72.5" r="2.4" fill="#16233f"/><circle cx="${x+1.8}" cy="70.5" r="1.2" fill="#fff"/><path d="M${x-8.5} 72Q${x} 64 ${x+8.5} 72V58H${x-8.5}Z" fill="${sk}"/><path d="M${x-8.5} 72Q${x} 64.5 ${x+8.5} 72" fill="none" stroke="#3a2a20" stroke-width="2" stroke-linecap="round"/>`;
 const back=c.long?`<path d="M24 60Q14 112 34 124L42 82ZM96 60Q106 112 86 124L78 82Z" fill="${h}"/>`:'';
 const tufts=c.m?`<g fill="${h}"><circle cx="36" cy="32" r="10"/><circle cx="60" cy="24" r="11"/><circle cx="84" cy="32" r="10"/><circle cx="26" cy="48" r="8"/><circle cx="94" cy="48" r="8"/></g>`:'';
 return `<svg viewBox="-4 -14 128 176" aria-hidden="true">${L('back')}${back}
 <ellipse cx="60" cy="152" rx="46" ry="7" fill="${c.base||'#6b4f33'}"/><ellipse cx="60" cy="150" rx="46" ry="6" fill="#fff" opacity=".08"/>
 <rect x="34" y="140" width="20" height="11" rx="5" fill="#2a2230"/><rect x="66" y="140" width="20" height="11" rx="5" fill="#2a2230"/>
 <path d="M36 100Q60 92 84 100L93 144H27Z" fill="${c.robe}"/><path d="M50 97L60 114L70 97Z" fill="#f4f1ea"/>
 <circle cx="30" cy="126" r="6" fill="${sk}"/><circle cx="90" cy="126" r="6" fill="${sk}"/>
 <circle cx="60" cy="64" r="34" fill="${sk}"/>${eye(46)}${eye(74)}
 <circle cx="37" cy="85" r="5" fill="#f08a78" opacity=".45"/><circle cx="83" cy="85" r="5" fill="#f08a78" opacity=".45"/>
 <path d="M55 89q5 3 10 0" stroke="#8a5a4a" fill="none" stroke-width="2" stroke-linecap="round"/>
 <path d="M37 59l16 4M83 59l-16 4" stroke="#3a2a20" stroke-width="2.4" stroke-linecap="round"/>
 ${tufts}<path d="M25 68C20 34 40 25 60 25C82 25 100 36 95 68C88 52 80 46 60 48C42 46 32 52 25 68Z" fill="${h}"/>${L('front')}</svg>`;
}

/* ---------- bintang + percikan sihir ---------- */
const sc=$('#stars'),sx=sc.getContext('2d'),fc=$('#fx'),fx=fc.getContext('2d');let W,H,S=[],P=[];
function size(){W=innerWidth;H=innerHeight;[sc,fc].forEach(c=>{c.width=W;c.height=H});S=Array.from({length:130},()=>({x:rnd(0,W),y:rnd(0,H*.8),r:rnd(.4,1.5),p:rnd(0,6)}))}
addEventListener('resize',size);size();
const PAL=['#e6c27a','#f1dba4','#ffffff','#b9a4ff','#9ec5ff'];
function burst(x,y,n=24,sp=6){for(let i=0;i<n;i++){const a=rnd(0,6.28),s=sp*rnd(.3,1);P.push({x,y,vx:Math.cos(a)*s,vy:Math.sin(a)*s-2,l:1,d:rnd(.008,.02),r:rnd(2,4.5),c:PAL[i%5]})}}
(function loop(t){
 sx.clearRect(0,0,W,H);S.forEach(s=>{sx.globalAlpha=.3+.6*Math.abs(Math.sin(t/1200+s.p));sx.fillStyle='#fff';sx.beginPath();sx.arc(s.x,s.y,s.r,0,6.28);sx.fill()});
 fx.clearRect(0,0,W,H);
 for(let i=P.length-1;i>=0;i--){const p=P[i];p.vy+=.1;p.x+=p.vx;p.y+=p.vy;p.vx*=.99;p.l-=p.d;if(p.l<=0){P.splice(i,1);continue}
  fx.globalAlpha=p.l;fx.fillStyle=p.c;fx.shadowColor=p.c;fx.shadowBlur=8;fx.beginPath();fx.arc(p.x,p.y,p.r,0,6.28);fx.fill()}
 fx.shadowBlur=0;requestAnimationFrame(loop)})(0);
function spell(t,x,y){const e=document.createElement('div');e.className='spell';e.textContent=t;e.style.left=x+'px';e.style.top=y+'px';document.body.appendChild(e);setTimeout(()=>e.remove(),1700)}
addEventListener('pointerdown',e=>{burst(e.clientX,e.clientY,8,3);if(Math.random()<.12&&!e.target.closest('button'))spell(['Lumos!','Expelliarmus!','Stupefy!','Revelio!'][Math.floor(rnd(0,4))],e.clientX,e.clientY)});
addEventListener('pointermove',e=>{
 if(Math.random()<.6)P.push({x:e.clientX,y:e.clientY,vx:rnd(-.6,.6),vy:rnd(-.8,.2),l:.9,d:.03,r:rnd(1.5,3.5),c:PAL[Math.floor(rnd(0,5))]});
 const px=e.clientX/W-.5,py=e.clientY/H-.5;document.documentElement.style.setProperty('--px',px.toFixed(3));
 const t=$('#env');if(t&&cur===0){t.style.setProperty('--ry',(px*14).toFixed(1)+'deg');t.style.setProperty('--rx',(-py*10).toFixed(1)+'deg')}});

/* ---------- langit Hogwarts ---------- */
const OWL='<svg viewBox="0 0 90 50" aria-hidden="true"><g fill="#0c0b1f"><ellipse cx="45" cy="28" rx="12" ry="9"/><circle cx="57" cy="21" r="7"/><path d="M60 16l3-7 3 8zM53 16l-1-7 5 5z"/><path class="wing" d="M45 24C30 2 10 4 2 14C18 14 30 20 40 30Z"/><path class="wing" d="M47 24C62 2 82 4 90 14C74 14 62 20 52 30Z"/></g><circle cx="59" cy="20" r="1.6" fill="#ffd166"/></svg>';
const SNT='<svg viewBox="0 0 50 24"><g fill="#f6e3a0"><path class="wing" d="M21 12L2 3L16 15Z"/><path class="wing" d="M29 12L48 3L34 15Z"/></g><circle cx="25" cy="13" r="6" fill="#e8b84a"/></svg>';
(function(){const s=$('#sky');
 const CAS='M0 220V176H44V132L56 104 68 132V176H110V96H128V66L142 34 156 66V96H176V176H214V124H244V92L258 66 272 92V124H300V176H334V70L348 24 362 70V176H392V110H428V84L442 54 456 84V110H488V176H530V136L544 108 558 136V176H596V90H616V70L630 42 644 70V90H664V176H706V150H752V176H800V220Z';
 let h='<div class="moon"></div><svg class="castle far" viewBox="0 0 800 220" preserveAspectRatio="xMidYMax slice"><path fill="#1a1a48" transform="translate(-30 -18) scale(1.04)" d="'+CAS+'"/></svg><svg class="castle near" viewBox="0 0 800 220" preserveAspectRatio="xMidYMax slice"><path fill="#06061a" d="'+CAS+'"/><path fill="#06061a" d="M0 190H800V220H0Z"/><g fill="#ffd9a0">';
 [[136,108],[348,80],[442,98],[630,98],[258,104],[56,146],[544,146],[722,162],[300,150],[500,150],[196,150]].forEach(p=>h+=`<rect class="w" x="${p[0]}" y="${p[1]}" width="5" height="9" style="animation-delay:${-rnd(0,3).toFixed(1)}s"/>`);
 h+='</g></svg><div class="mist"></div><div class="mist b"></div>';
 for(let i=0;i<10;i++)h+=`<div class="fcandle" style="--x:${rnd(3,94).toFixed(0)}%;--y:${rnd(6,48).toFixed(0)}%;--d:${rnd(3,6).toFixed(1)}s;--dl:${-rnd(0,5).toFixed(1)}s"><svg viewBox="0 0 16 60"><path class="fl" d="M8 0C12 8 12 14 8 17 4 14 4 8 8 0Z" fill="#ffb938"/><rect x="3" y="18" width="10" height="40" rx="2" fill="#fbf3e6"/></svg></div>`;
 s.innerHTML=h;
 const add=(c,html,css,ms)=>{const o=document.createElement('div');o.className=c;o.style.cssText=css;o.innerHTML=html;s.appendChild(o);setTimeout(()=>o.remove(),ms)};
 const owl=()=>add('owlf',OWL,`--y:${rnd(6,36).toFixed(0)}vh;--t:${rnd(11,16).toFixed(0)}s`,16500);
 setTimeout(owl,800);setInterval(owl,9000);
 setInterval(()=>add('snitch',SNT,`--y:${rnd(12,45).toFixed(0)}vh`,7200),16000);setTimeout(()=>add('snitch',SNT,'--y:25vh',7200),5000);
 setInterval(()=>add('shoot','',`--x:${rnd(5,60).toFixed(0)}%;--y:${rnd(3,30).toFixed(0)}%`,1200),3500)})();

/* ---------- mesin adegan ---------- */
$('#steps').innerHTML='<i></i>'.repeat(5);
let cur=0;
function go(n){cur=n;const fl=$('#flash');fl.classList.remove('go');void fl.offsetWidth;fl.classList.add('go');
 $$('.scene').forEach((s,i)=>s.classList.toggle('on',i===n));$$('#steps i').forEach((d,i)=>d.classList.toggle('on',i<=Math.min(n,4)));
 if(n===1)showLetter();if(n===2)setupCake();if(n===3)setupPick();if(n===4)setupGifts();if(n===5)setupEnd()}
const show=id=>$(id).classList.add('show');

/* 1. surat */
$('#addr').innerHTML=`${C.name}<small>Aula Besar, Kastil Hogwarts</small>`;
$('#seal').onclick=e=>{const r=e.currentTarget.getBoundingClientRect();e.currentTarget.classList.add('crack');$('#h0').style.opacity=0;
 burst(r.left+37,r.top+37,34,6);spell('Alohomora!',r.left+37,r.top-20);
 setTimeout(()=>$('#env').classList.add('open'),350);setTimeout(()=>$('#env').classList.add('gone'),1900);setTimeout(()=>go(1),2500)};
function showLetter(){
 let k=0;const words=t=>T(t).split(' ').map(w=>`<span class="wd" style="animation-delay:${(.6+k++*.11).toFixed(2)}s">${w}</span>`).join(' ');
 $('#letter').innerHTML=`<div class="lh"><svg><use href="#crest"/></svg><b>SEKOLAH SIHIR HOGWARTS</b><em>Kepala Sekolah: Albus Dumbledore</em></div><h2><br></h2>`+C.message.map(m=>`<p>${words(m)}</p>`).join('')+`<p class="sig">${words(C.from)}</p>`;
 $('#letter').scrollTop=0;setTimeout(()=>show('#n1'),(.6+k*.11)*1000+900)}
$('#n1').onclick=()=>go(2);

/* 2. lilin */
let blown=false,mic=null,nl=0;
function setupCake(){
 blown=false;nl=0;$('#wish').textContent='';$('#n2').classList.remove('show');$('#blowRow').style.display='none';$('#lightRow').style.display='';$('#chint').textContent='ketuk tiap lilin untuk menyalakannya dengan mantra';$('#cake').classList.remove('out');
 let c='';const n=C.candles,sp=Math.min(46,150/n);
 for(let i=0;i<n;i++){const x=160+(i-(n-1)/2)*sp;
  c+=`<g class="cd" transform="translate(${x} 0)"><rect x="-22" y="20" width="44" height="90" fill="transparent"/><circle class="glow" cy="52" r="30" fill="#ffd166" opacity=".22"/><rect x="-5" y="62" width="10" height="42" rx="2" fill="#f5eddc"/><path d="M-5 74l10-5M-5 88l10-5" stroke="#d8b46a" stroke-width="3"/><path d="M0 62v-5" stroke="#333" stroke-width="2"/>
  <path class="flame" d="M0 28C8 40 8 52 0 57-8 52-8 40 0 28Z" fill="#ffb938"/><path class="flame" d="M0 40C4 46 4 52 0 54-4 52-4 46 0 40Z" fill="#fff4c2"/><path class="smoke" d="M0 54q-7-9 0-18t0-16" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"/></g>`}
 $('#cake').innerHTML=`<ellipse cx="160" cy="232" rx="140" ry="12" fill="#1d2350"/><rect x="50" y="160" width="220" height="70" rx="10" fill="#7d1d2b"/><rect x="78" y="104" width="164" height="60" rx="10" fill="#93243a"/>
 <path d="M50 172Q50 156 66 156H254Q270 156 270 172Z" fill="#f5eddc"/><path d="M78 116Q78 100 94 100H226Q242 100 242 116Z" fill="#f5eddc"/>
 <path d="M50 196H270M78 138H242" stroke="#e6c27a" stroke-width="2.5"/><path d="M160 190l6 10-6 10-6-10z" fill="#e6c27a"/>${c}`;$$('.cd').forEach(g=>g.onclick=()=>lightC(g))}
function lightC(g){if(g.classList.contains('lit')||blown)return;g.classList.add('lit');nl++;const r=g.getBoundingClientRect();burst(r.left+r.width/2,r.top+10,14,4);spell('Incendio!',r.left+r.width/2,r.top-10);
 if(nl>=C.candles){$('#lightRow').style.display='none';$('#blowRow').style.display='';$('#chint').textContent='buat permohonan dalam hati, lalu padamkan'}}
$('#lightAll').onclick=()=>$$('.cd').forEach((g,i)=>setTimeout(()=>lightC(g),i*260));
function stopMic(){if(mic){mic.getTracks().forEach(t=>t.stop());mic=null}}
function blow(){if(blown)return;blown=true;stopMic();spell('Nox!',W/2,H*.4);$('#chint').textContent='';$('#cake').classList.add('out');$('#blowRow').style.display='none';
 setTimeout(()=>{$('#wish').textContent=C.wish;for(let i=0;i<5;i++)setTimeout(()=>burst(rnd(W*.15,W*.85),rnd(H*.15,H*.45),32,7),i*200);show('#n2')},600)}
$('#blow').onclick=blow;$('#n2').onclick=()=>go(3);
$('#mic').onclick=async()=>{try{mic=await navigator.mediaDevices.getUserMedia({audio:true});const ac=new AudioContext(),an=ac.createAnalyser();an.fftSize=512;ac.createMediaStreamSource(mic).connect(an);
 const b=new Uint8Array(512);let hot=0;$('#wish').textContent='tiup ke arah mikrofon…';
 (function l(){if(!mic)return;an.getByteTimeDomainData(b);let s=0;b.forEach(v=>{const d=(v-128)/128;s+=d*d});hot=Math.sqrt(s/512)>.16?hot+1:Math.max(0,hot-1);hot>7?blow():requestAnimationFrame(l)})()}
 catch(e){$('#wish').textContent='mikrofon tidak tersedia, gunakan tombol Padamkan'}};

/* 3. pilih */
let sel=[];
function setupPick(){sel=[];$('#grid').innerHTML=CH.map((c,i)=>`<button class="card" data-i="${i}" style="--i:${i}">${fig(c)}<span>${c.n}</span><b class="n"></b></button>`).join('');
 $$('.card').forEach(b=>{b.onclick=()=>pickCard(b);b.onpointerenter=()=>{const r=b.getBoundingClientRect();burst(r.left+r.width/2,r.top+24,5,2)}});upd()}
function pickCard(b){const i=+b.dataset.i,k=sel.indexOf(i);
 if(k>-1)sel.splice(k,1);else if(sel.length<3){sel.push(i);const r=b.getBoundingClientRect();burst(r.left+r.width/2,r.top+r.height/2,12,4);spell(['Accio!','Wingardium!','Alohomora!'][sel.length-1],r.left+r.width/2,r.top)}
 else{b.classList.remove('shake');void b.offsetWidth;b.classList.add('shake');return}
 upd()}
function upd(){$$('.card').forEach(b=>{const k=sel.indexOf(+b.dataset.i);b.classList.toggle('sel',k>-1);b.querySelector('.n').textContent=k+1});
 $('#cnt').textContent=sel.length<3?`${sel.length} dari 3 dipilih`:'Pilihan lengkap';$('#n3').disabled=sel.length<3}
$('#n3').onclick=()=>go(4);

/* 4. hadiah */
function setupGifts(){let o=0;$('#n4').classList.remove('show');$('#gh').textContent='ketuk tiap kotak untuk membukanya';
 $('#gifts').innerHTML=sel.map(i=>{const c=CH[i];return `<div class="gift"><div class="gi"><div class="gf"><svg viewBox="0 0 80 80" width="70%" aria-hidden="true"><rect x="10" y="30" width="60" height="42" rx="4" fill="#7d1d2b" stroke="#e6c27a" stroke-width="2"/><rect x="6" y="22" width="68" height="14" rx="3" fill="#93243a" stroke="#e6c27a" stroke-width="2"/><path d="M40 22V72" stroke="#e6c27a" stroke-width="6"/><path d="M40 22Q24 6 22 16T40 22Q56 6 58 16T40 22" fill="none" stroke="#e6c27a" stroke-width="3"/></svg><span class="sub" style="font-size:14px">ketuk</span></div><div class="gb">${fig(c)}<b>${c.n}</b><small>${c.s}</small></div></div></div>`}).join('');
 $$('.gift').forEach(g=>g.onclick=()=>{if(g.classList.contains('open'))return;g.classList.add('open');const r=g.getBoundingClientRect();spell(['Alohomora!','Wingardium Leviosa!','Lumos Maxima!'][o],r.left+r.width/2,r.top);for(let q=0;q<3;q++)setTimeout(()=>burst(r.left+r.width/2,r.top+r.height/2,20,7),q*220);
  if(++o===3){$('#gh').textContent='tiga hadiah, untukmu';setTimeout(()=>show('#n4'),900)}})}
$('#n4').onclick=()=>go(5);

/* 5. penutup */
let fw=null,lt=null;
function setupEnd(){$('#f1').innerHTML=`Selamat Ulang Tahun <br> Monyet Latimojong`;$('#f2').textContent=C.closing;
 clearInterval(lt);lt=setInterval(()=>{const l=document.createElement('i');l.className='lant';l.style.cssText=`--x:${rnd(3,94).toFixed(0)}%;--t:${rnd(7,11).toFixed(0)}s;--dx:${rnd(-60,60).toFixed(0)}px`;document.body.appendChild(l);setTimeout(()=>l.remove(),11500)},700);
 clearInterval(fw);const f=()=>burst(rnd(W*.15,W*.85),rnd(H*.1,H*.45),34,7);
 for(let i=0;i<4;i++)setTimeout(f,400+i*350);fw=setInterval(f,1500)}
$('#pat').onclick=()=>{spell('Expecto Patronum!',W/2,H*.3);let k=0;const t=setInterval(()=>{const x=k*W/70,y=H*.55+Math.sin(k/6)*H*.08;for(let i=0;i<5;i++)P.push({x,y:y+rnd(-14,14),vx:rnd(-.5,.5),vy:rnd(-1,.2),l:1,d:rnd(.01,.02),r:rnd(2,5),c:['#e8f6ff','#bfe3ff','#fff'][i%3]});if(++k>70)clearInterval(t)},25)};
$('#again').onclick=()=>{clearInterval(fw);clearInterval(lt);blown=false;$('#env').classList.remove('open','gone');$('#seal').classList.remove('crack');$('#h0').style.opacity=1;go(0)};
})();
</script>
</body>
</html>
