<?php

namespace App\Mail\Company\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \Spatie\MailTemplates\TemplateMailable;


use \App\Models\CompanyUser;
use \App\Models\Company;

class WelcomeMail extends TemplateMailable{
    use Queueable, SerializesModels;

    public $company_name;
    public $activation_link;
    public $first_name;
    public $last_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CompanyUser $company_user, Company $company){
        //
        $this->first_name = $company_user->first_name;
        $this->last_name = $company_user->last_name;
        $this->company_name = $company->company_name;
        $this->activation_link = route('company-activation-link', ['activation_key' => $company->activation_key]);
    }


    public function getHtmlLayout(): string
    {
        //return '{{{ body }}}';
        $pathToLayout = public_path('mail/template-1.html');
        return file_get_contents($pathToLayout);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
