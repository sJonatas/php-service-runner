<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Samples\VanillaPhp\Data\CreateUserData;
use Samples\VanillaPhp\Middleware\HashPassword;
use Samples\VanillaPhp\Middleware\PersistUser;
use Samples\VanillaPhp\Middleware\ValidateEmail;
use Samples\VanillaPhp\Service\CreateUserService;
use ServiceRunner\Middleware\BasicResolver;
use ServiceRunner\Middleware\Runner;
use ServiceRunner\Middleware\ServicePayload;

$dbPath = '/app/database.sqlite';
$pdo    = new PDO("sqlite:{$dbPath}");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
)');

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

header('Content-Type: application/json');

if ($path === '/users' && $method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    $name     = $body['name']     ?? '';
    $email    = $body['email']    ?? '';
    $password = $body['password'] ?? '';

    $middlewares = [
        new PersistUser($pdo),
        new HashPassword(),
        new ValidateEmail(),
    ];

    $runner  = new Runner($middlewares, new BasicResolver());
    $service = new CreateUserService([], $runner);

    $result = $service->run(new ServicePayload(new CreateUserData(
        name: $name,
        email: $email,
        password: $password,
    )));

    if ($result && $result->getAttribute('error')) {
        http_response_code(422);
        echo json_encode(['error' => $result->getAttribute('error')]);
    } elseif ($result) {
        http_response_code(201);
        echo json_encode([
            'id'    => $result->getAttribute('user_id'),
            'name'  => $result->getAttribute('name'),
            'email' => $result->getAttribute('email'),
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Could not process request']);
    }
} elseif ($path === '/users' && $method === 'GET') {
    $stmt  = $pdo->query('SELECT id, name, email FROM users ORDER BY id');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
