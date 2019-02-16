<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $product = \App\Product::first();
        $user = \TCG\Voyager\Models\User::first();
        $table = \App\Table::first();
        return view('home')->with('permission', [
            'product' => $product,
            'user' => $user,
            'table' => $table
        ]);
    }
}
