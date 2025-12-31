<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TazAPIService;
use App\Models\Custom;
use App\Rules\ValidSSNRule;

class TazworksApiController extends Controller {

    protected $tazAPIService;
    protected $web_settings;
    public function __construct(TazAPIService $tazAPIService)
    {
        $this->tazAPIService = $tazAPIService;
        $this->web_settings = Custom::getSettings();
    }

    public function background_check() {

        /* $filename = public_path()."/background_check_xml/sample-logo.png";

          echo base64_encode($filename);
          exit; */



        //$filename = public_path()."/background_check_xml/test_company.xml";
        $filename = "/var/www/html/background_check_xml/test_company.xml";
        $handle = fopen($filename, "r");
        $XPost = fread($handle, filesize($filename));
        fclose($handle);

        // Sandbox
        //$apiUrl = "https://lightning.instascreen.net/send/interchange";
        // Live
        $apiUrl = "https://reliable.instascreen.net/send/interchange";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
        curl_setopt($ch, CURLOPT_URL, $apiUrl); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost); // add POST fields
        curl_setopt($ch, CURLOPT_POST, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        //dd($data);

        $response = simplexml_load_string($data);
        // Convert into json
        $con = json_encode($response);

// Convert into associative array
        $newArr = json_decode($con, true);

        //print_r($newArr);
        dd($newArr);
        //echo $data;
    }

    public function background_check_status() {


        //$filename = public_path()."/background_check_xml/test_company-status.xml";
        $filename = "/var/www/html/background_check_xml/test_company-status.xml";

        $handle = fopen($filename, "r");
        $XPost = fread($handle, filesize($filename));
        fclose($handle);

        // Sandbox
        //$apiUrl = "https://lightning.instascreen.net/send/interchange";
        // Live
        $apiUrl = "https://reliable.instascreen.net/send/interchange";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
        curl_setopt($ch, CURLOPT_URL, $apiUrl); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $XPost); // add POST fields
        curl_setopt($ch, CURLOPT_POST, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        //dd($data);

        $response = simplexml_load_string($data);
        // Convert into json
        $con = json_encode($response);

        // Convert into associative array
        $newArr = json_decode($con, true);

        //print_r($newArr);
        echo '<pre>';
        print_r($newArr);



        exit;

        //echo $data;
    }
    public function getClients(Request $request)
    {
        $page = $request->input('page', 0);
        $size = $request->input('size', 5);
        $clients = $this->tazAPIService->getClients($page, $size);
        return $clients->json();
    }

    public function getClientGuidByName(Request $request)
    {
        $name = $request->input('name');
        $clientGuid = $this->tazAPIService->getClientGuidByName($name);

        if ($clientGuid !== null) {
            return response()->json(['clientGuid' => $clientGuid]);
        } else {
            return response()->json(['message' => 'Client not found'], 404);
        }
    }

    public function getClientProducts(Request $request)
    {
        $clientGuid = $request->input('clientGuid');
        $page = $request->input('page', 0);
        $size = $request->input('size', 30);

        $response = $this->tazAPIService->getClientProducts($clientGuid, $page, $size);
        return $response->json();
    }

    public function getClientProductByName(Request $request)
    {
        $clientName = $this->web_settings['tazapi_clientname'];
        $productName = $this->web_settings['tazapi_productname'];

        $product = $this->tazAPIService->getClientProductByName($clientName, $productName);
       
        if ($product) {
            return $product;
        } else {
            return ['message' => 'Product not found'];
        }
    }

    public function createApplicant(Request $request)
    {        
        try {

            $validatedData = $request->validate([            
                'firstName' => 'required',
                'lastName' => 'required',
                'ssn' => ['required', new ValidSSNRule]
            ]);
            
            $validatedData['clientName'] = $this->web_settings['tazapi_clientname'];
            
            $response = $this->tazAPIService->createApplicant($validatedData);

            if ($response) {
                // Applicant created successfully
                return ['message' => 'Applicant created successfully', 'response' => $response->json()];
            } else {
                // Failed to create applicant
                return ['message' => 'Failed to create applicant', 'response' => $response->json()];
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    public function submitOrder(Request $request)
    {
        try {

                // Validate the request data
                $validationRules = [
                    'applicantGuid' => 'required',
                    'clientProductGuid' => 'required',
                    'externalIdentifier' => 'required',
                ];

                $attachments = $request->input('attachments');
                if (!empty($attachments)) {
                    foreach ($attachments as $key => $attachment) {
                        $validationRules["attachments.{$key}.name"] = 'required|string';
                        $validationRules["attachments.{$key}.originalFileName"] = 'required|string';
                        $validationRules["attachments.{$key}.encodedContent"] = 'required|string';
                    }
                }

                $validatedData = $request->validate($validationRules);

                // Call the submitOrder method from TazAPIService
                $response = $this->tazAPIService->submitOrder($validatedData);

                // Return the response from the submitOrder method
                return ['message' => 'Order submitted successfully', 'response' => $response->json()];
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json(['errors' => $e->validator->errors()], 422);
            }
    }
}
