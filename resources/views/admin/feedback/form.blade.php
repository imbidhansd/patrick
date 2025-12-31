<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Company') !!}
            {!! Form::text('company_name', (isset($formObj) ? $formObj->company->company_name : null), ['class' => 'form-control', 'id' => 'company', 'placeholder' => 'Select Company', 'autocomplete' => 'off', 'required' => true]) !!}

            {!! Form::hidden('company_id', isset($formObj) ? $formObj->company_id : null, ['id' => 'company_id_h']) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Customer Name') !!}
            {!! Form::text('customer_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Customer Name', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Customer Email') !!}
            {!! Form::email('customer_email', null, ['class' => 'form-control', 'placeholder' => 'Enter Customer Email', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Customer Phone') !!}
            {!! Form::text('customer_phone', null, ['class' => 'form-control', 'placeholder' => 'Enter Customer Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Images') !!}
            {!! Form::file('media[]', ['class' => 'filestyle', 'accept' => 'application/pdf, image/*', 'multiple' => true]) !!}
        </div>
    </div>
    
    @if (isset($formObj) && count($formObj->feedback_files) > 0)
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
            @foreach($formObj->feedback_files AS $files)
                @if(!is_null($files->media))
                <div class="col-md-1">
                    <div class="media_box">
                        <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}" data-fancybox="gallery">
                            <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $files->media->file_name }}"
                                class='img-thumbnail' />
                        </a>
                        <br />
                        <a class="btn img-del-btn btn-danger btn-xs" data-id="{{ $files->media->id }}"> Remove</a>
                    </div>
                </div>
                @endif
            @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<hr />
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label('Ratings') !!}
            <div id="starHalf"></div>

            {!! Form::hidden('ratings', isset($formObj) ? $formObj->ratings : null, ['id' => 'ratings_h']) !!}
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Review') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('feedback_status', $feedback_statuses, null, ['class' => 'form-control custom-select', 'required' => true]) !!}
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>



@push('page_scripts')
<script src="{{ asset('/thirdparty/ckeditor/ckeditor.js') }}"></script>

<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<!-- rating js -->
<script src="{{ asset('/themes/admin/assets/libs/ratings/jquery.raty-fa.js') }}"></script>

<script src="{{ asset('/themes/admin/assets/libs/autocomplete/jquery.autocomplete.min.js') }}"></script>
<script>
    CKEDITOR.replace('content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });

    $(function (){
        var company = $.map({!! $companies !!}, function (a, e){
            return {value: a, data: e};
        });

        $("#company").autocomplete( {
            lookup: company, lookupFilter:function(a, e, n) {
                return new RegExp("\\b"+$.Autocomplete.utils.escapeRegExChars(n), "gi").test(a.value)
            },
            onSelect:function(a) {
                $("#company_id_h").val(a.data);
            }
        });


        $("#starHalf").raty({
            half: !0,
            starHalf: "fas fa-star-half text-success",
            starOff: "far fa-star text-muted",
            starOn: "fas fa-star text-success",
            score: "{{ isset($formObj) ? $formObj->ratings : '' }}",
            click: function (a,t){
                $("#ratings_h").val(a);
                //alert("ID: "+$(this).attr("id")+"\nscore: "+a+"\nevent: "+t.type);
            }
        });
    });
    
</script>
@endpush