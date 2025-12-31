<div class="row">
    @foreach ($zipcode AS $zip_code_item)
    <div class="col-sm-4">
        <ul class="pl20">
            <li>
                <div class="checkbox checkbox-primary">
                    <input name="zipcode_item[]" value="{{ $zip_code_item['zip_code'] }}" id="miles_{{ $zip_code_item['zip_code'] }}" type="checkbox" checked />
                    <label for="miles_{{ $zip_code_item['zip_code'] }}">
                        {{ $zip_code_item['zip_code'].', '.$zip_code_item['city'].', ('.$zip_code_item['distance'].' miles)' }}
                    </label>
                </div>
            </li>
        </ul>
    </div>
    @endforeach
</div>
