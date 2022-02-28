FROM php:8.0.5-fpm-alpine

ARG user=king
ARG uid=1000
ARG development='false'

ENV DEVELOPMENT_BUILD=${development}

# Essentials
RUN echo "UTC" > /etc/timezone
RUN apk add --no-cache zip unzip curl nginx openrc supervisor

# Bash
RUN \
     if [[ $development = 'true' ]] ;  \
        then apk add bash nano && sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd; \
     fi

# Packages
RUN apk update \
    && apk upgrade \
    && apk add --no-cache \
        # gmp (arithmethic calculations)
        gmp-dev \
        # regex
        oniguruma-dev \
        # Config  \
        pcre-dev ${PHPIZE_DEPS} \
        libzip-dev

# PHP extensions
RUN docker-php-ext-install zip mysqli pdo_mysql gmp pcntl

# XDebug (if needed)
RUN \
     if [[ $development = 'true' ]] ;  \
        then pecl install xdebug && docker-php-ext-enable xdebug; \
     fi

# Redis (if needed)
RUN pecl install -o -f redis \
    && docker-php-ext-enable redis

# BCMath extension is required for Google Cloud Tasks
RUN docker-php-ext-install bcmath

# OPCache (if not development)
COPY .docker/php8-fpm-alpine/php8-fpm/opcache.ini /usr/local/etc/php/conf.d/
RUN \
     if [[ -z $development || $development != 'true' ]] ;  \
        then \
          docker-php-ext-install opcache && docker-php-ext-enable opcache; \
     fi

# GD (if needed)
#RUN apk add --no-cache \
#        zlib-dev \
#        freetype \
#        jpeg \
#        libpng \
#    && docker-php-ext-install -j$(nproc) gd \
#    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/

# Cleanup
RUN docker-php-source delete \
    && rm -rf /tmp/pear
RUN apk del pcre-dev ${PHPIZE_DEPS}

WORKDIR /var/www/

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

# Configure nginx
COPY .docker/php8-fpm-alpine/nginx/nginx.conf /etc/nginx/
RUN sed -i "s/_USER_/$user/g" /etc/nginx/nginx.conf
COPY .docker/php8-fpm-alpine/nginx/nginx-laravel.conf /etc/nginx/modules

RUN mkdir -p /run/nginx/
RUN touch /run/nginx/nginx.pid

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

# Configure PHP-FPM
RUN sed -i "s/www-data/$user/g" /usr/local/etc/php-fpm.d/www.conf
COPY .docker/php8-fpm-alpine/php8-fpm/php.ini /usr/local/etc/php/conf.d/php.ini

# Configure supervisor
RUN mkdir /etc/supervisor/
ADD .docker/php8-fpm-alpine/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
RUN sed -i "s/_USER_/$user/g" /etc/supervisor/supervisord.conf
RUN touch /etc/supervisor/supervisord.log

# Copy source
COPY . /var/www/

# Install dependencies when not in development mode
RUN if [[ -z $development || $development != 'true' ]] ;  \
    then composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader ; \
    else echo "Skipping composer in development mode"; \
    fi

# Add user
RUN addgroup -S $user && adduser -S $user -G $user -u $uid

# In development, make /opt writable for PHPStorm (coverage)
RUN if [[ $development == 'true' ]] ;  \
    then chown $user:$user -Rf /opt && chmod -R 777 /opt; \
    fi

# Apply ownage/permissions to user
RUN chown $user:$user -Rf /var/www/storage /var/www/bootstrap /var/lib/nginx /run/nginx/nginx.pid /etc/nginx/modules /etc/supervisor/supervisord.conf /etc/supervisor/supervisord.log
RUN chmod -R 755 /var/www/storage /etc/supervisor/supervisord.log
RUN echo 'alias pa="php artisan"' >> /home/$user/.bashrc
RUN echo 'alias phpunit="./vendor/bin/phpunit"' >> /home/$user/.bashrc
RUN echo 'alias refresh="pa migrate:fresh"' >> /home/$user/.bashrc

COPY .docker/php8-fpm-alpine/entrypoint.sh /etc/entrypoint.sh
RUN chmod +x /etc/entrypoint.sh
RUN chown $user:$user /etc/entrypoint.sh

USER $user
ENTRYPOINT /etc/entrypoint.sh
