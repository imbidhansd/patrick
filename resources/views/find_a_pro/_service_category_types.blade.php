<div class="container">
    <!-- Service -->
    <div class="service_inn">
        @php
            $title = '';
            if (count($service_category_types_list) > 1){
                $title = 'Residential or Commercial Application?';
            } else {
                foreach ($service_category_types_list AS $service_category_types_item){
                    $title = $service_category_types_item->title.' Application?';
                }
            }
        @endphp
        <h2 class="global_title">{{ $title }}</h2>
        <div class="row">
            @foreach ($service_category_types_list AS $service_category_types_item)
            <div class="{{ $loop->first ? 'col-xl-2 offset-xl-4 col-lg-2 offset-lg-4 col-md-4 offset-md-2 col-xs-6 col-6' : 'col-xl-2 col-lg-2 col-md-4 col-xs-6 col-6' }}">
                <a href="javsacript:;" data-id="{{ $service_category_types_item->id }}" class="service_category_type_selection">
                    <div class="cat_block">
                        @if(!is_null($service_category_types_item->media))
                        <i>
                            <img src="{{ asset('/uploads/media/'.$service_category_types_item->media->file_name) }}" width="100px" height="77px;" alt="">
                        </i>
                        @endif
                        <h4>{{ $service_category_types_item->title }}</h4>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <?php /* <div class="btn_block">
            <button type="button" class="btn previous_section">Previous</button>
            <button type="button" class="btn next_section">Next</button>
        </div> */ ?>
    </div>
</div>