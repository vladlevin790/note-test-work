# <h1><i style="font-style: bold;">Note</i></h1>

## Содержание
- [Установка и настройка](#установка-и-настройка)
- [Запуск проекта](#запуск-проекта)
- [Выполнение миграций и генерация документации](#выполнение-миграций-и-генерация-документации)
- [Проверка тестов](#проверка-тестов)
- [Функционал](#функционал)
- [Как тестировал](#как-тестировал)
- [Содержимое Dockerfile](#содержимое-dockerfile)
- [Содержимое docker-compose файла](#содержимое-docker-compose-файла)

## Установка и настройка
<ol>
    <li><code>cd api</code> / <code>composer install</code> - установка зависимостей, а также подтягивание autoloader</li>
    <li><code>cd ..</code> / <code>docker-compose build</code> - сборка приложения в Docker контейнер</li>
</ol>

## Запуск проекта
Запустите проект <code>docker-compose up</code> </br>
Откройте Docker или выполните в терминале команду <code>docker exec -it laravel_app bash</code>, после чего выполните команды:

## Выполнение миграций и генерация документации
<ul>
    <li><code>php artisan migrate</code> - выполнение миграций базы данных</li>
    <li><code>php artisan l5-swagger:generate</code> - генерация документации Swagger (хоть комманда и есть в Dockerfile, "на всякий случай")</li>
    <li><code>php artisan storage:link</code> - создание символической ссылки для хранилища</li>
</ul>

## Проверка тестов
Для проверки тестов выполните команду <code>php artisan test</code> внутри того же Docker контейнера.

## Функционал
Функционал реализован в рамках Laravel, были созданы модель Note и миграция таблицы note соответственно. Также был создан контроллер, каждый метод которого пользуется Eloquent ORM для выборки/вставки/удаления данных из таблицы в базе данных. В контроллере существует 4 метода, каждый из которых планировалось вынести в отдельный класс, для облегчения читаемости кода, но затем от этой идеи отказался, так как задачка являлась простенькой.

## Как тестировал
Тестирования осуществлял методом функционального тестирования в рамках фреймворка Laravel, инструментом PHPUnit. Также потыкался в Swagger и Postman, для проверки возвращаемого/вставки/обновления и удаления данных.

## Содержимое Dockerfile
```Dockerfile
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
```
## Содержимое docker-compose файла
version: '3.8'

services:
mysql:
image: mysql:8.0
container_name: mysql
restart: unless-stopped
ports:
- "3306:3306"
  environment:
  MYSQL_DATABASE: write-note
  MYSQL_USER: admin
  MYSQL_PASSWORD: secret
  MYSQL_ROOT_PASSWORD: adminsecret
  volumes:
- mysql-data:/var/lib/mysql
  networks:
- laravel
  app:
  build:
  context: ./api
  dockerfile: Dockerfile
  container_name: laravel_app
  ports:
- "8000:8000"
  volumes:
- ./api:/var/www/html
  networks:
- laravel

networks:
laravel:
driver: bridge

volumes:
mysql-data:
