<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Auth;

class HostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function host(){
      dd('xx');

    }
    
}
