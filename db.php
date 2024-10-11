<?php
try {
    $db = new PDO('sqlite:hutang.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS debts (
        id INTEGER PRIMARY KEY,
        name TEXT,
        amount INTEGER,
        status TEXT
    )");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
