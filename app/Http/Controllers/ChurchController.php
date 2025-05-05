<?php

namespace App\Http\Controllers;

use App\Models\Church;
use Illuminate\Http\Request;

class ChurchController extends Controller
{
    //

    public function index(){
        return Church::all();
    }

    public function findChurch($id){
        return Church::where('id', $id)->firstOrFail();
    }
}
