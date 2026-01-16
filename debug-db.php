<?php
// debug-db.php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

echo "=== DATABASE CONFIGURATION ===\n\n";

// 1. Какая конфигурация?
echo "Config default: " . config('database.default') . "\n";

$connection = config('database.connections.' . config('database.default'));
echo "Driver: " . ($connection['driver'] ?? 'none') . "\n";
echo "Host: " . ($connection['host'] ?? 'none') . "\n";
echo "Database: " . ($connection['database'] ?? 'none') . "\n";

// 2. Переменные окружения
echo "\nEnvironment variables:\n";
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "\n";

// 3. Пробуем подключиться
echo "\n=== CONNECTION TEST ===\n";
try {
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Connected to: " . \Illuminate\Support\Facades\DB::connection()->getDatabaseName() . "\n";
    echo "Using driver: " . \Illuminate\Support\Facades\DB::connection()->getDriverName() . "\n";

    // Создаём тестовую таблицу
    \Illuminate\Support\Facades\DB::statement("CREATE TABLE IF NOT EXISTS debug_test (id SERIAL, created_at TIMESTAMP DEFAULT NOW())");
    echo "✅ Created debug_test table\n";

    \Illuminate\Support\Facades\DB::insert("INSERT INTO debug_test DEFAULT VALUES");
    echo "✅ Inserted test row\n";

    // Показываем все таблицы
    $tables = \Illuminate\Support\Facades\DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "Tables in database: " . count($tables) . "\n";
    foreach ($tables as $table) {
        echo "  - " . $table->table_name . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
