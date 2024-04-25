<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCaseBase;

class UserTest extends TestCaseBase
{
    use DatabaseTransactions;

    public function testGetUser(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');

        $response = $this->makeRequest('get', '/api/user/index', $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals(User::count(), count($responseData));
    }

    public function testGetUserForbbiden(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken();

        $response = $this->makeRequest('get', '/api/user/index', $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(403);
        $this->assertEquals('Unauthorized', $responseData['error']);
    }

    public function testGetUserById(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        
        $response = $this->makeRequest('get', '/api/user/show/' . $mock['user']->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals($mock['user']->id, $responseData['id']);
    }

    public function testCreateUser(): void
    {
        $data = [
            'email' => 'teste@teste.com',
            'password' => 'password',
            'name' => 'teste'
        ];

        $response = $this->makeRequest('post', '/api/user/store/', [], $data);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals('teste@teste.com', $responseData['email']);
    }

    public function testUpdateUser(): void
    {
        $authData = $this->createUserAndGetToken();
        $data =   [
            'email' => $authData['user']->email,
            'password' => 'password1',
            'name' => 'teste2'
        ];
        
        $response = $this->makeRequest('put', '/api/user/update/'.$authData['user']->id, $authData['header'], $data);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals('teste2', $responseData['name']);
    }

    public function testDeleteUser(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');

        $response = $this->makeRequest('delete', '/api/user/delete/' .$authData['user']->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
    }

    public function testDeleteUserForbbiden(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken();

        $response = $this->makeRequest('delete', '/api/user/delete/' . $mock['user']->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(403);
        $this->assertEquals('Unauthorized', $responseData['error']);
    }

    protected function mocks() 
    {
        $user = User::factory()->create();

        $mock = [
            'user' => $user,
        ];

        return $mock;
    }
}
