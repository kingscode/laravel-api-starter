
if [ -z "$PORT" ]
  then export PORT=80 && echo "Setting port to default: 80";
fi

echo "Updating nginx port to $PORT"
sed -i "s/_PORT_/$PORT/g" /etc/nginx/modules/nginx-laravel.conf

if [[ -z "$DEVELOPMENT_BUILD" || $DEVELOPMENT_BUILD != 'true' ]]
  then php artisan migrate && php artisan optimize;
fi

supervisord -c /etc/supervisor/supervisord.conf
