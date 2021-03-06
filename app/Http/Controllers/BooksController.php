<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    //

    public function store(){
        $data=request()->validate([
            'title'=>'required|string',
            'author'=>'required|string'
        ]);
        $book=Book::create($data);
        return redirect($book->path());

    }

    public function update(Book $book){
        $data=request()->validate([
            'title'=>'required|string',
            'author'=>'required|string'
        ]);
        $book->update($data);
        return redirect($book->path());

    }

    public function destroy(Book $book){
        $book->delete();
        return redirect('/books');

    }
}
