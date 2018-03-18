#!/usr/bin/env sh
set -ev

phpunit -c phpunit.xml.dist --coverage-clover build/logs/clover.xml
