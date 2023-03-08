FROM thekingscode/php8.2-apache as base

FROM base as dev

ARG user=king
ARG uid=1000

# Use development php ini
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Setup user.
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer
RUN chown -R $user:$user /home/$user
RUN chown $user:$user -Rf /opt && chmod -R 777 /opt; # Make /opt writable for PHPStorm (coverage)

RUN echo 'alias pa="php artisan"' >> /home/$user/.bashrc
RUN echo 'alias phpunit="./vendor/bin/phpunit"' >> /home/$user/.bashrc
RUN echo 'alias refresh="pa migrate:fresh"' >> /home/$user/.bashrc

USER $user

FROM base as prod

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache;

# Use development php ini
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

COPY . /var/www
WORKDIR /var/www

RUN composer install --no-dev --optimize-autoloader

# Make storage and bootstrap writable for the www-data user
RUN chown -R www-data:www-data /var/www/storage && chmod -R 777 /var/www/storage
RUN chown -R www-data:www-data /var/www/bootstrap && chmod -R 777 /var/www/bootstrap

COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT [ "sh","/usr/local/bin/entrypoint.sh" ]

FROM php:8.2 as base-cli

# TODO Install any required libraries and/or php extensions that you might need in the queue-worker.

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

FROM base-cli as prod-cli

RUN docker-php-ext-install opcache && docker-php-ext-enable opcache;

COPY . /var/www
WORKDIR /var/www

RUN composer install --prefer-dist --no-interaction

FROM prod-cli as schedule

COPY .docker/schedule-entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT [ "sh","/usr/local/bin/entrypoint.sh" ]

FROM prod-cli as queue

COPY .docker/queue-entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT [ "sh","/usr/local/bin/entrypoint.sh" ]
