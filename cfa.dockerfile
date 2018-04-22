FROM php:7.1-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html

COPY . /var/www/html/
COPY ./config/site.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update \
    && apt-get install -y --no-install-recommends npm wget zlib1g-dev mysql-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && chown -R www-data:www-data /var/www/html \
    && chmod +x ./install-composer.sh \
    && sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf \
    && sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && ./install-composer.sh \
    && a2enmod rewrite

WORKDIR /var/www/html/

USER www-data:www-data

RUN php composer.phar install --optimize-autoloader --no-dev \
    && mv .env.prod .env \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/views \
    && php artisan key:generate \
    && php artisan migrate --force \
    && php artisan passport:install


USER root

EXPOSE 80

MAINTAINER lmagiera@gmail.com
LABEL maintainer="lmagiera@gmail.com"