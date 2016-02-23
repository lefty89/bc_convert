#!/bin/bash


# ./bugcluster-video-converter.sh {HASH} {FULLPATH} {FORMAT} {VIDEOQUALY} {AUDIOQUALY}


HASH=$1
FULLPATH=$2
FORMAT=$3

AUDIOQUALY="-acodec libvorbis -ac 2 -ab 96k -ar 44100"
VIDEOQUALY="-b 345k -s 640x360"

# split name and path
FILENAME="${FULLPATH##*/}"                      	# Strip longest match of */ from start
DIR="${FULLPATH:0:${#FULLPATH} - ${#FILENAME}}" 	# Substring from 0 thru pos of filename
BASE="${FILENAME%.[^.]*}"                			# Strip shortest match of . plus at least one non-dot char from end
EXT="${FILENAME:${#BASE} + 1}"            			# Substring from len of base thru end


# check whether format is valid
if [ "$EXT" != "mp4" ] && [ "$EXT" != "webm" ] && [ "$EXT" != "ogv" ] ; then
 exit 1
fi

# get new format
case "$FORMAT" in
"1")
    NEW_EXT="ogv"
    ;;
"2")
    NEW_EXT="webm"
    ;;
"3")
    NEW_EXT="mp4"
    ;;
*)
     exit 1
    ;;
esac


bash -c "avconv -i ${FULLPATH} ${AUDIOQUALY} ${VIDEOQUALY} ${DIR}${BASE}.${NEW_EXT} 2>${DIR}log.txt"