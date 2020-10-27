<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return response('Thank you for logging in.');
    }
}
