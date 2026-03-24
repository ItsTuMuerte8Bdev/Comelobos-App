<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index() {
        $menuDeHoy = DB::table('menus')->where('status', 'available')->first();    
        $apodo = 'Yayo';

        return view('index', [
            'nombreUsuario' => $apodo,
            'menuDelDia' => $menuDeHoy
        ]);

    }
}
