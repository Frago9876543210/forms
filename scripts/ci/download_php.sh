#!/usr/bin/env bash

get_latest_release(){
  curl -s "https://api.github.com/repos/$1/releases/latest" | grep '"browser_download_url":.'$2 | sed -E 's/.*"([^"]+)".*/\1/'
}

download(){
  curl --insecure --silent --show-error --location --globoff $1 > $2
}

URL=$(get_latest_release "Frago9876543210/php-build-scripts" | grep ubuntu-20.04)
DIR=$(echo $URL | rev | cut -d'/' -f1 | rev | cut -d'-' -f1)

if [ -d /opt/$DIR ]; then
  rm -rf /opt/$DIR
fi

download $URL bin.zip
unzip -d /opt/$DIR bin.zip
rm bin.zip

echo "/opt/$DIR/bin/php7/bin/php"
