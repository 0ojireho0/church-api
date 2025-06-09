<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class SearchServiceController extends Controller
{
    //

    public function searchService($searchStatus){

        $result = [];

        $service_types = ['baptism', 'wedding', 'memorial', 'confirmation', 'mass'];

        switch((int)$searchStatus){
            case 0:
                $result = Booking::with('user', 'church')->whereIn('service_type', $service_types)->orderBy('id', 'desc')->get();
                break;
            case 1:
                $result = Booking::with('user', 'church')->where('service_type', 'baptism')->get();
                break;
            case 2:
                $result = Booking::with('user', 'church')->where('service_type', 'wedding')->get();
                break;
            case 3:
                $result = Booking::with('user', 'church')->where('service_type', 'memorial')->get();
                break;
            case 4:
                $result = Booking::with('user', 'church')->where('service_type', 'confirmation')->get();
                break;
            case 5:
                $result = Booking::with('user', 'church')->where('service_type', 'mass')->get();
                break;

            default:
                $result = [];
                break;
        }

        return $result;


    }
}
