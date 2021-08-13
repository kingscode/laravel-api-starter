FROM php:8.0-apache
ARG user
ARG uid

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y \
        # utils
        nano \
        unzip \
        #gd
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        #zip
        libzip-dev \
        # gmp
        libgmp-dev \
        # regex
        libonig-dev \
    && pecl install -o -f redis \
    && pecl install xdebug \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd mbstring zip mysqli pdo_mysql gmp pcntl \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable redis \
    && docker-php-source delete \
    && rm -rf /tmp/pear

# Create working directory
RUN mkdir -p /var/www/

ENV APACHE_DOCUMENT_ROOT /var/www/public

# Install composer from image. You may change it to the latest
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Some aliasses.
RUN echo 'alias pa="php artisan"' >> /home/$user/.bashrc
RUN echo 'alias phpunit="./vendor/bin/phpunit"' >> /home/$user/.bashrc
RUN echo 'alias refresh="pa migrate:fresh"' >> /home/$user/.bashrc

# make laravel feel comfortable with mod-rewrite
RUN a2enmod rewrite && service apache2 restart

# Set the user
USER $user

EXPOSE 80
