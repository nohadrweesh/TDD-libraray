<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_added_to_the_library(){

        $this->withoutExceptionHandling();
        $response=$this->post('/api/books',[
            'title'=>'New Book',
            'author'=>'Noha Drweesh'
        ]);
        $response->assertOk();
        $this->assertCount(1,Book::all());
    }

     /** @test */
     public function a_title_is_required(){
        //$this->withoutExceptionHandling(); to view error itself
        $response=$this->post('/api/books',[
            'title'=>'',
            'author'=>'Noha Drweesh'
        ]);
        $response->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_author_is_required(){
        //$this->withoutExceptionHandling(); to view error itself
        $response=$this->post('/api/books',[
            'title'=>'New Book',
            'author'=>''
        ]);
        $response->assertSessionHasErrors('author');

    }

    /** @test */
    public function a_book_can_be_updated(){

        $this->withoutExceptionHandling();
        $this->post('/api/books',[
            'title'=>'New Book',
            'author'=>'Noha Drweesh'
        ]);
        $book=Book::first();
        $this->patch('/api/books/'.$book->id,[
            'title'=>'New Book updated',
            'author'=>'Noha Darweesh'
        ]);

        $this->assertEquals('New Book updated',Book::first()->title);
        $this->assertEquals('Noha Darweesh',Book::first()->author);
    }
}
