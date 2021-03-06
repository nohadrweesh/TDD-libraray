<?php

namespace App\Models;

use App\Models\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function path(){
        return 'books/'.$this->id;
    }

    public function  setAuthorIdAttribute($author){
        $this->attributes['author_id']=Author::firstOrCreate([
            'name'=>$author
        ])->id;
    }

}
