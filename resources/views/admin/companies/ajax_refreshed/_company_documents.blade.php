<div class="row">
    @include('admin.companies.documents.__company_application')
    @include('admin.companies.documents.__company_credit_report')
    @include('admin.companies.documents.__company_online_reputation_report')
    @for($bg_check=1;$bg_check<=$company_item->number_of_owners;$bg_check++)
    @include('admin.companies.documents.__company_owner_bg_check_report')
    @endfor
    @include('admin.companies.documents.__company_pre_screen_report')
    @include('admin.companies.documents.__company_state_business_registration')
    @include('admin.companies.documents.__company_proof_of_ownership')
    @include('admin.companies.documents.__company_income_tax_filling')
    @include('admin.companies.documents.__company_state_licensed')
    @include('admin.companies.documents.__company_country_licensed')
    @include('admin.companies.documents.__company_city_licensed')
    @include('admin.companies.documents.__company_written_warrenty')
    @include('admin.companies.documents.__company_subcontractor_agreement')
    @include('admin.companies.documents.__company_customer_references')

    <?php /* 12-3-2020 Added start */ ?>
    @include('admin.companies.documents.__company_general_liablity_insurance')
    @include('admin.companies.documents.__company_worker_comsensation_insurance')
    <?php /* 12-3-2020 Added end */ ?>
    <?php /* @include('admin.companies.documents.__company_insurances') */ ?>
</div>