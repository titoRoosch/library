<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

abstract class TestCaseBase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected function createUserAndGetToken($role = 'common')
    {
        $user = User::factory()->create([
            'role' => $role,
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = json_decode($response->getContent(), true)['access_token'];

        return ['user' => $user, 'token' => $token];
    }
}
