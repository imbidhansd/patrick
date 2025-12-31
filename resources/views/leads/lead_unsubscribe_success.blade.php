<?php
    $admin_page_title = 'Unsubscribe';

    $unsubscribe_from = "";
    if ($lead->regarding_your_request == 'unsubscribe' && $lead->special_offers == 'unsubscribe' && $lead->scams_updates == 'unsubscribe' && $lead->general_updates == 'unsubscribe'){
        $unsubscribe_from = "All";
    } else {
        if ($lead->regarding_your_request == 'unsubscribe') {
            $unsubscribe_from .= "Regarding Your Request, ";
        }

        if ($lead->special_offers == 'unsubscribe') {
            $unsubscribe_from .= "Special Promotions/Offers, ";
        }

        if ($lead->scams_updates == 'unsubscribe') {
            $unsubscribe_from .= "Scams & Ripoffs Updates, ";
        }

        if ($lead->general_updates == 'unsubscribe') {
            $unsubscribe_from .= "General Updates, ";
        }
    }
?>
@extends('leads.layout')

@section ('content')
<?php /* @include('admin.includes.breadcrumb') */ ?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card-box text-center">
            <h3>Thank you for your feedback!</h3>
            <?php /*
             * <h3>Unsubscribed Successfully</h3>
             * <p class="font-14">
                You have been unsubscribed from {{ rtrim($unsubscribe_from, ', ') }} emails regarding TrustPatrick.com referral network.
            </p> */ ?>
            <a href="https://opp.trustpatrick.com/" class="text-muted"><i class="fas fa-angle-double-left"></i> Return to our website</a>
            
        </div>
    </div>
</div>
@endsection

@section ('page_js')
@endsection
