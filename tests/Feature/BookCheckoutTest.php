<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_checked_out_by_a_signed_in_user(){
        //$this->withoutExceptionHandling();
        
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        
        $this->actingAs($user=User::factory()->create())
        ->post('/api/checkout/'.$book->id);

        
        $this->assertCount(1,Reservation::all());
        $this->assertEquals($book->id,Reservation::first()->book_id);
        $this->assertEquals($user->id,Reservation::first()->user_id);
        $this->assertEquals(now(),Reservation::first()->checked_out_at);
    }

    /** @test */
    public function only_signed_in_user_can_checkout_a_book(){
        //$this->withoutExceptionHandling();
        
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        
        $this->post('/api/checkout/'.$book->id)
                ->assertRedirect('login');
        
        $this->assertCount(0,Reservation::all());
    }

     /** @test */
     public function only_real_books_can_be_checked_out(){
        
        $this->actingAs($user=User::factory()->create())
        ->post('/api/checkout/123')
        ->assertStatus(404);

        
        $this->assertCount(0,Reservation::all());
       
    }


    /** @test */
    public function a_book_can_be_checked_in_by_a_signed_in_user(){
        //$this->withoutExceptionHandling();
        
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        $user=User::factory()->create();
        
        $this->actingAs($user)
        ->post('/api/checkout/'.$book->id);

        $this->actingAs($user)
        ->post('/api/checkin/'.$book->id);

        
        $this->assertCount(1,Reservation::all());
        $this->assertEquals($book->id,Reservation::first()->book_id);
        $this->assertEquals($user->id,Reservation::first()->user_id);
        $this->assertEquals(now(),Reservation::first()->checked_out_at);
        $this->assertEquals(now(),Reservation::first()->checked_in_at);
    }


    /** @test */
    public function only_signed_in_user_can_checkin_a_book(){
        //$this->withoutExceptionHandling();
        
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        
        $this->actingAs(User::factory()->create())
                ->post('/api/checkout/'.$book->id);

        Auth::logout();
        
        $this->post('/api/checkin/'.$book->id)
                ->assertRedirect('login');
        
        $this->assertCount(1,Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);
    }

     /** @test */
     public function only_real_books_can_be_checked_in(){
        
        $this->actingAs($user=User::factory()->create())
        ->post('/api/checkin/123')
        ->assertStatus(404);

        
        $this->assertCount(0,Reservation::all());
       
    }

    /** @test */
    public function a_404_is_thrown_if_book_not_checked_out_first(){
        
        //$this->withoutExceptionHandling();
        
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        $user=User::factory()->create();
        
       
        $this->actingAs($user)
        ->post('/api/checkin/'.$book->id)
        ->assertStatus(404);

        
        $this->assertCount(0,Reservation::all());
              
    }





}
