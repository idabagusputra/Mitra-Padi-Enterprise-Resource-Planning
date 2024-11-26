<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return redirect()->route('giling.index'); // Redirect to the giling index route
    }
}
