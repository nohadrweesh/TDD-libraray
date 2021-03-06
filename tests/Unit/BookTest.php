<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_author_id_is_required(){
        Book::create([
            'title'=>'New Book',
            'author_id'=>'Noha Darwish'
        ]);

        $this->assertCount(1,Book::all());
    }
}
