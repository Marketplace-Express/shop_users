#!/usr/bin/env bash

# Executing migrations
php artisan migrate -q -n

# Copy PHP extensions configurations to container
cp -a php_extensions/. /usr/local/etc/php/conf.d/
exec "$@"