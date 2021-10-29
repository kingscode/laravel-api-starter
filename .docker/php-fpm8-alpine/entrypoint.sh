#!/usr/bin/env sh

set -e

sed -i "s/_PORT_/$PORT/g" /etc/nginx/modules/nginx-laravel.conf

echo "Starting php-fpm8"
php-fpm8 -D

echo "Starting nginx"
nginx
