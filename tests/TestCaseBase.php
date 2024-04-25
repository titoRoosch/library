<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
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

        $header = [
            'Authorization' => 'Bearer ' . $token ,
        ];

        return ['user' => $user, 'token' => $token, 'header' => $header];
    }

    protected function makeRequest(string $method, string $uri, array $headers = [], array $data = []): TestResponse
    {
        $response = $this->withHeaders($headers);

        switch ($method) {
            case 'get':
                $response = $response->get($uri);
                break;
            case 'post':
                $response = $response->post($uri, $data);
                break;
            case 'put':
                $response = $response->put($uri, $data);
                break;
            case 'delete':
                $response = $response->delete($uri);
                break;
            default:
                throw new \InvalidArgumentException("Unsupported HTTP method: {$method}");
        }

        return $response;
    }
}
