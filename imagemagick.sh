#!/bin/bash

## add watermark
docker run -v  /Users/almasry/Desktop/photos:/images  --rm -it almasry/image-magick  composite -gravity center  /images/watermark.png  /images/main.jpg   /images/unnamed2.jpg

## add watermark with transparency
docker run -v  /Users/almasry/Desktop/photos:/images  --rm -it almasry/image-magick  composite -dissolve 60%  -gravity center  /images/watermark.png  /images/main.jpg   /images/unnamed2.jpg



## resize to 1000 width
docker run -v  /Users/almasry/Desktop/photos:/images  --rm -it almasry/image-magick  convert  /images/unnamed2.jpg  -resize 1000  /images/unnamed3.jpg

## resize to 750 width
docker run -v  /Users/almasry/Desktop/photos:/images  --rm -it almasry/image-magick  convert  /images/unnamed2.jpg  -resize 750  /images/unnamed3.jpg

## resize to 500 width
docker run -v  /Users/almasry/Desktop/photos:/images  --rm -it almasry/image-magick  convert  /images/unnamed2.jpg  -resize 500  /images/unnamed3.jpg

## resize to 250 width
docker run -v  /Users/almasry/Desktop/photos:/images  --rm -it almasry/image-magick  convert  /images/unnamed2.jpg  -resize 250  /images/unnamed3.jpg