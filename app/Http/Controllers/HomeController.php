<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function profil()
    {
        return view('profil');
    }

    public function fasilitas()
    {
        return view('fasilitas');
    }

    public function kontak()
    {
        return view('kontak');
    }
}