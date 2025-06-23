<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;



class ChatController extends Controller
{
    //

    public function processMessage(Request $request){
        $message = $request->input('message', '');

        if (empty($message)) {
            return response()->json(['error' => 'Message cannot be empty'], 400);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.wit_ai.access_token'),
        ])->get(config('services.wit_ai.base_url') . '/message', [
            'v' => config('services.wit_ai.version'),
            'q' => $message,
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Failed to connect to Wit.ai',
                'details' => $response->json(),
            ], $response->status());
        }

        $responseEntities = $response['entities'] ?? [];
        $responseIntents = $response['intents'][0]['name'];

        if($response['intents'][0]['confidence'] >= 0.95){
            if($responseIntents === "schedule_wedding"){
                $schedule_wedding = $this->ScheduleWedding($responseEntities);
                return $schedule_wedding;
            }elseif($responseIntents === "schedule_baptism"){
                $schedule_baptism = $this->ScheduleBaptism($responseEntities);
                return $schedule_baptism;
            }elseif($responseIntents == "schedule_memorial"){
                $schedule_memorial = $this->ScheduleMemorial($responseEntities);
                return $schedule_memorial;
            }elseif($responseIntents == "schedule_confirmation"){
                $schedule_confirmation = $this->ScheduleConfirmation($responseEntities);
                return $schedule_confirmation;
            }elseif($responseIntents == "check_churchconnect_availability"){
                $check_churchconnect_availability = $this->CheckChurchConnectAvailability($responseEntities);
                return $check_churchconnect_availability;
            }elseif($responseIntents == "apply_for_someone_else"){
                $apply_for_someone_else = $this->ApplyForSomeoneElse($responseEntities);
                return $apply_for_someone_else;
            }elseif($responseIntents == "multi_service_application"){
                $multi_service_application = $this->multi_service_application($responseEntities);
                return $multi_service_application;
            }elseif($responseIntents == "check_request_notification_status"){
                $check_request_notification_status = $this->check_request_notification_status($responseEntities);
                return $check_request_notification_status;
            }elseif($responseIntents == "check_service_availability_time"){
                $check_service_availability_time = $this->check_service_availability_time($responseEntities);
                return $check_service_availability_time;
            }
        }else{
            return "Di ko alam";
        }



    }

    public function ScheduleWedding($entities){

        $randomResponseWedding = [
            'You can schedule it through ChurchConnect by filling out the wedding application form.',
            'After selecting preferred church, click “Book a Service” button and select the "Wedding" section. Follow the steps provided.',
            'To book a wedding, select your church and preferred date, then submit the needed documents.',
            'The wedding request form is available online. Once submitted, the parish office will contact you.',
            'Make sure to apply at least 3 months in advance and prepare the required documents.'
        ];

        if($entities['event_type:event_type'][0]['body'] === "wedding" || $entities['location:location'][0]['body'] === "church"){
            $randomKey = array_rand($randomResponseWedding);
            return $randomResponseWedding[$randomKey];
        }
    }

    public function ScheduleBaptism($entities){
        $randomResponseBaptism = [
            'You can book a baptism by filling out the baptism application form on ChurchConnect.',
            'Please visit your selected parish’s page and click on “Book a Service,” then choose “Baptism” to start the process.',
            'To schedule a baptism, select the church, preferred date, and submit the required documents.',
            'Baptism requests are made online, once your application is submitted, the parish office will reach out to you.',
            'Make sure to apply for the baptism at least 1 month in advance and prepare the child’s birth certificate and other needed documents.'
        ];

        if($entities['event_type:event_type'][0]['body'] === "baptism"){
            $randomKey = array_rand($randomResponseBaptism);
            return $randomResponseBaptism[$randomKey];
        }
    }

    public function ScheduleMemorial($entities){
        $randomResponseMemorial = [
            'To book a memorial, please fill out the memorial service form on ChurchConnect or visit your parish office directly.',
            'Start by selecting your preferred church and date, then choose “Memorial” under the Book a Service section.',
            'You can arrange a memorial by submitting the required documents and completing the online application form.',
            'Memorial service requests can be submitted through our website. Once received, the parish will get in touch for confirmation.',
            'Please ensure to schedule the memorial at least 2 weeks in advance to allow for proper preparation and coordination.'
        ];

        if($entities['event_type:event_type'][0]['body'] === "memorial"){
            $randomKey = array_rand($randomResponseMemorial);
            return $randomResponseMemorial[$randomKey];
        }
    }

    public function ScheduleConfirmation($entities){
        $randomResponseConfirmation = [
            'You can schedule a confirmation by filling out the confirmation form on ChurchConnect and submitting the required documents.',
            'To book a confirmation, select your parish, choose the “Book a Service” option, and then click on “Confirmation.”',
            'Confirmation requests are made online. Once the form is submitted, the parish office will follow up with next steps.',
            'Please ensure all necessary requirements are ready before scheduling the confirmation date, including baptism records and sponsor details.',
            'Confirmation bookings must be made in advance through our system or directly through the parish office, depending on availability.'
        ];

        if($entities['event_type:event_type'][0]['body'] === "confirmation"){
            $randomKey = array_rand($randomResponseConfirmation);
            return $randomResponseConfirmation[$randomKey];
        }
    }

    public function CheckChurchConnectAvailability($entities){
        $randomResponseAvailability = [
            'ChurchConnect is currently available in only selected Archdiocese parishes in Manila.',
            'Availability may vary, as some parishes have yet to implement the platform.',
            'Only participating parishes can be accessed through ChurchConnect. You may still explore general features in the meantime',
            'ChurchConnect is being used by selected parishes at the moment. Please let me know your parish so I can check.',
            'Access to ChurchConnect depends on whether a parish has officially joined the platform. Let me know your parish so I can assist you further.'
        ];

        if($entities['parish_scope:parish_scope'][0]['confidence'] >= 0.95 && $entities['platform:platform'][0]['body'] == "ChurchConnect"){
            $randomKey = array_rand($randomResponseAvailability);
            return $randomResponseAvailability[$randomKey];
        }
    }

    public function ApplyForSomeoneElse($entities){
        $randomResponse = [
            'Yes, if you’re a family member or legal guardian',
            'Fill out the form using their personal details, not yours.',
            'Indicate your name as the representative in the remarks.',
            'Upload their required documents.',
            'You may be asked for a written authorization for certain requests.'
        ];

        if($entities['action_type:action_type'][0]['confidence'] >= 0.95){
            $randomKey = array_rand($randomResponse);
            return $randomResponse[$randomKey];
        }
    }

    public function multi_service_application($entities){
        $randomResponse = [
            'Yes, you can apply for multiple services (e.g., baptism + certificate request).',
            'Each service must be filled out separately through its dedicated form.',
            'Ensure all required documents are uploaded per service.',
            'You will receive a separate confirmation for each application.',
            'Applications are processed independently, based on the parish’s schedule.'
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];

    }

    public function check_request_notification_status($entities){
        $randomResponse = [
            'Yes, the system will update your application status.',
            'You may also receive a message from the parish.',
            'Rejection reasons will be noted.',
            'You can revise and re-submit.',
            'Contact the church office for clarification.'
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];

    }

    public function check_service_availability_time($entities){
        $randomResponse = [
            'Yes, you can access and apply anytime.',
            'Responses may only be sent during church office hours.',
            'Weekends and holidays may delay processing.',
            'Chatbot is available 24/7 for general inquiries.',
            'Application confirmation depends on parish approval.'
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];
    }
}
