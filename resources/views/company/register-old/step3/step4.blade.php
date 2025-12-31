<h5>Would you like to add any of these additional service category listings? *</h5>

<?php
    $yes_checked = '';
    $no_checked = '';
    if (!isset($selected_include_rest_categories)){
        $yes_checked = 'checked';
    } elseif (isset($selected_include_rest_categories) && $selected_include_rest_categories == 'yes'){
        $yes_checked = 'checked';
    }else{
        $no_checked = 'checked';
    }
?>

<div class="radio radio-success">
    <input type="radio" name="include_rest_categories" class="include_rest_categories" id="include_rest_categories_yes"
        value="yes" {{ $yes_checked }}>
    <label for="include_rest_categories_yes">
        Yes
    </label>
</div>

<div class="radio radio-danger">
    <input type="radio" name="include_rest_categories" class="include_rest_categories last_input"
        id="include_rest_categories_no" value="no" {{ $no_checked }}>
    <label for="include_rest_categories_no">
        No
    </label>
</div>


<div class="card">
    <div class="card-body rest_service_category_container">
    </div>
</div>


<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back-btn">Back</button>
<button type="button" class="btn btn-info float-md-right next_btn_3_4 current_step_submit_btn">Next</button>
