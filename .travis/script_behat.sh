#!/usr/bin/env sh
set -ev

features/Fixtures/Project/bin/console --no-interaction cache:clear --env=test
features/Fixtures/Project/bin/console doctrine:schema:update --force --env=test
features/Fixtures/Project/bin/console assets:install
nohup php -S 127.0.0.1:8000 -t features/Fixtures/Project/public 2>&1 &
vendor/bin/behat
