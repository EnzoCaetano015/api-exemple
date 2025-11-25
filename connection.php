<?php

$publicUrl = getenv("MYSQL_PUBLIC_URL");

// Faz o parse automático da URL mysql://user:pass@host:port/db
$parts = parse_url($publicUrl);

$host = $parts["host"];
$port = $parts["port"];
$user = $parts["user"];
$pass = $parts["pass"];
$db   = ltrim($parts["path"], "/");

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo "Conectado com sucesso!";
} catch (PDOException $e) {
    echo json_encode([
        "error" => "Falha ao conectar com o banco de dados",
        "details" => $e->getMessage()
    ]);
}
