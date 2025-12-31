<?php 
    $paid_and_lead_levels = \App\Models\MembershipLevel::where([
        'paid_members' => 'yes',
        'lead_access' => 'yes',
    ])->whereNotIn('id', [4, 5])->orderBy('sort_order', 'ASC')->active()->get();
?>
<div class="text-center">
    <h3 class="mb-4 mt-3">Choose your perfect plan</h3>
    <?php /* <p>
        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
    </p> */ ?>
</div>

@if (!is_null($paid_and_lead_levels) && count($paid_and_lead_levels) > 0)
<div class="row mt-5 justify-content-center">
    @foreach ($paid_and_lead_levels as $level_item)
    <div class="col-lg-4">

        <div class="card-pricing ribbon-box card mb-4 pb-4">
            @if ($level_item->is_popular == 'yes')
            <div class="ribbon-two ribbon-two-primary"><span>Popular</span></div>
            @endif
            <div class="card-plan-header text-center p-4">
                <h4 class="card-plan-title font-weight-normal text-{{ $level_item->color }} text-uppercase">
                    <strong>{{ $level_item->title }}</strong>
                </h4>
            </div>
            
            {!! $level_item->short_content !!}

            <div class="text-center">
                <a href="javascript:;" data-id="{{ $level_item->id }}" data-membership_level_id="{{ $level_item->id }}" data-type="annual_membership"
                    class="btn btn-{{ $level_item->color }} width-xl membership_selection_btn">Choose <i class="fas fa-angle-right"></i></a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
