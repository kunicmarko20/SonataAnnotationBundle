#!/usr/bin/env sh
set -ev

./vendor/bin/phpunit -c phpunit.xml.dist --coverage-clover build/logs/clover.xml
