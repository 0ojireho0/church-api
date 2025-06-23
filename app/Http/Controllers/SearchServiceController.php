<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Admin;
use Carbon\Carbon;
use App\Services\SendingEmail;


class SearchServiceController extends Controller
{
    //

    public function searchService($searchStatus, $church_id){

        $result = [];

        $service_types = ['baptism', 'wedding', 'memorial', 'confirmation', 'mass', 'certificate'];


        if((int)$church_id === 0){
            switch((int)$searchStatus){
                case 0:
                    $result = Booking::with('user', 'church')
                                        ->whereIn('service_type', $service_types)
                                        ->orderBy('id', 'desc')
                                        ->get();
                    break;
                case 1:
                    $result = Booking::with('user', 'church')
                                        ->where('service_type', 'baptism')
                                        ->get();
                    break;
                case 2:
                    $result = Booking::with('user', 'church')
                                        ->where('service_type', 'wedding')
                                        ->get();
                    break;
                case 3:
                    $result = Booking::with('user', 'church')
                                        ->where('service_type', 'memorial')
                                        ->get();
                    break;
                case 4:
                    $result = Booking::with('user', 'church')
                                        ->where('service_type', 'confirmation')
                                        ->get();
                    break;
                case 5:
                    $result = Booking::with('user', 'church')
                                        ->where('service_type', 'mass')
                                        ->get();
                    break;
                case 6:
                    $result = Booking::with('user', 'church')
                                        ->where('service_type', 'certificate')
                                        ->get();
                    break;

                default:
                    $result = [];
                    break;
            }
            return $result;
        }

        switch((int)$searchStatus){
            case 0:
                $result = Booking::with('user', 'church')
                                    ->where('church_id', $church_id)
                                    ->whereIn('service_type', $service_types)
                                    ->orderBy('id', 'desc')
                                    ->get();
                break;
            case 1:
                $result = Booking::with('user', 'church')
                                    ->where('church_id', $church_id)
                                    ->where('service_type', 'baptism')
                                    ->get();
                break;
            case 2:
                $result = Booking::with('user', 'church')
                                    ->where('church_id', $church_id)
                                    ->where('service_type', 'wedding')
                                    ->get();
                break;
            case 3:
                $result = Booking::with('user', 'church')
                                    ->where('church_id', $church_id)
                                    ->where('service_type', 'memorial')
                                    ->get();
                break;
            case 4:
                $result = Booking::with('user', 'church')
                                    ->where('church_id', $church_id)
                                    ->where('service_type', 'confirmation')
                                    ->get();
                break;
            case 5:
                $result = Booking::with('user', 'church')
                                    ->where('church_id', $church_id)
                                    ->where('service_type', 'mass')
                                    ->get();
                break;
            case 6:
                $result = Booking::with('user', 'church')
                                    ->where('church_id', $church_id)
                                    ->where('service_type', 'certificate')
                                    ->get();
                break;

            default:
                $result = [];
                break;
        }

        return $result;


    }

    public function showAllBook($church_id){
        $result = [];

        $service_types = ['baptism', 'wedding', 'memorial', 'confirmation', 'mass', 'certificate'];


        if((int)$church_id === 0){
            $result = Booking::with('user', 'church')
                                ->whereIn('service_type', $service_types)
                                ->orderBy('id', 'desc')
                                ->get();
            return $result;
        }

        $result = Booking::with('user', 'church')
                            ->where('church_id', $church_id)
                            ->whereIn('service_type', $service_types)
                            ->orderBy('id', 'desc')
                            ->get();
        return $result;
    }

    public function changeStatus(Request $request){
        $id = $request->id;
        $status = $request->selectedStatus;
        $remarks = $request->remarks;


        $result = Booking::with('user', 'church')->findOrFail($id);



        $mop_status = "";

        if($status == "Approved" && $result->book_type == "schedule"){
            $mop_status = "Paid";
            $this->sendApprovedBookContact($result->user['name'], $result->service_type, $result->reference_num, $result->date, $result->time_slot, $result->church['church_name'], $result->user['contact']);

            $this->sendApprovedBookEmail($result->user['name'], $result->service_type, $result->reference_num, $result->date, $result->time_slot, $result->church['church_name'], $result->user['email']);

        }

        if($status == "Rejected" && $result->book_type == "schedule"){
            $mop_status = "Not Paid";
            $this->sendRejectedBookContact($result->user['name'], $result->service_type, $result->reference_num, $result->date, $result->time_slot, $result->church['church_name'], $result->user['contact'], $remarks);

            $this->sendRejectedBookEmail($result->user['name'], $result->service_type, $result->reference_num, $result->date, $result->time_slot, $result->church['church_name'], $result->user['email'], $remarks);
        }

        if($status == "Approved" && $result->book_type == 'certificate'){
           $mop_status = "Paid";
        //    return $result->form_data['services'];

            $this->sendApprovedCertificateEmail($result->user['name'], $result->church['church_name'], $result->form_data['services'], $result->reference_num, $result->user['email']);

            $this->sendApprovedCertificateContact($result->user['name'], $result->church['church_name'], $result->form_data['services'], $result->reference_num, $result->user['contact']);

        }

        if($status == "Rejected" && $result->book_type == 'certificate'){
           $mop_status = "Not Paid";

           $this->sendRejectedCertificateEmail($result->user['name'], $result->church['church_name'], $result->form_data['services'], $result->reference_num, $result->user['email'], $remarks);

            $this->sendRejectedCertificateContact($result->user['name'], $result->church['church_name'], $result->form_data['services'], $result->reference_num, $result->user['contact'], $remarks);
        }



        $result->update([
            'status' => $status,
            'mop_status' => $mop_status,
            'set_status' => 1,
            'remarks' => $remarks
        ]);

        return response()->json([
            'result' => $result
        ], 200);
    }

    public function sendApprovedBookContact($fullname, $service_type, $ref_no, $date, $timeslot, $churchname, $contact){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('F j, Y');
        $formattedTime = Carbon::createFromFormat('H:i:s', $timeslot)->format('g:iA');
        $firstUpperLtr = ucfirst(trim($service_type));

        $message = "Dear $fullname, your booking via ChurchConnect has been approved.\n\nReference #: $ref_no for $firstUpperLtr on {$formattedDate} {$formattedTime} at $churchname\n\nKind Regards,\nChurchConnect Team";

        $parameters = array(
            'auth' => array('username' => "root", 'password' => "LACSONSMS"), //Your API KEY
            'provider' => "SIMNETWORK",
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

    public function sendApprovedBookEmail($fullname, $service_type, $ref_no, $date, $timeslot, $churchname, $email){

        $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('F j, Y');
        $formattedTime = Carbon::createFromFormat('H:i:s', $timeslot)->format('g:iA');
        $firstUpperLtr = ucfirst(trim($service_type));

        $body = view('send-approved-book', [
            'ref_no' => $ref_no,
            'username' => $fullname,
            'service_type' => $firstUpperLtr,
            'date' => $formattedDate,
            'timeslot' => $formattedTime,
            'churchname' => $churchname
        ]);
        $emailer = new SendingEmail(email: $email, body: $body, subject: "ChurchConnect Booking Approved - Reference #$ref_no ");

        $emailer->send();

        return true;
    }

    public function sendRejectedBookContact($fullname, $service_type, $ref_no, $date, $timeslot, $churchname, $contact, $remarks){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');

        // $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('F j, Y');
        // $formattedTime = Carbon::createFromFormat('H:i:s', $timeslot)->format('g:iA');
        $firstUpperLtr = ucfirst(trim($service_type));

        $message = "Dear $fullname\n\nWe regret to inform you that your request for a $firstUpperLtr submitted through ChurchConnect has been rejected at $churchname due to the following reason(s):\n\n $remarks \n\nYou may contact the parish office for clarification\n\nReference #:$ref_no\n\nKind Regards,\nChurchConnect Team";

        $parameters = array(
            'auth' => array('username' => "root", 'password' => "LACSONSMS"), //Your API KEY
            'provider' => "SIMNETWORK",
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

    public function sendRejectedBookEmail($fullname, $service_type, $ref_no, $date, $timeslot, $churchname, $email, $remarks){

        $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('F j, Y');
        $formattedTime = Carbon::createFromFormat('H:i:s', $timeslot)->format('g:iA');
        $firstUpperLtr = ucfirst(trim($service_type));

        $body = view('send-rejected-book', [
            'ref_no' => $ref_no,
            'username' => $fullname,
            'service_type' => $firstUpperLtr,
            'date' => $formattedDate,
            'timeslot' => $formattedTime,
            'churchname' => $churchname,
            'remarks' => $remarks
        ]);
        $emailer = new SendingEmail(email: $email, body: $body, subject: "ChurchConnect Booking Rejected - Reference #$ref_no ");

        $emailer->send();

        return true;
    }

    public function sendApprovedCertificateEmail($fullname, $churchname, $cert_type, $ref_no, $email){

        $body = view('send-approved-certificate', [
            'cert_type' => $cert_type,
            'fullname' => $fullname,
            'churchname' => $churchname,
            'reference_no' => $ref_no
        ]);
        $emailer = new SendingEmail(email: $email, body: $body, subject: "Certificate Request Approved - $churchname ");

        $emailer->send();

        return true;
    }

    public function sendApprovedCertificateContact($fullname, $churchname, $cert_type, $ref_no, $contact){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');
        $certList = implode(', ', $cert_type);
        $message = "Dear $fullname, your certificate request has been approved.\n\nType: $certList\nChurch: $churchname\nRef No.: $ref_no\nClaim at: Parish Office\nOffice Hours: [e.g., Mon - Fri, 9:00 AM - 4:00 PM]\n\nKind Regards,\nChurchConnect Team";

        $parameters = array(
            'auth' => array('username' => "root", 'password' => "LACSONSMS"), //Your API KEY
            'provider' => "SIMNETWORK",
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

    public function sendRejectedCertificateEmail($fullname, $churchname, $cert_type, $ref_no, $email, $remarks){
        $body = view('send-rejected-certificate', [
            'cert_type' => $cert_type,
            'fullname' => $fullname,
            'churchname' => $churchname,
            'reference_no' => $ref_no,
            'remarks' => $remarks
        ]);
        $emailer = new SendingEmail(email: $email, body: $body, subject: "Certificate Request Rejected - $churchname ");

        $emailer->send();

        return true;
    }

    public function sendRejectedCertificateContact($fullname, $churchname, $cert_type, $ref_no, $contact, $remarks){
        $ch = curl_init('http://192.159.66.221/goip/sendsms/');
        $certList = implode(', ', $cert_type);
        $message = "Dear $fullname, your certificate request has been rejected.\n\nType: $certList\nChurch: $churchname\nRef No.: $ref_no\nReason: $remarks\n\nPlease contact the parish office\n\nKind Regards,\nChurchConnect Team";

        $parameters = array(
            'auth' => array('username' => "root", 'password' => "LACSONSMS"), //Your API KEY
            'provider' => "SIMNETWORK",
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

    public function allAdmin(){
        return Admin::with('church')->where('admin_type', 'Admin')->get();
    }

    public function deleteAdmin(Request $request){
        $request->validate([
            'id' => 'required|exists:admins,id',
        ]);

        $admin = Admin::find($request->id);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found.'], 404);
        }

        $admin->delete();

        return response()->json(['message' => 'Admin deleted successfully.']);
    }

    public function updateAdmin(Request $request){
        // Validate incoming data
        $validated = $request->validate([
            'id' => 'required|exists:admins,id',
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $request->id,
        ]);

        // Find and update the admin
        $admin = Admin::findOrFail($validated['id']);
        $admin->fullname = $validated['fullname'];
        $admin->email = $validated['email'];
        $admin->save();

        return response()->json([
            'message' => 'Admin updated successfully.',
            'admin' => $admin
        ], 200);
    }

}
