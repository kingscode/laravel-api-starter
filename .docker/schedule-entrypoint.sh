#!bin/sh

cd /var/www && \
    php artisan optimize && \
    php artisan schedule:run
