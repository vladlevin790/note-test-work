FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader
RUN php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"


RUN chown -R www-data:www-data /var/www/html

USER www-data

EXPOSE 8000

CMD ["php", "artisan", "l5-swagger:generate"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
