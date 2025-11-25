<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = rtrim(parse_url($uri, PHP_URL_PATH) ?? '/', '/');
$path = $path === '' ? '/' : $path;

if ($method === 'GET' && $path === '/') {
    sendResponse(200, ['message' => 'conectado ao banco de dados']);
}

if ($method === 'GET' && $path === '/usuario') {
    $idParam = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    if ($idParam !== null && $idParam !== false) {
        $statement = $pdo->prepare('SELECT nome FROM teste WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', $idParam, PDO::PARAM_INT);
    } else {
        $statement = $pdo->prepare('SELECT nome FROM teste ORDER BY id ASC LIMIT 1');
    }

    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        sendResponse(404, ['error' => 'Usuario nao encontrado']);
    }

    sendResponse(200, ['nome' => $row['nome']]);
}

sendResponse(404, ['error' => 'Rota nao encontrada']);

function sendResponse(int $statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}
