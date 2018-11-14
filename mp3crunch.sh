#!/bin/bash

set -e

MEDIADIR=app/src/main/assets/www

for foo in `find $MEDIADIR -type f -name "*.mp3" -size +400k`
do
	FILE=`basename $foo`
	ffmpeg -loglevel quiet -i $MEDIADIR/$FILE $MEDIADIR/s.$FILE
	mv $MEDIADIR/s.$FILE $MEDIADIR/$FILE
done
