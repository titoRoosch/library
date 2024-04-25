<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCaseBase;

class AuthorTest extends TestCaseBase
{

    use DatabaseTransactions;

    public function testGetAuthor(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->get('/api/author/index');

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        $data = $responseData;

        $response->assertStatus(200);
        // $this->assertEquals(3, count($data));
    }

    public function testGetAuthorById(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->get('/api/author/show/' . $mock['author'][0]->id);

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        $data = $responseData;


        $response->assertStatus(200);
        $this->assertEquals($mock['author'][0]->id, $data['id']);
    }

    public function testCreateAuthor(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->post('/api/author/store/', [
            'name' => 'teste',
            'birth_date' => '1995-07-04'
        ]);

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        $data = $responseData;


        $response->assertStatus(200);
        $this->assertEquals('teste', $data['name']);
    }


    public function testUpdateAuthor(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->put('/api/author/update/' . $mock['author'][0]->id, [
            'name' => $mock['author'][0]->name,
            'birth_date' => '1995-07-04'
        ]);

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        $data = $responseData;


        $response->assertStatus(200);
        $this->assertEquals($mock['author'][0]->id, $data['id']);
    }

    public function testDeleteAuthor(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->delete('/api/author/delete/' . $mock['author'][0]->id);

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        $data = $responseData;


        $response->assertStatus(200);
    }

    protected function mocks() 
    {
        $author = Author::factory(3)->create();

        $mock = [
            'author' => $author,
        ];

        return $mock;
    }
}