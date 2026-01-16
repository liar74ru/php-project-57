<?php
// Простой тест без Laravel
echo "=== DIRECT POSTGRESQL TEST ===\n";

// Твои credentials с Render
$host = 'dpg-d5kc5s4hg0os739gtrc0-a.virginia-postgres.render.com';
$db   = 'laravel_db_040b';
$user = 'laravel_db_user';
$pass = 'Woi8hgP90czH3FfD0EMOOayzDnMRXtzP';

try {
    // Прямое подключение
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "✅ Connected to PostgreSQL\n";
    echo "Database: $db\n";
    echo "Host: $host\n\n";

    // 1. Покажем ВСЕ таблицы
    $stmt = $pdo->query("
        SELECT schemaname, tablename
        FROM pg_tables
        WHERE schemaname NOT IN ('pg_catalog', 'information_schema')
        ORDER BY schemaname, tablename
    ");

    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "📊 ALL TABLES IN DATABASE:\n";
    if (empty($tables)) {
        echo "   No tables found!\n";
    } else {
        foreach ($tables as $table) {
            echo "   - {$table['schemaname']}.{$table['tablename']}\n";
        }
    }

    echo "\n=== CREATING TEST TABLE ===\n";

    // 2. Создадим тестовую таблицу
    $pdo->exec("CREATE TABLE IF NOT EXISTS our_direct_test (
        id SERIAL PRIMARY KEY,
        test_time TIMESTAMP DEFAULT NOW(),
        data TEXT
    )");
    echo "✅ Created 'our_direct_test' table\n";

    // 3. Вставим данные
    $pdo->exec("INSERT INTO our_direct_test (data) VALUES ('Test at " . date('Y-m-d H:i:s') . "')");
    echo "✅ Inserted test data\n";

    // 4. Проверим
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM our_direct_test");
    $count = $stmt->fetchColumn();
    echo "✅ Rows in our_direct_test: $count\n";

} catch (PDOException $e) {
    echo "❌ Connection error: " . $e->getMessage() . "\n";
    echo "Tried to connect to: $host / $db\n";
}
