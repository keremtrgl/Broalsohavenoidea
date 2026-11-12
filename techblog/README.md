# TechBlog

A technology blog with an admin panel, built for shared cPanel hosting: plain PHP 8.2+, MySQL/MariaDB, no framework, no Node.js or Composer on the server. Installation publishes 15 full Turkish articles.

- **Backend:** plain PHP, small MVC (`app/Core`, `Controllers`, `Models`, `Services`, `Views`), PDO prepared statements only.
- **Auth:** Firebase Authentication (Google and e-mail/password) in the browser; PHP verifies the ID token against Google's public keys (signature, `aud`, `iss`, `exp`, `iat`, `sub`) and then opens a hardened PHP session. Roles (`admin`, `editor`, `user`) live in MySQL.
- **Frontend:** HTML, CSS and vanilla ES modules, shipped pre-built and minified with content-hashed filenames. Chart.js, Editor.js, highlight.js and the Firebase SDK are bundled locally; nothing is loaded from a CDN except Google's own sign-in endpoints on the login page.
- **Content:** block JSON (Editor.js) rendered on the server. Text goes through HTML Purifier, SVG through a sanitizer, charts are emitted as data.

## Folder layout

```
/home/USERNAME/
├── app/            controllers, models, views, services, core
├── config/         routes
├── database/       migrations and seeds (settings, categories, 15 articles)
├── data/           ai-models.json, ai-benchmarks.json (edited from the admin panel)
├── storage/        logs, cache, sessions, backups (must be writable)
├── vendor/         Composer dependencies (already installed)
├── resources/      front-end sources and build scripts (development only, optional on the server)
├── .env            created by the installer (never commit)
└── public_html/    index.php, install.php, .htaccess, assets/, uploads/
```

Only `public_html/` is reachable from the web.

## Installing on cPanel

1. **PHP version.** cPanel → *Select PHP Version* (or *MultiPHP Manager*): choose PHP 8.2 or newer and enable `pdo_mysql`, `mbstring`, `openssl`, `curl`, `gd`, `fileinfo`, `dom`, `iconv`, `zlib` and `exif`. For image uploads raise `upload_max_filesize` and `post_max_size` to at least `10M` (*MultiPHP INI Editor*).
2. **Database.** cPanel → *MySQL Databases*: create a database and a user, then add the user to the database with **All Privileges**. Note the names (they are usually prefixed, for example `username_techblog`).
3. **Upload the files.** Upload everything in this folder to your home directory (`/home/USERNAME/`), so that `app/`, `vendor/`, `storage/` and the others sit **next to** `public_html/`, not inside it. Upload the contents of `public_html/` into your existing `public_html/`. Make sure the hidden `.htaccess` files are uploaded too (enable "Show Hidden Files" in File Manager). Uploading a zip and extracting it in File Manager is the fastest way.
4. **Permissions.** `storage/` (and its sub-folders), `data/` and `public_html/uploads/` must be writable by PHP (755 is normally enough on cPanel; use 775 if the installer reports them as not writable).
5. **SSL.** cPanel → *SSL/TLS Status* → *Run AutoSSL*. Do this before installing so the site address starts with `https://`.
6. **Firebase project.**
   1. Go to <https://console.firebase.google.com>, create a project.
   2. *Build → Authentication → Get started → Sign-in method*: enable **Google** and **Email/Password**.
   3. *Authentication → Settings → Authorized domains*: add your domain (for example `example.com`, and `www.example.com` if you use it).
   4. *Project settings → General → Your apps → Web app (`</>`)*: register an app and copy `apiKey`, `authDomain`, `projectId` and `appId`.
   No service-account key is needed: ID tokens are verified with Google's public certificates.
7. **Run the installer.** Open `https://your-domain/install.php`. It checks the requirements, asks for the database and Firebase values and the first administrator's e-mail address, writes `.env`, runs the migrations and seeds (15 articles), creates `storage/installed.lock` and deletes itself. If it cannot delete itself it stays locked (HTTP 403); delete `public_html/install.php` by hand.
8. **Become admin.** Open `/login` and sign in with the administrator e-mail you entered. The account becomes `admin` on its first sign-in **if Firebase reports the e-mail as verified** (Google sign-in always is; for e-mail/password click the verification link first, then sign in again).
9. **Check `.htaccess`.** At the top of the rewrite section in `public_html/.htaccess` two values are configurable: `TB_HTTPS` (`on`/`off`) and `TB_CANON` (`non-www`, `www` or `off`).

### Alternative layout (host does not allow folders outside the web root)

1. Put everything except the contents of `public_html/` into a folder inside the web root, for example `public_html/private/`.
2. Create `public_html/tb-path.php` containing: `<?php return __DIR__ . '/private';`
3. Every private folder ships with its own `.htaccess` (`Require all denied`), and the main `.htaccess` blocks `/private`, `/app`, `/vendor`, `/storage`, `/data`, `/config` and `/database` as well. After installing, confirm that `https://your-domain/private/.env` returns 403.

### Configuration (`.env`)

See `.env.example`. Notable keys: `APP_URL` (no trailing slash), `APP_DEBUG` (keep `false` in production; errors go to `storage/logs/`), `TRUST_PROXY` (`true` only behind Cloudflare or another proxy you trust), `PAGE_CACHE`, `ADMIN_EMAIL`.

## Admin panel

`/admin` (editors and admins only). Dashboard, posts (block editor with heading, paragraph, list, code, table, quote, callout, image, gallery, SVG/animation, chart, FAQ and AI-data blocks; drafts, scheduling, revisions, SEO fields), media library (drag-and-drop, WebP conversion and `srcset` sizes, required alt text), categories and tags (drag-and-drop order), comment moderation, users (roles, bans), AI data management, site settings, system tools (cache, sitemap, database backup, logs) and an audit log. Press `?` in the panel for keyboard shortcuts.

**AVIF:** AVIF variants are generated only when the server's GD build supports `imageavif`; otherwise WebP only. The System page shows which applies.

**Analytics:** because of the strict Content-Security-Policy the settings page takes a GA4 measurement ID or a script URL rather than free-form code. Scripts load only after the visitor accepts analytics cookies.

**Scheduled posts** need no cron job: a post whose publish time has passed becomes visible on the next request.

## Updating AI data

Admin → *Yapay zekâ verileri*. Models, benchmarks and scores are stored in `data/ai-models.json` and `data/ai-benchmarks.json`. A score cannot be saved without a source name, source URL and measurement date. Saving clears the caches, so the tables, charts and the "Which AI is right for me?" tool in the articles update immediately. Missing scores are shown as "—".

## Backups

- **Database:** Admin → *Sistem → Veritabanı yedeğini indir* (gzip SQL), or cPanel → *Backup* / phpMyAdmin export.
- **Files:** keep copies of `public_html/uploads/`, `data/` and `.env`.
- **Restore:** import the SQL in phpMyAdmin, copy the three items back, then clear the cache from the System page (or delete the files in `storage/cache/pages` and `storage/cache/data`).

## Updating the site

1. Take a backup (above).
2. Enable maintenance mode in *Site ayarları*.
3. Upload the new `app/`, `config/`, `database/`, `vendor/`, `resources/` and `public_html/` files over the old ones. **Do not** overwrite `.env`, `storage/`, `data/` (unless you want the shipped data) or `public_html/uploads/`. Do not upload `install.php` again.
4. Admin → *Sistem → Veritabanını güncelle* applies any new files in `database/migrations/`.
5. Clear the cache from the System page and turn maintenance mode off.

## Development (optional, not needed on the server)

```
cd resources
npm ci
npm run build     # bundles JS/CSS into public_html/assets with hashed names + manifest.json
npm run covers    # regenerates the cover SVGs (and PNG copies when Playwright is available)
npm run fonts     # subsets the self-hosted fonts (needs: pip install fonttools brotli)
```

Composer dependencies (`ezyang/htmlpurifier`, `firebase/php-jwt`, `enshrined/svg-sanitize`) are committed in `vendor/`.

Seed articles live in `database/seeds/articles/*.md` (a small Markdown subset plus `:::svg`, `:::chart`, `:::faq`, `:::ai` and callout directives, converted to editor blocks by `app/Services/ArticleParser.php`).

## Verification record

Checked on 12–13 November 2026 against Apache 2.4 + PHP-FPM 8.3 + MariaDB 10.11 (local stack, `.htaccess` active):

| Item | Result |
|---|---|
| No TODOs, placeholders or lorem ipsum in the code | Checked with a search; none found |
| No PHP errors or warnings with `E_ALL` | All public and admin pages loaded with an empty error log |
| CSRF protection and validation on every form | Every non-GET route is CSRF-checked centrally; missing/invalid token returns 419. The only exemption is the view-counter beacon (origin-checked and rate-limited) |
| `.env`, `vendor`, `storage`, `app`, `data` not reachable | 403 for each (tested in the in-web-root position as well, via the rewrite rule) |
| PHP does not execute in `uploads/` | `x.php` and `x.php.jpg` placed there return 403; uploads of PHP files or mismatched types are rejected; SVG is sanitized |
| Firebase token verified on the server | Forged tokens (wrong audience, issuer, key, `alg: none`, HS256 confusion, expired, future-dated) all rejected; valid token accepted; Google certificate download works |
| Non-admin users cannot access the panel | Anonymous → redirect to login; `user` → 403; `editor` → 403 on admin-only pages |
| 15 articles, full content, ≥3 animated visuals, sources | 2,518–4,443 words each; 3–4 animated SVGs, tables, charts, FAQ and a sources section in every article |
| AI tables, charts and picker load from JSON | Yes; verified by rendering and by editing data in the panel |
| Lighthouse | Home, an article and the AI comparison page: Performance 99–100, Accessibility 100, Best Practices 100, SEO 100 on mobile and desktop |
| `prefers-reduced-motion` | Animations do not run; each figure shows one frozen frame; CSS transitions are disabled |
| `sitemap.xml`, `robots.txt`, RSS, structured data | XML validates; `Article`, `BreadcrumbList` and `FAQPage` JSON-LD present; one `<h1>` per page |

Not covered by these checks, so verify after deploying: a real Firebase sign-in (it needs your own project and domain), behaviour on your host's exact Apache/PHP build, and Lighthouse over the public internet (the scores above were measured on a local server, where network latency is negligible; home-page CLS measured 0.007–0.018 rather than exactly 0).

## Data and accuracy

Benchmark figures in the hardware articles come from PassMark (retrieved 12 November 2026) and are marked as approximate. AI model data comes from Artificial Analysis, ARC Prize, Epoch AI, Vals AI and LMArena; several LMArena and Epoch figures were taken through the BenchLeader aggregator because the primary pages could not be fetched, and are labelled that way. Two API prices (Muse Wonder, Kimi K3.1 Pro) come from secondary reports. All of this ages quickly: update it from the admin panel.

## Licences

Application code: MIT. Fonts: Inter and JetBrains Mono under the SIL Open Font License (texts in `public_html/assets/fonts/`). Bundled libraries keep their own licences (Chart.js MIT, Editor.js Apache-2.0, highlight.js BSD-3-Clause, Firebase JS SDK Apache-2.0, HTML Purifier LGPL-2.1, php-jwt BSD-3-Clause, svg-sanitize GPL-2.0-or-later).
