FROM composer

WORKDIR /usr/src

COPY . .
RUN composer install

CMD php -S 0.0.0.0:8000 -t public
