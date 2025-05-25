<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\FullyBook;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    //

    public function bookAvailable(){
        $bookedSlots = DB::table('bookings')
                        ->get()
                        ->groupBy('date')
                        ->map(function ($group){
                            return $group->pluck('time_slot')->toArray();
                        })
                        ->toArray();

        $fullyBook = DB::table('fully_books')
                        ->pluck('date');

        return response()->json([
            'bookedSlots' => $bookedSlots,
            'fullyBook' => $fullyBook
        ]);
    }

    public function baptismBook(Request $request){

        $form_data = json_decode($request->jsonData);
        $dateBook = $request->date;
        $time_slot = $request->selectedTime;
        $mop = $request->selectedPayment;
        $user_id = $request->user['id'];
        $fullyBook = $request->fullyBooked;


        if($fullyBook){
            FullyBook::create([
                'date' => $fullyBook
            ]);
        }

        try {

            $result = Booking::create([
                'user_id' => $user_id,
                'date' => $dateBook,
                'time_slot' => $time_slot,
                'service_type' => 'baptism',
                'filename' => null,
                'filepath' => null,
                'mop' => $mop,
                'status' => 'Pending',
                'form_data' => $form_data
            ]);


        } catch (\Throwable $th) {
            throw $th;
        }



        return response()->json([
            'result' => $result
        ], 200);
    }
}
