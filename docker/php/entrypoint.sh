#!/bin/sh
set -e

# Install PHP dependencies on first start (vendor/ is not committed)
if [ -f composer.json ] && [ ! -f vendor/autoload_runtime.php ]; then
    composer install --prefer-dist --no-interaction --no-progress
fi

# Bring the database schema up to date (db service is healthy thanks to depends_on)
if [ -f bin/console ]; then
    php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
fi

exec "$@"
