<?php

declare(strict_types=1);

namespace App\Controller;

use App\Data\CreateUserData;
use App\Middleware\HashPassword;
use App\Middleware\PersistUser;
use App\Middleware\ValidateEmail;
use App\Service\CreateUserService;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use ServiceRunner\Middleware\BasicResolver;
use ServiceRunner\Middleware\Runner;
use ServiceRunner\Middleware\ServicePayload;

class UsersController extends AppController
{
    public function add(): Response
    {
        $body = $this->request->getData() ?: json_decode((string) $this->request->getBody(), true) ?? [];

        $middlewares = [
            new PersistUser(),
            new HashPassword(),
            new ValidateEmail(),
        ];

        $runner  = new Runner($middlewares, new BasicResolver());
        $service = new CreateUserService([], $runner);

        $result = $service->run(new ServicePayload(new CreateUserData(
            name: $body['name'] ?? '',
            email: $body['email'] ?? '',
            password: $body['password'] ?? '',
        )));

        if (! $result || $result->getAttribute('error')) {
            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'error' => $result?->getAttribute('error') ?? 'Could not create user',
                ]))
                ->withStatus(422);
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'id'    => $result->getAttribute('user_id'),
                'name'  => $result->getAttribute('name'),
                'email' => $result->getAttribute('email'),
            ]))
            ->withStatus(201);
    }

    public function index(): Response
    {
        $connection = ConnectionManager::get('default');
        $users = $connection->execute('SELECT id, name, email FROM users ORDER BY id')
            ->fetchAll('assoc');

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($users));
    }
}
