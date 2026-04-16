FROM php:8.3-fpm

# Зеркало Yandex
RUN sed -i 's|deb.debian.org|mirror.yandex.ru|g' /etc/apt/sources.list.d/debian.sources

# Расширения PHP + нужные пакеты
RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ←←← ЭТО САМАЯ ВАЖНАЯ ЧАСТЬ ←←←
RUN mkdir -p storage/framework/{sessions,views,cache,testing} \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
