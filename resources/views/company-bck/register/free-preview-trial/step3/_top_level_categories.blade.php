@if (isset($top_level_categories) && count($top_level_categories) > 0)
<h5 class="service_categories">Select 1 or more Service Categories : *</h5>

<div class="row">

    <?php
        $top_level_category_arr = array_chunk($top_level_categories->toArray(), ceil(count($top_level_categories) / 3));
    ?>
    @foreach ($top_level_category_arr as $arr_item)

    <div class="col-md-4">
        <ul>
            @foreach ($arr_item as $item)
            <li>
                <div class="checkbox checkbox-primary">
                    <input name="top_level_category_ids[]" class="chk_top_level_category_id"
                        data-text="{{ $item['title'] }}" id="top_level_category_{{ $item['id'] }}"
                        value="{{ $item['id'] }}" type="checkbox">
                    <label for="top_level_category_{{ $item['id'] }}">
                        {{ $item['title'] }}
                    </label>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endforeach
</div>

<div class="clearfix">&nbsp;</div>
<button type="button" data-step="1" data-step="2" class="btn btn-info float-md-right next_btn_3_1">Next</button>

@else
<h2 class="text-center text-danger">No Category Found</h2>
@endif
