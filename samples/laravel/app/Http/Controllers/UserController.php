<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\CreateUserData;
use App\Service\CreateUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ServiceRunner\Middleware\ServicePayload;

class UserController
{
    public function __construct(
        private CreateUserService $createUser,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $result = $this->createUser->run(new ServicePayload(new CreateUserData(
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
        )));

        if (! $result || $result->getAttribute('error')) {
            return response()->json([
                'error' => $result?->getAttribute('error') ?? 'Could not create user',
            ], 422);
        }

        return response()->json([
            'id'    => $result->getAttribute('user_id'),
            'name'  => $result->getAttribute('name'),
            'email' => $result->getAttribute('email'),
        ], 201);
    }

    public function index(): JsonResponse
    {
        $users = DB::table('users')
            ->select('id', 'name', 'email')
            ->orderBy('id')
            ->get();

        return response()->json($users);
    }
}
