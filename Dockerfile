FROM php:8.2-apache

# MySQL support install
RUN docker-php-ext-install mysqli pdo pdo_mysql

# CHange document root to /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-install intl

# copy files to the container
COPY . /var/www/html/
