FROM php:8.2.9-apache-bullseye
RUN docker-php-ext-install pdo_mysql 

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
