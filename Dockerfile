FROM php:8.4-cli

# Установка только PostgreSQL расширений
RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /app
COPY . .

# ПРОСТЕЙШАЯ КОМАНДА - сначала тест, потом Laravel
CMD bash -c "\
    echo '=== STARTING CONTAINER ==='; \
    echo 'Time: ' \$(date); \
    echo '=== RUNNING DIRECT PHP TEST ==='; \
    php test-deploy.php; \
    sleep 2; \
    echo '=== TRYING LARAVEL MIGRATIONS ==='; \
    if [ -f vendor/autoload.php ]; then \
        php artisan migrate:fresh --seed --force --no-interaction --verbose 2>&1; \
        echo 'Laravel migrations attempted'; \
    else \
        echo 'Vendor not found, skipping Laravel'; \
    fi; \
    echo '=== STARTING SERVER ==='; \
    php -S 0.0.0.0:10000 -t public"
