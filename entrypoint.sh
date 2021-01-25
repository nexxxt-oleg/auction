#!/usr/bin/env bash
set -e

env | while read -r LINE; do
    IFS="=" read VAR VAL <<< ${LINE}
    if [[ "$VAR" = "env" ]]; then
        cp ./environments/$VAL/nginx.conf /etc/nginx/nginx.conf
#        /app/init --env=$VAL --overwrite=y
      if [[ "$VAL" = "dev" ]]; then
#        composer install --ignore-platform-reqs --no-interaction --no-scripts --prefer-dist
      echo "composer skip"
      fi
        chown -R www-data:www-data /app
    fi
done

#echo "Starting supervisor..."
#supervisord -c /etc/supervisor/supervisord.conf

echo "Starting nginx..."
exec $(which nginx) -c /etc/nginx/nginx.conf -g "daemon off;" &

echo "Starting php-fpm..."
php-fpm
