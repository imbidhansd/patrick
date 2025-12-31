<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        // Check monthly subscription and generate new invoice as per the subscription
        Commands\MonthlySubscriptionCheck::class,
        //generate ppl membership invoices for previous month leads
        Commands\GeneratePplInvoice::class,
        //check ppl membership last invoice is paid or not
        Commands\CheckPplInvoice::class,
        //pause leads 
        Commands\PauseLead::class,
        //resume leads
        Commands\ResumeLead::class,
        // ppl membership budget udpate 
        Commands\MonthlyBudgetUpdate::class,
        // Background check process
        Commands\BackgroundCheckProcess::class,
        // Send broadcast emails
        Commands\SendBroadcastEmail::class,
        //Send leads followup emails
        Commands\FollowUpLeadEmail::class,
        //Send Non member followup emails
        Commands\FollowUpNonMemberEmail::class,
        //Send Registered member followup emails
        Commands\FollowUpRegisteredMemberEmail::class, 
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        // // Check monthly subscription and generate new invoice as per the subscription
        $schedule->command('MonthlySubscription:Check')->everyFiveMinutes();

        // //generate ppl membership invoices for previous month leads
        $schedule->command('PplInvoice:Generate')->monthlyOn(7, '00:01');

        // //check ppl membership last invoice is paid or not
        $schedule->command('PplInvoice:Check')->monthlyOn(15, '00:01');

        // //pause leads 
        $schedule->command('Lead:Pause')->daily();

        // //resume leads
        $schedule->command('Lead:Resume')->daily();

        // // ppl membership budget udpate 
        $schedule->command('MonthlyBudget:Update')->monthlyOn(1, '00:01');

        // // Background check submittal
        $schedule->command('BackgroundCheck:Process')->everyFifteenMinutes();

        // // Send Broadcast emails
        $schedule->command('BroadcastEmail:Send')->everyMinute();

        // //Send leads followup emails
        $schedule->command('FollowUpLeadEmail:Send')->everyMinute();

        // //Send Non member followup emails
        $schedule->command('FollowUpNonMemberEmail:Send')->everyMinute();

        // //Send Registered member followup emails
        $schedule->command('FollowUpRegisteredMemberEmail:Send')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
