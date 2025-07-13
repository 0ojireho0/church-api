<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Models\FullyBook;
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

    public function findEvents($searchStatus){

        $result = FullyBook::where('church_id', $searchStatus)
                            ->where('is_event', 1)
                            ->get();

        return $result;



    }
}
