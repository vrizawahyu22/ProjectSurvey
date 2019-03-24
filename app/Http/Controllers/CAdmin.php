<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class CAdmin extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'Usename'	=> 'required|max:255|unique:posts|string',
            'Email'	    => 'required|max:255|unique:posts|string',
            'Password'	=> 'required|max:255|unique:posts|string',
    	]);
    }
}
