#!/bin/sh

# Clear cache
php bin/console cache:clear

# Migrate database
php bin/console doctrine:migrations:migrate --no-interaction -v

# Fetch GeoIP database (only if MAXMIND_LICENCE_KEY is set)
if [ -n "${MAXMIND_LICENCE_KEY}" ]; then
    php bin/console geoip2:update
fi

# Start container
/usr/bin/supervisord -c "/etc/supervisor/conf.d/supervisord.conf"
