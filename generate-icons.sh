#!/bin/bash

cd public/images/brand

# Generate maskable icons (with padding and background)
convert logo.svg -background white -gravity center -extent 512x512 -resize 512x512 icon-512.png
convert logo.svg -background white -gravity center -extent 192x192 -resize 192x192 icon-192.png
convert logo.svg -background white -gravity center -extent 180x180 -resize 180x180 apple-touch-icon.png
convert logo.svg -background white -gravity center -extent 144x144 -resize 144x144 icon-144.png
convert logo.svg -background white -gravity center -extent 32x32 -resize 32x32 favicon-32.png
convert logo.svg -background white -gravity center -extent 16x16 -resize 16x16 favicon-16.png
