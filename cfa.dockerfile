FROM php:7.1-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html

#ENV DB_CONNECTION mysql
#ENV DB_HOST 192.168.100.9
#ENV DB_PORT 3306
#ENV DB_DATABASE prod_cash_flow_api
#ENV DB_USERNAME cfaprod
#ENV DB_PASSWORD 1qaz2wsx


COPY . /var/www/html/
COPY ./config/site.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update

RUN apt-get install -y npm wget zlib1g-dev mysql-client \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && chown -R www-data:www-data /var/www/html \
    && chmod +x ./install-composer.sh \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && ./install-composer.sh \
    && a2enmod rewrite

WORKDIR /var/www/html/

USER www-data:www-data

RUN php composer.phar install --optimize-autoloader --no-dev \
    && mv .env.prod .env \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/cache \
    && mkdir -p storage/framework/views \
    && php artisan key:generate
    & php artisan migrate --force \
    #&& php artisan passport:install


USER root

EXPOSE 80


#libapache2-mod-php5 php5-mysql php5-gd php-apc php5-curl php5-xdebug

#RUN a2enmod php5
#RUN a2enmod rewrite

#Set up debugger
#RUN echo "zend_extension=/usr/lib/php5/20131226/xdebug.so" >> /etc/php5/apache2/php.ini
#RUN echo "xdebug.remote_enable=1" >> /etc/php5/apache2/php.ini
#RUN echo "xdebug.remote_host=192.168.2.117" >> /etc/php5/apache2/php.ini #Please provide your host (local machine IP)

#ENV APACHE_RUN_USER www-data
#ENV APACHE_RUN_GROUP www-data
#ENV APACHE_LOG_DIR /var/log/apache2
#ENV APACHE_LOCK_DIR /var/lock/apache2
#ENV APACHE_PID_FILE /var/run/apache2.pid



#ADD . /var/www/site

#ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

#CMD /usr/sbin/apache2ctl -D FOREGROUND