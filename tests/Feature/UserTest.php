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

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->get('/api/user/index');

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        // $this->assertEquals(3, count($responseData));
    }

    public function testGetUserById(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->get('/api/user/show/' . $mock['user']->id);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals($mock['user']->id, $responseData['id']);
    }

    public function testCreateUser(): void
    {
        $response = $this->post('/api/user/store', [
            'email' => 'teste@teste.com',
            'password' => 'password',
            'name' => 'teste'
        ]);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals('teste@teste.com', $responseData['email']);
    }

    public function testUpdateUser(): void
    {
        $authData = $this->createUserAndGetToken('super');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->put('/api/user/update/'.$authData['user']->id, [
            'email' => $authData['user']->email,
            'password' => 'password1',
            'name' => 'teste2'
        ]);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals('teste2', $responseData['name']);
    }

    public function testDeleteUser(): void
    {
        $authData = $this->createUserAndGetToken('super');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->delete('/api/user/delete/'.$authData['user']->id);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
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
