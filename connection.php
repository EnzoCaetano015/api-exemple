<?php
declare(strict_types=1);

$pdo = null;
$connectionError = null;

$publicUrl = getenv('MYSQL_PUBLIC_URL') ?: '';

if ($publicUrl === '') {
    $connectionError = 'MYSQL_PUBLIC_URL nao definida';
} else {
    $parts = parse_url($publicUrl);

    if ($parts === false || !isset($parts['host'], $parts['user'], $parts['pass'], $parts['path'])) {
        $connectionError = 'URL de conexao invalida';
    } else {
        $host = $parts['host'];
        $port = $parts['port'] ?? 3306;
        $user = $parts['user'];
        $pass = $parts['pass'];
        $db = ltrim($parts['path'], '/');

        if ($db === '') {
            $connectionError = 'Nome do banco nao informado na URL';
        } else {
            try {
                $pdo = new PDO(
                    "mysql:host=$host;port=$port;dbname=$db;charset=utf8",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                $connectionError = $e->getMessage();
            }
        }
    }
}
