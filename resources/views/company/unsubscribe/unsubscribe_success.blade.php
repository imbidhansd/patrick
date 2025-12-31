<?php
$admin_page_title = 'Unsubscribe Emails';
?>
@extends('company.unsubscribe.layout')

@section ('content')
<?php /* @include('admin.includes.breadcrumb') */ ?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card-box text-center">
            @if ($companyObj->membership_level->paid_members == 'yes')
            <h3>Thank you for your feedback regarding your email subscription.</h3>
            <p class="font-14">However, to insure proper communication with our members, active members may not unsubscribe from receiving communication from us. If you would like to unsubscribe from any particular emails, please forward the email us at <a href="mailto:members@trustpatrick.com">members@trustpatrick.com</a>.</p>
            @else
            <h3>Thank you for your feedback!</h3>
            @endif
            <?php /*
             * <h3>Unsubscribed Successfully</h3>
             * <p class="font-14">You have been unsubscribed from all emails regarding TrustPatrick.com referral network.</p> */ ?>
            <a href="https://opp.trustpatrick.com/" class="text-muted"><i class="fas fa-angle-double-left"></i> Return to our website</a>
            
        </div>
    </div>
</div>
@endsection

@section ('page_js')
@endsection