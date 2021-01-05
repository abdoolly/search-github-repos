# build stage
FROM composer AS build
WORKDIR /usr/src
COPY . .
RUN composer install

# test stage
FROM composer AS test
WORKDIR /usr/src
COPY --from=build /usr/src .
CMD ./vendor/phpunit/phpunit/phpunit

# running stage
FROM composer AS run
WORKDIR /usr/src
COPY --from=build /usr/src .
CMD php -S 0.0.0.0:8000 -t public
