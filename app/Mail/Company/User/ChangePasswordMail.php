<?php

namespace App\Mail\Company\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \Spatie\MailTemplates\TemplateMailable;


use \App\Models\CompanyUser;

class ChangePasswordMail extends TemplateMailable{
    use Queueable, SerializesModels;

    public $first_name;
    public $last_name;
    public $website;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CompanyUser $company_user){
        //
        $this->first_name = $company_user->first_name;
        $this->last_name = $company_user->last_name;
        $this->website = env('SITE_TITLE');
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
