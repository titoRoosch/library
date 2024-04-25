<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use App\Models\Rent;
use App\Events\NewBookRent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCaseBase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

class RentTest extends TestCaseBase
{

    use DatabaseTransactions;

    public function testGetRent(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        
        $response = $this->makeRequest('get', '/api/rent/index', $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals(Rent::count(), count($responseData));
    }

    public function testGetRentForbbiden(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken();

        $response = $this->makeRequest('get', '/api/rent/index', $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(403);
        $this->assertEquals('Unauthorized', $responseData['error']);
    }

    public function testGetRentById(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        
        $response = $this->makeRequest('get', '/api/rent/show/' . $mock['user']->rents[0]->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals($mock['user']->rents[0]->id, $responseData['id']);
    }

    public function testCreateRent() : void
    {
        Event::fake();
        Queue::fake();

        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        $data =  [
            'books' => [
                ['book_id' => $mock['books'][0]->id]
            ]
        ];
        
        $response = $this->makeRequest('post', '/api/rent/store', $authData['header'], $data);
        $content = $response->getContent();
        $responseData = json_decode($content, true);
        
        Queue::assertPushed(NewBookRent::class);
        $response->assertStatus(200);
    }

    public function testUpdateRent() : void
    {

        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');
        $data = [
            'status' => 'returned'
        ];

        $response = $this->makeRequest('put', '/api/rent/update/'.$mock['user']->rents[0]->id, $authData['header'], $data);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        $this->assertEquals($responseData['status'], 'returned');
        
    }

    public function testDeleteRent() : void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken('super');

        $response = $this->makeRequest('delete', '/api/rent/delete/'.$mock['user']->rents[0]->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(200);
    }
    
    public function testDeleteRentForbbiden(): void
    {
        $mock = $this->mocks();
        $authData = $this->createUserAndGetToken();

        $response = $this->makeRequest('delete', '/api/rent/delete/' . $mock['user']->rents[0]->id, $authData['header']);
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $response->assertStatus(403);
        $this->assertEquals('Unauthorized', $responseData['error']);
    }

    protected function mocks() 
    {
        $author = Author::factory()->create();
        $books = Book::factory(3)->create();
        $user = User::factory()->create();

        foreach($books as $book) {
            $book->authors()->attach($author->id);   
        }

        foreach ($books as $book) {
            $user->books()->attach($book->id, [
                'rent_date' => Carbon::now()->format('Y-m-d'),
                'scheduled_return' => Carbon::now()->addWeek()->format('Y-m-d'),
                'status' => 'rented'
            ]);
        }

        $mock = [
            'author' => $author,
            'books' => $books,
            'user' => $user,
        ];

        return $mock;
    }
}