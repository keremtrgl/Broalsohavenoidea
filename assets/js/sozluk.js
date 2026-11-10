/* Sözlük sayfası: arama kutusu */
(function(){
  "use strict";
  const q=document.getElementById("gl-q");
  const items=Array.from(document.querySelectorAll("#gloss > div"));
  const none=document.getElementById("gl-none");
  const count=document.getElementById("gl-count");
  const norm=s=>s.toLocaleLowerCase("tr").normalize("NFD").replace(/[̀-ͯ]/g,"").replace(/ı/g,"i");
  function run(){
    const v=norm(q.value.trim());let shown=0;
    /* Türkçede kelime sonu yumuşar (önbellek → önbelleği), bu yüzden son harfi atılmış hâlini de dene */
    const vs=v.length>=5?[v,v.slice(0,-1)]:[v];
    items.forEach(it=>{const t=norm(it.textContent);const ok=!v||vs.some(x=>t.includes(x));it.hidden=!ok;if(ok)shown++;});
    none.hidden=shown>0;
    count.textContent=v?shown+" / "+items.length+" terim":items.length+" terim";
  }
  q.addEventListener("input",run);
  run();
})();
