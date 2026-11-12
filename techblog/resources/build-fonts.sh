#!/usr/bin/env bash
# Subsets the self-hosted fonts to the characters the site uses (Latin + Turkish + common symbols).
# Development-time tool: needs `pip install fonttools brotli`. Output is committed; the server never runs this.
set -euo pipefail
cd "$(dirname "$0")"
OUT=../public_html/assets/fonts
UNICODES="U+0020-007E,U+00A0-00FF,U+011E,U+011F,U+0130,U+0131,U+015E,U+015F,U+2013,U+2014,U+2018,U+2019,U+201C,U+201D,U+2022,U+2026,U+2190-2193,U+2212,U+2248,U+2260,U+2264,U+2265,U+00D7,U+03C4,U+03BC,U+20AC,U+20BA,U+2713,U+2192"
pyftsubset node_modules/inter-ui/variable/InterVariable.woff2 --unicodes="$UNICODES" --flavor=woff2 \
  --layout-features='kern,liga,calt,tnum,case,ccmp,locl' --output-file="$OUT/inter-tr.v1.woff2"
pyftsubset node_modules/jetbrains-mono/fonts/webfonts/JetBrainsMono-Medium.woff2 --unicodes="$UNICODES" --flavor=woff2 \
  --layout-features='kern,ccmp,locl' --output-file="$OUT/jbmono-tr.v1.woff2"
cp node_modules/inter-ui/LICENSE.txt "$OUT/Inter-OFL.txt" 2>/dev/null || cp node_modules/inter-ui/LICENSE* "$OUT/Inter-OFL.txt"
cp node_modules/jetbrains-mono/OFL.txt "$OUT/JetBrainsMono-OFL.txt" 2>/dev/null || cp node_modules/jetbrains-mono/LICENSE "$OUT/JetBrainsMono-OFL.txt"
ls -la "$OUT"
