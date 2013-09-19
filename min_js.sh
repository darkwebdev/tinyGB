#!/bin/bash

STATIC=public/static

echo 'Compressing JS...'

nunjucks-precompile -f $STATIC/templates > $STATIC/js/templates.min.js
cat $STATIC/js/routie.min.js $STATIC/js/nunjucks-min.js $STATIC/js/templates.min.js $STATIC/js/main.js > $STATIC/js/app.js
uglifyjs $STATIC/js/app.js -c -m -o $STATIC/js/app.min.js

ls -lh $STATIC/js/app.min.js|awk '{print $5, $9}'
