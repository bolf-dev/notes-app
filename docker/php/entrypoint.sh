#!/bin/sh
set -e

# Install PHP dependencies on first start (vendor/ is not committed)
if [ -f composer.json ] && [ ! -f vendor/autoload_runtime.php ]; then
    composer install --prefer-dist --no-interaction --no-progress
fi

exec "$@"
