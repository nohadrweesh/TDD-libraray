<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_book_can_be_checked_out(){
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        $user = User::factory()->create();

        $book->checkout($user);

        $this->assertCount(1,Reservation::all());
        $this->assertEquals($book->id,Reservation::first()->book_id);
        $this->assertEquals($user->id,Reservation::first()->user_id);
        $this->assertEquals(now(),Reservation::first()->checked_out_at);
    }

    /** @test */
    public function a_book_can_be_returned(){
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        $user = User::factory()->create();

        $book->checkout($user);
        $book->checkin($user);

        $this->assertCount(1,Reservation::all());
        $this->assertEquals($book->id,Reservation::first()->book_id);
        $this->assertEquals($user->id,Reservation::first()->user_id);
        $this->assertNotNull(Reservation::first()->checked_in_at);
        $this->assertEquals(now(),Reservation::first()->checked_in_at);
    }

    /** @test */
    public function if_not_checked_out_exception_is_thrown(){
        $this->expectException(\Exception::class);
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        $user = User::factory()->create();

        $book->checkin($user);

    }



    /** @test */
    public function a_user_can_checkout_a_book_twice(){
        $book = Book::factory()->create(); //create presist to db ,but make only creates the model 
        $user = User::factory()->create();

        $book->checkout($user);
        $book->checkin($user);

        $book->checkout($user);

        $this->assertCount(2,Reservation::all());
        $this->assertEquals($book->id,Reservation::find(2)->book_id);
        $this->assertEquals($user->id,Reservation::find(2)->user_id);
        $this->assertNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(),Reservation::find(2)->checked_out_at);

        $book->checkin($user);

        $this->assertCount(2,Reservation::all());
        $this->assertEquals($book->id,Reservation::find(2)->book_id);
        $this->assertEquals($user->id,Reservation::find(2)->user_id);
        $this->assertNotNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(),Reservation::find(2)->checked_in_at);
    }
}
