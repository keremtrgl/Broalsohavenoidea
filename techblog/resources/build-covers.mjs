// Generates the original cover illustrations (SVG, 1200x630) for the seeded articles and a PNG copy
// of each for Open Graph / Twitter cards. Development-time only: the output in
// public_html/assets/covers is committed.  Usage: cd resources && npm run covers
// (PNG rendering needs Playwright with Chromium; without it only the SVG files are written.)
import { mkdirSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const out = join(dirname(fileURLToPath(import.meta.url)), '..', 'public_html', 'assets', 'covers');
mkdirSync(out, { recursive: true });

const A = '#3ddc97', B = '#6aa8ff', O = '#fbbf24', P = '#b69cff', R = '#ff7a90', W = '#e9eef4', M = '#9eabba', L = '#253142', C = '#111821';
const box = (x, y, w, h, s = W, f = C, r = 12, sw = 4) => `<rect x="${x}" y="${y}" width="${w}" height="${h}" rx="${r}" fill="${f}" stroke="${s}" stroke-width="${sw}"/>`;
const line = (d, s = M, sw = 4) => `<path d="${d}" fill="none" stroke="${s}" stroke-width="${sw}" stroke-linecap="round" stroke-linejoin="round"/>`;
const dot = (x, y, r, f) => `<circle cx="${x}" cy="${y}" r="${r}" fill="${f}"/>`;
const ring = (x, y, r, s, sw = 4) => `<circle cx="${x}" cy="${y}" r="${r}" fill="none" stroke="${s}" stroke-width="${sw}"/>`;
const txt = (x, y, t, f = W, size = 30, anchor = 'middle') => `<text x="${x}" y="${y}" fill="${f}" font-family="ui-monospace,Menlo,Consolas,monospace" font-size="${size}" font-weight="700" text-anchor="${anchor}">${t}</text>`;
const grid = (x, y, cols, rows, size, gap, fill) => Array.from({ length: cols * rows }, (_, i) =>
  `<rect x="${x + (i % cols) * (size + gap)}" y="${y + Math.floor(i / cols) * (size + gap)}" width="${size}" height="${size}" rx="${Math.max(2, size / 6)}" fill="${typeof fill === 'function' ? fill(i) : fill}"/>`).join('');

/** Each motif is drawn inside a 520x420 area. */
const covers = {
  'programming-languages': ['Every Programming', 'Language Explained', 'Programlama dilleri',
    box(20, 40, 300, 200) + line('M20 84h300', W, 3) + dot(46, 62, 7, R) + dot(70, 62, 7, O) + dot(94, 62, 7, A)
    + line('M52 120h120M52 150h190M52 180h90M52 210h150', A, 6) + line('M190 120h70', B, 6)
    + box(200, 200, 300, 190, A) + txt(350, 310, '{ }', A, 96) + txt(100, 340, '&lt;/&gt;', B, 56) + txt(430, 120, 'fn()', O, 40)],
  'networking-terms': ['Every Networking', 'Term Explained', 'Ağ terimleri',
    line('M260 210 90 80M260 210 430 80M260 210 90 340M260 210 430 340M260 210V40', L, 6)
    + box(200, 160, 120, 100, A, C, 16) + txt(260, 222, 'IP', A, 40) + [[90, 80, B], [430, 80, O], [90, 340, P], [430, 340, B], [260, 40, R]].map(([x, y, c]) => dot(x, y, 34, C) + ring(x, y, 34, c, 5)).join('')
    + dot(175, 145, 10, A) + dot(345, 145, 10, O) + dot(175, 275, 10, B) + dot(345, 275, 10, A) + txt(90, 90, 'DNS', B, 20) + txt(430, 90, 'TCP', O, 20) + txt(90, 350, 'NAT', P, 20) + txt(430, 350, 'VPN', B, 20)],
  'data-structures': ['Every Data', 'Structure Explained', 'Veri yapıları',
    line('M260 70 140 170M260 70l120 100M140 170 80 280M140 170l60 110M380 170l-60 110M380 170l60 110', M, 5)
    + [[260, 70, A], [140, 170, B], [380, 170, B], [80, 280, O], [200, 280, O], [320, 280, O], [440, 280, O]].map(([x, y, c]) => dot(x, y, 32, C) + ring(x, y, 32, c, 5)).join('')
    + [0, 1, 2, 3, 4].map((i) => box(40 + i * 92, 340, 70, 56, i === 2 ? A : W, C, 8)).join('') + line('M110 368h22M202 368h22M294 368h22M386 368h22', A, 5)],
  'web-browsers': ['Every Web', 'Browser Explained', 'Web tarayıcıları',
    box(110, 30, 390, 250, M) + box(60, 80, 390, 250, B) + box(10, 130, 390, 250, A)
    + line('M10 180h390', A, 3) + dot(38, 156, 8, R) + dot(64, 156, 8, O) + dot(90, 156, 8, A) + box(120, 143, 250, 26, L, L, 13, 0)
    + ring(205, 285, 60, W, 5) + line('M145 285h120M205 225c40 40 40 80 0 120M205 225c-40 40-40 80 0 120', W, 4)],
  'resolutions': ['Every Resolution', 'Explained', 'Çözünürlükler',
    box(10, 20, 500, 281, P, C, 8) + box(10, 20, 375, 211, B, C, 8) + box(10, 20, 250, 141, O, C, 8) + box(10, 20, 150, 84, A, '#10291f', 8)
    + txt(85, 72, '720p', A, 24) + txt(205, 150, '1080p', O, 22) + txt(325, 220, '4K', B, 24) + txt(460, 290, '8K', P, 24)
    + grid(40, 330, 16, 2, 22, 6, (i) => [R, A, B][i % 3])],
  'intel-cpus': ['Every Intel CPU', 'Explained', 'Intel işlemciler',
    box(110, 60, 300, 300, B, C, 24, 6) + grid(150, 100, 4, 2, 46, 12, B) + grid(150, 220, 8, 3, 18, 11, A)
    + line(['150', '210', '270', '330', '370'].map((x) => `M${x} 60V24M${x} 396v-36`).join('') + ['100', '160', '220', '280', '320'].map((y) => `M110 ${y}H74M446 ${y}h-36`).join(''), B, 6)],
  'ai-models': ['Every AI Model', 'Explained', 'Yapay zekâ modelleri',
    (() => { const cols = [[60, [110, 210, 310]], [200, [60, 160, 260, 360]], [340, [60, 160, 260, 360]], [480, [160, 260]]]; let s = '';
      for (let c = 0; c < cols.length - 1; c++) for (const y1 of cols[c][1]) for (const y2 of cols[c + 1][1]) s += line(`M${cols[c][0]} ${y1} ${cols[c + 1][0]} ${y2}`, L, 3);
      cols.forEach(([x, ys], c) => ys.forEach((y) => { s += dot(x, y, 24, C) + ring(x, y, 24, [B, A, A, O][c], 5); })); return s; })()],
  'mobile-networks': ['Every Mobile', 'Network Explained', 'Mobil şebekeler',
    line('M150 390 200 150l50 240M168 300h64M180 240h40', W, 6) + dot(200, 140, 12, A)
    + [50, 90, 130].map((r, i) => `<path d="M${200 - r * 0.7} ${140 - r * 0.7}a${r} ${r} 0 0 1 ${r * 1.4} 0" fill="none" stroke="${A}" stroke-width="6" stroke-linecap="round" opacity="${1 - i * 0.25}"/>`).join('')
    + [0, 1, 2, 3, 4].map((i) => `<rect x="${300 + i * 44}" y="${350 - i * 60}" width="30" height="${40 + i * 60}" rx="6" fill="${[M, M, B, B, A][i]}"/>`).join('')
    + txt(315, 420, '1G', M, 18) + txt(491, 420, '5G', A, 18)],
  'forms-of-government': ['Every Form of', 'Government Explained', 'Yönetim biçimleri',
    line('M40 120 260 30l220 90z', W, 6) + box(60, 130, 400, 30, W, C, 4) + [100, 180, 260, 340, 420].map((x) => box(x - 16, 170, 32, 150, W, C, 4)).join('')
    + box(40, 330, 440, 30, W, C, 4) + box(10, 370, 500, 30, A, C, 4) + dot(260, 82, 16, A)],
  'operating-systems': ['Every Operating', 'System Explained', 'İşletim sistemleri',
    box(40, 30, 440, 90, B) + txt(260, 86, 'APPS', B, 34) + box(40, 140, 440, 90, A, '#10291f') + txt(260, 196, 'KERNEL', A, 34) + box(40, 250, 440, 90, M) + txt(260, 306, 'HARDWARE', M, 30)
    + line('M150 120v20M260 120v20M370 120v20M150 230v20M260 230v20M370 230v20', O, 6) + grid(70, 364, 12, 1, 22, 10, (i) => [B, A, O, P][i % 4])],
  'gpus': ['Every GPU', 'Explained', 'Ekran kartları',
    box(10, 110, 500, 210, A, C, 18, 6) + [140, 370].map((x) => ring(x, 215, 78, W, 5) + ring(x, 215, 22, A, 5)
      + [0, 60, 120, 180, 240, 300].map((d) => `<path d="M${x} 215 q30 -50 0 -70" fill="none" stroke="${M}" stroke-width="8" stroke-linecap="round" transform="rotate(${d} ${x} 215)"/>`).join('')).join('')
    + line('M40 320v40h130v-40M200 320v24h230v-24', A, 6) + [0, 1, 2, 3].map((i) => `<rect x="${200 + i * 70}" y="${60 - i * 14}" width="44" height="${30 + i * 14}" rx="6" fill="${B}" opacity="${0.5 + i * 0.16}"/>`).join('')],
  'network-hardware': ['Every Network', 'Hardware Explained', 'Ağ donanımı',
    box(110, 260, 300, 100, A, C, 16, 6) + line('M160 260V150M360 260V150', A, 8) + dot(160, 310, 9, A) + dot(195, 310, 9, O) + dot(230, 310, 9, B) + line('M290 310h80', M, 6)
    + [60, 110, 160].map((r, i) => `<path d="M${260 - r} 150a${r} ${r} 0 0 1 ${2 * r} 0" fill="none" stroke="${B}" stroke-width="7" stroke-linecap="round" opacity="${1 - i * 0.28}"/>`).join('')
    + box(10, 170, 60, 100, W, C, 10) + box(450, 190, 60, 44, W, C, 8) + line('M436 248h88', W, 5)],
  'motherboards': ['Every Motherboard', 'Form Factor Explained', 'Anakart form faktörleri',
    box(40, 10, 380, 400, A, C, 12, 6) + box(110, 60, 120, 120, W, C, 10) + box(140, 90, 60, 60, M, L, 6, 3)
    + [0, 1, 2, 3].map((i) => box(270 + i * 28, 40, 16, 170, B, C, 4, 3)).join('') + box(80, 240, 280, 20, O, C, 5, 3) + box(80, 290, 280, 20, O, C, 5, 3) + box(80, 340, 120, 20, O, C, 5, 3)
    + box(280, 330, 70, 60, P, C, 8, 4) + box(54, 30, 30, 160, M, C, 6, 3) + box(440, 10, 70, 70, B, 'none', 6, 4) + box(440, 100, 70, 110, O, 'none', 6, 4)],
  'ai-benchmarks': ['Every AI Benchmark', 'Explained', 'Yapay zekâ kıyaslamaları',
    [0, 1, 2, 3, 4].map((i) => `<rect x="${40 + i * 70}" y="${330 - [90, 150, 120, 230, 280][i]}" width="46" height="${[90, 150, 120, 230, 280][i]}" rx="8" fill="${[M, B, B, A, A][i]}"/>`).join('')
    + line('M20 340h380', W, 5) + box(380, 60, 130, 170, W) + line('M402 100h86M402 130h86M402 160h50', M, 5) + line('M404 196l18 18 40-44', A, 9)
    + line('M30 20h340M30 20v24M98 20v14M166 20v24M234 20v14M302 20v24M370 20v14', O, 5)],
  'best-ai': ['Best AI for', 'Every Task', 'Model karşılaştırması',
    (() => { const cx = 260, cy = 210, pts = (r, k = [1, 1, 1, 1, 1, 1]) => k.map((f, i) => { const a = (Math.PI / 3) * i - Math.PI / 2; return `${cx + Math.cos(a) * r * f},${cy + Math.sin(a) * r * f}`; }).join(' ');
      return [60, 120, 180].map((r) => `<polygon points="${pts(r)}" fill="none" stroke="${L}" stroke-width="3"/>`).join('')
        + [0, 1, 2, 3, 4, 5].map((i) => { const a = (Math.PI / 3) * i - Math.PI / 2; return line(`M${cx} ${cy} ${cx + Math.cos(a) * 180} ${cy + Math.sin(a) * 180}`, L, 3); }).join('')
        + `<polygon points="${pts(180, [0.95, 0.6, 0.8, 0.5, 0.9, 0.7])}" fill="${A}" fill-opacity=".22" stroke="${A}" stroke-width="5"/>`
        + `<polygon points="${pts(180, [0.6, 0.95, 0.55, 0.9, 0.5, 0.85])}" fill="${B}" fill-opacity=".18" stroke="${B}" stroke-width="5"/>`
        + `<polygon points="${pts(180, [0.75, 0.7, 0.95, 0.65, 0.7, 0.5])}" fill="${O}" fill-opacity=".14" stroke="${O}" stroke-width="5"/>`; })()],
};

const dots = Array.from({ length: 13 * 7 }, (_, i) => `<circle cx="${60 + (i % 13) * 90}" cy="${45 + Math.floor(i / 13) * 90}" r="2" fill="#1c2633"/>`).join('');
const svg = ([l1, l2, tr, motif], fs = Math.max(l1.length, l2.length) > 19 ? 44 : 58) => `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 630" width="1200" height="630">
<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#0a0f15"/><stop offset="1" stop-color="#131c27"/></linearGradient></defs>
<rect width="1200" height="630" fill="url(#g)"/>${dots}
<rect x="60" y="150" width="64" height="8" rx="4" fill="${A}"/>
<text x="60" y="250" fill="${W}" font-family="system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif" font-size="${fs}" font-weight="800" letter-spacing="-1.5">${l1}</text>
<text x="60" y="320" fill="${A}" font-family="system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif" font-size="${fs}" font-weight="800" letter-spacing="-1.5">${l2}</text>
<text x="60" y="390" fill="${M}" font-family="system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif" font-size="30" font-weight="500">${tr}</text>
<g transform="translate(640 105)">${motif}</g>
</svg>
`;

const files = {};
for (const [name, def] of Object.entries(covers)) {
  files[name] = svg(def);
  writeFileSync(join(out, `${name}.svg`), files[name]);
}
files['og-default'] = svg(['TechBlog', 'Teknoloji rehberleri', 'Her kavram, tek yazıda', covers['intel-cpus'][3]]);

let chromium;
try {
  // require() also finds a globally installed Playwright, which import() does not.
  ({ chromium } = (await import('node:module')).createRequire(import.meta.url)('playwright'));
} catch {
  console.log(`${Object.keys(covers).length} SVG covers written. Playwright not found: PNG copies skipped.`);
  process.exit(0);
}
const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1200, height: 630 }, deviceScaleFactor: 1 });
for (const [name, markup] of Object.entries(files)) {
  await page.setContent(`<body style="margin:0">${markup}</body>`);
  const target = name === 'og-default' ? join(out, '..', 'img', 'og-default.png') : join(out, `${name}.png`);
  await page.screenshot({ path: target, type: 'png' });
}
await browser.close();
console.log(`${Object.keys(covers).length} covers written (SVG + PNG) and og-default.png.`);
