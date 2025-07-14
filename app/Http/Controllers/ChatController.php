<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use App\Models\Church;
use Illuminate\Support\Facades\Log;

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




        if (
            empty($response['intents']) ||
            empty($response['entities'])
        ) {
            return "Kindly rephrase your question";
        }

        $responseEntities = $response['entities'] ?? [];
        $responseIntents = $response['intents'][0]['name'];

        if($response['intents'][0]['confidence'] >= 0.90){
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
            }elseif($responseIntents == "get_parish_info"){
                $get_parish_info = $this->get_parish_info($responseEntities);
                return $get_parish_info;
            }elseif($responseIntents == "check_walkin_policy"){
                $check_walkin_policy = $this->check_walkin_policy($responseEntities);
                return $check_walkin_policy;
            }elseif($responseIntents == "request_private_mass"){
                $request_private_mass = $this->request_private_mass($responseEntities);
                return $request_private_mass;
            }elseif($responseIntents == "get_parish_location"){
                $get_parish_location = $this->get_parish_location($responseEntities);
                return $get_parish_location;
            }elseif($responseIntents == "check_non_resident_baptism_eligibility"){
                $check_non_resident_baptism_eligibility = $this->check_non_resident_baptism_eligibility($responseEntities);
                return $check_non_resident_baptism_eligibility;
            }elseif($responseIntents == "list_churchconnect_services"){
                $list_churchconnect_services = $this->list_churchconnect_services($responseEntities);
                return $list_churchconnect_services;
            }elseif($responseIntents == "greeting"){
                $greeting = $this->greeting($responseEntities);
                return $greeting;
            }elseif($responseIntents == "inquire_service_fee"){
                $inquire_service_fee = $this->inquire_service_fee();
                return $inquire_service_fee;
            }elseif($responseIntents == "inquire_mobile_access"){
                $inquire_mobile_access = $this->inquire_mobile_access();
                return $inquire_mobile_access;
            }
        }else{

            return "Kindly rephrase your question";


        }



    }

    public function greeting($entities){
        $greetingResponses = [
            "Hello! How can I assist you today? 😊",
            "Hi there! Need help with a church service?",
            "Welcome to ChurchConnect! ✟",
            "Hey! Feel free to ask me anything about parish services.",
            "Good to see you! How can I help?"
        ];


        $randomKey = array_rand($greetingResponses);
        return $greetingResponses[$randomKey];

    }

    public function ScheduleWedding($entities){

        $randomResponseWedding = [
            'You can schedule it through ChurchConnect by filling out the wedding application form.',
            'After selecting preferred church, click “Book a Service” button and select the "Wedding" section. Follow the steps provided.',
            'To book a wedding, select your church and preferred date, then submit the needed documents.',
            'The wedding request form is available online. Once submitted, the parish office will contact you.',
            'Make sure to apply at least 3 months in advance and prepare the required documents.'
        ];



        if (
            ($entities['event_type:event_type'][0]['body'] ?? null) === "wedding" ||
            ($entities['location:location'][0]['body'] ?? null) === "church"
        ) {
            $randomKey = array_rand($randomResponseWedding);
            return $randomResponseWedding[$randomKey];

        } elseif (
            isset($entities['ask_requirement:ask_requirement']) &&
            isset($entities['wedding_type:wedding_type'])
        ) {
            $randomResponse = [
                "Wedding requires updated baptismal and confirmation certificates (marked ‘For Marriage’), birth certificates, CENOMAR, a marriage license, and attendance in Pre-Cana seminars.",
                "For a church wedding, you'll need both parties’ baptismal and confirmation certificates, a valid CENOMAR from PSA, a civil marriage license, and to attend a canonical interview.",
                "To get married at any Churches, couples must submit church-issued certificates, government-issued documents like the CENOMAR and marriage license, and attend a Pre-Cana seminar.",
                "You’ll need documents such as your baptismal and confirmation certificates, birth certificates, a marriage license, and proof of attending a marriage preparation seminar.",
                "The required documents include baptismal and confirmation certificates (recently issued), CENOMAR, marriage license, ID photos, and seminar certificates. A canonical interview is also part of the process."
            ];
            $randomKey = array_rand($randomResponse);
            return $randomResponse[$randomKey];
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
        $randomResponseRequirements = [
            "For baptism at any churches, you’ll need the child’s birth certificate, valid IDs of parents and godparents, and attendance in a pre-baptism seminar.",
            "The basic requirements include the child’s original and photocopy of the birth certificate, the parents’ valid IDs, and baptismal/confirmation certificates of the godparents.",
            "Any Churches requires both parents and godparents to attend a pre-baptism seminar. You'll also need to submit the child's birth certificate and IDs of all involved.",
            "You'll need to prepare the child’s birth certificate, sponsor certificates, and identification cards. Seminar attendance for parents and godparents is also part of the process.",
            "To proceed with baptism, kindly submit the child’s PSA birth certificate, the valid IDs of parents and sponsors, and ensure that all required seminars have been completed."
        ];

        // Ask requirements
        if($entities['event_type:event_type'][0]['body'] === "baptism" && isset($entities['ask_requirement:ask_requirement'])){
            $randomKey = array_rand($randomResponseRequirements);
            return $randomResponseRequirements[$randomKey];
        }
        // Ask how to book in churchconnect
        elseif($entities['event_type:event_type'][0]['body'] === "baptism"){
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
            'ChurchConnect is currently available only in selected Archdiocese of Manila parishes.',
            'Availability may vary, as not all parishes within the Archdiocese have adopted the platform yet.',
            'Only participating parishes within the Archdiocese of Manila can be accessed through ChurchConnect. You may still explore general features in the meantime.',
            'ChurchConnect is being used by select parishes under the Archdiocese of Manila. Let me know your parish so I can check for you.',
            'Access to ChurchConnect depends on whether a parish under the Archdiocese of Manila has officially joined the platform. Let me know your parish so I can assist further.'
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
            "Yes, you'll be notified if your request is not approved.",
            "You may also receive a message from the parish explaining the reason.",
            "If your request is declined, the reason will be provided in your application status.",
            "You can revise and resubmit your request if needed.",
            "For more details, feel free to contact the parish office directly."
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

    public function get_parish_info($entities){

        $churchName = $entities['parish_name:parish_name'][0]['value'] ?? null;
        $churchId = 0;


        if($churchName && in_array(strtolower($churchName), ['our lady of fatima parish', 'our lady of fatima church'])){
            $churchId = 7;
        }elseif($churchName && in_array(strtolower($churchName), ['san jose de trozo church', 'san jose de trozo parish'])){
            $churchId = 3;
        }elseif($churchName && in_array(strtolower($churchName), ['tondo church', 'tondo parish'])){
            $churchId = 1;
        }elseif($churchName && in_array(strtolower($churchName), ['quiapo church', 'quiapo parish'])){
            $churchId = 5;
        }elseif($churchName && in_array(strtolower($churchName), ['sta. cruz parish', 'sta. cruz church'])){
            $churchId = 2;
        }elseif($churchName && in_array(strtolower($churchName), ['our lady of the abandoned of'])){
            $churchId = 8;
        }

        if ($churchId === 0) {
            return "Sorry, I couldn't find the church you're referring to.";
        }

       $church = Church::findOrFail($churchId);

        $randomResponse = [
            "Office Hours: $church->office_hours. Email: $church->email.",
            "The $church->church_name is open from $church->office_hours. For inquiries, you may email at $church->email. ",
            "You may visit the parish office from $church->office_hours. Feel free to reach out via email at $church->email.",
            "The parish is open to assist you between $church->office_hours. You may email $church->email for questions or concerns.",
            "Office hours are from $church->office_hours. You can also contact the parish through $church->email."
        ];


        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];
    }

    public function check_walkin_policy($entities){
        $randomResponse = [
            'Yes, walk-ins are welcome at the any churches. But for quicker transactions, you can also inquire through ChurchConnect.',
            'You don’t need an appointment to inquire about sacraments at any churches. However, using ChurchConnect lets you check requirements and submit forms in advance.',
            "Feel free to walk in during parish office hours. But to avoid long lines or if you're submitting documents, we recommend using ChurchConnect for faster service.",
            "Any Churches accepts walk-in inquiries, but most applications — like baptism or confirmation — can also be started online through ChurchConnect.",
            'Yes, you can walk in without an appointment, especially for questions. But if you want to process or reserve anything, we suggest using ChurchConnect to save time.'
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];
    }

    public function request_private_mass($entities){

        $randomResponse = [
            'Yes, you may request a private mass at any Church for special occasions. You can coordinate this through ChurchConnect or by visiting the parish office.',
            'Private masses are allowed depending on availability. You can easily make your request through ChurchConnect to check the schedule and submit your details.',
            "Any Church accommodates private mass requests for intentions like memorials or thanksgiving. You can inquire and apply directly via ChurchConnect.",
            "Yes, you can request a private mass. It's best to process your request early through ChurchConnect or confirm with the parish office for available dates.",
            'Private masses can be arranged, but they are subject to priest availability. You may submit a request using ChurchConnect for quicker coordination.'
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];
    }

    public function get_parish_location($entities){

        $churchName = $entities['parish_name:parish_name'][0]['body'] ?? null;
        $churchId = 0;

        // Normalize church name comparison (case-insensitive)
        if ($churchName && in_array(strtolower($churchName), [
            'san jose de trozo church',
            'san jose de trozo parish'
        ])) {
            $churchId = 3;
        } elseif ($churchName && in_array(strtolower($churchName), [
                'tondo church',
                'tondo parish',
            ])) {
                $churchId = 1; // Replace with the actual church ID for Tondo Church
        } elseif($churchName && in_array(strtolower($churchName), [
                'quiapo church',
                'quiapo parish',
            ])) {
                $churchId = 5;
        } elseif($churchName && in_array(strtolower($churchName), [
            'sta. cruz parish',
            'sta. cruz church'
        ])) {
            $churchId = 2;
        } elseif($churchName && in_array(strtolower($churchName), [
            'our lady of fatima parish',
            'our lady of fatima church'
        ])) {
            $churchId = 7;
        }

        if ($churchId === 0) {
            return "Sorry, I couldn't find the church you're referring to.";
        }

        $church = Church::findOrFail($churchId);

        $randomResponses = [
            "{$church->church_name} is located at {$church->address}.",
            "You can find {$church->church_name} at: {$church->address}.",
            "The church is situated on {$church->address}.",
            "{$church->church_name} is in {$church->address}.",
            "{$church->church_name} is located at {$church->address}. Just ask locals for “{$church->church_name}”—they’ll point you in the right direction!"
        ];

        $randomKey = array_rand($randomResponses);

        return $randomResponses[$randomKey];
    }

    public function check_non_resident_baptism_eligibility($entities){
        $randomResponse = [
            'Yes, you can! Just make sure to secure a Permit to Baptize Outside the Parish from your home parish and upload it through ChurchConnect during the baptism registration.',
            'Definitely! Non-residents are welcome to have their child baptized at Any Church, provided you submit a baptismal permit from your local parish.',
            "Any Church accepts baptism requests from non-parishioners. A Permit to Baptize is required and must be submitted during your ChurchConnect application.",
            "Even if you're not a parish resident, you can still proceed with baptism as long as your home parish issues a written permit. This is a standard requirement for sacraments outside your territory.",
            'Yes, just include the Permit to Baptize Outside the Parish with your other documents like your child’s birth certificate. ChurchConnect will notify you if anything is missing before your request is approved.'
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];
    }

    public function list_churchconnect_services($entities){
        $randomResponse = [
            'ChurchConnect offers several church-related services, including Baptism, Wedding, Confirmation, Memorial, and Mass Intentions. You can submit applications and required documents online for each service without needing to go to the parish office in person.',
            'You can access services like Baptism, Wedding, Confirmation, Memorial, and Mass Intentions directly through ChurchConnect. Everything can be done online, making it easier to apply and manage your requests from home.',
            "Through ChurchConnect, you can book church services such as Baptism, Wedding, Confirmation, Memorial, and Mass Intentions. Applications and document submissions are handled entirely online for your convenience.",
            "ChurchConnect allows you to request a range of church services—Baptism, Wedding, Confirmation, Memorial, and Mass Intentions—all from your device. There's no need to visit the parish office to get started.",
            "With ChurchConnect, you can apply for Baptism, Wedding, Confirmation, Memorial, and Mass Intention services online. It's a simple and efficient way to connect with your parish without leaving home."
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];
    }

    public function inquire_service_fee(){

        $randomResponse = [
            "No, ChurchConnect's online services are completely free to use.",
            "There are no fees required to access or use ChurchConnect.",
            "Using ChurchConnect doesn’t cost anything — it’s free!",
            "ChurchConnect services are free of charge for all users.",
            "No payment is needed to use ChurchConnect’s features online."
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];
    }

    public function inquire_mobile_access(){
        $randomResponse = [
            "Yes, ChurchConnect is fully accessible on mobile phones.",
            "Absolutely! You can use ChurchConnect from any smartphone or tablet.",
            "Yes, the platform is mobile-friendly and works well on most devices.",
            "ChurchConnect works smoothly on mobile, no app installation needed.",
            "You can access all ChurchConnect services directly through your phone’s browser."
        ];

        $randomKey = array_rand($randomResponse);
        return $randomResponse[$randomKey];
    }
}
