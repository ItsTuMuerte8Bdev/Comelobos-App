<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index() {
        $estudiante = DB::table('students')->first();
        $nombreUsuario = $estudiante->first_name ?? 'Usuario';

        return view('index', [
            'nombreUsuario' => $nombreUsuario
        ]);

    }
}
