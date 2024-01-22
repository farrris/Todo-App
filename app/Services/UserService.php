<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser(array $userData): User
    {
        $user = User::create([
            "name" => $userData["name"],
            "email" => $userData["email"],
            "password" => $userData["password"]
        ]);

        return $user;
    }
}