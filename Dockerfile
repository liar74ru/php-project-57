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

RUN touch database/database.sqlite

CMD bash -c "\
    echo '=== DEPLOYMENT STARTED ==='; \
    echo 'Time: ' \$(date); \
    echo '=== DATABASE CONNECTION ==='; \
    php -r '\
        try { \
            \$pdo = new PDO( \
                \"pgsql:host=\" . getenv(\"DB_HOST\") . \";dbname=\" . getenv(\"DB_DATABASE\"), \
                getenv(\"DB_USERNAME\"), \
                getenv(\"DB_PASSWORD\") \
            ); \
            echo \"✅ Connected to: \" . getenv(\"DB_DATABASE\") . \"\\\\n\"; \
            \$stmt = \$pdo->query(\"SELECT table_name FROM information_schema.tables WHERE table_schema = '\''public'\''\"); \
            \$tables = \$stmt->fetchAll(PDO::FETCH_COLUMN); \
            echo \"📊 Existing tables: \" . count(\$tables) . \"\\\\n\"; \
            foreach (\$tables as \$table) { \
                echo \"   - \" . \$table . \"\\\\n\"; \
            } \
        } catch (PDOException \$e) { \
            echo \"❌ DB Error: \" . \$e->getMessage() . \"\\\\n\"; \
        } \
    '; \
    echo '=== RUNNING MIGRATIONS ==='; \
    php artisan migrate:fresh --seed --force --verbose 2>&1 || echo 'Migration command failed'; \
    echo '=== CHECKING RESULT ==='; \
    php -r '\
        try { \
            \$pdo = new PDO( \
                \"pgsql:host=\" . getenv(\"DB_HOST\") . \";dbname=\" . getenv(\"DB_DATABASE\"), \
                getenv(\"DB_USERNAME\"), \
                getenv(\"DB_PASSWORD\") \
            ); \
            \$stmt = \$pdo->query(\"SELECT table_name FROM information_schema.tables WHERE table_schema = '\''public'\''\"); \
            \$tables = \$stmt->fetchAll(PDO::FETCH_COLUMN); \
            echo \"📊 Tables after migration: \" . count(\$tables) . \"\\\\n\"; \
            if (count(\$tables) === 0) { \
                echo \"❌ ERROR: No tables were created!\\\\n\"; \
            } else { \
                echo \"✅ Migration successful! Tables:\\\\n\"; \
                foreach (\$tables as \$table) { \
                    echo \"   - \" . \$table . \"\\\\n\"; \
                } \
            } \
        } catch (Exception \$e) { \
            echo \"❌ Check failed: \" . \$e->getMessage() . \"\\\\n\"; \
        } \
    '; \
    echo '=== STARTING SERVER ==='; \
    php artisan serve --host=0.0.0.0 --port=10000"
