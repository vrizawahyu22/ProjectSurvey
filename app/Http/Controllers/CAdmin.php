<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\DB;

class CAdmin extends Controller
{
    public function login(Request $req)
    { 
        $coba = $req->Username;
        return $coba;
        // $password = md5($request->Password);
        // $hasil = DB::table('admin')->where([
        //     ['Username', '=', $request->Username],
        //     ['Password', '=', $password]
        // ])->get();

        // var_dump($hasil);
    }

    public function coba()
    {
        return 'Haloo';
    }
}
