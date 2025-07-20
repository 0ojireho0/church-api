<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;



class CertificateController extends Controller
{
    //

    public function showCert($status){


        switch((int)$status){

            case 0:
                $result = Certificate::with('church')->orderBy('id', 'desc')->get();

                break;

            case 1:
                $result = Certificate::with('church')
                                    ->where('cert_type', 'Baptism')
                                    ->orderBy('id', 'desc')
                                    ->get();

                break;

            case 2:
                $result = Certificate::with('church')
                                    ->where('cert_type', 'Wedding')
                                    ->orderBy('id', 'desc')
                                    ->get();
                break;

            case 3:
                $result = Certificate::with('church')
                                    ->where('cert_type', 'Confirmation')
                                    ->orderBy('id', 'desc')
                                    ->get();

                break;

            default:
                $result = [];
                break;

        }

        return $result;




    }

    public function addNewCertificate(Request $request){

        $request->validate([
            'cert_type' => ['required', 'string'],
            'church_id' => ['required', 'integer'],
            'dob' => ['required', Rule::date()->format('Y-m-d')],
            'fullname' => ['required', 'string'],
            'files.*' => ['required', 'mimes:jpg,png,pdf']
        ]);

        if($request->hasFile('files')){

            $files = $request->file('files');

            foreach($files as $file){

                $filename = $file->getClientOriginalName();

                Storage::disk('llibiapp_dms')->put(
                        'certificate/' . $filename,
                        file_get_contents($file)
                    );

                Certificate::create([
                    'church_id' => $request->church_id,
                    'fullname' => $request->fullname,
                    'filename' => $filename,
                    'filepath' => env('DO_LLIBI_CDN_ENDPOINT_DMS') . '/certificate/' . $filename,
                    'dob' => $request->dob,
                    'cert_type' => $request->cert_type
                ]);
            }

            return response()->json([
                'success' => true
            ], 200);


        }


    }
}
