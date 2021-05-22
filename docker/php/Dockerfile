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
WORKDIR /root/
COPY --from=composer:2.0.13 /usr/bin/composer /usr/local/bin/composer


# Document Root
RUN mkdir -p /var/www/php-enum