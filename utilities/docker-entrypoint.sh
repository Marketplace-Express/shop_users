#!/usr/bin/env bash

# Executing migrations
if [ -z $UNIT_TEST ]; then
  php artisan migrate -q -n
fi

# Copy PHP extensions configurations to container
cp -a php_extensions/. /usr/local/etc/php/conf.d/
exec "$@"