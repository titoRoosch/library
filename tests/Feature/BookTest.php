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

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->get('/api/author/index');

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        $data = $responseData;


        $response->assertStatus(200);
        // $this->assertEquals(3, count($data));
    }

    public function testGetBookById(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->get('/api/book/show/' . $mock['books'][0]->id);

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        $data = $responseData;

        $response->assertStatus(200);
        $this->assertEquals($mock['books'][0]->id, $data['id']);
    }

    public function testCreateBook(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->post('/api/book/store/', [
            'title' => 'teste',
            'publish_date' => '1995-04-08',
            'authors' => [
                ["author_id" => $mock['author']->id]
            ]
        ]);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals('teste', $responseData['title']);
    }

    public function testUpdateBook(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->put('/api/book/update/'. $mock['books'][0]->id, [
            'title' => 'teste',
            'publish_date' => '1995-04-08',
            'authors' => [
                ["author_id" => $mock['author']->id]
            ]
        ]);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals('teste', $responseData['title']);
    }

    public function testDeleteBook(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->delete('/api/book/delete/'. $mock['books'][0]->id);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
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
