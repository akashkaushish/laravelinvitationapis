<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    function show($name)
    { 
        if (view()->exists('Hello')){
            return view('Hello',['username'=>$name]);
        }else{
            return "This view is not available.";
        }
    }
}
