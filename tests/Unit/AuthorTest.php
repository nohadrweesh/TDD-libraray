<?php

namespace Tests\Unit;

use App\Models\Author;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function only_name_is_required_to_create_an_author()
    {
        Author::firstOrCreate([
            'name'=>'Noha Darwish'
        ]);
        $this->assertCount(1,Author::all());
    }
}
