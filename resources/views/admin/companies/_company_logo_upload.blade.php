<div class="row">
    <div class="col-md-12">
        <a class="button actionUpload">
            <span>Upload Logo</span>
            <input type="file" id="upload" class="filestyle" value="Choose Image" accept="image/*">
        </a>
        
        <div id="main-cropper"></div>
        <div class="text-center">
            <button class="btn btn-sm btn-primary hide" id="showResult">Crop Logo</button>
        </div>
    </div>
    
    <div class="col-md-12 text-center">
        <div id="result-wrapper">
            <h5 class="mt-0">Logo Preview</h5>

            @if (!is_null($company_item->company_logo))
            <a href="{{ asset('/') }}uploads/media/{{ $company_item->company_logo->file_name }}"
                       data-fancybox="gallery">
                <img src="{{ asset('/') }}/uploads/media/{{ $company_item->company_logo->file_name }}" id="result" />
            </a>
            @else
            <img src="" id="result" class="hide" />
            @endif


            @if (!is_null($company_item->company_approval_status) && $company_item->company_approval_status->company_logo == 'in process')
                <br />
                <label class="btn btn-danger btn-xs mt-2">Pending Approval</label>
            @endif
            
            
        </div>
        <div class="clearfix">&nbsp;</div>
        <button class="btn btn-sm btn-primary hide" id="uploadLogo">Upload Logo</button>
    </div>
</div>



@push('additional_scripts')

<style type="text/css">
    #modal {
        float: left;
        z-index: 100;
        height: 350px;
        width: 350px;
        background: white;
        border: 1px solid #ccc;
        -moz-box-shadow: 0 0 3px #ccc;
        -webkit-box-shadow: 0 0 3px #ccc;
        box-shadow: 0 0 3px #ccc;
        text-align: center;
    }

    .croppie-container{ width: 100%; height: auto !important; }

    #main-cropper{
        display: none;
        margin-top: 20px;
    }
    #result-wrapper { width: 100%; padding: 20px; text-align: center; }
    #result-wrapper img{border: 2px solid #ddd; padding: 5px; margin-top: 25px; }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('/thirdparty/croppie/croppie.min.css') }}" />
<script type="text/javascript" src="{{ asset('/thirdparty/croppie/croppie.min.js') }}"></script>

<script>
    
    var basic = $("#main-cropper").croppie({
        viewport: {
            width: '{{ env('MAX_LOGO_WIDTH') }}',
            height: '{{ env('MAX_LOGO_HEIGHT') }}',
            type: 'square'
        },
        boundary: {
            width: 420,
            height: 280
        },
        showZoomer: true,
        enableExif: true,
        enforceBoundary: false,
    });

    function readFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#main-cropper").show().croppie("bind", {
                    url: e.target.result
                });
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on("change", ".actionUpload input", function () {
        readFile(this);
        $('#showResult').removeClass('hide');
    });
    
    $(document).on("click", "#showResult", function () {
        $("#main-cropper").croppie("result", {
            type: "canvas",
            size: "viewport",
            circle: false,
        }).then(function (resp) {
            $("#result").attr("src", resp);

            $('#result').removeClass('hide');
            $('#uploadLogo').removeClass('hide');

        });
    });

    $(document).on("click", "#uploadLogo", function () {
        $("#main-cropper").croppie("result", {
            type: "canvas",
            size: "viewport",
            circle: false,
        }).then(function (resp) {
            $("#result").attr("src", resp);

            var data = new FormData();
            data.append('file', resp);
            data.append('_token', '{{ csrf_token() }}');
            data.append('company_id', '{{ $company_item->id }}');


            @if (isset($admin_form) && $admin_form)
            var logo_upload_url = '{{ url("admin/companies/upload-company-logo") }}';    
            @else
            var logo_upload_url = '{{ route("upload-company-logo") }}';
            @endif
            
            $("#uploadLogo").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $("#uploadLogo").attr('disabled', true);
            
            $.ajax({
                url: logo_upload_url,
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (data) {
                    @if (isset($admin_form) && $admin_form)
                    Swal.fire({
                        title: 'Success',
                        text: 'Company logo has been updated successfully.',
                        type: 'success',
                    });
                    
                    $("#company_logo_update").html(data);
                    
                    $(".filestyle").each(function() {
                        var t = $(this),
                        i = {
                                input: "false" !== t.attr("data-input"),
                                htmlIcon: t.attr("data-icon"),
                                buttonBefore: "true" === t.attr("data-buttonBefore"),
                                disabled: "true" === t.attr("data-disabled"),
                                size: t.attr("data-size"),
                                text: t.attr("data-text"),
                                btnClass: t.attr("data-btnClass"),
                                badge: "true" === t.attr("data-badge"),
                                dragdrop: "false" !== t.attr("data-dragdrop"),
                                badgeName: t.attr("data-badgeName"),
                                placeholder: t.attr("data-placeholder")
                            };
                        t.filestyle(i)
                    });
                    @else
                    
                    //window.location.href = '{{ url("company-profile") }}';
                    window.location.reload();
                    
                    $("#uploadLogo").html('Upload Logo');
                    $("#uploadLogo").attr('disabled', false);
                    @endif
                },
                error: function () {
                    type = "error";
                    html = "Error while processing, ";

                    Swal.fire({
                        type: type,
                        html: html
                    });
                }
            });

        });
    });

</script>
@endpush

