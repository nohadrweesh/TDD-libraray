<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_added_to_the_library(){

        //$this->withoutExceptionHandling();
        $response=$this->post('/api/books',$this->data());
        //$response->assertOk();
        $this->assertCount(1,Book::all());
        $response->assertRedirect(Book::first()->path());
    }

     /** @test */
     public function a_title_is_required(){
        //$this->withoutExceptionHandling(); to view error itself
        $response=$this->post('/api/books',array_merge($this->data(),['title'=>'']));
        $response->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_author_is_required(){
        //$this->withoutExceptionHandling(); to view error itself
        $response=$this->post('/api/books',array_merge($this->data(),['author_id'=>'']));
        $response->assertSessionHasErrors('author_id');

    }

    /** @test */
    public function a_book_can_be_updated(){

        //$this->withoutExceptionHandling();
        $this->post('/api/books',$this->data());
        $book=Book::first();
        $response=$this->patch('/api/books/'.$book->id,[
            'title'=>'New Book updated',
            'author_id'=>'Noha Darweesh'
        ]);

        $this->assertEquals('New Book updated',Book::first()->title);
        $this->assertEquals(2,Book::first()->author_id);
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_book_can_be_deleted(){
        //$this->withoutExceptionHandling();
        $this->post('/api/books',$this->data());

        $this->assertCount(1,Book::all());
        $book=Book::first();

        $response=$this->delete('/api/books/'.$book->id);
        $this->assertCount(0,Book::all());

        $response->assertRedirect('/books');
    }


     /** @test */
     public function a_new_author_is_added_automatically(){
        $this->withoutExceptionHandling();
        $this->post('/api/books',[
            'title'=>'New Book',
            'author_id'=>'Noha Drweesh'
        ]);
        $book=Book::first();
        $author=Author::first();

        $this->assertEquals($author->id,$book->author_id);
        $this->assertCount(1,Author::all());

     }


     private function data(){
        return [
            'title'=>'New Book',
            'author_id'=>'Noha Drweesh'
        ];
     }
}
