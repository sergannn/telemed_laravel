<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PubHostController extends Controller
{
    public function index()
    {
        return view('pubhost.pub-host');
    }
}