<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCaseBase;

class BookTest extends TestCaseBase
{

    use DatabaseTransactions;

    public function testGetBook(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken();
        
        $response = $this->makeRequest('get', '/api/book/index', $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals(Book::count(), count($responseData));
    }

    public function testGetBookById(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        
        $response = $this->makeRequest('get', '/api/book/show/' . $mock['books'][0]->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals($mock['books'][0]->id, $responseData['id']);
    }

    public function testCreateBook(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        $data =   [
            'title' => 'teste',
            'publish_date' => '1995-04-08',
            'authors' => [
                ["author_id" => $mock['author']->id]
            ]
        ];
        
        $response = $this->makeRequest('post', '/api/book/store', $authData['header'], $data);
        $content = $response->getContent();
        $responseData = json_decode($content, true);
        $this->assertEquals('teste', $responseData['title']);
    }

    public function testUpdateBook(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        $data = [
            'title' => $mock['books'][0]->title,
            'publish_date' => '1995-07-04',
            'authors' => [
                ["author_id" => $mock['author']->id]
            ]
        ];

        $response = $this->makeRequest('put', '/api/book/update/' . $mock['books'][0]->id, $authData['header'], $data);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals($responseData['id'], $mock['books'][0]->id);
        $this->assertEquals($responseData['publish_date'], '1995-07-04');
    }

    public function testDeleteBook(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');

        $response = $this->makeRequest('delete', '/api/book/delete/' . $mock['books'][0]->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
    }

    public function testDeleteBookForbbiden(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken();

        $response = $this->makeRequest('delete', '/api/book/delete/' . $mock['books'][0]->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(403);
        $this->assertEquals('Unauthorized', $responseData['error']);
    }


    protected function mocks() 
    {
        $author = Author::factory()->create();
        $books = Book::factory(3)->create();

        foreach($books as $book) {
            $book->authors()->attach($author->id);   
        }

        $mock = [
            'author' => $author,
            'books' => $books,
        ];

        return $mock;
    }
}
