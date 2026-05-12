FROM php:8.3-fpm

# Зеркало Yandex
RUN sed -i 's|deb.debian.org|mirror.yandex.ru|g' /etc/apt/sources.list.d/debian.sources

RUN apt-get update && apt-get install -y \
    libzip-dev \
    curl \
    && docker-php-ext-install -j$(nproc) zip pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Параметризируем UID и GID
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd -g $GROUP_ID www
RUN useradd -u $USER_ID -ms /bin/bash -g www www

USER www
WORKDIR /var/www/html
