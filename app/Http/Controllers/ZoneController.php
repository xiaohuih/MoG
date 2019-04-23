<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Facades\Game;

class ZoneController extends Controller
{
    public function index()
    {
        return ['results' => [1, 2, 3, 100]];
    }

    public function select()
    {
        Game::setZone(Input::get('_zone', '0'));
    }
}
