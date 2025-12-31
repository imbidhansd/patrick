<?php

namespace App\Models;

class AweberSubscriberRequest
{
    //public string $ad_tracking;
    public $email = "";
    public $custom_fields = array();
    //public string $ip_address;
    //public string $name;

    public function __construct($email, $custom_fields)
    {
        $this->email = $email;
        $this->custom_fields = $custom_fields;        
    }
}
