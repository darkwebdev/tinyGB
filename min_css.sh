#!/bin/bash

STATIC=public/static

echo 'Compressing CSS...'

./clean_css.py > $STATIC/css/main-use.css
csso $STATIC/css/main-use.css $STATIC/css/app.min.css

ls -lh $STATIC/css/app.min.css|awk '{print $5, $9}'
