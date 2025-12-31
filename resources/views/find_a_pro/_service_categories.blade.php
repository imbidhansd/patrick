<div class="container">
    <!-- Service -->
    <div class="service_inn">
        @php
            $title = '';
            
            foreach ($service_category_list AS $service_category_item){
                $title = $service_category_item->service_category_type->title.' '.$service_category_item->main_category->title;
                break;
            }
        @endphp
    
        <h2 class="global_title text-center">{{ $title }}</h2>
        <div class="list_grp">
            @foreach ($service_category_list AS $service_category_item)
            <div class="check_detail">
                <label id="a">
                    <input type="radio" name="service_category" value="{{ $service_category_item->id }}" class="service_category_selection" />
                    <span class="lbl">{{ $service_category_item->title }}</span> 
                </label>
            </div>
            @endforeach
        </div>

        <div class="btn_block">
            <button type="button" class="btn previous_section">Previous</button>
            <?php /* <button type="button" class="btn next_section">Next</button> */ ?>
        </div>
    </div>
</div>