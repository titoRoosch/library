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
        
        $response = $this->makeRequest('get', '/api/author/index', $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals(Author::count(), count($responseData));
    }

    public function testGetAuthorById(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        
        $response = $this->makeRequest('get', '/api/author/show/' . $mock['author'][0]->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals($mock['author'][0]->id, $responseData['id']);
    }

    public function testCreateAuthor(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        $data =  [
            'name' => 'teste',
            'birth_date' => '1995-07-04'
        ];
        
        $response = $this->makeRequest('post', '/api/author/store', $authData['header'], $data);
        $content = $response->getContent();
        $responseData = json_decode($content, true);
        $this->assertEquals('teste', $responseData['name']);
    }


    public function testUpdateAuthor(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        $data = [
            'name' => $mock['author'][0]->name,
            'birth_date' => '1995-07-04'
        ];

        $response = $this->makeRequest('put', '/api/author/update/' . $mock['author'][0]->id, $authData['header'], $data);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals($responseData['id'], $mock['author'][0]->id);
        $this->assertEquals($responseData['birth_date'], '1995-07-04');
    }

    public function testDeleteAuthor(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');

        $response = $this->makeRequest('delete', '/api/author/delete/' . $mock['author'][0]->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

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