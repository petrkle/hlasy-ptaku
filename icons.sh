#!/bin/bash

RES=app/src/main/res/mipmap

declare -A icon

icon[48]='mdpi'
icon[72]='hdpi'
icon[96]='xhdpi'
icon[144]='xxhdpi'
icon[192]='xxxhdpi'

for foo in "${!icon[@]}"
do
  OUT="${RES}-${icon[$foo]}"
  ICO="ic_launcher"
  [ -d ${OUT} ] || mkdir -p ${OUT}
  [ -f ${OUT}/${ICO}.png ] || convert -resize ${foo}x${foo} ptak.png ${OUT}/${ICO}.png
  [ -f ${OUT}/${ICO}_round.png ] || cp ${OUT}/${ICO}.png ${OUT}/${ICO}_round.png
done
