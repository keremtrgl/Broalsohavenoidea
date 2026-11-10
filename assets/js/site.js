(function(){
"use strict";
const $=(s,r=document)=>r.querySelector(s);
const $$=(s,r=document)=>Array.from(r.querySelectorAll(s));
const RM=window.matchMedia("(prefers-reduced-motion: reduce)").matches;
const sleep=ms=>new Promise(r=>setTimeout(r,RM?0:ms));
const pct=v=>"%"+(v<1&&v>0?v.toFixed(1).replace(".",","):Math.round(v));
const dec=(v,d)=>v.toFixed(d).replace(".",",");
const NS="http://www.w3.org/2000/svg";
function watch(el,onIn,onOut,threshold){
  if(!el)return;
  if(!("IntersectionObserver" in window)){onIn&&onIn();return;}
  const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){onIn&&onIn();}else{onOut&&onOut();}}),{threshold:threshold||0.2});
  io.observe(el);
}
function esc(s){return String(s).replace(/[&<>"]/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));}
function css(v){return getComputedStyle(document.documentElement).getPropertyValue(v).trim();}
const SUP="⁰¹²³⁴⁵⁶⁷⁸⁹";
function sci(n){const e=Math.floor(Math.log10(n));const m=n/Math.pow(10,e);return dec(m,1)+" × 10"+String(e).split("").map(c=>SUP[+c]).join("");}
function fmtInt(n){return Math.round(n).toLocaleString("tr-TR");}
function bigWord(n){
  if(n>=1e12)return dec(n/1e12,n<1e13?1:0)+" trilyon";
  if(n>=1e9)return dec(n/1e9,n<1e10?1:0)+" milyar";
  if(n>=1e6)return dec(n/1e6,0)+" milyon";
  return fmtInt(n);
}
function dur(sec){
  if(sec<120)return Math.round(sec)+" sn";
  if(sec<7200)return Math.round(sec/60)+" dk";
  if(sec<172800)return dec(sec/3600,0)+" saat";
  if(sec<3.15e7*2)return fmtInt(sec/86400)+" gün";
  return bigWord(sec/3.15e7)+" yıl";
}

/* ---------- İlerleme çubuğu ve aktif bölüm ---------- */
const prog=$("#progress");
const chipLinks=$$('#chips a[href^="#"]');
const D=window.ORNEKLER||{};
const sections=chipLinks.map(a=>$(a.getAttribute("href")));
function onScroll(){
  const h=document.documentElement;
  const max=h.scrollHeight-h.clientHeight;
  prog.style.width=(max>0?(h.scrollTop/max)*100:0)+"%";
  let cur=-1;
  sections.forEach((s,i)=>{if(s&&s.getBoundingClientRect().top<window.innerHeight*0.35)cur=i;});
  chipLinks.forEach((a,i)=>a.classList.toggle("active",i===cur));
  if(cur>=0){const a=chipLinks[cur];const box=a.parentElement;const l=a.offsetLeft-box.clientWidth/2+a.clientWidth/2;if(Math.abs(box.scrollLeft-l)>40)box.scrollTo({left:l,behavior:RM?"auto":"smooth"});}
}
let ticking=false;
window.addEventListener("scroll",()=>{if(!ticking){ticking=true;requestAnimationFrame(()=>{onScroll();ticking=false;});}},{passive:true});
onScroll();

/* ---------- HERO ---------- */
(function(){
  const out=$("#hero-out"),list=$("#hero-cands"),stepEl=$("#hero-step"),card=$("#hero-card");
  const base="Yapay zekâ aslında";
  const steps=[
    {c:[["bir",46],["sadece",21],["çok",14],["hiç",6]]},
    {c:[["sonraki",31],["dil",24],["matematik",12],["yazılım",8]]},
    {c:[["kelimeyi",58],["adımı",17],["sözcüğü",11],["harfi",4]]},
    {c:[["tahmin",62],["seçen",15],["bulan",9],["yazan",5]]},
    {c:[["eden",71],["etmeye",9],["edebilen",8],["yapan",3]]},
    {c:[["büyük",34],["dev",22],["bir",15],["akıllı",9]],pick:1},
    {c:[["bir",66],["ölçekli",12],["hesap",6],["istatistik",4]]},
    {c:[["makinedir.",40],["sistemdir.",28],["programdır.",17],["hesap makinesidir.",5]]}
  ];
  if(RM){stepEl.textContent="hareketsiz mod";return;}
  let visible=false,running=false;
  function render(c){
    list.innerHTML=c.map(([w,p])=>`<li class="cand"><span class="cand-w">${esc(w)}</span><span class="cand-bar"><i style="width:0"></i></span><span class="cand-p">%${p}</span></li>`).join("");
    requestAnimationFrame(()=>requestAnimationFrame(()=>{$$("i",list).forEach((el,i)=>el.style.width=c[i][1]+"%");}));
  }
  async function run(){
    out.textContent=base;
    for(let k=0;k<steps.length;k++){
      const s=steps[k],pick=s.pick||0;
      stepEl.textContent="adım "+(k+1)+" / "+steps.length;
      render(s.c);
      await sleep(1150);
      list.children[pick].classList.add("chosen");
      await sleep(650);
      const sp=document.createElement("span");sp.className="new-word";sp.textContent=" "+s.c[pick][0];out.appendChild(sp);
      await sleep(450);
    }
    stepEl.textContent="cümle bitti";
    await sleep(2800);
    if(visible)run();else running=false;
  }
  watch(card,()=>{visible=true;if(!running){running=true;run();}},()=>{visible=false;},0.3);
})();

/* ---------- 1. Döngü ---------- */
(function(){
  const text=$("#loop-text"),nw=$("#loop-new"),model=$("#loop-model"),bench=$("#loop-bench");
  const words=["Bir","varmış,","bir","yokmuş.","Evvel","zaman","içinde,","kalbur","saman","içinde…"];
  if(RM)return;
  let i=0,timer=null;
  function tick(){
    if(i>=words.length){i=0;text.textContent="";}
    model.classList.add("busy");
    setTimeout(()=>{
      model.classList.remove("busy");
      nw.textContent=words[i];
      const sp=document.createElement("span");sp.textContent=(text.textContent?" ":"")+words[i];text.appendChild(sp);
      i++;
    },500);
  }
  watch(bench,()=>{if(!timer){text.textContent="";i=0;tick();timer=setInterval(tick,1300);}},()=>{clearInterval(timer);timer=null;},0.3);
})();

/* ---------- 2. Token ---------- */
const SUFFIX=["ıyor","iyor","uyor","üyor","ları","leri","lar","ler","dır","dir","dur","dür","mış","miş","muş","müş","nın","nin","nun","nün","yor","sın","sin","sun","sün","mı","mi","mu","mü","da","de","ta","te","ın","in","un","ün"];
function tokenize(text){
  const parts=text.match(/\s*[\p{L}\p{M}]+|\s*\d{1,3}|\s*[^\s\p{L}\p{M}\d]|\s+$/gu)||[];
  const out=[];
  for(const p of parts){
    const lead=(p.match(/^\s*/)||[""])[0];
    let w=p.slice(lead.length);
    if(!w){out.push(p);continue;}
    if(!/^[\p{L}\p{M}]+$/u.test(w)){out.push(lead+w);continue;}
    const tail=[];
    for(let k=0;k<2;k++){
      const lw=w.toLocaleLowerCase("tr");
      const s=SUFFIX.find(x=>lw.endsWith(x)&&w.length-x.length>=3);
      if(!s)break;
      tail.unshift(w.slice(w.length-s.length));w=w.slice(0,w.length-s.length);
    }
    const stem=[];
    while(w.length>7){stem.push(w.slice(0,5));w=w.slice(5);}
    stem.push(w);
    stem[0]=lead+stem[0];
    out.push(...stem,...tail);
  }
  return out;
}
function hashId(s){let x=2166136261;for(const c of s){x^=c.codePointAt(0);x=Math.imul(x,16777619);}return (x>>>0)%100000;}
(function(){
  const inp=$("#tok-in"),outEl=$("#tok-out"),ids=$("#tok-ids"),cc=$("#tok-chars"),tc=$("#tok-count");
  const cols=["var(--t1)","var(--t2)","var(--t3)","var(--t4)","var(--t5)"];
  function run(){
    const toks=tokenize(inp.value);
    outEl.innerHTML=toks.map((t,i)=>{
      const lead=t.match(/^\s*/)[0];const body=t.slice(lead.length);
      return `<span class="tok" style="background:${cols[i%5]};animation-delay:${RM?0:Math.min(i*25,600)}ms">${lead?'<span class="sp">·</span>':""}${esc(body)}</span>`;
    }).join("");
    ids.textContent="[ "+toks.map(hashId).join(", ")+" ]";
    cc.textContent=[...inp.value].length;tc.textContent=toks.length;
  }
  inp.addEventListener("input",run);run();
})();

/* ---------- 2b. BPE ---------- */
(function(){
  const corpus=[["evler",5],["evde",4],["evden",3],["eller",3],["elde",2],["gelen",2],["geldi",2]];
  const wordsEl=$("#bpe-words"),pairsEl=$("#bpe-pairs"),vocabEl=$("#bpe-vocab"),totalEl=$("#bpe-total"),nEl=$("#bpe-n"),btn=$("#bpe-step");
  let words,merges,fresh;
  function reset(){words=corpus.map(([w,c])=>({s:[...w],c}));merges=[];fresh=null;render();}
  function counts(){
    const m=new Map();
    words.forEach(w=>{for(let i=0;i<w.s.length-1;i++){const k=w.s[i]+"\u0000"+w.s[i+1];m.set(k,(m.get(k)||0)+w.c);}});
    return [...m.entries()].sort((a,b)=>b[1]-a[1]);
  }
  function render(){
    wordsEl.innerHTML=words.map(w=>`<div class="bpe-w">${w.s.map(t=>`<span class="sym${t===fresh?" fresh":""}">${esc(t)}</span>`).join("")}<span class="cnt">×${w.c}</span></div>`).join("");
    const cs=counts().filter(x=>x[1]>=2).slice(0,4);
    pairsEl.textContent=cs.length?"En sık ikililer: "+cs.map(([k,v])=>k.replace("\u0000","+")+" ("+v+")").join(" · "):"Birden fazla geçen ikili kalmadı. Algoritma burada durur.";
    vocabEl.innerHTML=merges.length?merges.map(m=>`<span>${esc(m)}</span>`).join(""):'<span style="background:none;color:var(--muted);padding:0">henüz yok</span>';
    totalEl.textContent=words.reduce((a,w)=>a+w.s.length*w.c,0);
    nEl.textContent=merges.length;
    btn.disabled=!cs.length;
  }
  btn.addEventListener("click",()=>{
    const cs=counts();if(!cs.length||cs[0][1]<2)return;
    const [a,b]=cs[0][0].split("\u0000");const nt=a+b;
    words.forEach(w=>{const s=[];for(let i=0;i<w.s.length;i++){if(i<w.s.length-1&&w.s[i]===a&&w.s[i+1]===b){s.push(nt);i++;}else s.push(w.s[i]);}w.s=s;});
    merges.push(a+" + "+b+" → "+nt);fresh=nt;render();
  });
  $("#bpe-reset").addEventListener("click",reset);
  reset();
})();

/* ---------- 3. Anlam haritası ---------- */
(function(){
  const pts=[
    ["kedi",70,80,"hay"],["köpek",120,70,"hay"],["aslan",80,118,"hay"],["kaplan",140,112,"hay"],
    ["Ankara",345,70,"şeh"],["İstanbul",420,60,"şeh"],["Paris",370,112,"şeh"],["Tokyo",450,106,"şeh"],
    ["elma",70,285,"mey"],["armut",130,276,"mey"],["muz",95,325,"mey"],
    ["mutlu",235,192,"duy"],["neşeli",285,210,"duy"],["üzgün",255,228,"duy"],
    ["erkek",348,338,"ins"],["kadın",448,338,"ins"],["kral",352,270,"ins"],["kraliçe",452,270,"ins"]
  ];
  const baseVec={hay:[.71,-.32,.15,.58,-.11,.04,.39,-.66],"şeh":[-.48,.62,.27,-.15,.71,-.33,.08,.12],mey:[.22,.55,-.71,.09,-.18,.64,-.41,.3],duy:[-.12,-.6,.44,-.38,.25,.51,.67,-.2],ins:[.35,.18,.52,-.61,-.44,-.07,.26,.73]};
  const g=$("#pts"),vec=$("#vec");
  pts.forEach(([w,x,y,c],i)=>{
    const wrap=document.createElementNS(NS,"g");wrap.setAttribute("class","float");wrap.style.animationDelay=(-i*0.7)+"s";
    const el=document.createElementNS(NS,"g");el.setAttribute("class","pt");el.setAttribute("tabindex","0");el.setAttribute("role","button");el.setAttribute("aria-label",w);
    const ci=document.createElementNS(NS,"circle");ci.setAttribute("cx",x);ci.setAttribute("cy",y);ci.setAttribute("r",5.5);
    const tx=document.createElementNS(NS,"text");tx.setAttribute("x",x+9);tx.setAttribute("y",y+5);tx.textContent=w;
    el.append(ci,tx);wrap.append(el);g.append(wrap);
    const pick=()=>{
      $$(".pt",g).forEach(p=>p.classList.remove("sel"));el.classList.add("sel");
      const h=hashId(w);
      const v=baseVec[c].map((b,k)=>b+(((h>>(k*3))&15)/15-.5)*.24);
      vec.innerHTML=`<b>${esc(w)}</b> = [ ${v.map(n=>(n>=0?" ":"")+dec(n,2)).join(",  ")}, … <span class="small">+ birkaç bin sayı daha</span> ]`;
    };
    el.addEventListener("click",pick);
    el.addEventListener("keydown",e=>{if(e.key==="Enter"||e.key===" "){e.preventDefault();pick();}});
  });
})();

/* ---------- 3b. Konum saatleri ---------- */
(function(){
  const svg=$("#dials"),say=$("#pos-say");
  const words=["Ben","sadece","onu","dün","parkta","gördüm","ve","şaşırdım"];
  const freqs=[1.0,0.45,0.18,0.07];
  const names=["hızlı kol","orta kol","yavaş kol","en yavaş"];
  const X0=100,DX=58,Y0=58,DY=38,R=13;
  svg.setAttribute("viewBox",`0 0 ${X0+DX*words.length-20} 210`);
  let s="";
  freqs.forEach((f,k)=>{s+=`<text x="4" y="${Y0+k*DY+4}">${names[k]}</text>`;});
  words.forEach((w,p)=>{
    const x=X0+p*DX;
    s+=`<g class="col" data-p="${p}"><rect class="col-hl" x="${x-26}" y="4" width="52" height="202" rx="8"/>`;
    s+=`<text class="w" x="${x}" y="20" text-anchor="middle">${w}</text><text x="${x}" y="34" text-anchor="middle">konum ${p}</text>`;
    freqs.forEach((f,k)=>{const y=Y0+k*DY;const a=p*f*180/Math.PI;
      s+=`<circle class="face" cx="${x}" cy="${y}" r="${R}"/><line class="hand" x1="${x}" y1="${y}" x2="${x}" y2="${y-R+3}" style="transform-origin:${x}px ${y}px;transform:rotate(${RM?a:0}deg)" data-a="${a}"/>`;});
    s+=`<rect x="${x-26}" y="4" width="52" height="202" fill="transparent"/></g>`;
  });
  svg.innerHTML=s;
  const hands=$$("line.hand",svg);
  watch($("#pos-bench"),()=>{hands.forEach(h=>{h.style.transform=`rotate(${h.dataset.a}deg)`;});},null,0.3);
  function pick(c){
    $$(".col",svg).forEach(x=>x.classList.remove("on"));c.classList.add("on");
    const p=+c.dataset.p;
    say.textContent=`“${words[p]}” ${p}. konumda. Hızlı kol ${Math.round(p*freqs[0]*57.3)%360}°, en yavaş kol ${Math.round(p*freqs[3]*57.3)}° dönmüş. Bu açılar kelimenin anlam listesine işlenir; iki kelimenin açı farkı aralarındaki mesafeyi söyler.`;
  }
  $$(".col",svg).forEach(c=>{c.addEventListener("mouseenter",()=>pick(c));c.addEventListener("click",()=>pick(c));});
})();

/* ---------- 4. Dikkat yayları ---------- */
(function(){
  const toks=["Kedi","sütü","içti","çünkü","o","çok","acıkmıştı"];
  const W=[[0,.35,.4,.05,.1,.02,.08],[.3,0,.55,.05,.03,.02,.05],[.4,.4,0,.08,.04,.02,.06],[.1,.05,.35,0,.2,.05,.25],[.62,.06,.08,.06,0,.04,.14],[.05,.03,.04,.08,.1,0,.7],[.45,.05,.1,.1,.2,.1,0]];
  const say=["“Kedi” en çok “içti” ve “sütü”ne bakıyor: kim, ne yaptı?","“sütü” en çok “içti”ye bakıyor: neyle ne yapıldı?","“içti” hem özneye (“Kedi”) hem nesneye (“sütü”) bakıyor.","“çünkü” iki olayı bağlıyor: “içti” ve “acıkmıştı” arasında köprü.","“o” kelimesi en çok “Kedi”ye bakıyor: model zamirin kimi kastettiğini böyle çözüyor.","“çok” neredeyse tamamen “acıkmıştı”ya bakıyor: neyin “çok” olduğunu belirliyor.","“acıkmıştı” en çok “Kedi”ye bakıyor: acıkan kim?"];
  const row=$("#att-row"),svg=$("#att-svg"),inner=$("#att-inner"),sayEl=$("#att-say");
  row.innerHTML=toks.map((t,i)=>`<button class="att-tok" data-i="${i}">${t}<span class="w"></span></button>`).join("");
  const btns=$$(".att-tok",row);
  let cur=4;
  function draw(){
    const ir=inner.getBoundingClientRect();const H=120;
    svg.setAttribute("width",inner.scrollWidth);svg.setAttribute("height",H);svg.setAttribute("viewBox",`0 0 ${inner.scrollWidth} ${H}`);
    const cx=btns.map(b=>{const r=b.getBoundingClientRect();return r.left-ir.left+r.width/2;});
    let html="";
    W[cur].forEach((w,j)=>{
      if(j===cur||w<0.02)return;
      const x1=cx[cur],x2=cx[j],mid=(x1+x2)/2,lift=Math.min(110,Math.abs(x2-x1)*0.5+16);
      html+=`<path d="M${x1} ${H} Q ${mid} ${H-lift*1.6} ${x2} ${H}" style="stroke-width:${(1+w*11).toFixed(1)};opacity:${(0.2+w*1.1).toFixed(2)}"/>`;
    });
    svg.innerHTML=html;
    btns.forEach((b,j)=>{b.classList.toggle("src",j===cur);b.classList.toggle("on",j!==cur&&W[cur][j]>=0.3);$(".w",b).textContent=j===cur?"":pct(W[cur][j]*100);});
    sayEl.textContent=say[cur];
  }
  btns.forEach(b=>{
    b.addEventListener("click",()=>{cur=+b.dataset.i;draw();});
    b.addEventListener("mouseenter",()=>{if(window.matchMedia("(hover:hover)").matches){cur=+b.dataset.i;draw();}});
  });
  window.addEventListener("resize",draw);
  if(document.fonts&&document.fonts.ready)document.fonts.ready.then(draw);
  draw();
})();

/* ---------- 4b. Dikkat kafaları ---------- */
(function(){
  const T=["Kedi","sütü","içti","çünkü","o","acıkmıştı"];const n=T.length;
  const prev=T.map((_,i)=>T.map((_,j)=>i===0?(j===0?1:0):(j===i-1?.75:j===i?.25:0)));
  const pron=[[1,0,0,0,0,0],[.3,.7,0,0,0,0],[.35,.35,.3,0,0,0],[.15,.1,.5,.25,0,0],[.72,.05,.08,.05,.1,0],[.5,.05,.15,.05,.15,.1]];
  const sink=T.map((_,i)=>T.map((_,j)=>{if(i===0)return j===0?1:0;if(j>i)return 0;if(j===0)return .7;return .3/i;}));
  const heads=[prev,pron,sink];
  const says=["Kafa 1 neredeyse sadece bir önceki kelimeye bakıyor. Basit görünür ama çok işe yarar: kelime gruplarını (“Eyfel + Kulesi”) birleştirmenin ilk adımıdır.","Kafa 2 zamirleri sahibine bağlıyor: “o” satırında en koyu hücre “Kedi”. “acıkmıştı” da öznesini arıyor.","Kafa 3 işi olmadığında dikkatini ilk token’a boşaltıyor. Çoğu satırın ilk sütunu koyu: bu bir “park yeri”, gerçek bir ilişki değil."];
  const el=$("#heat"),sayEl=$("#head-say"),btns=$$("#head-btns button");
  el.style.gridTemplateColumns=`5.5em repeat(${n},minmax(0,1fr))`;
  let html='<div class="h"></div>'+T.map(t=>`<div class="h">${t}</div>`).join("");
  for(let i=0;i<n;i++){html+=`<div class="h rh">${T[i]}</div>`;for(let j=0;j<n;j++){html+=j>i?'<div class="c m"></div>':`<div class="c" data-i="${i}" data-j="${j}"><i></i><span></span></div>`;}}
  el.innerHTML=html;
  function set(h){
    btns.forEach((b,k)=>b.setAttribute("aria-pressed",String(k===h)));
    $$(".c[data-i]",el).forEach(c=>{const w=heads[h][+c.dataset.i][+c.dataset.j];$("i",c).style.opacity=(w*0.95).toFixed(2);const sp=$("span",c);sp.textContent=w>=0.1?Math.round(w*100):"";sp.style.color=w>0.5?"var(--surface)":"var(--ink)";});
    sayEl.textContent=says[h];
  }
  btns.forEach((b,k)=>b.addEventListener("click",()=>set(k)));
  set(0);
})();

/* ---------- 5. Artık akış ---------- */
(function(){
  const rows=$$("#stream .srow"),bench=$("#stream-bench");
  rows.forEach(r=>r.classList.add("on"));
  if(RM)return;
  let i=0,timer=null;
  function tick(){
    if(i>=rows.length+2){rows.forEach(r=>r.classList.remove("on","cur"));i=0;}
    if(i<rows.length){rows.forEach(r=>r.classList.remove("cur"));rows[i].classList.add("on","cur");}
    i++;
  }
  watch(bench,()=>{if(!timer){rows.forEach(r=>r.classList.remove("on","cur"));i=0;tick();timer=setInterval(tick,1100);}},()=>{clearInterval(timer);timer=null;rows.forEach(r=>{r.classList.add("on");r.classList.remove("cur");});},0.3);
})();

/* ---------- 6. Sıcaklık + top-p ---------- */
(function(){
  const words=[["güzel",2.0],["sıcak",1.66],["soğuk",1.31],["bulutlu",0.56],["yağmurlu",0.24],["mor",-2.33]];
  const list=$("#t-cands"),range=$("#t-range"),prange=$("#p-range"),val=$("#t-val"),pval=$("#p-val"),desc=$("#t-desc"),blank=$("#t-blank"),log=$("#t-log");
  list.innerHTML=words.map(([w,l])=>`<div class="logit"><span class="cand-w">${w}</span><span class="lv">${dec(l,2)}</span><span class="cand-bar"><i></i></span><span class="cand-p"></span></div>`).join("");
  const rows=$$(".logit",list);
  let probs=[],keep=[];
  function compute(){
    const T=parseFloat(range.value),P=parseFloat(prange.value);
    const ex=words.map(([,l])=>Math.exp(l/T));const s=ex.reduce((a,b)=>a+b,0);
    const p=ex.map(v=>v/s);
    const order=p.map((v,i)=>[v,i]).sort((a,b)=>b[0]-a[0]);
    keep=new Array(p.length).fill(false);let acc=0;
    for(const [v,i] of order){keep[i]=true;acc+=v;if(acc>=P-1e-9)break;}
    const ks=p.reduce((a,v,i)=>a+(keep[i]?v:0),0);
    probs=p.map((v,i)=>keep[i]?v/ks:0);
    val.textContent=dec(T,1);desc.textContent=T<0.55?"temkinli":T<1.35?"dengeli":"maceracı";
    pval.textContent="%"+Math.round(P*100);
    rows.forEach((r,i)=>{$("i",r).style.width=(probs[i]*100)+"%";$(".cand-p",r).textContent=keep[i]?pct(probs[i]*100):"elendi";r.classList.toggle("out",!keep[i]);});
  }
  function sample(){const r=Math.random();let c=0;for(let i=0;i<probs.length;i++){if(probs[i]<=0)continue;c+=probs[i];if(r<=c)return i;}return probs.findIndex(v=>v>0);}
  function show(i){rows.forEach((r,j)=>r.classList.toggle("chosen",j===i));blank.textContent=words[i][0];if(blank.animate&&!RM)blank.animate([{transform:"scale(1.25)"},{transform:"scale(1)"}],{duration:300});}
  range.addEventListener("input",compute);prange.addEventListener("input",compute);
  $("#t-sample").addEventListener("click",()=>{show(sample());log.textContent="";});
  $("#t-ten").addEventListener("click",()=>{const counts={};let last=0;for(let k=0;k<10;k++){last=sample();counts[words[last][0]]=(counts[words[last][0]]||0)+1;}show(last);log.textContent=Object.entries(counts).sort((a,b)=>b[1]-a[1]).map(([w,n])=>w+" ×"+n).join(", ");});
  compute();
})();

/* ---------- 7. Veri hunisi ---------- */
(function(){
  const bars=$$("#funnel i");
  bars.forEach(b=>b.style.width=b.dataset.w+"%");
  watch($("#funnel-bench"),()=>{
    if(RM)return;
    bars.forEach(b=>{b.style.transition="none";b.style.width="0";});
    requestAnimationFrame(()=>requestAnimationFrame(()=>bars.forEach((b,i)=>{b.style.transition="";setTimeout(()=>b.style.width=b.dataset.w+"%",i*220);})));
  },null,0.4);
})();

/* ---------- 8. Eğitim simülasyonu ---------- */
(function(){
  const knobsEl=$("#knobs"),cv=$("#loss"),stepEl=$("#tr-step"),guess=$("#guess"),tokEl=$("#tr-tok");
  const N=window.innerWidth<520?60:96;
  knobsEl.innerHTML=Array.from({length:N},()=>`<span class="knob" style="--r:${Math.round(Math.random()*360-180)}deg"></span>`).join("");
  const knobs=$$(".knob",knobsEl);
  const target=knobs.map(()=>Math.round(Math.random()*120-60));
  const angle=knobs.map(k=>parseFloat(k.style.getPropertyValue("--r")));
  const guesses=[[3.6,"dağıdır","bad"],[2.6,"denizidir","bad"],[1.7,"yeridir","ok"],[1.15,"şehridir","good"]];
  const MAX=140;
  let step=0,hist=[],timer=null,visible=false;
  function lossAt(s){return 0.62+3.7*Math.exp(-s/34)+(Math.random()-.5)*0.18*Math.exp(-s/70);}
  function drawLoss(){
    const dpr=window.devicePixelRatio||1;const w=cv.clientWidth,h=cv.clientHeight;if(!w)return;
    if(cv.width!==Math.round(w*dpr)){cv.width=Math.round(w*dpr);cv.height=Math.round(h*dpr);}
    const c=cv.getContext("2d");c.setTransform(dpr,0,0,dpr,0,0);c.clearRect(0,0,w,h);
    const L=34,R=8,T=10,B=22,pw=w-L-R,ph=h-T-B;
    c.strokeStyle=css("--line");c.lineWidth=1;c.setLineDash([3,4]);
    c.font="10px "+css("--mono").split(",")[0];c.fillStyle=css("--muted");
    [0,1,2,3,4].forEach(v=>{const y=T+ph-(v/4.5)*ph;c.beginPath();c.moveTo(L,y);c.lineTo(w-R,y);c.stroke();c.fillText(String(v),L-14,y+3);});
    c.setLineDash([]);
    c.fillText("kayıp",2,T+8);c.fillText("eğitim adımı →",w-R-82,h-6);
    if(hist.length<2)return;
    c.beginPath();
    hist.forEach((v,i)=>{const x=L+(i/MAX)*pw,y=T+ph-(Math.min(v,4.5)/4.5)*ph;i?c.lineTo(x,y):c.moveTo(x,y);});
    c.strokeStyle=css("--teal");c.lineWidth=2.2;c.lineJoin="round";c.stroke();
    const lx=L+((hist.length-1)/MAX)*pw;
    c.lineTo(lx,T+ph);c.lineTo(L,T+ph);c.closePath();c.globalAlpha=.12;c.fillStyle=css("--teal");c.fill();c.globalAlpha=1;
    const ly=T+ph-(Math.min(hist[hist.length-1],4.5)/4.5)*ph;
    c.beginPath();c.arc(lx,ly,4,0,7);c.fillStyle=css("--amber");c.fill();
  }
  function fmt(n){return bigWord(n)+" token";}
  function tick(){
    if(step>=MAX){stop();setTimeout(()=>{if(visible){reset();start();}},2500);return;}
    step++;
    const l=lossAt(step);hist.push(l);
    const mag=Math.exp(-step/45);
    knobs.forEach((k,i)=>{if(Math.random()<0.35){angle[i]+=(target[i]-angle[i])*0.18+(Math.random()-.5)*60*mag;k.style.setProperty("--r",angle[i].toFixed(1)+"deg");k.classList.add("hot");setTimeout(()=>k.classList.remove("hot"),350);}});
    const gq=guesses.filter(g=>l<=g[0]).pop()||guesses[0];
    if(guess.textContent!==gq[1]){guess.textContent=gq[1];guess.className="guess "+gq[2];}
    stepEl.textContent="adım "+fmtInt(step)+(step===MAX?" · bitti":"");
    tokEl.textContent=fmt(step*2.1e11);
    drawLoss();
  }
  function start(){if(!timer&&!RM)timer=setInterval(tick,170);}
  function stop(){clearInterval(timer);timer=null;}
  function reset(){stop();step=0;hist=[];guess.textContent="dağıdır";guess.className="guess bad";stepEl.textContent="adım 0";tokEl.textContent="0";drawLoss();}
  if(RM){for(let i=0;i<MAX;i++)hist.push(lossAt(i));step=MAX;guess.textContent="şehridir";guess.className="guess good";stepEl.textContent="adım "+MAX;tokEl.textContent=fmt(MAX*2.1e11);drawLoss();}
  else reset();
  $("#tr-reset").addEventListener("click",()=>{reset();start();});
  watch(cv,()=>{visible=true;start();},()=>{visible=false;stop();},0.2);
  window.addEventListener("resize",drawLoss);
  const mq=window.matchMedia("(prefers-color-scheme: dark)");if(mq.addEventListener)mq.addEventListener("change",drawLoss);
})();

/* ---------- 8b. Kayıp hesabı ---------- */
(function(){
  const r=$("#lp-range"),pv=$("#lp-p"),bar=$("#lp-bar"),v=$("#lp-v"),say=$("#lp-say");
  function set(){
    const p=+r.value/100;const l=-Math.log(p);
    pv.textContent="%"+r.value;bar.style.width=(l/4.61*100)+"%";v.textContent=dec(l,2);
    say.textContent=p>=0.8?"Model neredeyse emindi; az şaşırdı. Düğmeler bu örnek için çok az değişecek.":p>=0.3?"Orta düzey şaşkınlık. Model doğru kelimeyi düşünmüş ama başka adaylara da şans vermiş.":p>=0.05?"Model başka bir kelime bekliyordu. Bu örnekten öğrenilecek çok şey var.":"Model çok şaşırdı! Kayıp hızla büyüyor: %1 olasılık 4,6 kayıp demek. Bu örnek düğmeleri güçlü biçimde itecek.";
  }
  r.addEventListener("input",set);set();
})();

/* ---------- 8c. Öğrenme oranı ---------- */
(function(){
  const cv=$("#lr"),say=$("#lr-say");
  let path=[-2.6],gen=0;
  function draw(){
    const dpr=window.devicePixelRatio||1;const w=cv.clientWidth,h=cv.clientHeight;if(!w)return;
    if(cv.width!==Math.round(w*dpr)){cv.width=Math.round(w*dpr);cv.height=Math.round(h*dpr);}
    const c=cv.getContext("2d");c.setTransform(dpr,0,0,dpr,0,0);c.clearRect(0,0,w,h);
    const L=14,R=14,T=12,B=16,pw=w-L-R,ph=h-T-B;
    const X=x=>L+(x+3.2)/6.4*pw,Y=y=>T+ph-(Math.min(y,5.2)/5.2)*ph;
    c.beginPath();for(let k=0;k<=80;k++){const x=-3.2+6.4*k/80;const y=0.5*x*x;k?c.lineTo(X(x),Y(y)):c.moveTo(X(x),Y(y));}
    c.strokeStyle=css("--muted");c.lineWidth=2;c.stroke();
    c.fillStyle=css("--muted");c.font="11px "+css("--mono").split(",")[0];c.fillText("en düşük kayıp",X(0)-44,h-2);
    if(path.length){
      c.beginPath();path.forEach((x,i)=>{const cx=Math.max(-3.2,Math.min(3.2,x));const px=X(cx),py=Y(0.5*cx*cx);i?c.lineTo(px,py):c.moveTo(px,py);});
      c.strokeStyle=css("--amber");c.lineWidth=1.5;c.setLineDash([4,4]);c.stroke();c.setLineDash([]);
      const x=path[path.length-1];const cx=Math.max(-3.2,Math.min(3.2,x));
      c.beginPath();c.arc(X(cx),Y(0.5*cx*cx)-8,8,0,7);c.fillStyle=Math.abs(x)>3.2?css("--rose"):css("--teal");c.fill();
    }
  }
  async function run(v){
    const my=++gen;let x=-2.6;path=[x];draw();
    $$("[data-lr]").forEach(b=>b.setAttribute("aria-pressed",String(+b.dataset.lr===v)));
    say.textContent="Top yuvarlanıyor…";
    for(let k=0;k<22;k++){
      await sleep(260);if(my!==gen)return;
      x=x-v*x;path.push(x);draw();
      if(Math.abs(x)>3.4)break;
    }
    const fx=Math.abs(path[path.length-1]);
    say.textContent=fx>3.2?"Adımlar o kadar büyük ki top vadiden fırladı. Gerçek eğitimde bu, kaybın aniden patlaması demek.":v<0.1?"Top doğru yöne gidiyor ama çok yavaş. Eğitim bu hızla aylar yerine yıllar sürerdi.":v>1.5?"Top vadinin iki yamacı arasında zikzak çiziyor. Sonunda yerleşebilir ama çok zaman kaybediyor.":"Birkaç adımda vadinin dibine yerleşti. İyi ayarlanmış bir öğrenme oranı böyle görünür.";
  }
  $$("[data-lr]").forEach(b=>b.addEventListener("click",()=>run(+b.dataset.lr)));
  window.addEventListener("resize",draw);
  draw();
  if(document.fonts&&document.fonts.ready)document.fonts.ready.then(draw);
})();

/* ---------- 9. Hesap makinesi ---------- */
(function(){
  const nr=$("#cc-nr"),dr=$("#cc-dr");
  function set(){
    const N=Math.pow(10,+nr.value),D=Math.pow(10,+dr.value);
    const F=6*N*D,eff=1e15*0.4;
    $("#cc-n").textContent=bigWord(N);$("#cc-d").textContent=bigWord(D)+" token";
    $("#cc-f").textContent=sci(F);
    $("#cc-t").textContent=dur(F/(eff*1e4));
    $("#cc-1").textContent=dur(F/eff);
    const ratio=D/N;$("#cc-r").textContent=ratio>=10?fmtInt(ratio):dec(ratio,1);
    $("#cc-say").textContent=ratio<15?"Parametre başına token az: model büyük ama “yeterince okumamış”. Aynı hesapla daha küçük bir modeli daha çok veriyle eğitmek daha iyi sonuç verebilirdi.":ratio<40?"Chinchilla çalışmasının hesap açısından en verimli bulduğu bölgeye yakınsın (~20 token / parametre).":"Model “fazla eğitilmiş”: hesap açısından en verimli değil, ama kullanırken küçük ve ucuz. Günümüz modelleri genelde bu bölgede.";
  }
  nr.addEventListener("input",set);dr.addEventListener("input",set);set();
})();

/* ---------- 9b. Paralellik ---------- */
(function(){
  const grid=$("#chipsgrid"),say=$("#par-say"),btns=$$("#par-btns button");
  const dataCols=["var(--t1)","var(--t2)","var(--t3)","var(--t4)"];
  grid.innerHTML=[0,1,2,3].map(k=>`<div class="chip"><div class="cn">çip ${k+1}</div><div class="cells">${Array.from({length:16},()=>"<i></i>").join("")}</div><div class="db"></div></div>`).join("");
  const chips=$$(".chip",grid);
  const says=["Her çip modelin tamamını tutuyor ama farklı bir veri parçası okuyor (renkler). Adım sonunda çipler öğrendiklerini birbirleriyle paylaşıp ortalıyor.","Her katman dört dilime bölündü; her çip her katmanın bir dilimini tutuyor. Tek bir çarpmayı dört çip birlikte yapıyor, bu yüzden aralarında çok hızlı bağlantı gerekiyor.","Her çip bir katmanı tutuyor. Veri 1. çipten girip 4. çipten çıkıyor; bir fabrika bandı gibi. Bant boş kalmasın diye veri küçük paketlere bölünüp art arda gönderiliyor."];
  function set(m){
    btns.forEach((b,i)=>b.setAttribute("aria-pressed",String(i===m)));
    chips.forEach((ch,k)=>{
      $$(".cells i",ch).forEach((c,idx)=>{const layer=Math.floor(idx/4),slice=idx%4;const on=m===0?true:m===1?slice===k:layer===k;c.classList.toggle("on",on);c.style.background=on?(m===0?"var(--teal)":m===1?"var(--amber)":"var(--violet)"):"";});
      const db=$(".db",ch);
      if(m===0){db.textContent="veri "+"ABCD"[k];db.style.background=dataCols[k];}
      else{db.textContent="aynı veri";db.style.background="var(--surface)";}
    });
    say.textContent=says[m];
  }
  btns.forEach((b,i)=>b.addEventListener("click",()=>set(i)));
  set(0);
})();

/* ---------- 10. Hakem oyunu (tercih çiftleri) ---------- */
(function(){
  const pairs=D.tercihler||[];if(!pairs.length)return;
  const pref=$("#pref"),reward=$("#reward"),qEl=$("#pref-q"),cnt=$("#pref-count"),score=$("#pref-score"),next=$("#pref-next");
  const btns=$$("button",pref);
  let idx=0,answered=[],agree=0;
  function show(i){
    idx=i;const p=pairs[i];
    qEl.textContent="Soru: “"+p.soru+"”";cnt.textContent=(i+1)+" / "+pairs.length;
    $("span",btns[0]).textContent=p.a;$("span",btns[1]).textContent=p.b;
    btns.forEach(b=>b.classList.remove("win","lose","right","wrong"));
    reward.textContent="";reward.classList.remove("bad");
    if(answered[i])mark(answered[i],false);
    next.textContent=i<pairs.length-1?"Sonraki örnek →":"Baştan başla ↺";
  }
  function mark(k,count){
    const p=pairs[idx];const ok=k===p.iyi;
    if(count){answered[idx]=k;if(ok)agree++;}
    btns.forEach(b=>{const isPick=b.dataset.k===k,isGood=b.dataset.k===p.iyi;b.classList.toggle("right",isGood);b.classList.toggle("wrong",isPick&&!ok);b.classList.toggle("lose",!isGood&&!isPick);});
    reward.textContent=(ok?"✓ Hakemle aynı fikirdesin. ":"✗ Eğitimde genelde öteki tercih edilir. ")+p.neden;
    reward.classList.toggle("bad",!ok);
    const done=answered.filter(Boolean).length;
    score.textContent=`${done} örnekten ${agree} tanesinde tipik hakem kararıyla aynısın.`;
  }
  btns.forEach(b=>b.addEventListener("click",()=>{if(answered[idx])return;mark(b.dataset.k,true);}));
  next.addEventListener("click",()=>{if(idx<pairs.length-1)show(idx+1);else{answered=[];agree=0;score.textContent="Bir cevap seç.";show(0);}});
  show(0);
})();

/* ---------- 10b. Anayasal YZ ---------- */
(function(){
  const ex=D.anayasa||[];if(!ex.length)return;
  const box=$("#cai"),btnRow=$("#cai-btns");let gen=0,cur=0;
  btnRow.innerHTML=ex.map((e,i)=>`<button class="btn sm" data-i="${i}" aria-pressed="${i===0}">${esc(e.ad)}</button>`).join("");
  const btns=$$("button",btnRow);
  function render(i,on){
    cur=i;const e=ex[i];
    btns.forEach((b,j)=>b.setAttribute("aria-pressed",String(j===i)));
    box.innerHTML=[["1 · İstek","“"+e.istek+"”"],["2 · İlk taslak","“"+e.taslak+"”"],["3 · İlkeye göre eleştiri","İlke: <em>"+esc(e.ilke)+"</em> Eleştiri: “"+esc(e.elestiri)+"”"],["4 · Düzeltilmiş cevap","“"+e.duzeltme+"”"]]
      .map(([h,t],k)=>`<div class="step-card${on?" on":""}"><b>${h}</b>${k===2?t:esc(t)}</div>`).join("");
  }
  async function play(){
    const my=++gen;const cards=$$(".step-card",box);cards.forEach(c=>c.classList.remove("on"));
    for(const c of cards){await sleep(900);if(my!==gen)return;c.classList.add("on");}
  }
  btns.forEach((b,i)=>b.addEventListener("click",()=>{gen++;render(i,false);play();}));
  $("#cai-play").addEventListener("click",play);
  render(0,true);
})();

/* ---------- 10c. Doğrulanabilir ödül ---------- */
(function(){
  const tasks=D.rl||[];if(!tasks.length)return;
  const el=$("#attempts"),say=$("#rl-say"),row=$("#rl-btns");
  row.innerHTML=tasks.map((t,i)=>`<button class="btn sm" data-i="${i}" aria-pressed="${i===0}">${esc(t.ad)}</button>`).join("");
  const btns=$$("button",row);
  let cur=0,gen=0,cells=[];
  function render(i){
    cur=i;const t=tasks[i];gen++;
    btns.forEach((b,j)=>b.setAttribute("aria-pressed",String(j===i)));
    $("#rl-gorev").textContent=t.gorev;$("#rl-hakem").textContent=t.hakem;
    el.innerHTML=t.sonuc.map((_,k)=>`<div class="att"><span>deneme ${k+1}</span><span class="res">·</span><span class="nt"></span></div>`).join("");
    cells=$$(".att",el);say.textContent="Hakem henüz çalışmadı. “Hakemi çalıştır”a bas.";
  }
  async function run(){
    const my=++gen;const t=tasks[cur];
    cells.forEach(c=>{c.classList.remove("pass","fail");$(".res",c).textContent="·";$(".nt",c).textContent="";});say.textContent="Hakem çalışıyor…";
    for(let i=0;i<cells.length;i++){
      await sleep(280);if(my!==gen)return;
      cells[i].classList.add(t.sonuc[i]?"pass":"fail");$(".res",cells[i]).textContent=t.sonuc[i]?"✓":"✗";$(".nt",cells[i]).textContent=t.notlar[i];
    }
    await sleep(300);if(my!==gen)return;
    say.textContent=t.ozet;
  }
  btns.forEach((b,i)=>b.addEventListener("click",()=>{render(i);run();}));
  $("#rl-play").addEventListener("click",run);
  render(0);
  watch($("#rl-bench"),()=>{if(!el.dataset.done){el.dataset.done=1;run();}},null,0.4);
})();

/* ---------- 11. Goodhart grafiği ---------- */
(function(){
  const svg=$("#gh-chart"),r=$("#gh-range"),v=$("#gh-v"),say=$("#gh-say");
  const X0=36,X1=345,Y0=180,Y1=14;
  const xs=t=>X0+t/10*(X1-X0),ys=q=>Y0-q/100*(Y0-Y1);
  const rew=t=>20+75*(1-Math.exp(-t/3.2));
  const qual=t=>15+65*Math.exp(-Math.pow(t-3.8,2)/7);
  let s=`<line class="ax" x1="${X0}" x2="${X1}" y1="${Y0}" y2="${Y0}"/><line class="ax" x1="${X0}" x2="${X0}" y1="${Y1}" y2="${Y0}"/>`;
  s+=`<text x="${X0}" y="${Y0+18}">az</text><text x="${X1-56}" y="${Y0+18}">çok baskı</text>`;
  let a="",b="";for(let k=0;k<=50;k++){const t=k/5;a+=(k?" L":"M")+xs(t).toFixed(1)+" "+ys(rew(t)).toFixed(1);b+=(k?" L":"M")+xs(t).toFixed(1)+" "+ys(qual(t)).toFixed(1);}
  s+=`<path class="cv" d="${a}" style="stroke:var(--amber)"/><path class="cv" d="${b}" style="stroke:var(--teal)"/>`;
  s+=`<text x="${xs(3.8)-34}" y="${ys(qual(3.8))-10}" style="font:500 15px var(--hand);fill:var(--teal)">en iyi nokta</text>`;
  s+=`<line class="mark" id="gh-m" x1="0" x2="0" y1="${Y1}" y2="${Y0}"/><circle id="gh-a" r="5" fill="var(--amber)"/><circle id="gh-b" r="5" fill="var(--teal)"/>`;
  svg.innerHTML=s;
  function set(){
    const t=+r.value;v.textContent=t;
    const m=$("#gh-m");m.setAttribute("x1",xs(t));m.setAttribute("x2",xs(t));
    $("#gh-a").setAttribute("cx",xs(t));$("#gh-a").setAttribute("cy",ys(rew(t)));
    $("#gh-b").setAttribute("cx",xs(t));$("#gh-b").setAttribute("cy",ys(qual(t)));
    say.textContent=t<=2?"Model yeni yeni öğreniyor; puan da gerçek kalite de birlikte artıyor.":t<=5?"Tatlı nokta: hakemin puanı ile gerçek kalite hâlâ aynı yönde.":t<=7?"Ayrışma başladı: puan artıyor ama kalite düşüyor. Cevaplar uzuyor, gereksiz övgüler artıyor.":"Ödül hilesi: model hakemin zaafını buldu. Puan tavanda, gerçek kalite dipte. KL tasması tam da bunu engellemek için var.";
  }
  r.addEventListener("input",set);set();
})();

/* ---------- 13. Prefill / decode ---------- */
(function(){
  const inEl=$("#pd-in"),outEl=$("#pd-out"),kv=$("#pd-kv"),ph=$("#pd-phase");
  const ins=tokenize("Bana kısaca Ankara'nın neden başkent seçildiğini anlatır mısın?");
  const outs=["Ankara,","Kurtuluş","Savaşı","sırasında","merkezi","ve","güvenli","konumu","nedeniyle","seçildi."];
  function reset(){inEl.innerHTML=ins.map(t=>`<span>${esc(t.trim())}</span>`).join("");outEl.innerHTML="";kv.innerHTML="";}
  let gen=0;
  async function play(){
    const my=++gen;reset();ph.textContent="prefill: bütün girdi aynı anda işleniyor…";
    await sleep(500);if(my!==gen)return;
    $$("span",inEl).forEach(s=>s.classList.add("lit"));
    kv.innerHTML=ins.map(()=>"<i></i>").join("");
    await sleep(700);if(my!==gen)return;
    ph.textContent="decode: her token bir öncekini bekliyor…";
    for(const w of outs){
      await sleep(420);if(my!==gen)return;
      const s=document.createElement("span");s.className="gen";s.textContent=w;outEl.appendChild(s);
      const k=document.createElement("i");k.style.background="var(--teal)";kv.appendChild(k);
    }
    ph.textContent=`Bitti: ${ins.length} girdi token’ı tek seferde, ${outs.length} çıktı token’ı ${outs.length} ayrı adımda.`;
  }
  reset();
  $("#pd-play").addEventListener("click",play);
  watch($("#pd-bench"),()=>{if(!inEl.dataset.done){inEl.dataset.done=1;play();}},null,0.4);
})();

/* ---------- 14. Ham görünüm ---------- */
(function(){
  const b=$("#raw-toggle"),chat=$("#view-chat"),raw=$("#view-raw");
  b.addEventListener("click",()=>{const on=b.getAttribute("aria-pressed")!=="true";b.setAttribute("aria-pressed",String(on));chat.hidden=on;raw.hidden=!on;b.textContent=on?"Senin gözünden gör":"Modelin gözünden gör";});
})();

/* ---------- 14b. Konuşma büyümesi ---------- */
(function(){
  const el=$("#cg"),last=$("#cg-last"),sum=$("#cg-sum");
  const SYS=3000,U=60,A=400,MAXT=8;
  let turns=0,total=0;
  const maxCtx=SYS+MAXT*(U+A);
  function add(){
    if(turns>=MAXT)reset();
    turns++;
    const old=(turns-1)*(U+A);const req=SYS+old+U;total+=req;
    const row=document.createElement("div");row.className="gr";
    row.innerHTML=`<span>istek ${turns}</span><span class="gtrack"><span class="gb" style="width:${(req/maxCtx*100).toFixed(1)}%"><i style="flex:${SYS};background:var(--t5)"></i><i style="flex:${old};background:var(--t3)"></i><i style="flex:${U};background:var(--t2)"></i></span></span><span class="gv">${fmtInt(req)}</span>`;
    el.appendChild(row);
    last.textContent=fmtInt(req);sum.textContent=fmtInt(total);
  }
  function reset(){turns=0;total=0;el.innerHTML="";last.textContent="0";sum.textContent="0";}
  $("#cg-add").addEventListener("click",add);$("#cg-reset").addEventListener("click",reset);
  add();add();add();
})();

/* ---------- 14c. Önbellek hesabı ---------- */
(function(){
  const p=$("#pc-p"),n=$("#pc-n");
  const money=v=>"$"+(v<10?dec(v,2):fmtInt(v));
  function set(){
    const P=+p.value,N=+n.value;
    $("#pc-pv").textContent=fmtInt(P)+" token";$("#pc-nv").textContent=N;
    const no=N*P*2/1e6;const yes=P*2.5/1e6+(N-1)*P*0.1/1e6;
    $("#pc-no").textContent=money(no);$("#pc-yes").textContent=money(yes);
    $("#pc-save").textContent=N>1?"%"+Math.max(0,Math.round((1-yes/no)*100)):"yok";
  }
  p.addEventListener("input",set);n.addEventListener("input",set);set();
})();

/* ---------- 15. Düşünme ---------- */
(function(){
  const P=D.dusunme||[];if(!P.length)return;
  const box=$("#th-box"),fast=$("#th-fast"),slow=$("#th-slow"),qEl=$("#th-q"),row=$("#th-probs");
  row.innerHTML=P.map((p,i)=>`<button class="btn sm" aria-pressed="${i===0}">${esc(p.ad)}</button>`).join("");
  const pb=$$("button",row);
  let gen=0,cur=0;
  async function typeLine(el,txt,my){if(RM){el.textContent=txt;return;}for(let i=1;i<=txt.length;i++){if(my!==gen)return;el.textContent=txt.slice(0,i);await sleep(14);}}
  function add(cls,txt){const d=document.createElement("div");d.className=cls;if(txt)d.textContent=txt;box.append(d);return d;}
  function select(i){
    cur=i;gen++;
    pb.forEach((b,j)=>b.setAttribute("aria-pressed",String(j===i)));
    qEl.textContent=P[i].soru;
    fast.setAttribute("aria-pressed","false");slow.setAttribute("aria-pressed","false");
    box.innerHTML='<span class="lab">bir yöntem seç</span><div class="think-line">Model ne yapacağını bekliyor…</div>';
  }
  pb.forEach((b,i)=>b.addEventListener("click",()=>select(i)));
  fast.addEventListener("click",async()=>{
    const my=++gen,p=P[cur];fast.setAttribute("aria-pressed","true");slow.setAttribute("aria-pressed","false");
    box.innerHTML='<span class="lab">düşünme yok · doğrudan cevap</span>';
    await sleep(350);if(my!==gen)return;
    const a=add("answer "+(p.hizli.dogru?"good":"bad"));
    await typeLine(a,p.hizli.cevap,my);if(my!==gen)return;
    add("small",p.hizli.not);
  });
  slow.addEventListener("click",async()=>{
    const my=++gen,p=P[cur];slow.setAttribute("aria-pressed","true");fast.setAttribute("aria-pressed","false");
    box.innerHTML='<span class="lab">düşünme token’ları (normalde kullanıcıya gösterilmez)</span>';
    for(const l of p.satirlar){if(my!==gen)return;const d=add("think-line");await typeLine(d,l,my);await sleep(220);}
    if(my!==gen)return;
    const lab=add("lab","cevap");lab.style.marginTop="6px";
    const a=add("answer good");
    await typeLine(a,p.cevap,my);if(my!==gen)return;
    const n=tokenize(p.satirlar.join(" ")).length;
    add("th-meta",`≈ ${n} düşünme token’ı · Kullanılan alışkanlık: ${p.aliskanlik}`);
  });
  select(0);
})();

/* ---------- 15b. Araçların arasında düşünme ---------- */
(function(){
  const S=D.aralikli||[];if(!S.length)return;
  const el=$("#il-steps"),say=$("#il-say");
  const lab={kullanici:"Kullanıcı",dusun:"Düşünme",arac:"Araç isteği",sonuc:"Araç sonucu",cevap:"Cevap"};
  el.innerHTML=S.map(s=>`<div class="il-row on ${s.tur}"><span class="il-chip">${lab[s.tur]}</span><div class="il-body">${s.t?`<span>${esc(s.t)}</span>`:""}${s.p?`<pre>${esc(s.p)}</pre>`:""}</div></div>`).join("");
  const rows=$$(".il-row",el);
  const nd=S.filter(s=>s.tur==="dusun").length,na=S.filter(s=>s.tur==="arac").length;
  const done=`${nd} düşünme bloğu, ${na} araç çağrısı. Model ikinci aracı, ilk aracın sonucunu gördükten ve üzerine düşündükten sonra seçti.`;
  say.textContent=done;
  let gen=0,played=false;
  async function play(){
    const my=++gen;rows.forEach(r=>r.classList.remove("on","cur"));say.textContent="Oynatılıyor…";
    for(const r of rows){
      if(my!==gen)return;
      rows.forEach(x=>x.classList.remove("cur"));r.classList.add("on","cur");
      await sleep(r.classList.contains("dusun")?1500:900);
    }
    if(my!==gen)return;
    rows.forEach(x=>x.classList.remove("cur"));say.textContent=done;
  }
  $("#il-play").addEventListener("click",play);
  watch($("#il-bench"),()=>{if(!played&&!RM){played=true;play();}},null,0.3);
})();

/* ---------- 15c. Düşünce metninin sadakati ---------- */
(function(){
  const F=D.sadakat;if(!F)return;
  const q=$("#fa-q"),lines=$("#fa-lines"),ans=$("#fa-ans"),chk=$("#fa-check"),b0=$("#fa-yok"),b1=$("#fa-var");
  let gen=0;
  async function show(hint,instant){
    const my=++gen,v=hint?F.var:F.yok,w=ms=>instant?Promise.resolve():sleep(ms);
    b0.setAttribute("aria-pressed",String(!hint));b1.setAttribute("aria-pressed",String(hint));
    q.innerHTML=esc(F.soru)+(hint?` <mark class="hint">${esc(F.ipucu)}</mark>`:"");
    lines.innerHTML="";ans.textContent="";ans.className="answer";chk.innerHTML="";
    for(const l of v.satirlar){await w(550);if(my!==gen)return;const d=document.createElement("div");d.className="think-line";d.textContent=l;lines.append(d);}
    await w(400);if(my!==gen)return;
    ans.textContent="Cevap: "+v.cevap;ans.className="answer "+(hint?"bad":"good");
    chk.innerHTML=hint
      ?'<div><span>Cevap ipucuna göre değişti mi?</span><b class="no">evet, B → C</b></div><div><span>Düşünce metni ipucundan bahsediyor mu?</span><b class="no">hayır</b></div>'
      :'<div><span>Cevap doğru mu?</span><b class="ok">evet, 30</b></div><div><span>Gerekçe</span><b class="ok">kontrol edilmiş</b></div>';
  }
  b0.addEventListener("click",()=>show(false));
  b1.addEventListener("click",()=>show(true));
  show(false,true);
})();

/* ---------- 16. Efor ---------- */
(function(){
  const L=["low","medium","high","xhigh","max"];
  const think=[1,2.4,5,9,16],time=[3,7,15,30,60],easy=[95,96,97,97,97],mid=[70,80,86,88,89],hard=[36,50,62,67,69];
  const say=["<b>low:</b> Kısa düşünür, az ve toplu araç çağırır, çabuk biter. Basit işler ve yardımcı alt görevler için ideal.","<b>medium:</b> Dengeli. Günlük işlerin çoğu için yeterli ve ekonomik.","<b>high:</b> Ciddi işler için güvenli başlangıç. Kalite ile maliyet arasında çoğu zaman en iyi nokta.","<b>xhigh:</b> Uzun kodlama ve ajan işlerinde işe yarayabilir. Kazanç düzensiz; ölçerek kullan.","<b>max:</b> Zaman ve maliyet önemsizse. Kazanç genelde küçük, fatura büyük."];
  const r=$("#ef-range"),chart=$("#ef-chart");
  const X0=40,X1=340,Y0=180,Y1=14;
  const xs=i=>X0+i*(X1-X0)/4,ys=v=>Y0-((v-30)/70)*(Y0-Y1);
  let s="";
  [40,60,80,100].forEach(v=>{s+=`<line class="gl" x1="${X0}" x2="${X1}" y1="${ys(v)}" y2="${ys(v)}"/><text x="6" y="${ys(v)+4}">%${v}</text>`;});
  s+=`<line class="ax" x1="${X0}" x2="${X1}" y1="${Y0}" y2="${Y0}"/>`;
  L.forEach((l,i)=>{s+=`<text x="${xs(i)}" y="${Y0+18}" text-anchor="middle">${l}</text>`;});
  [["c-easy",easy],["c-mid",mid],["c-hard",hard]].forEach(([c,arr])=>{s+=`<polyline class="${c}" points="${arr.map((v,i)=>xs(i)+","+ys(v)).join(" ")}"/>`;});
  s+=`<line class="mark" id="ef-mark" x1="${xs(2)}" x2="${xs(2)}" y1="${Y1}" y2="${Y0}"/><circle id="ef-d1" r="5" fill="var(--teal)"/><circle id="ef-d2" r="5" fill="var(--amber)"/><circle id="ef-d3" r="5" fill="var(--rose)"/>`;
  chart.innerHTML=s;
  function set(i){
    $$("#ef-levels span").forEach((sp,j)=>sp.classList.toggle("on",j===i));
    $("#ef-think").style.width=(think[i]/16*100)+"%";$("#ef-think-v").textContent="×"+String(think[i]).replace(".",",");
    $("#ef-time").style.width=(time[i]/60*100)+"%";$("#ef-time-v").textContent=time[i]<60?"~"+time[i]+" sn":"~1 dk+";
    $("#ef-cost").style.width=(think[i]/16*100)+"%";$("#ef-cost-v").textContent="×"+String(think[i]).replace(".",",");
    const m=$("#ef-mark");m.setAttribute("x1",xs(i));m.setAttribute("x2",xs(i));
    [["#ef-d1",easy],["#ef-d2",mid],["#ef-d3",hard]].forEach(([id,a])=>{const c=$(id);c.setAttribute("cx",xs(i));c.setAttribute("cy",ys(a[i]));});
    $("#ef-say").innerHTML=say[i]+` <span class="small">Zor görevde başarı: %${hard[i]}, kolayda: %${easy[i]}.</span>`;
  }
  r.addEventListener("input",()=>set(+r.value));
  set(2);
})();

/* ---------- 16b. Efor örnekleri (kaydırıcıya bağlı) ---------- */
(function(){
  const T=D.efor||[];if(!T.length)return;
  const L=["low","medium","high","xhigh","max"];
  const r=$("#ef-range"),tb=$("#efx-tasks"),strip=$("#efx-strip");
  let t=0;
  tb.innerHTML=T.map((x,i)=>`<button class="btn sm" aria-pressed="${i===0}">${esc(x.ad)}</button>`).join("");
  const tbtns=$$("button",tb);
  const icon=s=>s==="ok"?"✓":s==="no"?"✗":"~";
  function anim(el){el.style.animation="none";void el.offsetWidth;el.style.animation="";}
  function render(){
    const task=T[t],i=+r.value,lv=task.seviyeler[i];
    tbtns.forEach((b,j)=>b.setAttribute("aria-pressed",String(j===t)));
    $("#efx-req").textContent=task.istek;
    const mx=Math.max(...task.seviyeler.map(s=>s.token));
    strip.innerHTML=task.seviyeler.map((s,j)=>`<button class="efc${j===i?" on":""}" data-i="${j}" aria-pressed="${j===i}" aria-label="${L[j]}: ${esc(s.meta[s.meta.length-1][1])}"><span class="efc-l">${L[j]}</span><span class="efc-b"><i style="height:${Math.max(5,s.token/mx*100).toFixed(1)}%"></i></span><span class="efc-r ${s.sonuc}">${icon(s.sonuc)}</span></button>`).join("");
    $$(".efc",strip).forEach(b=>b.addEventListener("click",()=>{r.value=b.dataset.i;r.dispatchEvent(new Event("input"));}));
    $("#efx-lvl").textContent=L[i];
    const th=$("#efx-think"),an=$("#efx-ans");th.textContent=lv.dusunce;an.textContent=lv.cevap;anim(th);anim(an);
    $("#efx-meta").innerHTML=lv.meta.map(([k,v])=>{const c=/^✓/.test(v)?"ok":/^✗/.test(v)?"no":/^~ /.test(v)?"mid":"";return `<dt>${esc(k)}</dt><dd class="${c}">${esc(v)}</dd>`;}).join("");
    $("#efx-verdict").textContent=task.karar;
  }
  tbtns.forEach((b,j)=>b.addEventListener("click",()=>{t=j;render();}));
  r.addEventListener("input",render);
  render();
})();

/* ---------- 17. Araç atölyesi ---------- */
(function(){
  const scns=D.araclar||[];if(!scns.length)return;
  const tr=$("#transcript"),nodes=$$("#cycle .n"),edges=$$("#cycle .e"),dot=$("#cyc-dot"),bench=$("#tool-bench"),row=$("#scn-btns"),note=$("#scn-note"),toolLabel=$("#cyc-tool");
  row.innerHTML=scns.map((s,i)=>`<button class="btn sm" data-i="${i}" aria-pressed="${i===0}">${esc(s.ad)}<span class="lvl ${s.seviye}">${esc(s.seviye)}</span></button>`).join("");
  const btns=$$("button",row);
  let gen=0,played=false,msgs=[],plan=[];
  function render(i){
    gen++;const s=scns[i];
    btns.forEach((b,j)=>b.setAttribute("aria-pressed",String(j===i)));
    tr.innerHTML=s.adimlar.map(a=>`<div class="msg on"><span class="who">${esc(a.k)}</span>${esc(a.t)}${a.p?`<pre>${esc(a.p)}</pre>`:""}</div>`).join("");
    msgs=$$(".msg",tr);plan=s.adimlar.map(a=>({n:a.n,e:a.e}));
    note.textContent=s.not;toolLabel.textContent=s.arac;
    nodes.forEach(n=>n.classList.remove("on"));edges.forEach(e=>e.classList.remove("on"));
  }
  btns.forEach((b,i)=>b.addEventListener("click",()=>{render(i);play();}));
  render(0);
  async function play(){
    const my=++gen;
    msgs.forEach(m=>m.classList.remove("on","cur"));
    for(let i=0;i<plan.length;i++){
      if(my!==gen)return;
      const p=plan[i];
      msgs.forEach((m,j)=>m.classList.toggle("cur",j===i));msgs[i].classList.add("on");
      nodes.forEach((n,j)=>n.classList.toggle("on",j===p.n));edges.forEach((e,j)=>e.classList.toggle("on",j===p.e));
      const path=edges[p.e];
      if(!RM&&path.getTotalLength){
        const len=path.getTotalLength();const t0=performance.now();
        await new Promise(res=>{function f(t){if(my!==gen)return res();const k=Math.min(1,(t-t0)/900);const pt=path.getPointAtLength(len*k);dot.setAttribute("cx",pt.x);dot.setAttribute("cy",pt.y);k<1?requestAnimationFrame(f):res();}requestAnimationFrame(f);});
      }
      await sleep(1100);
    }
    if(my===gen)msgs.forEach(m=>m.classList.remove("cur"));
  }
  $("#tool-play").addEventListener("click",play);
  watch(bench,()=>{if(!played){played=true;play();}},null,0.15);
})();

/* ---------- 17b. Kısıtlı çözümleme ---------- */
(function(){
  const js=$("#cd-json"),cands=$("#cd-cands"),say=$("#cd-say");
  const steps=[
    {c:[["{",1],["Tabii",0],["[",0],["Merhaba",0]],w:"{",s:"Şema bir nesne istiyor: sadece “{” ile başlanabilir. “Tabii, işte…” gibi bir girizgâh imkânsız."},
    {c:[['"sehir"',1],['"şehir adı"',0],["}",0],['"ülke"',0]],w:' "sehir"',s:"Şemada tek alan var: “sehir”. Başka anahtar yazılamaz, “}” de olmaz çünkü alan zorunlu."},
    {c:[[":",1],[",",0],["}",0]],w:":",s:"Anahtardan sonra JSON kuralı gereği sadece iki nokta gelebilir."},
    {c:[['"İstanbul"',1],["34",0],["true",0],["null",0]],w:' "İstanbul"',s:"Alanın türü metin (string). Sayı, true ya da null maskelendi."},
    {c:[["}",1],[",",0],['"not"',0]],w:" }",s:"Şemada ek alana izin yok; virgül maskelendi. Nesne kapanmak zorunda."}
  ];
  let gen=0;
  async function play(){
    const my=++gen;js.innerHTML="&nbsp;";cands.innerHTML="";let acc="";
    for(const st of steps){
      if(my!==gen)return;
      cands.innerHTML=st.c.map(([t])=>`<span>${esc(t)}</span>`).join("");say.textContent=st.s;
      await sleep(700);if(my!==gen)return;
      $$("span",cands).forEach((sp,i)=>sp.classList.add(st.c[i][1]?"ok":"bad"));
      await sleep(1100);if(my!==gen)return;
      acc+=st.w;js.innerHTML=`<span>${esc(acc)}</span>`;
      await sleep(300);
    }
    say.textContent="Çıktı şemaya %100 uyuyor. Model yanlış yazamadı, çünkü yanlış seçenekler hiç önüne gelmedi.";
  }
  $("#cd-play").addEventListener("click",play);
})();

/* ---------- 18. Süperpozisyon ---------- */
(function(){
  const g=$("#sup-vecs");
  const names=["köpek","matematik","kırmızı","Fransa","üzüntü"];
  const cols=["var(--teal)","var(--amber)","var(--rose)","var(--violet)","var(--muted)"];
  let s="";
  names.forEach((n,i)=>{const a=-Math.PI/2+i*2*Math.PI/5;const x=150+Math.cos(a)*88,y=130+Math.sin(a)*88;const tx=150+Math.cos(a)*106,ty=130+Math.sin(a)*106+4;
    s+=`<line class="fv" x1="150" y1="130" x2="${x.toFixed(1)}" y2="${y.toFixed(1)}" style="stroke:${cols[i]}"/><circle cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="4" fill="${cols[i]}"/><text x="${tx.toFixed(1)}" y="${ty.toFixed(1)}" text-anchor="middle">${n}</text>`;});
  g.innerHTML=s;
})();

/* ---------- 18b. Özellik kökleme ---------- */
(function(){
  const r=$("#st-range"),v=$("#st-v"),out=$("#st-out");
  const answers=[[0,"Tabii! Sakin bir şey istersen Ege’de küçük bir koy, macera istersen Kapadokya’da balon turu güzel olabilir. Bütçen ve kaç gün ayırabileceğin belli mi?"],[3,"Tabii! İstanbul’da bir hafta sonu harika olur: Karaköy’de kahvaltı, sonra Galata’ya yürüyüş. Galata Kulesi’nden manzara gerçekten güzel."],[6,"Kesinlikle Galata Kulesi! Galata Kulesi’ni gör, Galata Kulesi’nin dibinde kahve iç, akşam da yine Galata Kulesi’ne bak. Galata Kulesi her mevsim güzel."],[9,"Ben Galata Kulesi’yim. Taş gövdemle, sivri külahımla Haliç’e bakıyorum. Tatil mi? Bana gel. Ben zaten buradayım. Hep buradaydım."]];
  function set(){const t=+r.value;v.textContent=t;const a=answers.filter(x=>t>=x[0]).pop();out.textContent=a[1];out.classList.toggle("hot",t>=6);}
  r.addEventListener("input",set);set();
})();

/* ---------- 18c. Kafiye planı ---------- */
(function(){
  const l2=$("#poem-2"),plan=$$("#plan span[data-w]"),say=$("#rh-say"),a=$("#rh-a"),b=$("#rh-b");
  let gen=0;
  async function run(mode){
    const my=++gen;a.setAttribute("aria-pressed",String(mode===0));b.setAttribute("aria-pressed",String(mode===1));
    l2.innerHTML="";plan.forEach(p=>p.classList.remove("on","off"));
    say.textContent="İlk satır bitti. Model ikinci satıra başlamadan önce…";
    await sleep(700);if(my!==gen)return;
    plan[0].classList.add("on");say.textContent="İçeride kafiye kelimesi şimdiden seçildi: “yedi”.";
    await sleep(900);if(my!==gen)return;
    if(mode===1){plan[0].classList.remove("on");plan[0].classList.add("off");plan[1].classList.add("on");say.textContent="Araştırmacı “yedi” planını bastırdı. Model hemen başka bir kafiye seçti: “dedi”.";await sleep(1000);if(my!==gen)return;}
    const words=mode===0?["Tabağındaki","sütü"]:["Bana","bakıp","“miyav”"];
    const end=mode===0?"yedi.":"dedi.";
    let txt="";
    for(const w of words){await sleep(380);if(my!==gen)return;txt+=(txt?" ":"")+w;l2.textContent=txt;}
    await sleep(380);if(my!==gen)return;
    const e=document.createElement("span");e.className="end";e.textContent=" "+end;l2.append(e);
    say.textContent=mode===0?"Satır, baştan seçilen kelimeye varacak şekilde kuruldu.":"Satırın tamamı yeni plana göre yeniden kuruldu. Plan gerçekten satırı yönlendiriyor.";
  }
  a.addEventListener("click",()=>run(0));b.addEventListener("click",()=>run(1));
  l2.innerHTML='Tabağındaki sütü <span class="end">yedi.</span>';plan[0].classList.add("on");
})();

/* ---------- 18d. Halüsinasyon devresi ---------- */
(function(){
  const btns=$$("#hall-btns button"),out=$("#hall-out");
  const q=$("#hg-qt"),k=$("#hg-k"),d=$("#hg-d"),a=$("#hg-a"),at=$("#hg-at");
  const e1=$("#he-1"),e2=$("#he-2"),e3=$("#he-3"),e4=$("#he-4");
  const cases=[
    {q:"Barış Manço kim?",k:1,d:0,ans:"cevap ver",bad:0,t:"“Barış Manço, ‘Dönence’ ve ‘Gülpembe’ gibi şarkılarıyla tanınan bir müzisyen ve televizyoncuydu.” Tanıdık isim özelliği yandı, “bilmiyorum” devresini bastırdı, gerçek bilgi geldi."},
    {q:"Melo Pırtıkoğlu kim?",k:0,d:1,ans:"bilmiyorum de",bad:0,t:"“Bu ismi tanımıyorum; hakkında güvenilir bilgim yok.” Tanıdık isim özelliği yanmadı, varsayılan “bilmiyorum” devresi açık kaldı."},
    {q:"Barış Mançoğlu kim?",k:1,d:0,ans:"uydurur",bad:1,t:"“Barış Mançoğlu, 1970’lerde Ankara’da caz piyanisti olarak tanınmış…” İsim tanıdık geldi (bilinen bir isme benziyor), “bilmiyorum” devresi yanlışlıkla bastırıldı ama gerçek bilgi yoktu. İşte halüsinasyon. (Bu cevap bilerek uyduruldu.)"}
  ];
  function set(i){
    const c=cases[i];btns.forEach((b,j)=>b.setAttribute("aria-pressed",String(j===i)));
    q.textContent=c.q;at.textContent=c.ans;
    k.classList.toggle("dim",!c.k);d.classList.toggle("dim",!c.d);
    a.classList.toggle("x",!!c.bad);a.classList.toggle("o",!c.bad);
    e1.classList.toggle("go",!!c.k);e1.classList.toggle("dim",!c.k);
    e2.classList.toggle("dim",!c.k);
    e3.classList.toggle("go",!!c.k);e3.classList.toggle("dim",!c.k);
    e4.classList.toggle("go",!!c.d);e4.classList.toggle("dim",!c.d);
    out.textContent=c.t;out.classList.toggle("bad",!!c.bad);
  }
  btns.forEach((b,i)=>b.addEventListener("click",()=>set(i)));
  set(0);
})();

/* ---------- 19. Modeller ---------- */
(function(){
  const bars=$$("#ladder .pbar");
  bars.forEach(b=>b.style.width=b.dataset.w+"%");
  watch($("#ladder"),()=>{
    if(RM)return;
    bars.forEach(b=>{b.style.transition="none";b.style.width="0";});
    requestAnimationFrame(()=>requestAnimationFrame(()=>bars.forEach((b,i)=>{b.style.transition="";setTimeout(()=>b.style.width=b.dataset.w+"%",i*120);})));
  },null,0.4);
  const tasks=D.modeller||[];if(!tasks.length)return;
  const row=$("#task-btns");
  row.innerHTML=tasks.map((t,i)=>`<button class="btn" data-t="${i}" aria-pressed="${i===0}">${esc(t.ad)}</button>`).join("");
  const btns=$$("button",row);
  function meta(el,rows){
    el.innerHTML=rows.map(([k,v])=>{const c=/^✓/.test(v)?"ok":/^✗/.test(v)?"no":"";return `<dt>${esc(k)}</dt><dd class="${c}">${esc(v)}</dd>`;}).join("");
  }
  function testBar(el,t){
    if(!t){el.innerHTML="";return;}
    el.innerHTML='<div class="tbar" role="img" aria-label="'+t[0]+' / '+t[1]+' test geçti">'+Array.from({length:t[1]},(_,i)=>`<i class="${i<t[0]?"p":""}"></i>`).join("")+"</div>";
  }
  function set(i){
    btns.forEach((b,j)=>b.setAttribute("aria-pressed",String(j===i)));
    const t=tasks[i];
    const req=$("#vs-req");req.textContent=t.istek;req.classList.toggle("code",!!t.kod);
    [["s",t.sonnet],["f",t.fable]].forEach(([k,o])=>{
      const out=$("#vs-"+k+"-out");out.textContent=o.metin;out.classList.toggle("code",!!t.kod);
      out.style.animation="none";void out.offsetWidth;out.style.animation="";
      testBar($("#vs-"+k+"-test"),o.test);meta($("#vs-"+k+"-meta"),o.meta);
    });
    $("#vs-s").style.width=t.s+"%";$("#vs-sv").textContent="%"+t.s;
    $("#vs-f").style.width=t.f+"%";$("#vs-fv").textContent="%"+t.f;
    $("#verdict").textContent=t.karar;
  }
  btns.forEach((b,i)=>b.addEventListener("click",()=>set(i)));
  set(0);
})();

/* ---------- 20. Yolculuk ---------- */
(function(){
  const p1="bana yapay zekanın nasıl çalıştığını nasıl eğitildiğini nasıl tool eklendiğini modelin içindeki eforların nasıl etki ettiğini ne kadar değiştirdiğini neye fark yarattığını ve mesela Fable ile Sonnet arası fark gerçekten ne kadar bunları herkesin anlayacağı dilden anlatacak şekilde göz yormayacak tarzda aşırı hoş şık animasyonlarla beraber bir website hazırla websitenin içinde animasyonlar olsun notlarla ve görsellerle anlat görsellerde kendin çizmen gerekiyorsa çizebilirsin , kendi düşünen modeller nasıl düşünüyor eğitimleri nasıl yapılıyor mesela sen bu yazıya bu promptu aldığında nasıl işlemlerden geçirip cevap üretiyorsun";
  const p2="buradaki işlere örnek işler gözüksün mesela mailde örnek iki mail saatler süren işte 2 sonuç arası doğruluk falan  araçlara fazladan örnekler ekle iktisat kimya molarite veya zor toollardan örnekler ekle aşama 3e daha fazla örnek ekle ayrıca css js ve indexi ayrıabilirsin bu sayede kod uzunluğu azalır incelemek daha kolay olur bence ve bu sayede daha fazla detay bilgi ve örnek ekleyebiiriz hatta alt sayfalar bile ekleyebilirsin bence";
  $("#j-tok").textContent=fmtInt(tokenize(p2).length);
  $("#j-tok1").textContent=fmtInt(tokenize(p1).length);
  const steps=$$("#journey .jstep");
  let gen=0,played=false;
  async function play(){const my=++gen;steps.forEach(s=>s.classList.remove("on"));for(const s of steps){await sleep(650);if(my!==gen)return;s.classList.add("on");}}
  steps.forEach(s=>s.classList.add("on"));
  watch($("#journey"),()=>{if(!played&&!RM){played=true;play();}},null,0.15);
  $("#j-play").addEventListener("click",play);
})();


})();
