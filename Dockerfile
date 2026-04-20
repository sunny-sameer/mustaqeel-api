FROM php:8.4-fpm-bullseye

WORKDIR /var/www/html

# System dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl wget \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    fontconfig xfonts-75dpi xfonts-base \
    libfreetype6 libjpeg62-turbo libxrender1 libxext6 \
    ca-certificates dpkg \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring bcmath zip exif pcntl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Composer install
COPY composer.json composer.lock ./
RUN composer install --no-scripts --optimize-autoloader

# wkhtmltopdf (FIXED & SAFE)
RUN wget -O /tmp/wkhtmltox.deb \
    https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.buster_amd64.deb

RUN dpkg -i /tmp/wkhtmltox.deb || true

RUN apt-get install -f -y

RUN rm -f /tmp/wkhtmltox.deb

# VERIFY installation (IMPORTANT)
RUN which wkhtmltopdf && wkhtmltopdf --version

# PHP config
RUN echo 'sys_temp_dir = "/tmp"' > /usr/local/etc/php/conf.d/sys_temp.ini
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

CMD ["php-fpm"]