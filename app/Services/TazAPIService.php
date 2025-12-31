<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TazAPIService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('TAZ_API_BASE_URL');
        $this->token = env('TAZ_API_CLIENT_TOKEN');
    }

    public function getClients($page = 0, $size = 5)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->token}"])->get("$this->baseUrl/clients", [
                    'page' => $page,
                    'size' => $size,
                ]);

        return $response;
    }

    public function getClientGuidByName($name)
    {
        $clients = $this->getClients()->json();
        foreach ($clients as $client) {
            if ($client['name'] === $name) {
                return $client['clientGuid'];
            }
        }

        return null;
    }

    public function getClientProducts($clientGuid, $page = 0, $size = 30)
    {
        $url = "$this->baseUrl/clients/$clientGuid/products?page=$page&size=$size";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->token}"])->get($url);

        return $response;
    }

    public function getClientProductByName($clientName, $productName)
    {
        $clientGuid = $this->getClientGuidByName($clientName);

        if (!$clientGuid) {
            return null;
        }

        $response = $this->getClientProducts($clientGuid)->json();
        $product = $this->filterProductByName($response, $productName);
        $product['clientGuid'] = $clientGuid;
        $product['clientName'] = $clientName;
        return $product;
    }

    private function filterProductByName($products, $productName)
    {
        foreach ($products as $product) {
            if ($product['productName'] === $productName) {
                return $product;
            }
        }

        return null;
    }

    public function createApplicant(array $applicantData)
    {
        // Get client GUID by name
        $clientGuid = $this->getClientGuidByName($applicantData['clientName']);

        if (!$clientGuid) {
            return null; // Return null if client not found
        }

        // Construct the URL with the client GUID
        $url = $this->baseUrl . '/clients/' . $clientGuid . '/applicants';

        // Define the request body
        $body = [
            'applicantGuid' => null,
            'textingEnabled' => false,
            'firstName' => $applicantData['firstName'],
            'middleName' => $applicantData['middleName'] ?? null,
            'noMiddleName' => !isset($applicantData['middleName']),
            'lastName' => $applicantData['lastName'],
            'generation' => $applicantData['generation'] ?? null,
            'gender' => $applicantData['gender'] ?? null,
            'ssn' => $applicantData['ssn'],
            'race' => $applicantData['race'] ?? null,
            'dateOfBirth' => $applicantData['dateOfBirth'] ?? null,
            'email' => $applicantData['email'] ?? null,
            'phoneNumber' => $applicantData['phoneNumber'] ?? null,
            'driverLicense' => $applicantData['driverLicense'] ?? null,
            'driverLicenseState' => $applicantData['driverLicenseState'] ?? null,
            'proposedPosition' => $applicantData['proposedPosition'] ?? null,
            'proposedSalary' => $applicantData['proposedSalary'] ?? null,
            'monthlyIncome' => $applicantData['monthlyIncome'] ?? null,
            'monthlyDebt' => $applicantData['monthlyDebt'] ?? null,
            'monthlyRent' => $applicantData['monthlyRent'] ?? null,
            'desiredUnit' => $applicantData['desiredUnit'] ?? null,
            'referredBy' => $applicantData['referredBy'] ?? null,
            'jobCode' => $applicantData['jobCode'] ?? null,
            'jobLocation' => $applicantData['jobLocation'] ?? null,
            'stateOfEmployment' => $applicantData['stateOfEmployment'] ?? null,
            'cityOfEmployment' => $applicantData['cityOfEmployment'] ?? null,
            'countyOfEmployment' => $applicantData['countyOfEmployment'] ?? null
        ];

        // Make a POST request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($url, $body);

        return $response;
    }

    public function submitOrder(array $orderData)
    {
        // Get client GUID by name
        $clientGuid = $this->getClientGuidByName($orderData['clientName']);

        if (!$clientGuid) {
            return null; // Return null if client not found
        }

        $url = $this->baseUrl . '/clients/' . $clientGuid . '/orders';
        $mappedOrderData = [
            'applicantGuid' => $orderData['applicantGuid'],
            'clientProductGuid' => $orderData['clientProductGuid'],
            'useQuickApp' => $orderData['useQuickApp'] ?? false,
            'generalReportReference' => $orderData['generalReportReference'] ?? null,
            'externalIdentifier' => $orderData['externalIdentifier'] ?? null,
            'queueConsumerDisclosure' => $orderData['queueConsumerDisclosure'] ?? false,
            'quickappNotifyApplicants' => $orderData['quickappNotifyApplicants'] ?? false,
            'certifyPermissiblePurpose' => $orderData['certifyPermissiblePurpose'] ?? false,
            'orderNotes' => $orderData['orderNotes'] ?? null,
            'attachments' => $orderData['attachments'] ?? null,
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->token}",
        ])->post($url, $mappedOrderData);

        return $response;
    }
}
