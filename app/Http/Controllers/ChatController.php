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


        // return $resposneEntities['event_type:event_type'][0]['body'];

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
}
