<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorTest extends TestCase
{

    use RefreshDatabase;

    public function testGetAuthor(): void
    {
        $this->mocks();
        $response = $this->get('/api/author/index');

        $content = $response->getContent();

        $responseData = json_decode($content, true);
        dd($response);
        $data = $responseData['data'];

        $response->assertStatus(200);
        $this->assertEquals(3, count($data));
    }

    protected function mocks() 
    {
        $author = Author::factory(3)->create();
        return $author;
    }
}