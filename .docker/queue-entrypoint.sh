#!bin/sh

cd /var/www && \
    php artisan optimize && \
    php artisan queue:work -v
