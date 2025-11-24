FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath zip exif pcntl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy only composer files
COPY composer.json composer.lock ./

RUN composer install --no-scripts --optimize-autoloader

CMD ["php-fpm"]
