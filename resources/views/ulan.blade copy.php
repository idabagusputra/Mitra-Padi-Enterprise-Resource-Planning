<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title>Selamat Ulang Tahun 🎂</title>
<meta name="theme-color" content="#a30f1c">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Great+Vibes&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
<style>
:root{
  --red:#c1121f; --red2:#a30f1c; --deep:#6d0912; --cream:#fbf3e6; --gold:#e0a93b; --rose:#ffb3c1;
  --script:'Great Vibes','Brush Script MT','Segoe Script',cursive;
  --hand:'Caveat','Segoe Print','Bradley Hand',cursive;
}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
html,body{height:100%;overflow:hidden;background:var(--deep);color:#fff;
  font-family:'Quicksand',system-ui,-apple-system,'Segoe UI',sans-serif;
  user-select:none;-webkit-user-select:none;touch-action:manipulation}
button{font:inherit;color:inherit;background:none;border:0;cursor:pointer}
svg{display:block}
.script{font-family:var(--script);font-weight:400}

/* ---------- scene system ---------- */
.scene{position:fixed;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;
  opacity:0;visibility:hidden;transform:scale(1.05);filter:blur(10px);overflow:hidden;
  transition:opacity .7s ease,transform .7s ease,filter .7s ease,visibility 0s .7s}
.scene.active{opacity:1;visibility:visible;transform:none;filter:none;
  transition:opacity .7s ease .08s,transform .8s cubic-bezier(.2,.8,.2,1) .08s,filter .7s ease .08s,visibility 0s}
#fx{position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:50}
.top{position:absolute;top:calc(env(safe-area-inset-top) + 58px);left:0;right:0;text-align:center;
  font-size:clamp(30px,8vw,54px);line-height:1.1;padding:0 16px;text-shadow:0 3px 18px rgba(0,0,0,.25)}
.hint{position:absolute;bottom:calc(env(safe-area-inset-bottom) + 16px);left:0;right:0;text-align:center;
  font-family:var(--hand);font-size:clamp(20px,5vw,26px);opacity:.95;animation:bob 1.8s ease-in-out infinite;pointer-events:none;z-index:6}
@keyframes bob{50%{transform:translateY(-6px)}}

/* ---------- UI chrome ---------- */
.pill{position:fixed;z-index:60;top:calc(env(safe-area-inset-top) + 12px);height:40px;padding:0 16px;border-radius:99px;
  background:rgba(255,255,255,.16);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.35);font-weight:700;font-size:14px;display:flex;align-items:center;gap:6px;
  transition:transform .25s,opacity .3s}
.pill:active{transform:scale(.94)}
#back{left:12px;opacity:0;pointer-events:none;transform:translateX(-14px)}
#back.show{opacity:1;pointer-events:auto;transform:none}
#snd{right:12px;width:40px;padding:0;justify-content:center;font-size:18px;opacity:0;pointer-events:none}
#snd.show{opacity:1;pointer-events:auto}
.cta{position:relative;margin-top:22px;padding:14px 30px;border-radius:99px;background:linear-gradient(180deg,#fff,#ffe9d2);
  color:var(--red2);font-weight:700;font-size:clamp(15px,4vw,18px);letter-spacing:.3px;box-shadow:0 10px 30px rgba(0,0,0,.3);
  transition:transform .25s}
.cta:hover{transform:scale(1.06)}
.cta:active{transform:scale(.95)}
.cta::before{content:"";position:absolute;inset:-6px;border-radius:99px;border:2px solid rgba(255,255,255,.7);animation:ring 1.8s ease-out infinite}
@keyframes ring{0%{transform:scale(.92);opacity:.9}100%{transform:scale(1.25,1.5);opacity:0}}

/* ---------- 1. ENVELOPE ---------- */
#s-env{background:radial-gradient(circle at 50% 38%,#fffaf2 0%,#f7ead7 60%,#eed9bd 100%);color:var(--red)}
#s-env::before{content:"";position:absolute;inset:0;opacity:.5;pointer-events:none;
  background-image:radial-gradient(rgba(193,18,31,.12) 1.4px,transparent 1.6px);background-size:26px 26px}
.env-title{position:absolute;top:calc(env(safe-area-inset-top) + 7vh);font-size:clamp(40px,11vw,76px);color:var(--red);
  text-shadow:2px 3px 0 rgba(193,18,31,.12);animation:float 4s ease-in-out infinite}
@keyframes float{50%{transform:translateY(-8px) rotate(-1deg)}}
.env-wrap{position:relative;width:min(82vw,440px);aspect-ratio:3/2;margin-top:6vh;
  filter:drop-shadow(0 24px 26px rgba(120,0,0,.32));perspective:1100px;transition:transform 1s cubic-bezier(.6,0,.4,1),opacity .9s}
.env{position:relative;width:100%;height:100%;animation:float 4.5s ease-in-out infinite}
.env-back{position:absolute;inset:0;background:#8f0b17;border-radius:6px}
.env-card{position:absolute;left:5%;right:5%;top:6%;bottom:4%;background:#fffaf0;border-radius:4px;z-index:2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--red);
  box-shadow:inset 0 0 0 5px #fffaf0,inset 0 0 0 6px rgba(193,18,31,.25);transition:transform 1.1s cubic-bezier(.3,1.2,.4,1)}
.env-card b{font-family:var(--script);font-weight:400;font-size:clamp(26px,7vw,44px)}
.env-card small{font-family:var(--hand);font-size:clamp(16px,4vw,22px);color:#7a2a2f}
.env-front{position:absolute;inset:0;z-index:3;border-radius:6px;
  background:linear-gradient(160deg,#d52434,#b50f1d);clip-path:polygon(0 0,50% 56%,100% 0,100% 100%,0 100%)}
.env-front::after{content:"";position:absolute;inset:0;background:linear-gradient(to top right,transparent 49.5%,rgba(0,0,0,.18) 50%,transparent 50.6%),linear-gradient(to top left,transparent 49.5%,rgba(0,0,0,.18) 50%,transparent 50.6%);opacity:.7}
.env-flap{position:absolute;left:0;right:0;top:0;height:100%;z-index:5;transform-origin:50% 0;
  background:linear-gradient(180deg,#c91c2b,#a70e1b);clip-path:polygon(0 0,100% 0,50% 58%);
  transition:transform .9s cubic-bezier(.5,0,.3,1),z-index 0s .45s}
.env-wrap.open .env-flap{transform:rotateX(180deg);z-index:1}
.env-wrap.rise .env-card{transform:translateY(-62%)}
.env-wrap.rise .env-front{z-index:3}
.env-wrap.fly{transform:scale(7) translateY(-8%);opacity:0}
.seal{position:absolute;left:50%;top:58%;width:23%;aspect-ratio:1;z-index:9;transform:translate(-50%,-50%);
  animation:pulse 1.6s ease-in-out infinite;transition:transform .3s}
.seal svg{width:100%;height:100%;filter:drop-shadow(0 4px 6px rgba(0,0,0,.4))}
.seal:hover{transform:translate(-50%,-50%) scale(1.12)}
.seal.crack{animation:crack .7s forwards}
@keyframes pulse{50%{transform:translate(-50%,-50%) scale(1.08)}}
@keyframes crack{30%{transform:translate(-50%,-50%) scale(1.35) rotate(8deg)}100%{transform:translate(-50%,-50%) scale(.2) rotate(-30deg);opacity:0}}
.env-hint{position:absolute;bottom:calc(env(safe-area-inset-bottom) + 9vh);font-family:var(--hand);font-size:clamp(20px,5vw,28px);
  color:#8f0b17;display:flex;align-items:center;gap:8px;animation:bob 1.6s ease-in-out infinite}
.env-hint i{font-style:normal;display:inline-block;animation:tap 1.6s ease-in-out infinite}
@keyframes tap{50%{transform:translateY(-8px) scale(1.15)}}
.doodle{position:absolute;opacity:.9;pointer-events:none}
.doodle.g{left:5%;bottom:5%;width:clamp(70px,16vw,130px);animation:wob 3s ease-in-out infinite}
.doodle.b{right:5%;bottom:7%;width:clamp(50px,11vw,90px);animation:wob 3.6s ease-in-out infinite reverse}
@keyframes wob{50%{transform:rotate(6deg) translateY(-6px)}}

/* ---------- 2. GREETING ---------- */
#s-greet{background:radial-gradient(ellipse at 50% 28%,#e5283a 0%,#a50f1d 52%,#560510 100%)}
.hero{position:absolute;inset:0;background:var(--hero) center/cover;opacity:0;filter:blur(2px) brightness(.5) saturate(1.15);transform:scale(1.08);animation:kb 16s ease-in-out infinite alternate}
.hero.on{opacity:1}
@keyframes kb{to{transform:scale(1.2) translate(-2%,-2%)}}
.bokeh{position:absolute;border-radius:50%;pointer-events:none;mix-blend-mode:screen;animation:drift linear infinite}
@keyframes drift{from{transform:translateY(20vh) scale(.7);opacity:0}15%{opacity:.5}to{transform:translateY(-120vh) scale(1.2);opacity:0}}
.g-box{position:relative;z-index:3;text-align:center;padding:0 18px}
.g-date{font-size:12px;letter-spacing:5px;text-transform:uppercase;opacity:.85;margin-bottom:10px}
.g-h{font-size:clamp(54px,16vw,132px);line-height:1;text-shadow:0 6px 30px rgba(0,0,0,.35)}
.g-n{font-size:clamp(46px,14vw,116px);line-height:1.05;color:#ffe3a3;text-shadow:0 0 26px rgba(255,200,100,.65),0 6px 20px rgba(0,0,0,.3);word-break:break-word}
.ltr{display:inline-block;opacity:0;transform:translateY(34px) scale(.5) rotate(10deg)}
.scene.active .ltr{animation:ltr .9s cubic-bezier(.2,1.5,.4,1) calc(var(--i)*.06s + .35s) forwards}
@keyframes ltr{to{opacity:1;transform:none}}
.g-sub{margin-top:14px;font-family:var(--hand);font-size:clamp(20px,5.2vw,30px);opacity:0;animation:fade 1s 1.8s forwards}
.g-box .cta{opacity:0;animation:fade 1s 2.4s forwards}
.scene:not(.active) .g-sub,.scene:not(.active) .g-box .cta{animation:none}
@keyframes fade{to{opacity:1}}
.balloon{position:absolute;bottom:0;width:var(--w);height:calc(var(--w)*1.25);border-radius:50% 50% 48% 48%/55% 55% 45% 45%;z-index:2;cursor:pointer;
  background:radial-gradient(circle at 32% 28%,rgba(255,255,255,.75) 0,rgba(255,255,255,0) 18%),radial-gradient(circle at 50% 40%,var(--c),var(--c2));
  box-shadow:inset -8px -10px 18px rgba(0,0,0,.18);animation:rise var(--d) linear var(--dl) infinite;left:var(--x)}
.balloon::before{content:"";position:absolute;left:50%;bottom:-6px;width:10px;height:10px;background:var(--c2);transform:translateX(-50%) rotate(45deg);border-radius:2px}
.balloon::after{content:"";position:absolute;left:50%;top:100%;width:1.5px;height:90px;background:rgba(255,255,255,.55);transform-origin:top;animation:sway 3s ease-in-out infinite}
@keyframes rise{from{transform:translateY(30vh) rotate(-4deg)}50%{transform:translateY(-60vh) rotate(5deg)}to{transform:translateY(-135vh) rotate(-4deg)}}
@keyframes sway{50%{transform:rotate(8deg)}}
.balloon.pop{animation:none;transform:scale(1.4);opacity:0;transition:.15s}

/* ---------- 3. MENU ---------- */
#s-menu{background:radial-gradient(circle at 50% 0%,#dc2231 0%,#a50f1d 55%,#6d0912 100%)}
#s-menu::before{content:"";position:absolute;inset:0;pointer-events:none;opacity:.18;
  background-image:radial-gradient(circle,#fff 1.2px,transparent 1.6px);background-size:30px 30px}
.m-in{position:relative;text-align:center;width:min(94vw,860px);padding:0 4px}
.m-t{font-size:clamp(40px,11vw,76px);text-shadow:0 4px 20px rgba(0,0,0,.3)}
.m-s{font-family:var(--hand);font-size:clamp(19px,5vw,26px);opacity:.9;margin-top:2px}
.items{display:grid;grid-template-columns:repeat(2,1fr);gap:clamp(14px,3.5vh,30px) 10px;margin-top:clamp(16px,4vh,34px)}
@media(min-width:760px){.items{grid-template-columns:repeat(4,1fr)}}
.item{position:relative;display:flex;flex-direction:column;align-items:center;gap:8px;padding:6px;border-radius:22px;transition:transform .3s cubic-bezier(.3,1.6,.5,1)}
.item .ic{height:clamp(84px,17vh,130px);display:flex;align-items:center;animation:bob 3s ease-in-out infinite;filter:drop-shadow(0 12px 14px rgba(0,0,0,.35))}
.item:nth-child(2) .ic{animation-delay:-.7s}.item:nth-child(3) .ic{animation-delay:-1.4s}.item:nth-child(4) .ic{animation-delay:-2.1s}
.item .ic svg{height:100%;width:auto}
.item:hover{transform:scale(1.1) rotate(-3deg)}
.item:active{transform:scale(.92)}
.item span{font-family:var(--script);font-size:clamp(24px,6vw,34px);line-height:1}
.item.done::after{content:"♥";position:absolute;top:2px;right:18%;width:26px;height:26px;border-radius:50%;background:#fff;color:var(--red);
  display:grid;place-items:center;font-size:14px;animation:pop .5s cubic-bezier(.3,1.8,.5,1);box-shadow:0 3px 8px rgba(0,0,0,.3)}
@keyframes pop{from{transform:scale(0)}}
.prog{display:flex;gap:10px;justify-content:center;margin-top:clamp(14px,3vh,26px);font-size:22px}
.prog i{font-style:normal;color:rgba(255,255,255,.28);transition:.4s}
.prog i.on{color:#ffd1d9;text-shadow:0 0 14px #ff6b81;transform:scale(1.2)}
#finaleBtn{display:none}
#finaleBtn.show{display:inline-block;animation:pop .7s cubic-bezier(.3,1.8,.5,1)}

/* ---------- 4. MESSAGE ---------- */
#s-msg{background:radial-gradient(circle at 50% 10%,#d41f2f,#9c0e1b 60%,#650812)}
.board{position:absolute;inset:0}
.pol{position:absolute;width:clamp(100px,27vw,168px);padding:7px 7px 24px;background:#fffdf8;box-shadow:0 12px 26px rgba(0,0,0,.4);
  rotate:var(--r);translate:var(--x,0px) var(--y,0px);cursor:grab;touch-action:none;z-index:1;transition:scale .2s,box-shadow .2s;
  animation:polin .9s cubic-bezier(.2,1.4,.4,1) both;animation-delay:var(--dl)}
.pol.drag{cursor:grabbing;scale:1.08;box-shadow:0 22px 40px rgba(0,0,0,.5);transition:none}
.pol .ph{aspect-ratio:1;background:center/cover #eee;pointer-events:none}
.pol .cap{position:absolute;left:0;right:0;bottom:3px;text-align:center;font-family:var(--hand);font-weight:700;font-size:clamp(13px,3.4vw,17px);color:#5a3a3a;pointer-events:none}
.pol::before{content:"";position:absolute;top:-10px;left:50%;width:46%;height:18px;transform:translateX(-50%) rotate(-3deg);background:rgba(255,235,170,.75);box-shadow:0 1px 3px rgba(0,0,0,.2)}
.pol.wig{animation:wig .5s}
@keyframes wig{25%{scale:1.1;rotate:calc(var(--r) + 6deg)}60%{scale:1.05;rotate:calc(var(--r) - 5deg)}}
@keyframes polin{from{opacity:0;scale:.3;translate:var(--x,0px) calc(var(--y,0px) + 60px)}}
.strip{width:clamp(54px,14vw,84px);padding:6px 6px 14px;display:flex;flex-direction:column;gap:5px}
.strip .ph{width:100%}
.paper{position:relative;z-index:5;width:min(86vw,500px);max-height:calc(100dvh - 170px);overflow:auto;border-radius:3px;
  --lh:clamp(25px,6.4vw,31px);
  background:#fffaf0 repeating-linear-gradient(transparent 0 calc(var(--lh) - 1px),rgba(190,150,130,.3) calc(var(--lh) - 1px) var(--lh));
  background-position:0 34px;color:#4a2a2a;box-shadow:0 20px 50px rgba(0,0,0,.5);rotate:-1deg;
  animation:paperin 1s cubic-bezier(.2,1,.3,1) both .15s;scrollbar-width:none}
.paper::-webkit-scrollbar{display:none}
@keyframes paperin{from{opacity:0;transform:translateY(90px) rotate(5deg)}}
.paper-in{padding:34px clamp(20px,6vw,36px) 28px;position:relative}
#typed{font-family:var(--hand);font-weight:500;font-size:calc(var(--lh)*.76);line-height:var(--lh);white-space:pre-wrap;user-select:text;-webkit-user-select:text}
#typed::after{content:"";display:inline-block;width:2px;height:1em;background:#b3121f;margin-left:2px;vertical-align:-2px;animation:blink .8s steps(1) infinite}
.typed-done #typed::after{display:none}
@keyframes blink{50%{opacity:0}}
.sign{margin-top:10px;font-family:var(--script);font-size:clamp(30px,8vw,40px);color:var(--red);text-align:right;opacity:0;transition:opacity 1s;line-height:1.1}
.typed-done .sign{opacity:1}
.paper-in .hrt{position:absolute;top:8px;right:16px;font-size:24px;color:var(--red);animation:beat 1.2s infinite}
@keyframes beat{15%{transform:scale(1.25)}30%{transform:scale(1)}45%{transform:scale(1.15)}}

/* ---------- 5. FLOWER ---------- */
#s-flower{background:radial-gradient(circle at 50% 100%,#e32c3b 0%,#a50f1d 55%,#650812 100%)}
.bq-stage{position:absolute;inset:112px 12px 48px;display:grid;grid-template-columns:1fr 1fr;grid-template-rows:1fr auto;
  grid-template-areas:"L R" "B B";gap:6px 10px}
.notes{display:flex;flex-direction:column;gap:9px;align-items:center;min-width:0}
.notes.L{grid-area:L}.notes.R{grid-area:R}
.bq{grid-area:B;justify-self:center;height:min(34vh,310px);animation:bqf 4s ease-in-out infinite;filter:drop-shadow(0 16px 18px rgba(0,0,0,.4));transition:transform .3s}
.bq:active{transform:scale(.94)}
.bq svg{height:100%;width:auto}
.sway{transform-origin:120px 240px;animation:sway2 4.5s ease-in-out infinite}
@keyframes sway2{50%{transform:rotate(2.2deg)}}
@keyframes bqf{50%{transform:translateY(-8px)}}
.note{position:relative;max-width:100%;padding:8px 12px;border-radius:3px;font-family:var(--hand);font-weight:700;line-height:1.08;text-align:center;
  font-size:clamp(13px,min(4.1vw,2.7vh),21px);color:#5a1a22;box-shadow:0 6px 14px rgba(0,0,0,.3);rotate:var(--r);
  animation:notein .8s cubic-bezier(.2,1.6,.4,1) both}
.note::before{content:"";position:absolute;top:-7px;left:50%;width:34px;height:12px;transform:translateX(-50%) rotate(-4deg);background:rgba(255,255,255,.55)}
.note.c0{background:#ffe3e8}.note.c1{background:#fff3c4}.note.c2{background:#e4f4ff}.note.c3{background:#e6f7dc}
@keyframes notein{from{opacity:0;transform:translateY(120px) scale(.2)}}
@media(min-width:820px) and (min-aspect-ratio:5/4){
  .bq-stage{grid-template-columns:1fr auto 1fr;grid-template-rows:1fr;grid-template-areas:"L B R";align-items:center;inset:100px 24px 40px}
  .bq{height:min(68vh,580px);align-self:center}
  .notes{justify-content:center;align-self:center;gap:16px}
  .note{font-size:clamp(17px,2.6vh,24px);max-width:300px}
}

/* ---------- 6. CAKE ---------- */
#s-cake{background:radial-gradient(circle at 50% 30%,#e02a39 0%,#a10f1c 55%,#5f0711 100%)}
.cake{width:min(88vw,430px);margin-top:8vh;filter:drop-shadow(0 20px 22px rgba(0,0,0,.4));animation:cakein 1s cubic-bezier(.2,1.3,.4,1) both}
@keyframes cakein{from{opacity:0;transform:translateY(60px) scale(.8)}}
.cake.wob{animation:wob2 .5s}
@keyframes wob2{30%{transform:rotate(-1.5deg) scale(1.02)}60%{transform:rotate(1.2deg)}}
.flame{opacity:0;transform:scale(0);transform-box:fill-box;transform-origin:50% 100%;transition:opacity .3s,transform .4s cubic-bezier(.3,1.8,.5,1)}
.lit .flame{opacity:1;transform:scale(1);animation:flick .35s ease-in-out infinite alternate}
.glow{opacity:0;transition:opacity .4s}.lit .glow{opacity:1}
@keyframes flick{from{transform:scale(1,1) rotate(-3deg)}to{transform:scale(.88,1.1) rotate(3deg)}}
.candle{cursor:pointer}
.smoke{opacity:0}
.smoke.go{animation:smoke 2.6s ease-out forwards}
@keyframes smoke{0%{opacity:.8;transform:translateY(0) scaleX(1)}100%{opacity:0;transform:translateY(-70px) scaleX(1.8)}}
.c-msg{font-size:clamp(34px,9vw,54px);text-align:center;margin-top:8px;min-height:1.2em;padding:0 12px;line-height:1.1;text-shadow:0 3px 16px rgba(0,0,0,.3)}
.c-btns{display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-top:10px;min-height:60px;padding:0 10px}
.btn{padding:12px 22px;border-radius:99px;background:rgba(255,255,255,.95);color:var(--red2);font-weight:700;font-size:15px;box-shadow:0 8px 22px rgba(0,0,0,.3);transition:transform .25s}
.btn:active{transform:scale(.93)}.btn:hover{transform:scale(1.05)}
.btn.ghost{background:rgba(255,255,255,.18);color:#fff;border:1px solid rgba(255,255,255,.5)}

/* ---------- 7. SONG ---------- */
#s-song{background:radial-gradient(circle at 50% 40%,#2b0a10 0%,#170509 100%)}
#s-song::before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 50% 45%,rgba(220,40,55,.35),transparent 60%);animation:glowp 3s ease-in-out infinite}
@keyframes glowp{50%{opacity:.5}}
.turn{position:relative;width:min(86vw,52vh,420px);margin-top:9vh;cursor:pointer;transition:transform .3s}
.turn:active{transform:scale(.97)}
.disc{transform-origin:140px 150px;animation:spin 3.4s linear infinite;animation-play-state:paused}
.playing .disc{animation-play-state:running}
@keyframes spin{to{transform:rotate(360deg)}}
.arm{transform-origin:305px 45px;transform:rotate(-35deg);transition:transform 1s cubic-bezier(.5,0,.3,1)}
.playing .arm{transform:rotate(0)}
.song-t{margin-top:16px;text-align:center;font-family:var(--hand);font-size:clamp(20px,5.4vw,28px);padding:0 16px;line-height:1.1}
.song-t small{display:block;opacity:.7;font-size:.75em;margin-top:2px}
.yt{position:absolute;left:50%;bottom:calc(env(safe-area-inset-bottom) + 46px);width:min(86vw,420px);aspect-ratio:16/9;translate:-50% 0;rotate:-2deg;
  border-radius:10px;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,.6);opacity:0;pointer-events:none;transform:translateY(40px) scale(.8);transition:.6s cubic-bezier(.2,1.2,.4,1);z-index:7;background:#000}
.yt.show{opacity:1;pointer-events:auto;transform:none}
.yt iframe{width:100%;height:100%;border:0}
.notes-float{position:absolute;font-size:30px;color:#ff8a9a;animation:nf 4s ease-in infinite;opacity:0}
@keyframes nf{0%{opacity:0;transform:translateY(0) rotate(0)}20%{opacity:.9}100%{opacity:0;transform:translateY(-140px) rotate(25deg)}}

/* ---------- 8. FINALE ---------- */
#s-finale{background:radial-gradient(ellipse at 50% 80%,#a10f1c 0%,#4a0610 55%,#1d0206 100%)}
.stars{position:absolute;inset:0;pointer-events:none}
.stars i{position:absolute;width:3px;height:3px;border-radius:50%;background:#fff;animation:tw 2.4s ease-in-out infinite}
@keyframes tw{50%{opacity:.15;transform:scale(.5)}}
.f-box{position:relative;z-index:3;text-align:center;padding:0 20px}
.f-1{font-size:clamp(46px,13vw,110px);line-height:1;text-shadow:0 0 30px rgba(255,120,140,.6)}
.f-2{font-size:clamp(52px,15vw,126px);line-height:1.05;color:#ffe3a3;text-shadow:0 0 30px rgba(255,200,100,.7);word-break:break-word}
.f-3{margin:16px auto 0;max-width:30em;font-family:var(--hand);font-size:clamp(21px,5.4vw,30px);line-height:1.15;opacity:0}
.scene.active .f-3{animation:fade 1.2s 2.4s forwards}
.f-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:24px;opacity:0}
.scene.active .f-btns{animation:fade 1s 3.2s forwards}
.f-btns a{text-decoration:none;display:inline-block}
.bigheart{position:absolute;font-size:clamp(70px,18vw,150px);color:#ff4d6d;opacity:.12;animation:beat 1.6s infinite;pointer-events:none;top:12%;filter:blur(1px)}
</style>
</head>
<body>

<canvas id="fx"></canvas>
<button class="pill" id="back" aria-label="Kembali">← Kembali</button>
<button class="pill" id="snd" aria-label="Suara">🔊</button>

<!-- 1. AMPLOP -->
<section class="scene active" id="s-env">
  <h1 class="env-title script">Happy Birthday!</h1>
  <div class="env-wrap" id="envWrap">
    <div class="env">
      <div class="env-back"></div>
      <div class="env-card"><b>Happy Birthday</b><small id="cardName"></small></div>
      <div class="env-front"></div>
      <div class="env-flap"></div>
      <button class="seal" id="seal" aria-label="Buka amplop">
        <svg viewBox="0 0 100 100">
          <defs><radialGradient id="gold" cx="35%" cy="30%"><stop offset="0" stop-color="#fff0b8"/><stop offset=".5" stop-color="#e0a93b"/><stop offset="1" stop-color="#9c6a14"/></radialGradient></defs>
          <circle cx="50" cy="50" r="44" fill="url(#gold)" stroke="#b9801f" stroke-width="3" stroke-dasharray="7 4"/>
          <circle cx="50" cy="50" r="36" fill="url(#gold)" stroke="#b9801f" stroke-width="1.5"/>
          <path d="M50 70 C20 50 28 28 42 33 C47 35 50 40 50 40 C50 40 53 35 58 33 C72 28 80 50 50 70Z" fill="#a8710f" opacity=".85"/>
          <path d="M50 66 C26 49 32 33 43 36 C47 37.5 50 42 50 42 C50 42 53 37.5 57 36 C68 33 74 49 50 66Z" fill="#f6d27a"/>
        </svg>
      </button>
    </div>
  </div>
  <div class="env-hint"><i>👆</i> ketuk segelnya untuk membuka</div>
  <svg class="doodle g" viewBox="0 0 100 100" fill="none" stroke="#c1121f" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
    <rect x="18" y="46" width="64" height="42" rx="3"/><rect x="12" y="34" width="76" height="14" rx="3"/><path d="M50 34v54M50 34C40 18 24 22 32 30c4 4 14 4 18 4M50 34C60 18 76 22 68 30c-4 4-14 4-18 4"/>
    <path d="M18 18l-6-6M82 16l7-6M50 12V4"/>
  </svg>
  <svg class="doodle b" viewBox="0 0 60 100" fill="none" stroke="#c1121f" stroke-width="2.6" stroke-linecap="round">
    <ellipse cx="30" cy="30" rx="22" ry="27"/><path d="M30 57l-4 6h8z" fill="#c1121f"/><path d="M30 63c-8 10 8 16 0 30"/>
  </svg>
</section>

<!-- 2. UCAPAN -->
<section class="scene" id="s-greet">
  <div class="hero" id="hero"></div>
  <div id="bokeh"></div>
  <div class="g-box">
    <div class="g-date" id="gDate"></div>
    <div class="g-h script" id="gH"></div>
    <div class="g-n script" id="gN"></div>
    <p class="g-sub" id="gSub"></p>
    <button class="cta" id="toMenu">Klik di sini ✨</button>
  </div>
</section>

<!-- 3. MENU -->
<section class="scene" id="s-menu">
  <div class="m-in">
    <h2 class="m-t script">Ini untukmu</h2>
    <p class="m-s">ketuk satu per satu ya 💌</p>
    <div class="items" id="items"></div>
    <div class="prog" id="prog"></div>
    <button class="cta" id="finaleBtn">Satu kejutan terakhir 🎁</button>
  </div>
</section>

<!-- 4. PESAN -->
<section class="scene" id="s-msg">
  <div class="board" id="board"></div>
  <article class="paper" id="paper"><div class="paper-in" id="paperIn"><span class="hrt">♥</span><p id="typed"></p><p class="sign" id="sign"></p></div></article>
  <p class="hint" id="msgHint">geser foto-fotonya ✨</p>
</section>

<!-- 5. BUNGA -->
<section class="scene" id="s-flower">
  <h2 class="top script">Bunga untukmu</h2>
  <div class="bq-stage">
    <div class="notes L" id="nL"></div>
    <button class="bq" id="bq" aria-label="Bunga"></button>
    <div class="notes R" id="nR"></div>
  </div>
  <p class="hint" id="bqHint">ketuk bunganya 🌻</p>
</section>

<!-- 6. KUE -->
<section class="scene" id="s-cake">
  <h2 class="top script">Buat permohonan…</h2>
  <div class="cake" id="cake"></div>
  <p class="c-msg script" id="cMsg"></p>
  <div class="c-btns" id="cBtns"></div>
  <p class="hint" id="cHint">ketuk lilinnya untuk menyalakan 🕯️</p>
</section>

<!-- 7. LAGU -->
<section class="scene" id="s-song">
  <h2 class="top script">Lagu ini ngingetin aku sama kamu</h2>
  <div class="turn" id="turn" role="button" aria-label="Putar lagu">
    <svg viewBox="0 0 340 300" id="vinyl"></svg>
  </div>
  <p class="song-t" id="songT"></p>
  <div class="yt" id="yt"></div>
  <p class="hint" id="sHint">ketuk piringan hitamnya 🎵</p>
</section>

<!-- 8. FINALE -->
<section class="scene" id="s-finale">
  <div class="stars" id="stars"></div>
  <div class="bigheart" style="left:8%">♥</div><div class="bigheart" style="right:8%;top:60%;animation-delay:-.6s">♥</div>
  <div class="f-box">
    <div class="f-1 script" id="f1"></div>
    <div class="f-2 script" id="f2"></div>
    <p class="f-3" id="f3"></p>
    <div class="f-btns" id="fBtns"></div>
  </div>
</section>

<script>
/* =====================================================================
   ✏️  UBAH BAGIAN INI SAJA — semua teks, foto, dan lagu ada di sini
   ===================================================================== */
const CONFIG = {
  name: "Nama Kamu",                 // ← nama gebetanmu
  from: "Dari seseorang yang diam-diam ngefans",   // ← tanda tangan di surat
  dateText: "Hari spesial kamu",     // ← mis. "20 Oktober 2026" (kosongkan "" kalau tak perlu)
  subtitle: "semoga harimu semanis senyummu",

  // Isi surat. Pakai {name} untuk menyisipkan nama.
  message: [
    "Selamat ulang tahun, {name}! 🎂",
    "Makasih ya sudah jadi orang yang bikin hari-hariku lebih berwarna, bahkan lewat hal kecil kayak balas chat atau sekadar senyum.",
    "Semoga di umur yang baru ini kamu makin bahagia, makin sehat, dan semua hal baik yang kamu doakan datang satu per satu.",
    "Dan kalau boleh jujur… semoga aku salah satu hal baik yang datang itu. 😳",
    "Panjang umur dan bahagia selalu ya."
  ],

  // Kalimat-kalimat yang muncul tiap bunga diketuk (boleh tambah/kurangi)
  notes: [
    "Senyum kamu itu bahaya, bikin hariku auto bagus",
    "Kamu pintar, tapi tetap rendah hati",
    "Tawa kamu lagu favoritku",
    "Dekat kamu rasanya nyaman banget",
    "Kamu kuat, bahkan di hari-hari yang berat",
    "Caramu peduli ke orang lain itu manis banget",
    "Kamu gemesin tanpa usaha sedikit pun",
    "Jadi dirimu sendiri aja, itu sudah lebih dari cukup"
  ],

  // Foto: isi dengan nama file di folder yang sama (mis. "foto1.jpg"), URL, atau data:base64.
  // Kosong = tampil foto placeholder bergambar hati. Maksimal 5 foto.
  photos: [],
  captions: ["kita 💛", "ingat ini?", "lucu banget", "favoritku", "♥"],

  candles: 3,
  wish: "Semoga semua doamu terkabul ✨",

  // Lagu di scene piringan hitam. Pilih salah satu (kosong semua = musik kotak "Happy Birthday" bawaan):
  song: { title: "Happy Birthday (versi kotak musik)", artist: "", youtubeId: "", audioUrl: "" },

  finale: "Semoga semua hal baik selalu menemukan jalannya ke kamu.",
  whatsapp: "",                      // mis. "6281234567890" → muncul tombol balas via WhatsApp
  whatsappText: "Makasih ya buat ucapannya 🥹"
};
/* ===================================================================== */

const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
const rnd=(a,b)=>a+Math.random()*(b-a), pick=a=>a[Math.floor(Math.random()*a.length)];
const T=s=>s.replaceAll('{name}',CONFIG.name);
const SUB=['s-msg','s-flower','s-cake','s-song'];

/* ---------- particles ---------- */
const cv=$('#fx'),cx=cv.getContext('2d');let W,H;
function resize(){const d=Math.min(devicePixelRatio||1,2);W=innerWidth;H=innerHeight;cv.width=W*d;cv.height=H*d;cx.setTransform(d,0,0,d,0,0)}
addEventListener('resize',resize);resize();
const P=[],PAL=['#ff4d6d','#ff758f','#ffb3c1','#ffffff','#ffd166','#ffe3a3'];
const spawn=o=>P.push(Object.assign({x:0,y:0,vx:0,vy:0,g:.15,drag:.99,life:1,decay:.01,size:8,rot:0,vr:0,type:'heart',color:'#fff',alpha:1},o));
function heart(c,s){c.beginPath();c.moveTo(0,s*.35);c.bezierCurveTo(-s*1.1,-s*.3,-s*.6,-s,0,-s*.4);c.bezierCurveTo(s*.6,-s,s*1.1,-s*.3,0,s*.35);c.fill()}
function star(c,s){c.beginPath();c.moveTo(0,-s);c.quadraticCurveTo(0,0,s,0);c.quadraticCurveTo(0,0,0,s);c.quadraticCurveTo(0,0,-s,0);c.quadraticCurveTo(0,0,0,-s);c.fill()}
function burst(x,y,n=24,o={}){
  const cols=o.colors||PAL,types=o.types||['heart','confetti','spark'];
  for(let i=0;i<n;i++){const a=Math.random()*6.283,s=(o.speed||6)*(.3+Math.random());
    spawn({x,y,vx:Math.cos(a)*s,vy:Math.sin(a)*s-(o.up??2),g:o.g??.18,decay:.008+Math.random()*.01,
      size:(o.size||9)*(.6+Math.random()*.8),type:types[i%types.length],color:cols[i%cols.length],rot:Math.random()*6.28,vr:(Math.random()-.5)*.3})}
}
function frame(){
  cx.clearRect(0,0,W,H);
  for(let i=P.length-1;i>=0;i--){const p=P[i];
    p.vy+=p.g;p.vx*=p.drag;p.x+=p.vx;p.y+=p.vy;p.rot+=p.vr;p.life-=p.decay;
    if(p.life<=0||p.y>H+80||p.y<-120||p.x<-80||p.x>W+80){P.splice(i,1);continue}
    cx.save();cx.globalAlpha=Math.max(0,Math.min(1,p.life*1.6))*p.alpha;cx.translate(p.x,p.y);cx.rotate(p.rot);cx.fillStyle=p.color;
    switch(p.type){
      case'heart':heart(cx,p.size);break;
      case'confetti':cx.fillRect(-p.size/2,-p.size/4,p.size,p.size/2);break;
      case'spark':star(cx,p.size);break;
      case'dot':cx.beginPath();cx.arc(0,0,p.size/2,0,6.283);cx.fill();break;
      case'petal':cx.beginPath();cx.ellipse(0,0,p.size*.7,p.size*.32,0,0,6.283);cx.fill();break}
    cx.restore()}
  requestAnimationFrame(frame)}
frame();

/* ambient hearts */
setInterval(()=>{ if(['s-greet','s-menu'].includes(cur)){
  spawn({x:rnd(0,W),y:H+10,vx:rnd(-.4,.4),vy:rnd(-2.2,-1),g:0,decay:.0022,size:rnd(7,17),type:'heart',color:pick(PAL),alpha:.5,vr:rnd(-.01,.01)})}},450);

/* tap / trail sparkles */
addEventListener('pointerdown',e=>burst(e.clientX,e.clientY,6,{types:['heart'],speed:3.2,size:6,up:1.5,g:.1}));
let lastT=0;
addEventListener('pointermove',e=>{const n=performance.now();if(n-lastT<50)return;lastT=n;
  spawn({x:e.clientX+rnd(-6,6),y:e.clientY+rnd(-6,6),vx:rnd(-.5,.5),vy:rnd(-.6,.2),g:.02,decay:.035,size:rnd(3,6),type:'spark',color:pick(['#fff','#ffe3a3','#ffb3c1'])})});

/* ---------- audio ---------- */
let AC,master,soundOn=true;
function ac(){
  if(!AC){try{AC=new(window.AudioContext||window.webkitAudioContext)();master=AC.createGain();master.gain.value=.55;
    const d=AC.createDelay();d.delayTime.value=.27;const fb=AC.createGain();fb.gain.value=.28;d.connect(fb);fb.connect(d);
    master.connect(AC.destination);master.connect(d);d.connect(AC.destination)}catch(e){return null}}
  if(AC.state==='suspended')AC.resume();return AC}
function tone(f,t,dur,vol,dest,type='triangle'){
  const o=AC.createOscillator(),o2=AC.createOscillator(),g=AC.createGain();
  o.type=type;o2.type='sine';o.frequency.value=f;o2.frequency.value=f*2;
  const g2=AC.createGain();g2.gain.value=.25;o2.connect(g2);g2.connect(g);o.connect(g);g.connect(dest||master);
  g.gain.setValueAtTime(0,t);g.gain.linearRampToValueAtTime(vol,t+.012);g.gain.exponentialRampToValueAtTime(.0001,t+dur);
  o.start(t);o2.start(t);o.stop(t+dur+.05);o2.stop(t+dur+.05)}
const NF={G4:392,A4:440,B4:493.88,C5:523.25,D5:587.33,E5:659.25,F5:698.46,G5:783.99};
const MEL=[['G4',.75],['G4',.25],['A4',1],['G4',1],['C5',1],['B4',2],['G4',.75],['G4',.25],['A4',1],['G4',1],['D5',1],['C5',2],
  ['G4',.75],['G4',.25],['G5',1],['E5',1],['C5',1],['B4',1],['A4',2],['F5',.75],['F5',.25],['E5',1],['C5',1],['D5',1],['C5',3]];
const Music={playing:false,timer:null,bus:null,
  start(o={}){ if(!soundOn||this.playing||!ac())return; this.playing=true;
    const vol=o.vol??.085,tempo=o.tempo??.7,gap=o.gap??3.5;
    this.bus=AC.createGain();this.bus.connect(master);const bus=this.bus;
    const run=()=>{if(!this.playing||this.bus!==bus)return;let t=AC.currentTime+.08;
      for(const [n,b] of MEL){tone(NF[n],t,b*tempo*1.7,vol,bus);t+=b*tempo}
      this.timer=setTimeout(run,(t-AC.currentTime+gap)*1000)};run()},
  stop(){this.playing=false;clearTimeout(this.timer);if(this.bus&&AC){const b=this.bus;b.gain.setTargetAtTime(0,AC.currentTime,.06);setTimeout(()=>{try{b.disconnect()}catch(e){}},600);this.bus=null}}};
function sfx(k){ if(!soundOn||!ac())return;const t=AC.currentTime;
  if(k==='pop'){const o=AC.createOscillator(),g=AC.createGain();o.type='square';o.frequency.setValueAtTime(500,t);o.frequency.exponentialRampToValueAtTime(70,t+.1);
    g.gain.setValueAtTime(.18,t);g.gain.exponentialRampToValueAtTime(.001,t+.12);o.connect(g);g.connect(master);o.start(t);o.stop(t+.14)}
  if(k==='chime'){[784,988,1319,1568].forEach((f,i)=>tone(f,t+i*.08,.9,.14))}
  if(k==='tick'){tone(1200,t,.12,.08,master,'sine')}
  if(k==='fw'){const o=AC.createOscillator(),g=AC.createGain();o.type='sawtooth';o.frequency.setValueAtTime(180,t);o.frequency.exponentialRampToValueAtTime(40,t+.35);
    g.gain.setValueAtTime(.08,t);g.gain.exponentialRampToValueAtTime(.001,t+.4);o.connect(g);g.connect(master);o.start(t);o.stop(t+.45)}
}
const sndBtn=$('#snd');
function setSound(v){soundOn=v;sndBtn.textContent=v?'🔊':'🔇';
  if(!v){Music.stop();stopSongPlayback(true)} else if(cur!=='s-song'||!songPlaying)Music.start()}
sndBtn.onclick=()=>setSound(!soundOn);

/* ---------- art (SVG generators) ---------- */
function sunflower(cx_,cy,r,rot=0){
  let s=`<g transform="translate(${cx_} ${cy}) rotate(${rot})">`;
  for(let k=0;k<2;k++){const n=14,off=k?180/n:0;for(let i=0;i<n;i++)
    s+=`<ellipse cx="0" cy="${-r*.72}" rx="${r*.2}" ry="${r*.42}" fill="${k?'#fcd34d':'#f59e0b'}" transform="rotate(${i*360/n+off})"/>`}
  s+=`<circle r="${r*.46}" fill="#6b3a12"/><circle r="${r*.34}" fill="#4a2508"/>`;
  for(let i=0;i<22;i++){const a=i*2.39996,d=Math.sqrt(i/22)*r*.3;s+=`<circle cx="${Math.cos(a)*d}" cy="${Math.sin(a)*d}" r="${r*.032}" fill="#9a6a2c"/>`}
  return s+'</g>'}
function bouquetSVG(){
  let baby='';[[40,66],[206,72],[28,104],[214,110],[56,40],[190,44]].forEach(([x,y])=>{baby+=`<g fill="#fff" opacity=".95"><circle cx="${x}" cy="${y}" r="5"/><circle cx="${x+7}" cy="${y-4}" r="4"/><circle cx="${x-6}" cy="${y-5}" r="3.5"/></g>`});
  return `<svg viewBox="0 0 240 320" xmlns="http://www.w3.org/2000/svg"><g class="sway">
  <g stroke="#3d7a38" stroke-width="5" stroke-linecap="round" fill="none"><path d="M120 90V235"/><path d="M62 120Q85 190 118 235"/><path d="M178 120Q155 190 122 235"/><path d="M40 70Q70 170 116 235"/><path d="M206 74Q170 170 124 235"/></g>
  <g fill="#4c9a43"><ellipse cx="84" cy="150" rx="26" ry="9" transform="rotate(-32 84 150)"/><ellipse cx="156" cy="150" rx="26" ry="9" transform="rotate(32 156 150)"/><ellipse cx="96" cy="118" rx="20" ry="7" transform="rotate(-55 96 118)" fill="#3d7a38"/><ellipse cx="144" cy="118" rx="20" ry="7" transform="rotate(55 144 118)" fill="#3d7a38"/></g>
  ${baby}${sunflower(62,122,32,-14)}${sunflower(178,122,32,12)}${sunflower(120,82,40,4)}
  <path d="M30 142Q120 168 210 142L136 300Q120 312 104 300Z" fill="#f3dfc3" stroke="#d9bf9b" stroke-width="2"/>
  <path d="M52 156Q120 176 188 156L130 292Q120 300 110 292Z" fill="#ffb3c1" opacity=".95"/>
  <path d="M52 156Q120 176 188 156" stroke="#fff" stroke-width="2" fill="none" opacity=".6"/>
  <g transform="translate(120 222)"><path d="M0 0C-30-22-48-4-40 12 -32 22-10 8 0 0Z" fill="#c1121f"/><path d="M0 0C30-22 48-4 40 12 32 22 10 8 0 0Z" fill="#c1121f"/><path d="M-4 6L-26 44 -10 38Z" fill="#a30f1c"/><path d="M4 6L26 44 10 38Z" fill="#a30f1c"/><circle r="8" fill="#e5283a"/></g>
  </g></svg>`}
const ICON={
  msg:`<svg viewBox="0 0 130 100"><rect x="6" y="14" width="118" height="76" rx="7" fill="#fff"/><path d="M6 20L65 62 124 20" fill="none" stroke="#d9d3cc" stroke-width="3"/><path d="M6 86L48 50M124 86L82 50" stroke="#e5ded6" stroke-width="2.5"/><circle cx="65" cy="58" r="13" fill="#c1121f" stroke="#8f0b17" stroke-width="2" stroke-dasharray="4 2.5"/><path d="M65 65C54 58 57 51 62 53 64 54 65 56 65 56 65 56 66 54 68 53 73 51 76 58 65 65Z" fill="#ffd1d9"/></svg>`,
  cake:`<svg viewBox="0 0 130 100"><ellipse cx="65" cy="90" rx="52" ry="7" fill="rgba(0,0,0,.25)"/><path d="M14 46H112V82Q112 90 104 90H22Q14 90 14 82Z" fill="#c1121f"/><rect x="14" y="62" width="98" height="8" fill="#ffeede"/><path d="M14 46Q14 32 30 32H96Q112 32 112 46Z" fill="#ffeede"/><path d="M20 46q4 12 9 0M40 46q4 14 9 0M64 46q4 12 9 0M88 46q4 14 9 0" fill="#ffeede" stroke="#ffeede" stroke-width="5" stroke-linecap="round"/><path d="M66 24Q70 10 82 8" stroke="#2d6a2e" stroke-width="3" fill="none" stroke-linecap="round"/><circle cx="64" cy="26" r="9" fill="#e5283a"/><circle cx="61" cy="23" r="2.6" fill="#fff" opacity=".7"/></svg>`,
  song:`<svg viewBox="0 0 110 100"><circle cx="50" cy="50" r="44" fill="#151515"/><circle cx="50" cy="50" r="34" fill="none" stroke="#333" stroke-width="1.5"/><circle cx="50" cy="50" r="26" fill="none" stroke="#2a2a2a" stroke-width="1.5"/><path d="M50 50L50 8A42 42 0 0 1 82 22Z" fill="#fff" opacity=".1"/><circle cx="50" cy="50" r="15" fill="#e5283a"/><circle cx="50" cy="50" r="3" fill="#fbf3e6"/><path d="M92 30v34" stroke="#fff" stroke-width="3" stroke-linecap="round"/><circle cx="86" cy="66" r="7" fill="#fff"/></svg>`
};
function vinylSVG(){
  let rings='';for(let r=118;r>=64;r-=7)rings+=`<circle cx="140" cy="150" r="${r}" fill="none" stroke="#262626" stroke-width="1.3"/>`;
  return `<defs><radialGradient id="lb" cx="40%" cy="35%"><stop offset="0" stop-color="#ff5a6a"/><stop offset="1" stop-color="#a30f1c"/></radialGradient></defs>
  <circle cx="146" cy="158" r="132" fill="rgba(0,0,0,.45)"/>
  <g class="disc"><circle cx="140" cy="150" r="130" fill="#0f0f0f"/>${rings}
   <path d="M140 150V20A130 130 0 0 1 252 85Z" fill="#fff" opacity=".07"/><path d="M140 150V280A130 130 0 0 1 28 215Z" fill="#fff" opacity=".07"/>
   <circle cx="140" cy="150" r="48" fill="url(#lb)"/><circle cx="140" cy="150" r="42" fill="none" stroke="rgba(255,255,255,.35)" stroke-dasharray="3 4"/>
   <path d="M140 172C118 158 124 140 134 142 138 143 140 147 140 147 140 147 142 143 146 142 156 140 162 158 140 172Z" fill="#ffd1d9"/>
   <circle cx="140" cy="150" r="5" fill="#170509"/></g>
  <g class="arm"><circle cx="305" cy="45" r="16" fill="#cfcfcf" stroke="#8a8a8a" stroke-width="3"/><circle cx="305" cy="45" r="6" fill="#555"/>
   <path d="M305 45L222 146" stroke="#d8d8d8" stroke-width="7" stroke-linecap="round"/><path d="M305 45L222 146" stroke="#fff" stroke-width="2" stroke-linecap="round" opacity=".7"/>
   <g transform="translate(222 146) rotate(-40)"><rect x="-8" y="-4" width="26" height="14" rx="3" fill="#e5283a"/><rect x="-6" y="10" width="4" height="7" fill="#222"/></g></g>`}
function cakeSVG(n){
  let drips='',sprinkles='',candles='';
  for(let x=96;x<=224;x+=16)drips+=`<rect x="${x-5}" y="104" width="10" height="${10+(x%32?6:16)}" rx="5" fill="#fff4e8"/>`;
  for(let x=62;x<=258;x+=18)drips+=`<rect x="${x-6}" y="168" width="12" height="${12+(x%36?6:18)}" rx="6" fill="#fff4e8"/>`;
  for(let i=0;i<26;i++){const x=rnd(66,254),y=rnd(205,250);sprinkles+=`<rect x="${x}" y="${y}" width="6" height="2.2" rx="1" fill="${pick(['#ffd166','#ffb3c1','#fff','#9be7ff'])}" transform="rotate(${rnd(0,180)} ${x} ${y})"/>`}
  const sp=Math.min(44,150/n);
  for(let i=0;i<n;i++){const x=160+(i-(n-1)/2)*sp;
    candles+=`<g class="candle" data-i="${i}" transform="translate(${x} 0)"><rect x="-22" y="20" width="44" height="100" fill="transparent"/>
      <circle class="glow" cx="0" cy="62" r="34" fill="url(#gl)"/>
      <rect x="-5.5" y="70" width="11" height="40" rx="2" fill="#fff"/><path d="M-5.5 78l11-6M-5.5 90l11-6M-5.5 102l11-6" stroke="#ff8fa3" stroke-width="3"/>
      <path d="M0 70V64" stroke="#333" stroke-width="2"/>
      <g class="flame"><path d="M0 34C9 48 9 60 0 64 -9 60 -9 48 0 34Z" fill="url(#fl)"/><path d="M0 48C4 54 4 60 0 62 -4 60 -4 54 0 48Z" fill="#fff6c9"/></g>
      <g class="smoke" id="sm${i}"><path d="M0 60q-8-10 0-20t0-20" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" opacity=".7"/></g></g>`}
  return `<svg viewBox="0 0 320 290" id="cakeSvg"><defs>
   <radialGradient id="gl"><stop offset="0" stop-color="#ffd166" stop-opacity=".7"/><stop offset="1" stop-color="#ffd166" stop-opacity="0"/></radialGradient>
   <linearGradient id="fl" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffe27a"/><stop offset="1" stop-color="#ff7b00"/></linearGradient></defs>
   <ellipse cx="160" cy="272" rx="150" ry="16" fill="#f7eee3"/><ellipse cx="160" cy="268" rx="126" ry="11" fill="#e9dccb"/>
   <rect x="46" y="168" width="228" height="100" rx="12" fill="#b3121f"/><rect x="76" y="106" width="168" height="66" rx="12" fill="#c1121f"/>
   <rect x="46" y="212" width="228" height="12" fill="#fff4e8"/><rect x="76" y="140" width="168" height="10" fill="#fff4e8"/>
   <path d="M76 114Q76 98 94 98H226Q244 98 244 114Z" fill="#fff4e8"/><path d="M46 176Q46 160 64 160H256Q274 160 274 176Z" fill="#fff4e8"/>
   ${drips}${sprinkles}
   <g fill="#e5283a" stroke="#fff" stroke-width="2"><circle cx="76" cy="160" r="8"/><circle cx="244" cy="160" r="8"/></g>
   ${candles}</svg>`}

/* ---------- placeholder photo ---------- */
function ph(i){const g=[['#ffb3c1','#ff758f'],['#ffd6a5','#ff9e7a'],['#cdb4db','#a2d2ff'],['#b7e4c7','#74c69d'],['#ffe5ec','#fb6f92']][i%5];
  const s=`<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'><defs><linearGradient id='g' x1='0' y1='0' x2='1' y2='1'><stop offset='0' stop-color='${g[0]}'/><stop offset='1' stop-color='${g[1]}'/></linearGradient></defs><rect width='200' height='200' fill='url(#g)'/><path d='M100 140C50 108 62 62 88 70 96 73 100 82 100 82 100 82 104 73 112 70 138 62 150 108 100 140Z' fill='white' opacity='.88'/><text x='100' y='176' text-anchor='middle' font-family='sans-serif' font-size='12' fill='white'>foto ${i+1} (ganti di CONFIG)</text></svg>`;
  return 'data:image/svg+xml;utf8,'+encodeURIComponent(s)}
const photo=i=>CONFIG.photos[i]||ph(i);
const cssUrl=u=>`url("${String(u).replace(/"/g,'%22')}")`;

/* ---------- scene engine ---------- */
let cur='s-env';
const visited=new Set(),backBtn=$('#back');
const hooks={
  's-msg':{enter:startTyping,leave(){typeTok++}},
  's-flower':{enter:resetBouquet},
  's-cake':{enter:resetCake,leave(){stopMic()}},
  's-song':{enter:resetSong,leave:leaveSong},
  's-menu':{enter:renderMenu},
  's-finale':{enter:startFW,leave:stopFW}
};
function go(id){
  if(id===cur)return;
  const h0=hooks[cur];h0&&h0.leave&&h0.leave();
  cur=id;$$('.scene').forEach(s=>s.classList.toggle('active',s.id===id));
  backBtn.classList.toggle('show',SUB.includes(id));
  if(SUB.includes(id))visited.add(id);
  const h=hooks[id];h&&h.enter&&h.enter();
}
backBtn.onclick=()=>go('s-menu');
addEventListener('keydown',e=>{if(e.key==='Escape'&&SUB.includes(cur))go('s-menu')});

/* ---------- 1. envelope ---------- */
let opened=false;
$('#seal').onclick=e=>{
  if(opened)return;opened=true;
  const seal=e.currentTarget,r=seal.getBoundingClientRect();
  ac();sfx('pop');seal.classList.add('crack');
  burst(r.left+r.width/2,r.top+r.height/2,36,{colors:['#ffe3a3','#e0a93b','#fff2c4','#c1121f'],types:['spark','confetti','dot'],speed:7});
  $('.env-hint').style.opacity=0;
  const w=$('#envWrap');
  setTimeout(()=>{w.classList.add('open');sfx('tick')},350);
  setTimeout(()=>{w.classList.add('rise');sfx('chime');Music.start()},1250);
  setTimeout(()=>w.classList.add('fly'),2500);
  setTimeout(()=>{go('s-greet');sndBtn.classList.add('show');
    for(let i=0;i<5;i++)setTimeout(()=>burst(rnd(W*.2,W*.8),rnd(H*.2,H*.5),26,{speed:7}),i*220)},2900);
};

/* ---------- 2. greeting ---------- */
function split(el,txt,off=0){el.innerHTML=[...txt].map((c,i)=>`<span class="ltr" style="--i:${i+off}">${c===' '?'&nbsp;':c}</span>`).join('')}
function initGreeting(){
  $('#cardName').textContent=CONFIG.name;
  split($('#gH'),'Happy Birthday,');split($('#gN'),CONFIG.name+'!',15);
  $('#gDate').textContent=CONFIG.dateText||'';$('#gSub').textContent=CONFIG.subtitle;
  if(CONFIG.photos[0]){const h=$('#hero');h.style.setProperty('--hero',cssUrl(CONFIG.photos[0]));h.classList.add('on')}
  const bk=$('#bokeh');for(let i=0;i<12;i++){const s=rnd(40,150),e=document.createElement('i');e.className='bokeh';
    e.style.cssText=`left:${rnd(0,95)}%;bottom:0;width:${s}px;height:${s}px;background:radial-gradient(circle,rgba(255,${Math.floor(rnd(150,220))},${Math.floor(rnd(150,200))},.55),transparent 70%);animation-duration:${rnd(10,22)}s;animation-delay:${-rnd(0,20)}s`;bk.appendChild(e)}
  const cols=[['#ff5d73','#c1121f'],['#ffb3c1','#e5527a'],['#ffe3a3','#e0a93b'],['#fff','#e8d8c8'],['#ff8fa3','#d9304f'],['#f7c6d0','#d77a92']];
  cols.forEach((c,i)=>makeBalloon(c,i));
}
function makeBalloon(c,i,delay){
  const b=document.createElement('div');b.className='balloon';
  b.style.cssText=`--c:${c[0]};--c2:${c[1]};--w:${Math.round(rnd(46,78))}px;--x:${(i*17+rnd(0,8))%90}%;--d:${rnd(15,24)}s;--dl:${delay??-rnd(0,18)}s`;
  b.onpointerdown=e=>{e.stopPropagation();const r=b.getBoundingClientRect();
    sfx('pop');burst(r.left+r.width/2,r.top+r.height/2,22,{colors:[c[0],c[1],'#fff'],speed:6});
    b.classList.add('pop');setTimeout(()=>{b.remove();makeBalloon(c,i,-2)},2500)};
  $('#s-greet').appendChild(b)}
$('#toMenu').onclick=()=>{sfx('chime');go('s-menu')};

/* ---------- 3. menu ---------- */
const MENU=[{id:'s-msg',l:'Pesan',i:ICON.msg},{id:'s-flower',l:'Bunga',i:bouquetSVG()},{id:'s-cake',l:'Kue',i:ICON.cake},{id:'s-song',l:'Lagu',i:ICON.song}];
let allDone=false;
function initMenu(){
  $('#items').innerHTML=MENU.map(m=>`<button class="item" data-go="${m.id}"><div class="ic">${m.i}</div><span class="script">${m.l}</span></button>`).join('');
  $$('.item').forEach(b=>b.onclick=()=>{sfx('tick');go(b.dataset.go)});
  $('#prog').innerHTML=MENU.map(()=>'<i>♥</i>').join('');
  $('#finaleBtn').onclick=()=>{sfx('chime');go('s-finale')};
}
function renderMenu(){
  $$('.item').forEach(i=>i.classList.toggle('done',visited.has(i.dataset.go)));
  $$('#prog i').forEach((h,k)=>h.classList.toggle('on',k<visited.size));
  if(visited.size>=MENU.length&&!allDone){allDone=true;$('#finaleBtn').classList.add('show');
    setTimeout(()=>{sfx('chime');for(let i=0;i<4;i++)setTimeout(()=>burst(rnd(W*.2,W*.8),rnd(H*.15,H*.4),28),i*200)},500)}
}

/* ---------- 4. message ---------- */
let typeTok=0,dragZ=10;
function initBoard(){
    const spots=[{l:'3%',t:'5%',r:-8,s:0},{r:'4%',t:'9%',rr:7,s:1},{l:'4%',b:'6%',rr:6,s:2},{r:'5%',b:'5%',rr:-6,s:3}];
  const SIDE={l:'left',r:'right',t:'top',b:'bottom'};
  let html='';
  spots.forEach((p,i)=>{
    const pos=['l','r','t','b'].filter(k=>p[k]).map(k=>SIDE[k]+':'+p[k]).join(';');
    html+=`<div class="pol" style="${pos};--r:${p.rr??p.r}deg;--dl:${.3+i*.15}s"><div class="ph" style="background-image:${cssUrl(photo(i))}"></div><div class="cap">${CONFIG.captions[i]||''}</div></div>`;
  });
  const stripPhotos=[4,0,2].map(k=>`<div class="ph" style="background-image:${cssUrl(photo(k))}"></div>`).join('');
  html+=`<div class="pol strip" style="right:1%;top:40%;--r:3deg;--dl:.9s">${stripPhotos}</div>`;
  $('#board').innerHTML=html;
  $$('.pol').forEach(el=>{
    let sx,sy,ox,oy,on=false,moved=false;
    el.addEventListener('pointerdown',e=>{e.stopPropagation();on=true;moved=false;el.setPointerCapture(e.pointerId);sx=e.clientX;sy=e.clientY;
      ox=parseFloat(el.style.getPropertyValue('--x'))||0;oy=parseFloat(el.style.getPropertyValue('--y'))||0;el.style.zIndex=++dragZ;el.classList.add('drag');
      el.style.animation='none';$('#msgHint').style.opacity=0});
    el.addEventListener('pointermove',e=>{if(!on)return;const dx=e.clientX-sx,dy=e.clientY-sy;if(Math.abs(dx)+Math.abs(dy)>5)moved=true;
      el.style.setProperty('--x',ox+dx+'px');el.style.setProperty('--y',oy+dy+'px')});
    const end=()=>{if(!on)return;on=false;el.classList.remove('drag');
      if(!moved){const r=el.getBoundingClientRect();burst(r.left+r.width/2,r.top+r.height/2,14,{types:['heart'],speed:4});el.classList.remove('wig');void el.offsetWidth;el.classList.add('wig');sfx('tick')}};
    el.addEventListener('pointerup',end);el.addEventListener('pointercancel',end);
  });
}
function startTyping(){
  const my=++typeTok,el=$('#typed'),paper=$('#paper'),pin=$('#paperIn');
  pin.classList.remove('typed-done');el.textContent='';$('#msgHint').style.opacity=1;
  $('#sign').textContent='— '+CONFIG.from;
  const full=CONFIG.message.map(T).join('\n\n');let i=0;
  const skip=()=>{typeTok++;el.textContent=full;pin.classList.add('typed-done');paper.scrollTop=paper.scrollHeight;paper.removeEventListener('click',skip)};
  paper.addEventListener('click',skip,{once:true});
  setTimeout(function step(){
    if(my!==typeTok)return;
    i=Math.min(full.length,i+1);el.textContent=full.slice(0,i);paper.scrollTop=paper.scrollHeight;
    if(i>=full.length){pin.classList.add('typed-done');paper.removeEventListener('click',skip);burst(W/2,H*.3,22,{types:['heart'],speed:5});return}
    const c=full[i-1];setTimeout(step,c==='\n'?260:c==='.'||c==='!'||c==='?'?200:c===','?110:rnd(18,38))},1100);
}

/* ---------- 5. bouquet ---------- */
let noteIdx=0;
function initBouquet(){$('#bq').innerHTML=bouquetSVG();$('#bq').onclick=addNote}
function resetBouquet(){noteIdx=0;$('#nL').innerHTML='';$('#nR').innerHTML='';$('#bqHint').textContent='ketuk bunganya 🌻';$('#bqHint').style.opacity=1}
function addNote(){
  const r=$('#bq').getBoundingClientRect(),cx0=r.left+r.width/2,cy0=r.top+r.height*.28;
  sfx('tick');
  for(let i=0;i<14;i++)spawn({x:cx0+rnd(-40,40),y:cy0+rnd(-20,20),vx:rnd(-2.4,2.4),vy:rnd(-3,.5),g:.05,drag:.985,decay:.006,size:rnd(8,14),type:'petal',color:pick(['#fcd34d','#f59e0b','#fde68a']),rot:rnd(0,6),vr:rnd(-.1,.1)});
  burst(cx0,cy0,6,{types:['heart'],speed:4,size:8});
  if(noteIdx>=CONFIG.notes.length){$('#bqHint').textContent='…dan masih banyak alasan lainnya 💛';return}
  const n=document.createElement('div');n.className='note c'+(noteIdx%4);n.textContent=CONFIG.notes[noteIdx];
  n.style.setProperty('--r',rnd(-4,4)+'deg');
  (noteIdx%2?$('#nR'):$('#nL')).appendChild(n);noteIdx++;
  if(noteIdx>=CONFIG.notes.length){$('#bqHint').textContent='…dan masih banyak alasan lainnya 💛';sfx('chime')}
  else $('#bqHint').style.opacity=.0;
}

/* ---------- 6. cake ---------- */
let lit=0,blown=false,micStream=null;
function resetCake(){
  lit=0;blown=false;stopMic();
  $('#cake').innerHTML=cakeSVG(CONFIG.candles);$('#cMsg').textContent='';
  $('#cHint').textContent='ketuk lilinnya untuk menyalakan 🕯️';$('#cHint').style.opacity=1;
  $$('.candle').forEach(c=>c.onclick=()=>light(c));
  cakeButtons();
}
function cakeButtons(){
  const b=$('#cBtns');
  if(blown){b.innerHTML='<button class="btn ghost" id="again">↺ Ulangi</button>';$('#again').onclick=resetCake;return}
  if(lit<CONFIG.candles){b.innerHTML='<button class="btn" id="lightAll">🔥 Nyalakan semua</button>';$('#lightAll').onclick=()=>$$('.candle').forEach((c,i)=>setTimeout(()=>light(c),i*220))}
  else{b.innerHTML='<button class="btn" id="mic">🎤 Tiup pakai suara</button><button class="btn ghost" id="blow">💨 Tiup</button>';
    $('#mic').onclick=startMic;$('#blow').onclick=blow}
}
function light(c){
  if(c.classList.contains('lit')||blown)return;
  c.classList.add('lit');lit++;sfx('tick');
  const r=c.getBoundingClientRect();burst(r.left+r.width/2,r.top+r.height*.3,8,{types:['spark'],colors:['#ffd166','#fff'],speed:3,g:.02,size:6});
  $('#cake').classList.remove('wob');void $('#cake').offsetWidth;$('#cake').classList.add('wob');
  if(lit>=CONFIG.candles){$('#cHint').textContent='sekarang… make a wish, lalu tiup! 🤫';$('#cMsg').textContent='';}
  cakeButtons();
}
function blow(){
  if(blown)return;
  if(lit<CONFIG.candles){$$('.candle').forEach(c=>c.classList.add('lit'));lit=CONFIG.candles}
  blown=true;stopMic();sfx('pop');
  $$('.candle').forEach((c,i)=>{c.classList.remove('lit');const s=$('#sm'+i);s.classList.remove('go');void s.getBoundingClientRect();s.classList.add('go')});
  setTimeout(()=>{sfx('chime');$('#cMsg').textContent=CONFIG.wish;$('#cHint').style.opacity=0;
    for(let i=0;i<5;i++)setTimeout(()=>burst(rnd(W*.15,W*.85),rnd(H*.1,H*.45),30,{speed:7}),i*180);
    for(let i=0;i<40;i++)spawn({x:rnd(0,W),y:-10,vx:rnd(-1,1),vy:rnd(1,4),g:.03,decay:.004,size:rnd(7,12),type:'confetti',color:pick(PAL),rot:rnd(0,6),vr:rnd(-.2,.2)});
    cakeButtons()},500);
}
async function startMic(){
  try{
    const st=await navigator.mediaDevices.getUserMedia({audio:true});micStream=st;
    const c=ac()||new AudioContext();const an=c.createAnalyser();an.fftSize=512;c.createMediaStreamSource(st).connect(an);
    const buf=new Uint8Array(an.fftSize);let hot=0;$('#cHint').textContent='tiup ke arah mikrofon… 💨';
    (function loop(){if(!micStream)return;an.getByteTimeDomainData(buf);let s=0;for(const v of buf){const d=(v-128)/128;s+=d*d}
      hot=Math.sqrt(s/buf.length)>.16?hot+1:Math.max(0,hot-1);if(hot>7)blow();else requestAnimationFrame(loop)})();
  }catch(e){$('#cHint').textContent='mikrofon tidak bisa dipakai — ketuk "Tiup" saja ya 💨'}
}
function stopMic(){if(micStream){micStream.getTracks().forEach(t=>t.stop());micStream=null}}

/* ---------- 7. song ---------- */
let songPlaying=false,aud=null;
function resetSong(){
  songPlaying=false;$('#turn').classList.remove('playing');$('#yt').classList.remove('show');$('#yt').innerHTML='';
  $('#vinyl').innerHTML=vinylSVG();
  const S=CONFIG.song;$('#songT').innerHTML=`${S.title||''}${S.artist?`<small>${S.artist}</small>`:''}`;
  $('#sHint').style.opacity=1;
}
$('#turn').onclick=()=>{ac();songPlaying?stopSongPlayback():playSong()};
let noteTimer=null;
function playSong(){
  if(!soundOn)setSound(true);
  songPlaying=true;$('#turn').classList.add('playing');$('#sHint').style.opacity=0;Music.stop();sfx('tick');
  const S=CONFIG.song;
  if(S.audioUrl){aud=new Audio(S.audioUrl);aud.loop=true;aud.play().catch(()=>{})}
  else if(S.youtubeId){$('#yt').innerHTML=`<iframe src="https://www.youtube.com/embed/${encodeURIComponent(S.youtubeId)}?autoplay=1&rel=0&playsinline=1" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>`;$('#yt').classList.add('show')}
  else Music.start({vol:.18,tempo:.52,gap:1.2});
  noteTimer=setInterval(()=>{const e=document.createElement('div');e.className='notes-float';e.textContent=pick(['♪','♫','♥']);
    e.style.left=rnd(20,80)+'%';e.style.top=rnd(35,60)+'%';$('#s-song').appendChild(e);setTimeout(()=>e.remove(),4000)},700);
}
function stopSongPlayback(silent){
  songPlaying=false;$('#turn')&&$('#turn').classList.remove('playing');clearInterval(noteTimer);
  if(aud){aud.pause();aud=null}$('#yt').classList.remove('show');setTimeout(()=>{if(!songPlaying)$('#yt').innerHTML=''},600);
  Music.stop();
  if(!silent&&soundOn&&cur==='s-song')setTimeout(()=>{if(!songPlaying)Music.start()},300)}
function leaveSong(){stopSongPlayback(true);if(soundOn)setTimeout(()=>Music.start(),250)}

/* ---------- 8. finale ---------- */
let fwTimer=null;
const FW=['#ff4d6d','#ffd166','#fff','#ff9bb0','#ffe3a3','#9be7ff','#ffb3c1'];
function firework(x,y){
  const col=pick(FW),n=64;sfx('fw');
  for(let i=0;i<n;i++){const a=i/n*6.283,s=rnd(2,5.2);spawn({x,y,vx:Math.cos(a)*s,vy:Math.sin(a)*s,g:.045,drag:.964,decay:.011,size:rnd(3,5),type:'dot',color:col})}
  for(let i=0;i<10;i++)spawn({x,y,vx:rnd(-3,3),vy:rnd(-3,3),g:.03,drag:.96,decay:.016,size:rnd(6,10),type:'spark',color:'#fff'});
  burst(x,y,6,{types:['heart'],colors:[col],speed:4,size:9})}
function startFW(){
  $('#f1').innerHTML='';split($('#f1'),'Selamat Ulang Tahun');split($('#f2'),CONFIG.name+'!',19);
  $('#f3').textContent=CONFIG.finale;
  let b='';if(CONFIG.whatsapp)b+=`<a class="btn" target="_blank" rel="noopener" href="https://wa.me/${CONFIG.whatsapp}?text=${encodeURIComponent(CONFIG.whatsappText)}">💬 Balas lewat WhatsApp</a>`;
  b+='<button class="btn ghost" id="replay">↺ Putar ulang</button>';$('#fBtns').innerHTML=b;
  $('#replay').onclick=()=>{visited.clear();allDone=false;$('#finaleBtn').classList.remove('show');go('s-menu')};
  let k=0;const launch=()=>firework(rnd(W*.12,W*.88),rnd(H*.1,H*.5));
  for(let i=0;i<4;i++)setTimeout(launch,400+i*350);
  fwTimer=setInterval(()=>{launch();if(Math.random()<.4)setTimeout(launch,200)},1100);
}
function stopFW(){clearInterval(fwTimer)}
$('#s-finale').addEventListener('pointerdown',e=>{if(e.target.closest('button,a'))return;firework(e.clientX,e.clientY)});
(function(){const s=$('#stars');for(let i=0;i<70;i++){const e=document.createElement('i');e.style.cssText=`left:${rnd(0,100)}%;top:${rnd(0,100)}%;animation-delay:${-rnd(0,3)}s;animation-duration:${rnd(1.6,3.6)}s;opacity:${rnd(.3,1)}`;s.appendChild(e)}})();

/* ---------- init ---------- */
initGreeting();initMenu();initBoard();initBouquet();
document.title='Selamat Ulang Tahun, '+CONFIG.name+' 🎂';
</script>
</body>
</html>
