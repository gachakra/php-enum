FROM php:7.3-fpm

MAINTAINER gachakra <gachakra.kangin@notonza.dev>


# Timezone
ARG TIMEZONE
RUN rm /etc/localtime \
    && echo ${TIMEZONE} > /etc/timezone
# check Timezone
# RUN dpkg-reconfigure -f noninteractive tzdata


# Libraries
RUN apt-get update \
    && apt-get install -y zlib1g-dev libzip-dev mariadb-client vim git sudo unzip


# PHP
COPY php.ini /usr/local/etc/php/
RUN docker-php-ext-install zip pdo_mysql

# set path so that able to run command without `vendor/bin`
ENV PATH $PATH:./vendor/bin


# Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && sudo mv composer.phar /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# change packagist mirror server to JP one and install concurrent installer to accelerate
RUN composer config -g repos.packagist composer https://packagist.jp \
    && composer global require hirak/prestissimo


# Document Root
RUN mkdir -p /var/www/php-enum