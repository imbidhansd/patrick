<?xml version='1.0'?>
<BackgroundCheck userId="{{ $API_USER_ID }}" password="{{ $API_PASSWORD }}">
    <BackgroundSearchPackage action="status">
        <OrderId>{{ $company_user_item->bg_check_order_id }}</OrderId>
    </BackgroundSearchPackage>
</BackgroundCheck>
