<?php

namespace App\Models;

use App\Models\Author;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function path(){
        return 'books/'.$this->id;
    }

    public function checkout($user){
        $this->reservations()->create([
            'book_id'=>$this->id,
            'user_id'=>$user->id,
            'checked_out_at'=>now()

        ]);

    }

    public function checkin($user){
        //reservations() to extend model relation and customize query,if we have error in query this will throw error like ->whereNotNull('checked_out_at',now())
        //reservations to get result of the relationship ,if we have error in query this will work like  ->whereNotNull('checked_out_at',now())
       $reservation = $this->reservations()->where('user_id',$user->id)
                            ->whereNotNull('checked_out_at')
                            ->whereNull('checked_in_at')
                            ->first();
        if(is_null($reservation)){
            throw new \Exception();
        }
        $reservation->update([
            'checked_in_at'=>now()
        ]);
    }

    public function  setAuthorIdAttribute($author){
        $this->attributes['author_id']=Author::firstOrCreate([
            'name'=>$author
        ])->id;
    }

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

}
