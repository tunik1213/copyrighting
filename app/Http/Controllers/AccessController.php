<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function forbiddenPage()
    {
        return view('staticPages.forbidden');
    }
}
