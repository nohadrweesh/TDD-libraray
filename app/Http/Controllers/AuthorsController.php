<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    //

    public function store(){
        $data=request()->validate([
            'name'=>'required',
            'dob'=>'required|date'
        ]);
        Author::create($data);

    }

}
