<?xml version='1.0'?>
<BackgroundCheck userId="{{ env('USER_ID') }}" password="{{ env('PASSWORD') }}">
    <BackgroundSearchPackage action="submit" type="{{ env('TYPE') }}">
        <ReferenceId>{{ $company_user_pre_screen_questions->company_user_id }}</ReferenceId>
        <PersonalData>
            <PersonName>
                <GivenName>{{ $company_user_pre_screen_questions->first_name }}</GivenName>
                <FamilyName>{{ $company_user_pre_screen_questions->last_name }}</FamilyName>
            </PersonName>
            <DemographicDetail>
                <!--Gender>M</Gender-->
                <DateOfBirth>{{ $company_user_pre_screen_questions->birth_date }}</DateOfBirth>
            </DemographicDetail>
            <PostalAddress>
                <CountryCode>US</CountryCode>
                <PostalCode>{{ $company_user_pre_screen_questions->zipcode }}</PostalCode>
                <Region>{{ $company_user_pre_screen_questions->state }}</Region>
                <Municipality>{{ $company_user_pre_screen_questions->city }}</Municipality>
                <DeliveryAddress>
                    <AddressLine>{{ $company_user_pre_screen_questions->address_line_1.' '.$company_user_pre_screen_questions->address_line_1}}</AddressLine>
                </DeliveryAddress>
            </PostalAddress>
            <EmailAddress>{{ $company_user_pre_screen_questions->email }}</EmailAddress>
            <Telephone>{{ $company_user_pre_screen_questions->telephone }}</Telephone>
        </PersonalData>

        <Screenings>
            <Screening type='credit'>
                <Vendor score='no' fraud='no'>transunion</Vendor>
            </Screening>
            <Screening type='workcomp' />
        </Screenings>
    </BackgroundSearchPackage>
</BackgroundCheck>