#!/bin/bash

############################################################
# ./bugcluster-video-converter.sh {HASH} {FULLPATH} {FORMAT} {VIDEOBITRATE}
############################################################

# path settings
HASH=${1}
INFILE=${2}
FORMAT=${3}

# video input settings
VIDEOBITRATE=${4}
VIDEOWIDTH=${5}
VIDEOHEIGHT=${6}

# audio input settings
AUDIOBITRATE=${7}
AUDIOQUALY=${8}
AUDIOSAMPLERATE=${9}

LOG=${10}
OUTFILE=${11}

# codecs
VIDEOCODEC=""
AUDIOCODEC=""

# detect codecs
case "$FORMAT" in
"ogv")
    VIDEOCODEC="libtheora"
    AUDIOCODEC="libvorbis"
    ;;
"webm")
    VIDEOCODEC="libvpx"
    AUDIOCODEC="libvorbis"
    ;;
"mp4")
    VIDEOCODEC="libx264"
    AUDIOCODEC="aac -strict experimental -ac ${AUDIOQUALY}"
    ;;
*)
     exit 1
    ;;
esac

bash -c "avconv -y -i ${INFILE} -acodec ${AUDIOCODEC} -b:a ${AUDIOBITRATE}k -ar ${AUDIOSAMPLERATE} -vcodec ${VIDEOCODEC} -b:v ${VIDEOBITRATE}k -s ${VIDEOWIDTH}x${VIDEOHEIGHT} ${OUTFILE} 2>${LOG}"