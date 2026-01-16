<?php
// test-deploy.php - прямой тест без Laravel
echo "<h1>Direct PostgreSQL Test</h1>";

$host = 'dpg-d5kc5s4hg0os739gtrc0-a.virginia-postgres.render.com';
$db   = 'laravel_db_040b';
$user = 'laravel_db_user';
$pass = 'Woi8hgP90czH3FfD0EMOOayzDnMRXtzP';

try {
    // Прямое подключение
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
    ]);

    echo "<p style='color: green;'>✅ Connected to PostgreSQL</p>";

    // 1. Создаём тестовую таблицу
    $pdo->exec("CREATE TABLE IF NOT EXISTS direct_test (
        id SERIAL PRIMARY KEY,
        created_at TIMESTAMP DEFAULT NOW(),
        message TEXT
    )");
    echo "<p>✅ Created table 'direct_test'</p>";

    // 2. Вставляем данные
    $pdo->exec("INSERT INTO direct_test (message) VALUES ('Test from PHP at " . date('Y-m-d H:i:s') . "')");
    echo "<p>✅ Inserted test row</p>";

    // 3. Читаем данные
    $stmt = $pdo->query("SELECT * FROM direct_test ORDER BY created_at DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Data in direct_test:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Created At</th><th>Message</th></tr>";
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['created_at']}</td>";
        echo "<td>{$row['message']}</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Connection details used:<br>";
    echo "Host: $host<br>";
    echo "Database: $db<br>";
    echo "User: $user<br>";
    echo "Has password: " . (strlen($pass) > 0 ? 'Yes' : 'No') . "</p>";
}
