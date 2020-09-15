FROM php:7.4.6-fpm

EXPOSE 80

WORKDIR /var/www/html

RUN apt-get update -y && apt-get install -y nginx

ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

RUN apt-get update && \
    apt-get install -y git procps vim wget

RUN docker-php-ext-install mysqli

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer && \
    apt-get -y autoremove && \
    apt-get clean

RUN composer self-update && \
    composer diagnose && \
    composer global require hirak/prestissimo

RUN docker-php-ext-install pdo pdo_mysql
COPY ./nginx-site.conf /etc/nginx/sites-enabled/default
RUN chown -R www-data:www-data /var/www/html

COPY entrypoint.sh /etc/entrypoint.sh
RUN chmod a+x /etc/entrypoint.sh
ENTRYPOINT ["/etc/entrypoint.sh"]
