FROM php:7.3-fpm

MAINTAINER gachakra <gachakra.kangin@notonza.dev>


COPY php.ini /usr/local/etc/php/

RUN apt-get update \
    && apt-get install -y zlib1g-dev mysql-client libzip-dev vim git sudo unzip \
    && docker-php-ext-install zip pdo_mysql

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && sudo mv composer.phar /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# change packagist mirror server to JP one and install concurrent installer to accelerate
RUN composer config -g repos.packagist composer https://packagist.jp \
    && composer global require hirak/prestissimo


RUN mkdir -p /var/www/php-enum
WORKDIR /var/www/php-enum



## Timezone
#RUN echo $'[cat] << EOF > /etc/sysconfig/clock \n\
#ZONE="Asia/Tokyo" \n\
#UTC=false \n\
#EOF'
#RUN cp /usr/share/zoneinfo/Japan /etc/localtime
#
## Locale
#RUN yum install -y glibc-langpack-ja \
#    && yum clean all
#ENV LANG ja_JP.utf8
#ENV LC_ALL ja_JP.utf8

EXPOSE 80