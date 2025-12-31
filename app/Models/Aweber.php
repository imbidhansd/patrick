<?php

namespace App\Models;
use Illuminate\Support\Facades\Http;
use Log;
/**
 * Aweber Class.
 *
 * @subpackage Aweber class
 * @author Tony Thomas
 */
class Aweber
{
    public static function Authorize($code)
    {        
        $authorizeResponse = Http::withBasicAuth(env('AWEBER_CLIENT_KEY'), env('AWEBER_CLIENT_SECRET'))
            ->post('https://auth.aweber.com/oauth2/token',
                [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => env('AWEBER_REDIRECT_URL'),
                    'http_errors' => false
                ]);
        if ($authorizeResponse->successful()) {
            $auth = json_decode($authorizeResponse->body());
            return $auth;
        }        
        return null;
    }

    private static function GetToken($refreshToken)
    {
        $tokenResponse = Http::withBasicAuth(env('AWEBER_CLIENT_KEY'), env('AWEBER_CLIENT_SECRET'))
            ->post('https://auth.aweber.com/oauth2/token',
                [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken
                ]);
        
        if ($tokenResponse->successful()) {
            $token = json_decode($tokenResponse->body());
            return $token->access_token;
        }
        return null;
    }

    public static function GetAccountId($accessToken)
    {
        $authorizeResponse = Http::withToken($accessToken)->get('https://api.aweber.com/1.0/accounts');

        if ($authorizeResponse->successful()) {
            $accounts = json_decode($authorizeResponse->body());

            if ($accounts->entries) {
                $accountId = $accounts->entries[0]->id;
                return $accountId;
            }
        }

        return null;
    }

    public static function GetLists( $accoutId, $refreshToken)
    { 
        $accessToken = self::GetToken($refreshToken); 

        if(empty($accessToken)){
            return null; 
        }
                
        $listsResponse = Http::withToken($accessToken)->get('https://api.aweber.com/1.0/accounts/'.$accoutId.'/lists');
        $lists = [];

        if ($listsResponse->successful()) {
            $listDetails = json_decode($listsResponse->body()); 

            if (!empty($listDetails->entries)) {               
            
               foreach( $listDetails->entries as $entry)
               {
                   $lists[$entry->id] = $entry->name;
               }    
            }
        }
        
        asort($lists);
        return $lists;
    }

    public static function SubscribeToList($accoutId, $listId, $refreshToken, $request)
    { 
        $accessToken = self::GetToken($refreshToken);         
        if(empty($accessToken)){
            Log::debug("Token could not be generated");
            return null; 
        }

        $subscribeListResponse = Http::withToken($accessToken)
            ->post("https://api.aweber.com/1.0/accounts/".$accoutId."/lists/".$listId."/subscribers", $request);        

        if ($subscribeListResponse->successful()) {
            return "success";
        }        
        return null;
    }
}
