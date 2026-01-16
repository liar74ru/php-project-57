FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev
RUN docker-php-ext-install pdo pdo_pgsql zip
# RUN docker-php-ext-configure pdo pdo_pgsql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN curl -sL https://deb.nodesource.com/setup_24.x | bash -
RUN apt-get install -y nodejs

WORKDIR /app

COPY . .
RUN composer install
RUN npm ci
RUN npm run build

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

RUN > database/database.sqlite

CMD bash -c "\
    # Laravel автоматически читает переменные окружения
    # НЕ нужно создавать .env файл!

    echo '=== ENVIRONMENT VARIABLES ===' && \
    echo \"DB_CONNECTION: \$DB_CONNECTION\" && \
    echo \"DB_HOST: \$DB_HOST\" && \
    echo \"DB_DATABASE: \$DB_DATABASE\" && \
    \
    # Проверяем что Laravel видит переменные
    php -r 'echo \"Laravel sees DB_CONNECTION: \" . (getenv(\"DB_CONNECTION\") ?: \"NOT SET\") . \"\\\\n\";' && \
    \
    echo '=== RUNNING MIGRATIONS ===' && \
    php artisan migrate:fresh --seed --force && \
    \
    echo '=== STARTING SERVER ===' && \
    php artisan serve --host=0.0.0.0 --port=10000"
