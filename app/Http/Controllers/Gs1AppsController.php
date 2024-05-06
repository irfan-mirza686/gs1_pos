<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Gs1AppsController extends Controller
{
    public function index()
    {
        $pageTitle = "GS1 Apps";
        return view('apps',compact('pageTitle'));
    }
}
