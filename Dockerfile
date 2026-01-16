FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    libpq-dev \
    libzip-dev
RUN docker-php-ext-install pdo pdo_pgsql zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN curl -sL https://deb.nodesource.com/setup_24.x | bash -
RUN apt-get install -y nodejs

WORKDIR /app

COPY . .

RUN mkdir -p storage/framework/sessions \
    && chmod -R 775 storage \
    && chown -R www-data:www-data storage \
    && ls -la storage/

# 1. Добавьте --prefer-dist (решает проблему с Git)
RUN composer install --prefer-dist

RUN npm ci
RUN npm run build

# 2. УДАЛИТЕ эту строку - она создает ПУСТОЙ файл
RUN > database/database.sqlite

# Вместо этого, если нужен SQLite для тестов:
# RUN touch database/database.sqlite

# 3. Используйте migrate:fresh (решает проблему с sessions таблицей)
CMD ["bash", "-c", "php artisan migrate:fresh --seed --force && php artisan serve --host=0.0.0.0 --port=$PORT"]
