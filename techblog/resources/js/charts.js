// Charts (Chart.js, bundled locally and pinned in package.json) and the AI data widgets.
// Charts are created only when they scroll into view and are rebuilt when the theme changes.
import {
  Chart, BarController, BarElement, LineController, LineElement, PointElement, RadarController, RadialLinearScale,
  ScatterController, DoughnutController, ArcElement, CategoryScale, LinearScale, LogarithmicScale, Tooltip, Legend, Filler,
} from 'chart.js';
import { $, $$, el, api, cssVar, fmt, reducedMotion } from './util.js';

Chart.register(BarController, BarElement, LineController, LineElement, PointElement, RadarController, RadialLinearScale,
  ScatterController, DoughnutController, ArcElement, CategoryScale, LinearScale, LogarithmicScale, Tooltip, Legend, Filler);

const series = () => [1, 2, 3, 4, 5, 6, 7, 8].map((i) => cssVar(`--s${i}`));
const alpha = (hex, a) => {
  const n = parseInt(hex.slice(1), 16);
  return `rgba(${n >> 16},${(n >> 8) & 255},${n & 255},${a})`;
};

/** Draws a text label next to each scatter point (identity is never colour-only). */
const pointLabels = {
  id: 'pointLabels',
  afterDatasetsDraw(chart) {
    if (chart.config.type !== 'scatter') return;
    const { ctx } = chart;
    ctx.save();
    ctx.font = `600 11px ${cssVar('--font') || 'sans-serif'}`;
    ctx.fillStyle = cssVar('--fg');
    chart.data.datasets.forEach((ds, i) => {
      chart.getDatasetMeta(i).data.forEach((pt, j) => {
        const label = ds.data[j]?.label;
        if (!label) return;
        const right = pt.x < chart.chartArea.right - 110;
        ctx.textAlign = right ? 'left' : 'right';
        ctx.fillText(label, pt.x + (right ? 9 : -9), pt.y + (j % 2 ? 12 : -6));
      });
    });
    ctx.restore();
  },
};

function build(canvas, cfg) {
  const colors = series();
  const fg = cssVar('--fg'), mut = cssVar('--mut'), line = cssVar('--line'), surface = cssVar('--bg2');
  const font = { family: cssVar('--font') || 'sans-serif', size: 12 };
  const unit = cfg.unit ? ` ${cfg.unit}` : '';
  const isRadar = cfg.type === 'radar', isScatter = cfg.type === 'scatter', isPie = cfg.type === 'doughnut';
  const multi = cfg.datasets.length > 1;

  const datasets = cfg.datasets.map((ds, i) => {
    const c = colors[i % colors.length];
    if (isPie) return { label: ds.label, data: ds.data, backgroundColor: ds.data.map((_, j) => colors[j % colors.length]), borderColor: surface, borderWidth: 2 };
    if (cfg.type === 'bar') return { label: ds.label, data: ds.data, backgroundColor: c, borderRadius: 4, borderSkipped: 'start', maxBarThickness: 28, borderColor: surface, borderWidth: cfg.stacked ? 1 : 0 };
    if (isScatter) return { label: ds.label, data: ds.data, backgroundColor: c, borderColor: surface, borderWidth: 2, pointRadius: 6, pointHoverRadius: 8 };
    return { label: ds.label, data: ds.data, borderColor: c, backgroundColor: alpha(c, isRadar ? 0.12 : 0.1), borderWidth: 2, pointRadius: isRadar ? 3 : 4, pointHoverRadius: 6, pointBackgroundColor: c, pointBorderColor: surface, pointBorderWidth: 2, tension: 0.25, fill: isRadar, spanGaps: false };
  });

  const axis = (title, isValue) => ({
    title: { display: Boolean(title), text: title, color: mut, font },
    ticks: { color: mut, font, callback(v) { return isValue ? fmt(v) : this.getLabelForValue(v); } },
    grid: { color: isValue ? line : 'transparent' },
    border: { color: line },
    stacked: Boolean(cfg.stacked),
  });
  const valueAxis = { ...axis(cfg.horizontal ? cfg.xTitle : cfg.yTitle, true), type: cfg.log ? 'logarithmic' : 'linear', beginAtZero: !cfg.log && cfg.type !== 'line' ? true : undefined };
  const catAxis = axis(cfg.horizontal ? cfg.yTitle : cfg.xTitle, false);

  let scales = {};
  if (isRadar) {
    scales = { r: { min: 0, max: cfg.max || undefined, angleLines: { color: line }, grid: { color: line }, pointLabels: { color: fg, font: { ...font, size: 12, weight: 600 } }, ticks: { color: mut, backdropColor: 'transparent', font: { ...font, size: 10 } } } };
  } else if (isScatter) {
    scales = { x: { ...axis(cfg.xTitle, true), type: cfg.log ? 'logarithmic' : 'linear' }, y: axis(cfg.yTitle, true) };
  } else if (!isPie) {
    scales = cfg.horizontal ? { x: valueAxis, y: catAxis } : { x: catAxis, y: valueAxis };
  }

  return new Chart(canvas, {
    type: cfg.type,
    data: { labels: cfg.labels, datasets },
    plugins: [pointLabels],
    options: {
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: cfg.horizontal ? 'y' : 'x',
      animation: reducedMotion() ? false : { duration: 600 },
      interaction: { mode: cfg.type === 'line' ? 'index' : 'nearest', intersect: cfg.type !== 'line' },
      layout: { padding: isScatter ? { right: 16, top: 12 } : 0 },
      plugins: {
        legend: { display: multi || isPie, position: 'bottom', labels: { color: fg, font, usePointStyle: true, boxWidth: 8, boxHeight: 8 } },
        tooltip: {
          backgroundColor: fg, titleColor: surface, bodyColor: surface, padding: 10, cornerRadius: 8, titleFont: font, bodyFont: font,
          callbacks: {
            title: (items) => (isScatter ? items[0].raw.label || items[0].dataset.label : items[0].label),
            label: (item) => {
              if (isScatter) return `${cfg.xTitle || 'x'}: ${fmt(item.raw.x)} · ${cfg.yTitle || 'y'}: ${fmt(item.raw.y)}`;
              const v = isRadar ? item.raw : (cfg.horizontal ? item.parsed.x : item.parsed.y) ?? item.raw;
              return `${item.dataset.label ? item.dataset.label + ': ' : ''}${fmt(v)}${unit}`;
            },
          },
        },
      },
      scales,
    },
  });
}

// ---- AI data (data/ai-models.json + data/ai-benchmarks.json via /api/ai-data) ----
let aiPromise = null;
const aiData = () => (aiPromise ??= api('/api/ai-data'));
const blended = (m) => (typeof m.price_in === 'number' && typeof m.price_out === 'number' ? (m.price_in * 3 + m.price_out) / 4 : null);
const scoreOf = (d, modelId, benchId) => d.scores.find((s) => s.model === modelId && s.benchmark === benchId && typeof s.value === 'number');
const benchOf = (d, id) => d.benchmarks.find((b) => b.id === id);

function ranking(d, benchId, filter = () => true) {
  const bm = benchOf(d, benchId);
  if (!bm) return [];
  return d.models.filter(filter).map((m) => ({ m, s: scoreOf(d, m.id, benchId) })).filter((r) => r.s)
    .sort((a, b) => (bm.higher_is_better ? b.s.value - a.s.value : a.s.value - b.s.value));
}

function aiConfig(d, kind, benchId, note) {
  const bm = benchOf(d, benchId);
  if (kind === 'bar') {
    const rows = ranking(d, benchId).slice(0, 14);
    if (!rows.length) return null;
    const vendor = rows.some((r) => r.s.reported_by === 'vendor');
    note.textContent = vendor ? '* işaretli skorlar üretici beyanıdır; diğerleri bağımsız kaynaklardan alınmıştır.' : 'Tüm skorlar bağımsız kaynaklardan alınmıştır.';
    return { type: 'bar', horizontal: true, labels: rows.map((r) => r.m.name + (r.s.reported_by === 'vendor' ? ' *' : '')), datasets: [{ label: bm.name, data: rows.map((r) => r.s.value) }], unit: bm.unit || '', xTitle: bm.name + (bm.unit ? ` (${bm.unit})` : ''), yTitle: '' };
  }
  if (kind === 'scatter') {
    const pts = ranking(d, benchId).map((r) => ({ x: blended(r.m), y: r.s.value, label: r.m.name })).filter((p) => p.x !== null && p.x > 0);
    if (!pts.length) return null;
    note.textContent = 'Yatay eksen: 1 milyon belirteç için harmanlanmış API fiyatı (3 birim girdi + 1 birim çıktı ortalaması, ABD doları, logaritmik ölçek). Sol üst köşe: düşük maliyet, yüksek skor.';
    return { type: 'scatter', labels: [], datasets: [{ label: 'Modeller', data: pts }], log: true, xTitle: 'Harmanlanmış fiyat ($ / 1M belirteç)', yTitle: bm.name + (bm.unit ? ` (${bm.unit})` : '') };
  }
  // Radar: every axis is normalised to 0–100 against the best model on that axis.
  const axes = d.radar?.axes || [];
  const ids = d.radar?.models || [];
  const models = ids.map((id) => d.models.find((m) => m.id === id)).filter(Boolean).slice(0, 5);
  if (!axes.length || !models.length) return null;
  const raw = (m, ax) => (ax.field === 'cost' ? blended(m) : scoreOf(d, m.id, ax.benchmark)?.value ?? null);
  const missing = [];
  const datasets = models.map((m) => ({ label: m.name, data: [] }));
  axes.forEach((ax) => {
    const vals = models.map((m) => raw(m, ax));
    const present = vals.filter((v) => v !== null && v > 0);
    const best = ax.lower_is_better ? Math.min(...present) : Math.max(...present);
    vals.forEach((v, i) => {
      if (v === null || v <= 0) { datasets[i].data.push(null); missing.push(`${models[i].name} – ${ax.label}`); return; }
      datasets[i].data.push(Math.round((ax.lower_is_better ? best / v : v / best) * 1000) / 10);
    });
  });
  note.textContent = 'Her eksende en iyi model 100 kabul edilir; diğerleri ona oranlanır (maliyet ve gecikmede düşük olan iyidir). Eksenler: '
    + axes.map((ax) => `${ax.label} = ${ax.field === 'cost' ? 'harmanlanmış API fiyatı' : benchOf(d, ax.benchmark)?.name || ax.benchmark}`).join('; ') + '.'
    + (missing.length ? ` Doğrulanmış verisi olmayan noktalar boş bırakılmıştır: ${missing.join(', ')}.` : '');
  return { type: 'radar', labels: axes.map((a) => a.label), datasets, max: 100 };
}

// ---- "Which AI is right for me?" ----
function picker(box, d) {
  const form = $('form', box);
  const out = $('.picker-result', box);
  const price = (m) => (blended(m) === null ? (m.open ? 'Kendi donanımınızda çalışır (API ücreti yok)' : 'API fiyatı: —') : `API: $${fmt(m.price_in)} girdi / $${fmt(m.price_out)} çıktı (1M belirteç)`);
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const goal = d.use_cases.find((u) => u.id === form.goal.value);
    if (!goal) return;
    const bm = benchOf(d, goal.benchmark);
    const type = goal.model_type || 'llm';
    const budget = form.budget.value, privacy = form.privacy.value;
    const lowMax = d.radar?.low_budget_max ?? 2;
    const all = ranking(d, goal.benchmark, (m) => (m.type || 'llm') === type);
    const rows = all.filter(({ m }) => {
      if (privacy === 'local' && !m.open) return false;
      if (budget === 'free' && !(m.free_tier || m.open)) return false;
      if (budget === 'low' && !(m.open || m.free_tier || (blended(m) !== null && blended(m) <= lowMax))) return false;
      return true;
    }).slice(0, 3);
    out.replaceChildren();
    if (!bm || !all.length) {
      out.append(el('p', { class: 'na-block', text: 'Bu amaç için doğrulanmış kıyaslama verisi bulunmuyor (—). Aşağıdaki bölümlerde nitel değerlendirmeyi okuyabilirsiniz.' }));
      return;
    }
    if (!rows.length) {
      out.append(el('p', { class: 'na-block', text: `Seçtiğiniz bütçe ve gizlilik koşullarını sağlayan, ${bm.name} skoru doğrulanmış bir model veride yok. Koşullardan birini gevşetmeyi deneyin.` }));
      return;
    }
    const cond = [privacy === 'local' ? 'açık ağırlıklı (kendi donanımınızda çalıştırılabilir)' : null, budget === 'free' ? 'ücretsiz kullanılabilen' : budget === 'low' ? `düşük maliyetli (harmanlanmış fiyat ≤ $${fmt(lowMax)} / 1M belirteç veya ücretsiz)` : null].filter(Boolean).join(', ');
    out.append(el('p', { class: 'hint', text: `Ölçüt: ${bm.name}${goal.why ? ' — ' + goal.why : ''}. ${cond ? 'Uygulanan süzgeç: ' + cond + '.' : 'Süzgeç uygulanmadı.'}` }));
    rows.forEach(({ m, s }, i) => {
      const src = s.source_url ? el('a', { href: s.source_url, target: '_blank', rel: 'noopener noreferrer', text: s.source_name || 'kaynak' }) : (s.source_name || 'kaynak');
      out.append(el('div', { class: `pick${i === 0 ? ' pick-first' : ''}` },
        el('h4', { text: `${i + 1}. ${m.name} (${m.provider})` }),
        el('p', {}, el('strong', { text: `${bm.name}: ${fmt(s.value)}${bm.unit ? ' ' + bm.unit : ''}` }), ' ',
          el('small', {}, '(', src, `, ${s.date}${s.reported_by === 'vendor' ? ', üretici beyanı' : ', bağımsız ölçüm'})`)),
        el('p', { text: i === 0
          ? `Neden: koşullarınızı sağlayan ${rows.length === 1 ? 'tek' : 'modeller arasında bu ölçütte en yüksek skorlu'} model.`
          : `Neden: aynı koşullarda ${i + 1}. sırada; ${rows[0].m.name} ile arasındaki fark ${fmt(Math.abs(rows[0].s.value - s.value))}${bm.unit ? ' ' + bm.unit : ' puan'}.` }),
        el('p', {}, el('small', { text: `${price(m)} · ${m.open ? 'Açık ağırlık' : 'Kapalı model'}${m.context_window ? ' · Bağlam: ' + fmt(m.context_window / 1000) + ' K belirteç' : ''}${m.best_for ? ' · ' + m.best_for : ''}` }))));
    });
    out.append(el('p', { class: 'hint', text: `Veri güncellemesi: ${d.updated}. Tek bir kıyaslama her ihtiyacı temsil etmez; karar vermeden önce kendi örnek görevinizle deneyin.` }));
  });
}

export function init() {
  const live = new Map();
  const mount = async (fig) => {
    const canvas = $('canvas', fig);
    if (!canvas) return;
    let cfg = null;
    if (fig.dataset.chart) {
      try { cfg = JSON.parse(fig.dataset.chart); } catch { return; }
    } else {
      const d = await aiData();
      if (d.models) cfg = aiConfig(d, fig.dataset.aiChart, fig.dataset.benchmark, $('[data-ai-note]', fig) || el('span'));
      if (!cfg) {
        $('.chart-box', fig)?.replaceChildren(el('p', { class: 'na-block', text: 'Bu grafik için doğrulanmış veri bulunmuyor (—).' }));
        return;
      }
      if (cfg.type === 'bar') $('.chart-box', fig).style.height = `${Math.max(240, cfg.labels.length * 34 + 70)}px`;
    }
    live.get(fig)?.chart.destroy();
    live.set(fig, { cfg, chart: build(canvas, cfg) });
  };

  const io = new IntersectionObserver((entries) => {
    entries.filter((e) => e.isIntersecting).forEach((e) => { io.unobserve(e.target); mount(e.target); });
  }, { rootMargin: '300px' });
  $$('[data-chart], [data-ai-chart]').forEach((f) => io.observe(f));

  document.addEventListener('themechange', () => {
    live.forEach((v, fig) => { v.chart.destroy(); v.chart = build($('canvas', fig), v.cfg); });
  });

  const box = $('[data-ai-picker]');
  if (box) aiData().then((d) => { if (d.models) picker(box, d); });
}
