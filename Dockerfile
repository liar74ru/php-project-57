FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
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

RUN composer install
RUN npm ci
RUN npm run build

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

CMD ["bash", "-c", "\
    echo 'APP_ENV=production' > .env && \
    echo 'APP_DEBUG=false' >> .env && \
    echo 'DB_CONNECTION=pgsql' >> .env && \
    echo \"DB_HOST=\$DB_HOST\" >> .env && \
    echo \"DB_PORT=\$DB_PORT\" >> .env && \
    echo \"DB_DATABASE=\$DB_DATABASE\" >> .env && \
    echo \"DB_USERNAME=\$DB_USERNAME\" >> .env && \
    echo \"DB_PASSWORD=\$DB_PASSWORD\" >> .env && \
    echo '=== .env created ===' && \
    cat .env && \
    php artisan migrate:fresh --seed --force && \
    php artisan serve --host=0.0.0.0 --port=10000"]
