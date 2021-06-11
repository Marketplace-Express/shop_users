#!/usr/bin/env bash

# Executing migrations
php artisan migrate -q -n

# Copy PHP extensions configurations to container
cp -a php_extensions/. /usr/local/etc/php/conf.d/

# Generate JWT key pairs
echo "Generate JWT key pairs"
if [ -f "config/auth/jwt_private.pem" ] && [ -f "config/auth/jwt_public.pem" ]; then
    echo "JWT key pairs exist, skipping...";
else
    openssl genrsa -out config/auth/jwt_private.pem 2048
    openssl rsa -in config/auth/jwt_private.pem -pubout > config/auth/jwt_public.pem
fi

exec "$@"