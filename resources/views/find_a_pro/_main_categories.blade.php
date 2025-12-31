<div class="container">
    <!-- Service -->
    <div class="service_inn">
        <h2 class="global_title text-center">{{ $top_level_category_item->title }}</h2>
        
        <div class="list_grp">
            @foreach ($main_category_list AS $main_category_item)
            <div class="check_detail">
                <label id="a">
                    <input type="radio" name="main_category" value="{{ $main_category_item->id }}" class="main_category_selection" />
                    <span class="lbl">{{ $main_category_item->title }}</span> 
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