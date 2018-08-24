#!/usr/bin/env sh
set -ev

sudo apt update
composer install --prefer-dist
sudo apt install xvfb
sudo apt install firefox
wget https://selenium-release.storage.googleapis.com/3.14/selenium-server-standalone-3.14.0.jar
DISPLAY=:10 xvfb-run java -jar selenium-server-standalone-3.14.0.jar > /dev/null &
sleep 5
