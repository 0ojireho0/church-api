<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\FullyBook;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Church;

use App\Services\SendingEmail;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    //

    public function bookAvailable($id){
        $bookedSlots = DB::table('bookings')
                        ->where('church_id', $id)
                        ->where('status', 'Approved')
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
        $user_id = $request->user_id;
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;






        if($fullyBook !== "null"){
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
                'mop' => $mop,
                'status' => 'Pending',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Online',
                'mop_status' => 'Not Paid'
            ]);

            if($request->hasFile('files')){
                $files = $request->file('files');

                foreach($files as $file){
                    $filename = $file->getClientOriginalName();
                    Storage::disk('llibiapp_dms')->put(
                        'service/' . $result->reference_num . '/' . $filename,
                        file_get_contents($file)
                    );
                    FileUpload::create([
                        'book_id' => $result->id,
                        'filename' => $filename,
                        'filepath' => env('DO_LLIBI_CDN_ENDPOINT_DMS') . '/service/' . $result->reference_num . '/' . $filename
                    ]);
                }
            }

            $user = User::findOrFail($user_id);
            $church = Church::findOrFail($church_id);

            $this->sendRefNoClient($result->reference_num, $user->name, $result->service_type, $result->date, $result->time_slot, $church->church_name, $user->contact);
            $this->sendEmail($result->reference_num, $user->name, $result->service_type, $result->date, $result->time_slot, $church->church_name, $user->email);

        } catch (\Throwable $th) {
            throw $th;
        }




        return response()->json([
            'result' => $result,
            'ref_num' => $result->reference_num
        ], 200);
    }

    public function weddingBook(Request $request){

        // return $request;

        $church_id = $request->church_id;
        $form_data = json_decode($request->jsonData);
        $rehearsalFullyBooked = $request->rehearsalFullyBooked;
        $rehearsal_date = $request->rehearsal_date;
        $rehearsal_time = $request->rehearsal_time;
        $selectedPayment = $request->selectedPayment;
        $weddingFullyBooked = $request->weddingFullyBooked;
        $wedding_date = $request->wedding_date;
        $wedding_time = $request->wedding_time;
        $user_id = $request->user_id;


        if($rehearsalFullyBooked !== "null"){
            FullyBook::create([
                'date' => $rehearsalFullyBooked,
                'church_id' => $church_id
            ]);
        }

        if($weddingFullyBooked !== "null"){
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
                'mop' => $selectedPayment,
                'status' => 'Pending',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Online',
                'mop_status' => 'Not Paid'
            ]);


            // Rehearsal
            Booking::create([
                'user_id' => $user_id,
                'wedding_rehearsal_id' => $wedding->id,
                'church_id' => $church_id,
                'reference_num' => $wedding->reference_num,
                'date' => $rehearsal_date,
                'time_slot' => $rehearsal_time,
                'mop' => $selectedPayment,
                'status' => 'Pending',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Online',
                'mop_status' => 'Not Paid'
            ]);

            if($request->hasFile('files')){
                $files = $request->file('files');

                foreach($files as $file){
                    $filename = $file->getClientOriginalName();
                    Storage::disk('llibiapp_dms')->put(
                        'service/' . $wedding->reference_num . '/' . $filename,
                        file_get_contents($file)
                    );
                    FileUpload::create([
                        'book_id' => $wedding->id,
                        'filename' => $filename,
                        'filepath' => env('DO_LLIBI_CDN_ENDPOINT_DMS') . '/service/' . $wedding->reference_num . '/' . $filename
                    ]);
                }
            }

            $user = User::findOrFail($user_id);
            $church = Church::findOrFail($church_id);

            $this->sendRefNoClient($wedding->reference_num, $user->name, ucfirst($wedding->service_type), $wedding->date, $wedding->time_slot, $church->church_name, $user->contact);
            $this->sendEmail($wedding->reference_num, $user->name, ucfirst($wedding->service_type), $wedding->date, $wedding->time_slot, $church->church_name, $user->email);


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
        $user_id = $request->user_id;
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;

        if($fullyBook !== "null"){
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
                'mop' => $mop,
                'status' => 'Pending',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Online',
                'mop_status' => 'Not Paid'
            ]);

            if($request->hasFile('files')){
                $files = $request->file('files');

                foreach($files as $file){
                    $filename = $file->getClientOriginalName();
                    Storage::disk('llibiapp_dms')->put(
                        'service/' . $result->reference_num . '/' . $filename,
                        file_get_contents($file)
                    );
                    FileUpload::create([
                        'book_id' => $result->id,
                        'filename' => $filename,
                        'filepath' => env('DO_LLIBI_CDN_ENDPOINT_DMS') . '/service/' . $result->reference_num . '/' . $filename
                    ]);
                }
            }

            $user = User::findOrFail($user_id);
            $church = Church::findOrFail($church_id);

            $this->sendRefNoClient($result->reference_num, $user->name, ucfirst($result->service_type), $result->date, $result->time_slot, $church->church_name, $user->contact);
            $this->sendEmail($result->reference_num, $user->name, ucfirst($result->service_type), $result->date, $result->time_slot, $church->church_name, $user->email);


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
        $user_id = $request->user_id;
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;

        if($fullyBook !== "null"){
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
                'mop' => $mop,
                'status' => 'Pending',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Online',
                'mop_status' => 'Not Paid'
            ]);

            if($request->hasFile('files')){
                $files = $request->file('files');

                foreach($files as $file){
                    $filename = $file->getClientOriginalName();
                    Storage::disk('llibiapp_dms')->put(
                        'service/' . $result->reference_num . '/' . $filename,
                        file_get_contents($file)
                    );
                    FileUpload::create([
                        'book_id' => $result->id,
                        'filename' => $filename,
                        'filepath' => env('DO_LLIBI_CDN_ENDPOINT_DMS') . '/service/' . $result->reference_num . '/' . $filename
                    ]);
                }
            }

            $user = User::findOrFail($user_id);
            $church = Church::findOrFail($church_id);

            $this->sendRefNoClient($result->reference_num, $user->name, ucfirst($result->service_type), $result->date, $result->time_slot, $church->church_name, $user->contact);
            $this->sendEmail($result->reference_num, $user->name, ucfirst($result->service_type), $result->date, $result->time_slot, $church->church_name, $user->email);



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
        $user_id = $request->user_id;
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;


        if($fullyBook !== "null"){
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
                'mop' => $mop,
                'status' => 'Pending',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Online',
                'mop_status' => 'Not Paid'
            ]);

            if($request->hasFile('files')){
                $files = $request->file('files');

                foreach($files as $file){
                    $filename = $file->getClientOriginalName();
                    Storage::disk('llibiapp_dms')->put(
                        'service/' . $result->reference_num . '/' . $filename,
                        file_get_contents($file)
                    );
                    FileUpload::create([
                        'book_id' => $result->id,
                        'filename' => $filename,
                        'filepath' => env('DO_LLIBI_CDN_ENDPOINT_DMS') . '/service/' . $result->reference_num . '/' . $filename
                    ]);
                }
            }

            $user = User::findOrFail($user_id);
            $church = Church::findOrFail($church_id);

            $this->sendRefNoClient($result->reference_num, $user->name, ucfirst($result->service_type), $result->date, $result->time_slot, $church->church_name, $user->contact);
            $this->sendEmail($result->reference_num, $user->name, ucfirst($result->service_type), $result->date, $result->time_slot, $church->church_name, $user->email);



        } catch (\Throwable $th) {
            throw $th;
        }



        return response()->json([
            'result' => $result,
            'ref_num' => $result->reference_num,
        ], 200);
    }

    public function requestCertificate(Request $request){
        $form_data = json_decode($request->jsonData);
        $mop = $request->selectedPayment;
        (int) $user_id = $request->user_id;
        (int) $church_id = $request->id;

        try {

            $result = Booking::create([
                'user_id' => $user_id,
                'church_id' => $church_id,
                'service_type' => 'certificate',
                'reference_num' => strtotime('now'),
                'mop' => $mop,
                'status' => 'Pending',
                'form_data' => $form_data,
                'book_type' => 'certificate',
                'mop_status' => 'Not Paid'
            ]);

            if($request->hasFile('files')){
                $files = $request->file('files');

                foreach($files as $file){
                    $filename = $file->getClientOriginalName();
                    Storage::disk('llibiapp_dms')->put(
                        'service/' . $result->reference_num . '/' . $filename,
                        file_get_contents($file)
                    );
                    FileUpload::create([
                        'book_id' => $result->id,
                        'filename' => $filename,
                        'filepath' => env('DO_LLIBI_CDN_ENDPOINT_DMS') . '/service/' . $result->reference_num . '/' . $filename
                    ]);
                }
            }

            // $user = User::findOrFail($user_id);
            // $church = Church::findOrFail($church_id);

            // $this->sendRequestCertEmail($request->user['name'], $result->form_data['services'], $result->created_at, $church->church_name, $result->reference_num, $request->user['email']);

            // $this->sendRequestCertContact($request->user['name'], $result->form_data['services'], $result->created_at, $church->church_name, $result->reference_num, $request->user['contact']);



        } catch (\Throwable $th) {
            throw $th;
        }



        return response()->json([
            'result' => $result,
            'ref_num' => $result->reference_num,
        ], 200);

    }

    public function cancelBooking(Request $request){
        $booking_id = $request->booking_id;

        $findBookingId = Booking::with('user')->where('id', $booking_id)->firstOrFail();

        $findBookingId->status = "Cancelled";
        $findBookingId->set_status = "1";

        $findBookingId->update();

        $this->sendCancelledSms($findBookingId->user->contact, $findBookingId->reference_num);
        $this->sendCancelledEmail($findBookingId->reference_num, $findBookingId->user->email, $findBookingId->user->name);

        return $findBookingId;


    }

    public function sendCancelledSms($contact, $ref_no){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        $message = 'This is to confirm that your booking has been cancelled as per your request. Your reference number is ' . $ref_no;

        $parameters = array(
            'auth' => array('username' => env('SMS_USERNAME'), 'password' => env('SMS_PASSWORD')), //Your API KEY
            'provider' => "SIMNETWORK2",
            'number' => $contact,
            'content' => $message,
          );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Send the parameters set above with the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        // Receive response from server
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public function sendCancelledEmail($ref_no, $email, $username){


        $body = view('send-cancelled-email', [
            'username' => $username,
            'ref_no' => $ref_no
        ]);
        $emailer = new SendingEmail(email: $email, body: $body, subject: "Cancellation of Booking - Reference #$ref_no ");

        $emailer->send();

        return true;
    }

    public function sendRefNoClient($ref_no, $username, $service_type, $date, $timeslot, $churchname, $contact){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('F j, Y');
        $formattedTime = Carbon::createFromFormat('H:i:s', $timeslot)->format('g:iA');
        $firstUpperLtr = ucfirst(trim($service_type));

        $message = "Dear $username, your booking via ChurchConnect is confirmed.\n\nReference #: $ref_no for $firstUpperLtr on {$formattedDate} {$formattedTime} at $churchname\n\nKind Regards,\nChurchConnect Team";

        $parameters = array(
            'auth' => array('username' => env('SMS_USERNAME'), 'password' => env('SMS_PASSWORD')), //Your API KEY
            'provider' => "SIMNETWORK2",
            'number' => $contact,
            'content' => $message,
          );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Send the parameters set above with the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        // Receive response from server
        $output = curl_exec($ch);
        curl_close($ch);
    }

    public function sendEmail($ref_no, $username, $service_type, $date, $timeslot, $churchname, $email){

        $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('F j, Y');
        $formattedTime = Carbon::createFromFormat('H:i:s', $timeslot)->format('g:iA');
        $firstUpperLtr = ucfirst(trim($service_type));

        $body = view('send-refno-email', [
            'ref_no' => $ref_no,
            'username' => $username,
            'service_type' => $firstUpperLtr,
            'date' => $formattedDate,
            'timeslot' => $formattedTime,
            'churchname' => $churchname
        ]);
        $emailer = new SendingEmail(email: $email, body: $body, subject: "Your ChurchConnect Booking Confirmation - Reference #$ref_no ");

        $emailer->send();

        return true;
    }

    public function sendRequestCertEmail($fullname, $cert_type, $created_at, $churchname, $ref_no, $email){

        $formattedDate = Carbon::parse($created_at)
            ->setTimezone('Asia/Manila')
            ->format('F d, Y h:ia');

        $body = view('send-request-cert', [
            'fullname' => $fullname,
            'cert_type' => $cert_type,
            'created_at' => $formattedDate,
            'churchname' => $churchname,
            'ref_no' => $ref_no,
        ]);
        $emailer = new SendingEmail(email: $email, body: $body, subject: "Certificate Request Received – $churchname ");

        $emailer->send();

        return true;
    }

    public function sendRequestCertContact($fullname, $cert_type, $created_at, $churchname, $ref_no, $contact){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        $certList = implode(', ', $cert_type);
        $message ="Dear $fullname,\n\nYour request for a $certList at $churchname via ChurchConnect has been received and is under review.\n\nReference #: $ref_no\n\nYou will be notified once processing is complete.\n\nKind Regards,\nChurchConnect Team";


        $parameters = array(
            'auth' => array('username' => env('SMS_USERNAME'), 'password' => env('SMS_PASSWORD')), //Your API KEY
            'provider' => "SIMNETWORK2",
            'number' => $contact,
            'content' => $message,
          );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Send the parameters set above with the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        // Receive response from server
        $output = curl_exec($ch);
        curl_close($ch);
    }


    public function myBooks($user_id){

        $result = Booking::with('user', 'files')
                            ->where('user_id', $user_id)
                            ->whereNull('wedding_rehearsal_id')
                            ->get();

        return $result;

    }


    public function walkinMass(Request $request){

        $form_data = json_decode($request->jsonData);
        $dateBook = $request->date;
        $time_slot = $request->selectedTime;
        $mop = $request->selectedPayment;
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;
        $reserved_by = $request->reserved_by;

        if($fullyBook !== null){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        try {
            $result = Booking::create([
                'walkin_name' => $reserved_by,
                'church_id' => $church_id,
                'date' => $dateBook,
                'time_slot' => $time_slot,
                'reference_num' => strtotime('now'),
                'service_type' => 'mass',
                'mop' => $mop,
                'status' => 'Approved',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Walk-In',
                'mop_status' => 'Paid',
                'set_status' => 1
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'result' => $result
        ]);


    }

    public function walkinBaptism(Request $request){

        $form_data = json_decode($request->jsonData);
        $dateBook = $request->date;
        $time_slot = $request->selectedTime;
        $mop = $request->selectedPayment;
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;
        $reserved_by = $request->reserved_by;

        if($fullyBook !== null){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        try {
            $result = Booking::create([
                'walkin_name' => $reserved_by,
                'church_id' => $church_id,
                'date' => $dateBook,
                'time_slot' => $time_slot,
                'reference_num' => strtotime('now'),
                'service_type' => 'baptism',
                'mop' => $mop,
                'status' => 'Approved',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Walk-In',
                'mop_status' => 'Paid',
                'set_status' => 1
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'result' => $result
        ]);
    }

    public function walkinWedding(Request $request){

        $church_id = $request->church_id;
        $form_data = json_decode($request->jsonData);
        $rehearsalFullyBooked = $request->rehearsalFullyBooked;
        $rehearsal_date = $request->rehearsal_date;
        $rehearsal_time = $request->rehearsal_time;
        $selectedPayment = $request->selectedPayment;
        $weddingFullyBooked = $request->weddingFullyBooked;
        $wedding_date = $request->wedding_date;
        $wedding_time = $request->wedding_time;
        $reserved_by = $request->reserved_by;

        if($rehearsalFullyBooked !== null){
            FullyBook::create([
                'date' => $rehearsalFullyBooked,
                'church_id' => $church_id
            ]);
        }

        if($weddingFullyBooked !== null){
            FullyBook::create([
                'date' => $weddingFullyBooked,
                'church_id' => $church_id
            ]);
        }

        try {
            // Wedding
            $wedding = Booking::create([
                'walkin_name' => $reserved_by,
                'church_id' => $church_id,
                'date' => $wedding_date,
                'reference_num' => strtotime("now"),
                'time_slot' => $wedding_time,
                'service_type' => 'wedding',
                'mop' => $selectedPayment,
                'status' => 'Approved',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Walk-In',
                'mop_status' => 'Paid',
                'set_status' => 1
            ]);

            // Rehearsal
            Booking::create([
                'walkin_name' => $reserved_by,
                'wedding_rehearsal_id' => $wedding->id,
                'church_id' => $church_id,
                'reference_num' => $wedding->reference_num,
                'date' => $rehearsal_date,
                'time_slot' => $rehearsal_time,
                'mop' => $selectedPayment,
                'status' => 'Pending',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Walk-In',
                'mop_status' => 'Paid',
                'set_status' => 1
            ]);


        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'result' => $wedding
        ]);


    }

    public function walkinMemorial(Request $request){

        $form_data = json_decode($request->jsonData);
        $dateBook = $request->date;
        $time_slot = $request->selectedTime;
        $mop = $request->selectedPayment;
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;
        $reserved_by = $request->reserved_by;

        if($fullyBook !== null){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        try {
            $result = Booking::create([
                'walkin_name' => $reserved_by,
                'church_id' => $church_id,
                'date' => $dateBook,
                'time_slot' => $time_slot,
                'reference_num' => strtotime('now'),
                'service_type' => 'memorial',
                'mop' => $mop,
                'status' => 'Approved',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Walk-In',
                'mop_status' => 'Paid',
                'set_status' => 1
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'result' => $result
        ]);
    }

    public function walkinConfirmation(Request $request){

        $form_data = json_decode($request->jsonData);
        $dateBook = $request->date;
        $time_slot = $request->selectedTime;
        $mop = $request->selectedPayment;
        $fullyBook = $request->fullyBooked;
        $church_id = $request->church_id;
        $reserved_by = $request->reserved_by;

        if($fullyBook !== null){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        try {
            $result = Booking::create([
                'walkin_name' => $reserved_by,
                'church_id' => $church_id,
                'date' => $dateBook,
                'time_slot' => $time_slot,
                'reference_num' => strtotime('now'),
                'service_type' => 'confirmation',
                'mop' => $mop,
                'status' => 'Approved',
                'form_data' => $form_data,
                'book_type' => 'schedule',
                'reservation_type' => 'Walk-In',
                'mop_status' => 'Paid',
                'set_status' => 1
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'result' => $result
        ]);
    }

    public function selectEvent(Request $request){
        $church_id = $request->church_id;
        $event_name = $request->event_name;
        $fulldates = $request->fulldates;

        $allUsers = User::all();
        $church_name = Church::where('id', $church_id)->get('church_name')->firstOrFail();

        // Notify All users that the event is created
        foreach($allUsers as $user){

            $this->notifyAllUserEmail($user->email, $event_name, $fulldates, $church_name);
            $this->notifyAllUserSms($user->contact, $event_name, $fulldates, $church_name);

        }


        foreach($fulldates as $date){

            // Block all dates
            FullyBook::create([
                'date' => $date,
                'church_id' => $church_id,
                'is_event' => 1,
                'event_name' => $event_name
            ]);

            $findApprovedBooking = Booking::where('date', $date)
                    ->where('status', 'Approved')
                    ->where('church_id', $church_id)
                    ->whereNotNull('user_id')
                    ->get();

            if(count($findApprovedBooking) !== 0){

                foreach($findApprovedBooking as $user){
                    $findBookingUser = Booking::where('id', $user->id)->firstOrFail();

                    $findBookingUser['status'] = "Pending";
                    $findBookingUser['set_status'] = 2;
                    $findBookingUser->save();

                    // After update, email the user
                    $this->notifySpecificUserEmail($findBookingUser->user->email, $findBookingUser->reference_num, $church_name->church_name, $event_name, $findBookingUser->date);

                    $this->notifySpecificUserSms($findBookingUser->user->contact, $findBookingUser->reference_num, $church_name->church_name, $event_name, $findBookingUser->date);

                }

            }




        }












    }

    public function notifyAllUserEmail($email, $event_name, $fulldates, $church_name){

        $formatted = collect($fulldates)->map(function ($date) {
            return Carbon::parse($date)->format('F d, Y');
        });

        $lastIndexOfDate = count($formatted) - 1;

        $formattedDate = "From $formatted[0] to $formatted[$lastIndexOfDate]";

        $body = view('notify-event-users', [
            'event_name' => $event_name,
            'fulldates' => $formattedDate,
            'church_name' => $church_name->church_name
        ]);

        $emailer = new SendingEmail(email: $email, body: $body, subject: "New Event Added");

        $emailer->send();
    }

    public function notifyAllUserSms($contact, $event_name, $fulldates, $church_name){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        $formatted = collect($fulldates)->map(function ($date) {
            return Carbon::parse($date)->format('F d, Y');
        });

        $lastIndexOfDate = count($formatted) - 1;

        $message = "New event added by $church_name->church_name: \"$event_name\" scheduled from $formatted[0] to $formatted[$lastIndexOfDate]. Mark your calendar!";


        $parameters = array(
            'auth' => array('username' => env('SMS_USERNAME'), 'password' => env('SMS_PASSWORD')), //Your API KEY
            'provider' => "SIMNETWORK2",
            'number' => $contact,
            'content' => $message,
          );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Send the parameters set above with the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        // Receive response from server
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public function findEventAdded(Request $request){
        $church_id = $request->admin['church_id'];

        $getFullyBook = FullyBook::where('church_id', $church_id)
                                ->whereNotNull('is_event')
                                 ->get();

        return $getFullyBook ;

    }

    public function notifySpecificUserEmail($email, $reference_no, $church, $event_name, $date){

    // Format single date
    $formattedDate = Carbon::parse($date)->format('F d, Y');

    // Render Blade view to string
    $body = view('notify-specific-user', [
        'email' => $email,
        'reference_no' => $reference_no,
        'church' => $church,
        'event_name' => $event_name,
        'date' => $formattedDate,
    ]);

        $emailer = new SendingEmail(email: $email, body: $body, subject: "Overlapped Booking");

        $emailer->send();
    }

    public function notifySpecificUserSms($contact, $reference_no, $church, $event_name, $fulldates){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        $formatted = collect($fulldates)->map(function ($date) {
            return Carbon::parse($date)->format('F d, Y');
        });

        $lastIndexOfDate = count($formatted) - 1;

        $formattedDate = "From {$formatted[0]} to {$formatted[$lastIndexOfDate]}";

        $message = "Notice: Your booking (Ref: $reference_no) at $church has an event conflict.\n"
                . "Event: $event_name\n"
                . "Date: $formattedDate\n"
                . "Please contact the church for details.";

        $parameters = array(
            'auth' => array(
                'username' => env('SMS_USERNAME'),
                'password' => env('SMS_PASSWORD')
            ),
            'provider' => "SIMNETWORK2",
            'number' => $contact,
            'content' => $message,
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public function submitAnotherBook(Request $request){
        $id = $request->id;
        $fullyBook = $request->fullyBooked;
        $selectedDate = $request->selectedDate;
        $selectedTime = $request->selectedTime;
        $church_id = $request->church_id;


        if($fullyBook !== null){
            FullyBook::create([
                'date' => $fullyBook,
                'church_id' => $church_id
            ]);
        }

        $findId = Booking::where('id', $id)->firstOrFail();
        $findId['date'] = $selectedDate;
        $findId['time_slot'] = $selectedTime;
        $findId['status'] = "Approved";
        $findId['set_status'] = 1;
        $findId->save();

        $this->notifyUserEmailAnotherBook($findId->user['email'], $findId->reference_num, $findId->user['name'], $findId->church['church_name'], $findId->date, $findId->time_slot, $findId->service_type);
        $this->notifyUserSmsAnotherBook($findId->user['contact'], $findId->reference_num, $findId->user['name'], $findId->church['church_name'], $findId->date, $findId->time_slot, $findId->service_type);



    }

    public function notifyUserEmailAnotherBook($email, $reference_no, $username, $church_name, $date, $time_slot, $service_type){
        $formattedDate = Carbon::parse($date)->format('F d, Y');
        $formattedTime = Carbon::parse($time_slot)->format('h:i A');

        // Render Blade view to string
        $body = view('notify-user-another-book', [
            'email' => $email,
            'refno' => $reference_no,
            "username" => $username,
            'churchname' => $church_name,
            'date' => $formattedDate,
            'time_slot' => $formattedTime,
            'service_type' => $service_type
        ]);

        $emailer = new SendingEmail(email: $email, body: $body, subject: "Submit Another Booking - $reference_no");
        $emailer->send();
    }

    public function notifyUserSmsAnotherBook($contact, $reference_no, $username, $church_name, $date, $time_slot, $service_type){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('F j, Y');
        $formattedTime = Carbon::createFromFormat('H:i:s', $time_slot)->format('g:iA');
        $firstUpperLtr = ucfirst(trim($service_type));

        $message = "Dear $username, your booking has been successfully rebooked via ChurchConnect.\n\nReference #: $reference_no for $firstUpperLtr on {$formattedDate} at {$formattedTime} at $church_name\n\nKind regards,\nChurchConnect Team";


        $parameters = array(
            'auth' => array('username' => env('SMS_USERNAME'), 'password' => env('SMS_PASSWORD')), //Your API KEY
            'provider' => "SIMNETWORK2",
            'number' => $contact,
            'content' => $message,
          );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Send the parameters set above with the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        // Receive response from server
        $output = curl_exec($ch);
        curl_close($ch);
    }



}
