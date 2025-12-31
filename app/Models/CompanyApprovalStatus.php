<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyApprovalStatus extends Model {

    //
    protected $fillable = [
        'company_id',
        'background_check_pre_screen_fees', 'one_time_setup_fee',
        'background_check_submittal', 'background_check_process', 'pre_screening_process',
        'online_application',
        'registered_legally_to_state', 'proof_of_ownership', 'income_tax_filling',
        'state_business_registration_file', 'state_licensing', 'country_licensing', 'city_licensing',
        'work_agreements_warranty',
        'insurance_documents',
        'general_liablity_insurance_file',
        'worker_comsensation_insurance_file',
        'customer_references',
        'subcontractor_agreement',
        'company_logo', 'company_logo_reject_note',
        'company_bio', 'company_bio_reject_note',
        'owner_1_bg_check_document_status',
        'owner_2_bg_check_document_status',
        'owner_3_bg_check_document_status',
        'owner_4_bg_check_document_status',
        'online_reputation_report_status',
        'credit_check_report_status'
    ];
    protected $table = 'company_approval_statuses';

    // Foreign References
    public function company() {
        return $this->belongsTo('\App\Models\Company', 'company_id', 'id');
    }

    public function showStatusIcon($field_val) {
        $tooltip_str = 'data-toggle="tooltip" data-placement="top" title="' . (($field_val == "in process") ? "In Progress" : ucwords($field_val)) . '"';

        if ($field_val == 'pending') {
            return '<i ' . $tooltip_str . ' class="fas fa-exclamation-circle font-20 text-danger ml-2"></i>';
        } elseif ($field_val == 'in process') {
            return '<i ' . $tooltip_str . ' class="fas fa-exclamation-triangle font-20 text-warning ml-2"></i>';
        } elseif ($field_val == 'completed') {
            return '<i ' . $tooltip_str . ' class="fas fas fa-check-circle font-20 text-success ml-2"></i>';
        }
    }

    public function getStatusColorClass($field_val) {
        return '';
        if ($field_val == 'pending') {
            return 'text-danger';
        } elseif ($field_val == 'in process') {
            return 'text-warning';
        } elseif ($field_val == 'completed') {
            return 'text-success';
        }
    }

    public static function changeCompanyApprovalStatus($company_id, $arr) {
        $obj = self::where('company_id', $company_id)->first();

        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as $field => $val) {
                $obj->$field = $val;
            }
            $obj->save();
        }
    }

}
