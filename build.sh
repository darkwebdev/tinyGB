#!/bin/bash
csso public/static/css/main.css public/static/css/main.min.css
uglifyjs public/static/js/main.js -c -m -o public/static/js/main.min.js
nunjucks-precompile -f templates/nj > public/static/js/templates.min.js
