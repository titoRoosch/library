<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
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

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->get('/api/rent/index');

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
        // $this->assertEquals(3, count($data));
    }

    public function testGetRentById(): void
    {
        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->get('/api/rent/show/'.$mock['user']->rents[0]->id);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
    }

    public function testCreateRent() : void
    {
        Event::fake();
        Queue::fake();

        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->post('/api/rent/store', [
            'books' => [
                ['book_id' => $mock['books'][0]->id]
            ]
        ]);

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        Queue::assertPushed(NewBookRent::class);
        $response->assertStatus(200);
    }

    public function testUpdateRent() : void
    {

        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->put('/api/rent/update/'.$mock['user']->rents[0]->id, [
            'status' => 'returned',
        ]);

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        $data = $responseData;

        $response->assertStatus(200);
        $this->assertEquals($responseData['status'], 'returned');
    }

    public function testDeleteRent() : void
    {

        $mock = $this->mocks();

        $authData = $this->createUserAndGetToken('super');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authData['token'],
        ])->delete('/api/rent/delete/'.$mock['user']->rents[0]->id );

        $content = $response->getContent();

        $responseData = json_decode($content, true);

        $response->assertStatus(200);
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
            // Verifica se o livro existe antes de tentar anexá-lo ao usuário
            if (Book::where('id', $book->id)->exists()) {
                $user->books()->attach($book->id, [
                    'rent_date' => Carbon::now()->format('Y-m-d'), // Use o formato 'Y-m-d' para datas
                    'scheduled_return' => Carbon::now()->addWeek()->format('Y-m-d'),
                    'status' => 'rented'
                ]);
            }
        }
        

        $mock = [
            'author' => $author,
            'books' => $books,
            'user' => $user,
        ];

        return $mock;
    }
}