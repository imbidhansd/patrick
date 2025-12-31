<?xml version="1.0"?>
   <BackgroundCheck userId="{{ $API_USER_ID }}" password="{{ $API_PASSWORD }}">
      <BackgroundSearchPackage action="submit" type="{{ $API_PACKAGE }}">
         <ReferenceId>BGCHECK_{{ $company_user_pre_screen_questions->id }}</ReferenceId>
         <PersonalData>
            <PersonName>
               <GivenName>{{ $company_user_pre_screen_questions->first_name }}</GivenName>
               <MiddleName>{{ $company_user_pre_screen_questions->middle_name }}</MiddleName>
               <FamilyName>{{ $company_user_pre_screen_questions->last_name }}</FamilyName>
            </PersonName>
            <DemographicDetail>
                <GovernmentId countryCode='US' issuingAuthority='SSN'>{{ $ssn }}</GovernmentId>
                <Gender>{{ $company_user_pre_screen_questions->gender }}</Gender>
                <DateOfBirth>{{ $company_user_pre_screen_questions->birth_date }}</DateOfBirth>
            </DemographicDetail>
            
            <PostalAddress>
                <CountryCode>US</CountryCode>
                <PostalCode>{{ $company_user_pre_screen_questions->zipcode }}</PostalCode>
                <Region>{{ $company_user_pre_screen_questions->state }}</Region>
                <Municipality>{{ $company_user_pre_screen_questions->city }}</Municipality>
                <DeliveryAddress>
                    <AddressLine>{{ trim($company_user_pre_screen_questions->address_line_1) }} {{ trim($company_user_pre_screen_questions->address_line_2) }}</AddressLine>
                </DeliveryAddress>
            </PostalAddress>
            <EmailAddress>{{ $company_user_pre_screen_questions->email }}</EmailAddress>
            <Telephone>{{ $company_user_pre_screen_questions->telephone }}</Telephone>
         </PersonalData>
         <Screenings useConfigurationDefaults='yes'>
            <AdditionalItems type='x:integration_type'>
               <Text>Integrating Platform Company Name</Text>
            </AdditionalItems>
         </Screenings>
         @if (isset($driver_license_data) && $driver_license_data != '')
         <SupportingDocumentation>
            <OriginalFileName>{{ $company_user_pre_screen_questions->first_name }}-{{ $company_user_pre_screen_questions->last_name }}-Driving-License.jpg</OriginalFileName>
            <Name>{{ $company_user_pre_screen_questions->first_name }} {{ $company_user_pre_screen_questions->last_name }} Driving License</Name>
            <EncodedContent>{{ $driver_license_data }}</EncodedContent>
         </SupportingDocumentation>
         @endif
         @if (isset($bg_check_pdf_data) && $bg_check_pdf_data != '')
         <SupportingDocumentation>
            <OriginalFileName>{{ $company_user_pre_screen_questions->first_name }}-{{ $company_user_pre_screen_questions->last_name }}-Background-Check-Appliaction.pdf</OriginalFileName>
            <Name>{{ $company_user_pre_screen_questions->first_name }} {{ $company_user_pre_screen_questions->last_name }} Background Check Appliaction</Name>
            <EncodedContent>{{ $bg_check_pdf_data }}</EncodedContent>
         </SupportingDocumentation>
         @endif
      </BackgroundSearchPackage>
   </BackgroundCheck>
