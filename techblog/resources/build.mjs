// Front-end build: bundles and minifies JS/CSS into public_html/assets with content-hashed filenames
// and writes assets/manifest.json. Development-time only; the built output is committed, so the
// server never needs Node.js.  Usage: cd resources && npm ci && npm run build
import { build } from 'esbuild';
import { createHash } from 'node:crypto';
import { mkdirSync, readdirSync, readFileSync, rmSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const src = join(root, 'resources');
const out = join(root, 'public_html', 'assets');
const manifest = {};

for (const dir of ['js', 'css']) {
  mkdirSync(join(out, dir), { recursive: true });
  for (const f of readdirSync(join(out, dir))) {
    if (f !== 'error.css') rmSync(join(out, dir, f));
  }
}

// JavaScript: ES modules with code splitting, so charts, the editor and Firebase load only where needed.
const js = await build({
  absWorkingDir: src,
  entryPoints: { app: 'js/app.js', login: 'js/login.js', admin: 'js/admin.js' },
  outdir: join(out, 'js'),
  bundle: true,
  splitting: true,
  format: 'esm',
  target: ['es2020'],
  minify: true,
  legalComments: 'none',
  entryNames: '[name].[hash]',
  chunkNames: 'c.[name].[hash]',
  metafile: true,
  logLevel: 'warning',
});
for (const [file, meta] of Object.entries(js.metafile.outputs)) {
  if (meta.entryPoint) manifest[meta.entryPoint.replace(/^js\//, '')] = 'js/' + file.split('/').pop();
}

// CSS: minified, hashed by content.
for (const name of ['critical', 'app', 'admin']) {
  const res = await build({ absWorkingDir: src, entryPoints: [`css/${name}.css`], bundle: true, minify: true, write: false, external: ['/assets/*'], logLevel: 'warning' });
  const css = res.outputFiles[0].text.trim();
  const hash = createHash('sha256').update(css).digest('hex').slice(0, 10);
  writeFileSync(join(out, 'css', `${name}.${hash}.css`), css);
  manifest[`${name}.css`] = `css/${name}.${hash}.css`;
}

writeFileSync(join(out, 'manifest.json'), JSON.stringify(manifest, null, 2) + '\n');
const size = (f) => (readFileSync(join(out, f)).length / 1024).toFixed(1) + ' kB';
for (const [k, v] of Object.entries(manifest)) console.log(k.padEnd(14), v.padEnd(34), size(v));
