<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Author;
use Carbon\Carbon;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function an_author_can_be_created(){

        $this->withoutExceptionHandling();
        $response=$this->post('/api/authors',[
            'name'=>'Noha Drweesh',
            'dob'=>'05/14/1988'
        ]);
        //$response->assertOk();
        $this->assertCount(1,Author::all());
        $authors=Author::all();
        $this->assertInstanceOf(Carbon::class,$authors->first()->dob);
        $this->assertEquals('14/05/1988',$authors->first()->dob->format('d/m/Y'));
    }
    
}
