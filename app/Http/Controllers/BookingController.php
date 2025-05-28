<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\FullyBook;
use Illuminate\Support\Facades\DB;

use App\Services\SendingEmail;

class BookingController extends Controller
{
    //

    public function bookAvailable($id){
        $bookedSlots = DB::table('bookings')
                        ->where('church_id', $id)
                        ->get()
                        ->groupBy('date')
                        ->map(function ($group){
                            return $group->pluck('time_slot')->toArray();
                        })
                        ->toArray();

        $fullyBook = DB::table('fully_books')
                        ->where('church_id', $id)
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
        $church_id = $request->church_id;


        if($fullyBook){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        try {

            $result = Booking::create([
                'user_id' => $user_id,
                'church_id' => $church_id,
                'reference_num' => strtotime("now"),
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
            'result' => $result,
            'ref_num' => $result->reference_num
        ], 200);
    }

    public function weddingBook(Request $request){

        $church_id = $request->church_id;
        $form_data = json_decode($request->jsonData);
        $rehearsalFullyBooked = $request->rehearsalFullyBooked;
        $rehearsal_date = $request->rehearsal_date;
        $rehearsal_time = $request->rehearsal_time;
        $selectedPayment = $request->selectedPayment;
        $weddingFullyBooked = $request->weddingFullyBooked;
        $wedding_date = $request->wedding_date;
        $wedding_time = $request->wedding_time;
        $user_id = $request->user['id'];


        if($rehearsalFullyBooked){
            FullyBook::create([
                'date' => $rehearsalFullyBooked,
                'church_id' => $church_id
            ]);
        }

        if($weddingFullyBooked){
            FullyBook::create([
                'date' => $weddingFullyBooked,
                'church_id' => $church_id
            ]);
        }

        try {

            // Wedding
            $wedding = Booking::create([
                'user_id' => $user_id,
                'church_id' => $church_id,
                'date' => $wedding_date,
                'reference_num' => strtotime("now"),
                'time_slot' => $wedding_time,
                'service_type' => 'wedding',
                'filename' => null,
                'filepath' => null,
                'mop' => $selectedPayment,
                'status' => 'Pending',
                'form_data' => $form_data
            ]);


            // Rehearsal
            Booking::create([
                'user_id' => $user_id,
                'wedding_rehearsal_id' => $wedding->id,
                'church_id' => $church_id,
                'reference_num' => $wedding->reference_num,
                'date' => $rehearsal_date,
                'time_slot' => $rehearsal_time,
                'service_type' => 'wedding - rehearsal',
                'filename' => null,
                'filepath' => null,
                'mop' => $selectedPayment,
                'status' => 'Pending',
                'form_data' => $form_data
            ]);


        } catch (\Throwable $th) {
            throw $th;
        }




        return response()->json([
            'result' => $wedding,
            'ref_num' => $wedding->reference_num
        ], 200);
    }

    public function memorialBook(Request $request){
        $form_data = json_decode($request->jsonData);
        $dateBook = $request->date;
        $time_slot = $request->selectedTime;
        $mop = $request->selectedPayment;
        $user_id = $request->user['id'];
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;

        if($fullyBook){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        try {

            $result = Booking::create([
                'user_id' => $user_id,
                'church_id' => $church_id,
                'date' => $dateBook,
                'reference_num' => strtotime('now'),
                'time_slot' => $time_slot,
                'service_type' => 'memorial',
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
            'result' => $result,
            'ref_num' => $result->reference_num
        ], 200);


    }

    public function confirmationBook(Request $request){
        $form_data = json_decode($request->jsonData);
        $dateBook = $request->date;
        $time_slot = $request->selectedTime;
        $mop = $request->selectedPayment;
        $user_id = $request->user['id'];
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;

        if($fullyBook){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        try {

            $result = Booking::create([
                'user_id' => $user_id,
                'church_id' => $church_id,
                'date' => $dateBook,
                'reference_num' => strtotime('now'),
                'time_slot' => $time_slot,
                'service_type' => 'confirmation',
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
            'result' => $result,
            'ref_num' => $result->reference_num
        ], 200);
    }

    public function massBook(Request $request){
        $form_data = json_decode($request->jsonData);
        $dateBook = $request->date;
        $time_slot = $request->selectedTime;
        $mop = $request->selectedPayment;
        $user_id = $request->user['id'];
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;


        if($fullyBook){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        try {

            $result = Booking::create([
                'user_id' => $user_id,
                'church_id' => $church_id,
                'date' => $dateBook,
                'time_slot' => $time_slot,
                'reference_num' => strtotime('now'),
                'service_type' => 'mass',
                'filename' => null,
                'filepath' => null,
                'mop' => $mop,
                'status' => 'Pending',
                'form_data' => $form_data
            ]);

            if($result){
                $this->sendRefNoClient($result->reference_num);
                $this->sendEmail($result->reference_num);
            }


        } catch (\Throwable $th) {
            throw $th;
        }



        return response()->json([
            'result' => $result,
            'ref_num' => $result->reference_num
        ], 200);
    }

    public function sendRefNoClient($ref_no){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        $message = 'This is your reference number: ' . $ref_no ;

        $parameters = array(
            'auth' => array('username' => "root", 'password' => "LACSONSMS"), //Your API KEY
            'provider' => "SIMNETWORK2",
            'number' => "+639398971380",
            'content' => $message,
          );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Send the parameters set above with the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        // Receive response from server
        $output = curl_exec($ch);
        curl_close($ch);
    }

    public function sendEmail($ref_no){
        $email = 'jeremiahquintano16@gmail.com';
        $body =

        '<p style="font-weight:normal;">

                This is your reference no: ' . $ref_no . '

            </p>';
        $emailer = new SendingEmail(email: $email, body: $body, subject: 'CHURCHCONNECT - BOOK');

        $emailer->send();

        return true;
    }

}
