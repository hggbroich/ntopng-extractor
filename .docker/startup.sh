#!/bin/sh

# Clear cache
/usr/local/bin/frankenphp php-cli bin/console cache:clear

# Migrate database
/usr/local/bin/frankenphp php-cli bin/console doctrine:migrations:migrate --no-interaction -v

# Fetch GeoIP database (only if MAXMIND_LICENSE_KEY is set)
if [ -n "${MAXMIND_LICENSE_KEY}" ]; then
    /usr/local/bin/frankenphp php-cli bin/console geoip2:update
fi

# Start FrankenPHP
/usr/local/bin/frankenphp run --config /etc/caddy/Caddyfile
