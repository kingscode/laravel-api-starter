FROM php:7.4-apache

# Install GIT, GnuPG, NodeJS and NPM.
RUN apt-get update \
    && apt-get upgrade -y \
    && curl -sL https://deb.nodesource.com/setup_10.x | bash - \
    && apt-get install -y nodejs git gnupg

# Add necessary php extensions.
RUN apt-get install -y \
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
    && pecl install xdebug \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd mbstring zip mysqli pdo_mysql gmp \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete

# Create working directory
RUN mkdir -p /var/www/

ENV APACHE_DOCUMENT_ROOT /var/www/public

# Install composer from image. You may change it to the latest
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Some aliasses.
RUN echo 'alias pa="php artisan"' >> ~/.bashrc
RUN echo 'alias phpunit="./vendor/bin/phpunit"' >> ~/.bashrc
RUN echo 'alias refresh="pa migrate:fresh"' >> ~/.bashrc

# make laravel feel comfortable with mod-rewrite
RUN a2enmod rewrite && service apache2 restart
EXPOSE 80
