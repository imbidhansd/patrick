<html>
<head>
    <!-- Title -->
    <title>Company Invoice | {{ env('SITE_TITLE') }}</title>
    <link href="{{ asset('themes/pdf-bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
    id="bootstrap-stylesheet" />

    <style type="text/css">
        body{
            font-size: 13px;
        }
        .bold_font {
            font-weight: bold;
        }
        .table tr td,
        .table tr th {
            padding: 5px;
        }

        .text-right{
            text-align: right;
        }
        .text-left{
            text-align: left;
        }
        .text-center{
            text-align: center;
        }

        .valigntop{
            vertical-align: text-top;
        }

        .invoice_item_table td,
        .invoice_item_table th{
            border: 1px solid #ccc;
        }
        .invoice_item_table .table_head{
            background: #0D3E73;
            color: #fff;
            padding: 12px;
            border: 0px;
        }

        .list_style{
            list-style: none;
        }
        .category_type{
            color: #0D3E73;
        }
        
        @page {
            header: page-header;
            footer: page-footer;
        }
    </style>
</head>
<body>
    <htmlpageheader name="page-header">
        <p style="font-size: 12px; text-align: right;">Page No:{PAGENO}</p>
        <table class="table" border="0">
            <tr>
                <td colspan="2" class="text-center">
                    <img src="{{ asset('/images/header-logo.png') }}" alt="{{ env('SITE_TITLE') }}" />
                </td>
            </tr>
        </table>
        </htmlpageheader>
        

        <table class="table" border="0">
            <tr>
                <th class="text-center"><h1>BACKGROUND CHECK APPLICATION</h1></th>
            </tr>
            <tr>
                <th class="text-center"><h4>DATASOURCE BACKGROUND SCREENING SERVICES</h4></th>
            </tr>
            
        </table>

        <table class="table" border="0">
            <tr>
                <th colspan="2"><h4>Applicant Info</h4></th>
            </tr>
            <tr>
                <td width="35%">First Name</td>
                <td>{{ $item->first_name }}</td>
            </tr>
            <tr>
                <td>Middle Name</td>
                <td>{{ $item->middle_name }}</td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td>{{ $item->last_name }}</td>
            </tr>
            <tr>
                <td>Social Security Number</td>
                <td>{{ $item->birth_date }}</td>
            </tr>
            <tr>
                <td>Date Of Birth</td>
                <td>{{ \Carbon\Carbon::parse($item->birth_date)->format(env('DATE_FORMAT')) }}</td>
                <tr>
                    <td>Email</td>
                    <td>{{ $item->email }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $item->telephone }}</td>
                </tr>
                <tr>
                    <td>SSN</td>
                    <td>XXX-XX-{{ $item->ssn }}</td>
                </tr>

                
            </table>
            <br/><br/><br/>
            <table class="table" border="0">
                <tr>
                    <th colspan="2"><h4>Address Information</h4></th>
                </tr>
                <tr>
                    <td width="35%">Address</td>
                    <td>
                        {{ $item->address_line_1 }}
                        @if ($item->address_line_2 != '')
                        , {{ $item->address_line_2 }}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td>City</td>
                    <td>{{ $item->city }}</td>
                </tr>
                <tr>
                    <td>State</td>
                    <td>{{ $item->state }}</td>
                </tr>
                <tr>
                    <td>Zipcode</td>
                    <td>{{ $item->zipcode }}</td>
                </tr>
            </table>

            <pagebreak></pagebreak>

            <?php // Step 4 [Start] ?>
            <table class="table" border="0">
                <tr>
                    <td>

                        <h4 class="text-center">ELECTRONIC SIGNATURE CONSENT</h4>
                        <br/><br/>
                        <p>As part of the selection process at Trust Patrick Referral Network, the "Company," you will need to consent to a background check electronically. By
                            typing your name, you are consenting to receive any communications (legally required or otherwise) and all changes to such communications electronically. In order to use
                            the website, you must provide at your own expense an Internet connected device that is compatible with the minimum requirements outlined below. You also confirm that
                            your device will meet these specifications and requirements and will permit you to access and retain the communications electronically each time you access and use the
                        website.</p>

                        <div class="clearfix">&nbsp;</div>

                        <h5 class="text-center">System Requirements to Access Information</h5>
                        <p>To receive and view an electronic copy of the Communications you must have the following equipment and software:</p>
                        <ul>
                            <li>A personal computer or other device which is capable of accessing the Internet. Your access to this page verifies that your system/device meets these requirements.</li>
                            <li>An Internet web browser which is capable of supporting 128-bit SSL encrypted communications, JavaScript, and cookies. Your system or device must have 128-bit SSL encryption software. Your access to this page verifies that your browser and encryption software/device meet these requirements.</li>
                        </ul>

                        <div class="clearfix">&nbsp;</div>


                        <h5 class="text-center">System Requirements to Retain Information</h5>
                        <p>To retain a copy, you must either have a printer connected to your personal computer or other device or, alternatively, the ability to save a copy through use of printing
                        service or software such as Adobe Acrobat®. If you would like to proceed using paper forms, please contact Datasource Background Screening Services.</p>

                        <div class="clearfix">&nbsp;</div>

                        <h5 class="text-center">Withdrawal of Electronic Acceptance of Disclosures and Notices</h5>
                        <p>You can also contact us to withdraw your consent to receive any future communications electronically, including if the system requirements described above change and
                            you no longer possess the required system. If you withdraw your consent, we will terminate your use of the Datasource Background Screening Services website and the services
                        provided through Datasource Background Screening Services website.</p>
                        <p>To ensure that a signature is unique and to safeguard you against unauthorized use of your name, your IP address (103.78.207.194) has been recorded and will be stored
                            along with your electronic signature. Please note that if you wish to submit your Disclosure and Authorization Forms electronically, Datasource Background Screening Services
                        requires that you include your social security number or user identification. All of your information will be encrypted and transmitted via our secure website.</p>
                        <p>I understand that Datasource Background Screening Services uses computer technology to ensure that my signed documents are not altered after submission. I agree to allow Reliable
                        Background Screening to validate my signed documents in this way.</p>
                        <br/>
                        <br/>
                    </td>
                </tr>

                <tr>
                    <td style="background-color: #ccc; padding: 5px 15px; width: 100%; float: left;">
                        I consent to transacting electronically, including receiving legally required notices electronically.
                        <br/>
                        [{{ $item->signature }}]
                        <hr/>
                        <p style="font-weight: bold; text-align: center">Signed {{ $item->created_at->format('D, d M Y h:i a') }} via {{ $item->ip_address }}</p>
                    </td>
                </tr>
            </table>
            <?php // Step 4 [End] ?>

            <pagebreak></pagebreak>


            <table class="table" border="0">
                <tr>
                    <td>

                        Para información en español, visite <a href="http://www.consumerfinance.gov/learnmore" target="_blank">www.consumerfinance.gov/learnmore</a> o escribe a la
                        Consumer Financial Protection Bureau, 1700 G Street N.W., Washington, DC 20552.<br/>

                    </td>
                </tr>
            </table>

            <br/>
            <h4 class="text-center">A Summary of Your Rights Under the Fair Credit Reporting Act</h4>
            <br/>


            <table class="table" border="0">
                <tr>
                    <td>
                        <ul>
                            <li><strong>You must be told if information in your file has been used against you.</strong> Anyone who
                                uses a credit report or another type of consumer report to deny your application for credit,
                                insurance, or employment – or to take another adverse action against you – must tell you,
                                and must give you the name, address, and phone number of the agency that provided the
                            information.</li>
                        </ul>
                    </td>
                </tr>
            </table>




            <table class="table" border="0">
                <tr>
                    <td>
                        <ul>
                            <li>
                                <strong>You have the right to know what is in your file.</strong> You may request and obtain all the
                                information about you in the files of a consumer reporting agency (your “file
                                disclosure”). You will be required to provide proper identification, which may include
                                your Social Security number. In many cases, the disclosure will be free. You are entitled
                                to a free file disclosure if:
                                <ul>
                                    <li>a person has taken adverse action against you because of information in your
                                    credit report;</li>
                                    <li>you are the victim of identity theft and place a fraud alert in your file;</li>
                                    <li>your file contains inaccurate information as a result of fraud;</li>
                                    <li>you are on public assistance;</li>
                                    <li>you are unemployed but expect to apply for employment within 60 days.</li>
                                </ul>

                                In addition, all consumers are entitled to one free disclosure every 12 months upon
                                request from each nationwide credit bureau and from nationwide specialty consumer
                                reporting agencies. See www.consumerfinance.gov/learnmore for additional
                                information.
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>


            <table class="table" border="0">
                <tr>
                    <td>
                        <ul>
                            <li><strong>You have the right to ask for a credit score.</strong> Credit scores are numerical summaries of
                                your credit-worthiness based on information from credit bureaus. You may request a
                                credit score from consumer reporting agencies that create scores or distribute scores used
                                in residential real property loans, but you will have to pay for it. In some mortgage
                            transactions, you will receive credit score information for free from the mortgage lender.</li>
                        </ul>
                    </td>
                </tr>
            </table>


            <table class="table" border="0">
                <tr>
                    <td>
                        <ul>
                            <li><strong>You have the right to dispute incomplete or inaccurate information.</strong> If you identify
                                information in your file that is incomplete or inaccurate, and report it to the consumer
                                1reporting agency, the agency must investigate unless your dispute is frivolous. See
                                <a href="http://www.consumerfinance.gov/learnmore" target="_blank">www.consumerfinance.gov/learnmore</a> for an explanation of dispute procedures.</li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <table class="table" border="0">
                    <tr>
                        <td>
                            <ul>
                                <li><strong>Consumer reporting agencies must correct or delete inaccurate, incomplete, or
                                unverifiable information.</strong> Inaccurate, incomplete, or unverifiable information must be
                                removed or corrected, usually within 30 days. However, a consumer reporting agency
                            may continue to report information it has verified as accurate.</li>
                        </ul>
                    </td>
                </tr>
            </table>

            <table class="table" border="0">
                <tr>
                    <td>
                        <ul>
                            <li><strong>Consumer reporting agencies may not report outdated negative information.</strong> In
                                most cases, a consumer reporting agency may not report negative information that is
                            more than seven years old, or bankruptcies that are more than 10 years old.</li>
                        </ul>
                    </td>
                </tr>
            </table>

            <table class="table" border="0">
                <tr>
                    <td>
                        <ul>
                            <li><strong>Access to your file is limited.</strong> A consumer reporting agency may provide information
                                about you only to people with a valid need – usually to consider an application with a
                                creditor, insurer, employer, landlord, or other business. The FCRA specifies those with a
                            valid need for access.</li>

                        </ul>
                    </td>
                </tr>
            </table>

            <table class="table" border="0">
                <tr>
                    <td>
                        <ul>
                            <li><strong>You must give your consent for reports to be provided to employers.</strong> A consumer
                                reporting agency may not give out information about you to your employer, or a potential
                                employer, without your written consent given to the employer. Written consent generally
                                is not required in the trucking industry. For more information, go to
                                <a href="http://www.consumerfinance.gov/learnmore" target="_blank">www.consumerfinance.gov/learnmore</a>.</li>
                            </ul>
                        </td>
                    </tr>
                </table>

                <table class="table" border="0">
                    <tr>
                        <td>
                            <ul>
                                <li><strong>You may limit “prescreened” offers of credit and insurance you get based on
                                information in your credit report.</strong> Unsolicited “prescreened” offers for credit and
                                insurance must include a toll-free phone number you can call if you choose to remove
                                your name and address form the lists these offers are based on. You may opt out with the
                            nationwide credit bureaus at 1-888-5-OPTOUT (1-888-567-8688).</li>
                        </ul>
                    </td>
                </tr>
            </table>

            <table class="table" border="0">
                <tr>
                    <td>
                        <ul>
                            <li>
                                The following FCRA right applies with respect to nationwide consumer reporting
                                agencies:
                                <br/><br/>
                                <h4>CONSUMERS HAVE THE RIGHT TO OBTAIN AS ECURITY FREEZE</h4>
                                <br/>
                                <br/>

                                <p><strong>You have a right to place a “security freeze” on your credit report, which will
                                    prohibit a consumer reporting agency from releasing information in your credit
                                report without your express authorization.</strong> The security freeze is designed to prevent
                                credit, loans, and services from being approved in your name without your consent.
                                However, you should be aware that using a security freeze to take control over who gets
                                access to the personal and financial information in your credit report may delay, interfere
                                with, or prohibit the timely approval of any subsequent request or application you make
                                regarding a new loan, credit, mortgage, or any other account involving the extension of
                            credit.</p>

                            <p>As an alternative to a security freeze, you have the right to place an initial or extended
                                fraud alert on your credit file at no cost. An initial fraud alert is a 1-year alert that is
                                2placed on a consumer’s credit file. Upon seeing a fraud alert display on a consumer’s
                                credit file, a business is required to take steps to verify the consumer’s identity before
                                extending new credit. If you are a victim of identity theft, you are entitled to an extended
                            fraud alert, which is a fraud alert lasting 7 years.</p>

                            <p>A security freeze does not apply to a person or entity, or its affiliates, or collection
                                agencies acting on behalf of the person or entity, with which you have an existing
                                account that requests information in your credit report for the purposes of reviewing or
                                collecting the account. Reviewing the account includes activities related to account
                            maintenance, monitoring, credit line increases, and account upgrades and enhancements.</p>

                        </li>
                    </ul>
                </td>
            </tr>
        </table>


        <table class="table" border="0">
            <tr>
                <td>
                    <ul>
                        <li><strong>You may seek damages from violators.</strong> If a consumer reporting agency, or, in some
                            cases, a user of consumer reports or a furnisher of information to a consumer reporting
                        agency violates the FCRA, you may be able to sue in state or federal court.</li>
                    </ul>
                </td>
            </tr>
        </table>
        
        <table class="table" border="0">
            <tr>
                <td>
                    <ul>
                        <li><strong>Identity theft victims and active duty military personnel have additional rights.</strong> For
                        more information, visit www.consumerfinance.gov/learnmore.</li>
                    </ul>
                </td>
            </tr>
        </table>




        <table class="table table-bordered table-striped">
            <tr>
                <th class="text-center" width="50%" ><h5>Type Of Business:</h5></th>
                <th class="text-center"><h5>CONTACT:</h5></th>
            </tr>

            <tr>
                <td>
                    1.a. Banks, savings associations, and credit unions with total
                    assets of over $10 billion and their affiliates
                    <br/>
                    <br/>
                    b. Such affiliates that are not banks, savings associations, or
                    credit unions also should list, in addition to the CFPB:
                </td>
                <td>
                    a. Consumer Financial Protection Bureau<br/>
                    1700 G Street, N.W.<br/>
                    Washington, DC 20552
                    <br/>
                    <br/>
                    b. Federal Trade Commission<br/>
                    Consumer Response Center<br/>
                    600 Pennsylvania Avenue, N.W.<br/>
                    Washington, DC 20580<br/>
                    (877) 382-4357
                </td>
            </tr>


            <tr>
                <td>
                    2. To the extent not included in item 1 above:
                    a. National banks, federal savings associations, and federal
                    branches and federal agencies of foreign banks
                    <br/><br/>
                    b. State member banks, branches and agencies of foreign banks
                    (other than federal branches, federal agencies, and Insured State
                    Branches of Foreign Banks), commercial lending companies
                    owned or controlled by foreign banks, and organizations
                    operating under section 25 or 25A of the Federal Reserve Act.
                    <br/><br/>
                    c. Nonmember Insured Banks, Insured State Branches of
                    Foreign Banks, and insured state savings associations
                    <br/><br/>
                    d. Federal Credit Unions

                </td>
                <td>
                    a. Office of the Comptroller of the Currency<br/>
                    Customer Assistance Group<br/>
                    1301 McKinney Street, Suite 3450<br/>
                    Houston, TX 77010-9050<br/>
                    <br/>


                    b. Federal Reserve Consumer Help Center<br/>
                    P.O. Box 1200<br/>
                    Minneapolis, MN 55480<br/>
                    <br/>

                    c. FDIC Consumer Response Center<br/>
                    1100 Walnut Street, Box #11<br/>
                    Kansas City, MO 64106<br/>
                    <br/>

                    d. National Credit Union Administration<br/>
                    Office of Consumer Financial Protection (OCFP)<br/>
                    Division of Consumer Compliance Policy and Outreach<br/>
                    1775 Duke Street<br/>
                    Alexandria, VA 22314<br/>

                </td>
            </tr>


            <tr>
                <td>
                    3. Air carriers
                </td>
                <td>
                    Asst. General Counsel for Aviation Enforcement & Proceedings<br/>
                    Aviation Consumer Protection Division<br/>
                    Department of Transportation<br/>
                    1200 New Jersey Avenue, S.E.<br/>
                    Washington, DC 20590
                </td>
            </tr>

            <tr>
                <td>
                    4. Creditors Subject to the Surface Transportation Board
                </td>
                <td>
                    Office of Proceedings, Surface Transportation Board     <br/>
                    Department of Transportation<br/>
                    395 E Street, S.W.<br/>
                    Washington, DC 20423
                </td>
            </tr>

            <tr>
                <td>
                    5. Creditors Subject to the Packers and Stockyards Act, 1921
                </td>
                <td>
                    Nearest Packers and Stockyards Administration area supervisor
                </td>
            </tr>

            <tr>
                <td>
                    6. Small Business Investment Companies
                </td>
                <td>
                    Associate Deputy Administrator for Capital Access<br/>
                    United States Small Business Administration<br/>
                    409 Third Street, S.W., Suite 8200<br/>
                    Washington, DC 20416
                </td>
            </tr>

            <tr>
                <td>
                    7. Brokers and Dealers
                </td>
                <td>
                    Securities and Exchange Commission<br/>
                    100 F Street, N.E.<br/>
                    Washington, DC 20549
                </td>
            </tr>


            <tr>
                <td>
                    8. Federal Land Banks, Federal Land Bank Associations,
                    Federal Intermediate Credit Banks, and Production Credit
                    Associations
                </td>
                <td>
                    Farm Credit Administration<br/>
                    1501 Farm Credit Drive<br/>
                    McLean, VA 22102-5090
                </td>
            </tr>


            <tr>
                <td>
                    9. Retailers, Finance Companies, and All Other Creditors Not
                    Listed Above
                </td>
                <td>
                    Federal Trade Commission<br/>
                    Consumer Response Center<br/>
                    600 Pennsylvania Avenue, N.W.<br/>
                    Washington, DC 20580<br/>
                    (877) 382-4357
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: #ccc; padding: 5px 15px">
                    
                    I acknowledge receipt of the Summary of Your Rights Under the Fair Credit Reporting Act (FCRA) and certify that I have read and understand this document
                    <br/>
                    [{{ $item->signature }}]
                    <hr/>
                    <p style="font-weight: bold; text-align: center">Signed {{ $item->created_at->format('D, d M Y h:i a') }} via {{ $item->ip_address }}</p>

                </td>
            </tr>
        </table>


        
        



        
        <pagebreak></pagebreak>

        <?php // Step 6 [Start] ?>
        <br/><br/>
        <table class="table" border="0">
            <tr>
                <td>
                    <h4 class="text-center">Release:</h4>
                    <p>I authorize Datasource Background Screening Services to perform a nationwide investigation of my criminal history and other information as
                        needed for the purpose of membership screening. The source of the information may come from, but is not limited to: federal, state,
                        county, municipal and other governmental entities public records, i.e., criminal, civil, motor vehicle, and other public records; or
                        other sources as required. It is understood that a photocopy or facsimile copy of this form, or an electronic request by the Company /
                        Organization listed on Application attached above will serve as authorization. By signing below, I authorize the release of all
                        information to the Company / Organization listed on Application attached above, and shall hold Datasource Background Screening Services
                    harmless from any liability or damages for furnishing such information to this Company / Organization.</p>
                    <br/><br/>


                </td>
            </tr>

            <tr>
                <td style="background-color: #ccc; padding: 5px 15px">
                    I acknowledge receipt of this Disclosure and certify that I have read and understand this document
                    <br/>
                    [{{ $item->signature }}]
                    <hr/>
                    <p style="font-weight: bold; text-align: center">Signed {{ $item->created_at->format('D, d M Y h:i a') }} via {{ $item->ip_address }}</p>                        
                </td>
            </tr>
        </table>
        <?php // Step 6 [End] ?>


    </body>
    </html>
