#!bin/sh

php artisan migrate --force 
php artisan optimize

apachectl -D FOREGROUND
