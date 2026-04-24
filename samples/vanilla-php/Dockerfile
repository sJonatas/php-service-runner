FROM php:8.4-cli-alpine

RUN apk add --no-cache git unzip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json ./

RUN mkdir -p /service-runner

COPY src/ src/
COPY public/ public/

EXPOSE 8080
