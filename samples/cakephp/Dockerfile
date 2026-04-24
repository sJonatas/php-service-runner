FROM php:8.4-cli-alpine

RUN apk add --no-cache git unzip sqlite icu-dev \
    && docker-php-ext-install intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN mkdir -p /service-runner logs tmp/cache/persistent tmp/cache/models tmp/sessions

EXPOSE 8082
