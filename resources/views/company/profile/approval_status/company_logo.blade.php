@if ($company_approval_status->company_logo != 'not required')
<li
    class="list-group-item d-flex justify-content-between align-items-center {{ $company_approval_status->getStatusColorClass($company_approval_status->company_logo) }}">

    @if ($company_approval_status->company_logo == 'pending')
    <a href="javascript:;" data-toggle="modal" data-target="#companyLogoModal">Company Logo</a>
    @elseif ($company_approval_status->company_logo == 'in process')
    <a href="javascript:;" data-toggle="modal" data-target="#companyLogoStatusModal">Company Logo</a>
    @elseif ($company_approval_status->company_logo == 'completed')
    <a href="javascript:;" data-toggle="modal" data-target="#companyLogoStatusModal">Company Logo</a>
    @else
    Company Logo
    @endif

    {!! $company_approval_status->showStatusIcon($company_approval_status->company_logo) !!}
</li>


@if ($company_approval_status->company_logo == 'pending' || $company_approval_status->company_logo == 'in process')
<div class="modal fade" id="companyLogoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Company Logo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-left">
                <div class="alert alert-danger">
                    <div class="row">
                        <div class="col-md-1 text-center"><i class="fas fa-exclamation-triangle text-danger alert-icon"></i></div>
                        <div class="col-md-11">As per our policy, logos should not contain <strong>phone numbers</strong>, <strong>website addresses</strong>, <strong>custom text numbers</strong>, or any other <strong>contact information</strong>.</div>
                    </div>
                    
                    
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <a class="button actionUploadPopup">
                            <span>Upload Logo</span>
                            <input type="file" id="uploadPopup" class="filestyle" value="Choose Image" accept="image/*">
                        </a>
                        <div id="main-cropper-popup"></div>
                        <div class="text-center">
                            <button class="btn btn-sm btn-primary hide" id="showResultPopup">Crop Logo</button>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div id="result-wrapper">
                            <h5 class="mt-0">Logo Preview</h5>

                            @if (!is_null($company_item->company_logo))
                            <a href="{{ asset('/') }}uploads/media/{{ $company_item->company_logo->file_name }}"
                               data-fancybox="gallery">
                                <img src="{{ asset('/') }}/uploads/media/{{ $company_item->company_logo->file_name }}" id="resultPopup" />
                            </a>
                            @else
                            <img src="" id="resultPopup" class="hide" />
                            @endif
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <button class="btn btn-sm btn-primary hide" id="uploadLogoPopup">Upload Logo</button>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>

                <div class="text-center text-danger"> Need help? Call Member Support at <a href="tel: 720-445-4400" class="text-info"><strong>720-445-4400</strong></a></div>
            </div>
        </div>
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

    #main-cropper-popup{
        display: none;
        margin-top: 20px;
    }
    #result-wrapper { width: 100%; padding: 20px; text-align: center; }
    #result-wrapper img{border: 2px solid #ddd; padding: 5px; margin-top: 25px; }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('/thirdparty/croppie/croppie.min.css') }}" />
<script type="text/javascript" src="{{ asset('/thirdparty/croppie/croppie.min.js') }}"></script>

<script>
    var basic = $("#main-cropper-popup").croppie({
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
        enableExif: true
    });

    function readFilePopup(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#main-cropper-popup").show().croppie("bind", {
                    url: e.target.result
                });
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on('change', "#uploadPopup", function(){
        readFilePopup(this);
        $('#showResultPopup').removeClass('hide');
    });

    
    $("#showResultPopup").click(function () {
        $("#main-cropper-popup").croppie("result", {
            type: "canvas",
            size: "viewport",
            circle: false
        }).then(function (resp) {
            $("#resultPopup").attr("src", resp);

            $('#resultPopup').removeClass('hide');
            $('#uploadLogoPopup').removeClass('hide');

        });
    });

    $("#uploadLogoPopup").click(function () {
        $("#main-cropper-popup").croppie("result", {
            type: "canvas",
            size: "viewport",
            circle: false
        }).then(function (resp) {
            $("#result").attr("src", resp);

            var data = new FormData();
            data.append('file', resp);
            data.append('_token', '{{ csrf_token() }}');
            data.append('company_id', '{{ $company_item->id }}');

            var logo_upload_url = '{{ route("upload-company-logo") }}';
            
            $("#uploadLogoPopup").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $("#uploadLogoPopup").attr('disabled', true);
            
            $.ajax({
                url: logo_upload_url,
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (data) {
                    //window.location.href = '{{ url("company-profile") }}';
                    window.location.reload();
                    
                    $("#uploadLogoPopup").html('Upload Logo');
                    $("#uploadLogoPopup").attr('disabled', false);
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
@endif



@if ($company_approval_status->company_logo == 'in process' || $company_approval_status->company_logo == 'completed')
<div class="modal fade" id="companyLogoStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Company Logo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                @if ($company_approval_status->company_logo == 'in process')
                <h2>Pending Review</h2>
                <p class="font-15">Thank you for uploading your Company Logo!</p>
                @elseif ($company_approval_status->company_logo == 'completed')
                <p class="font-15 font-bold">Thank you for uploading your Company Logo!</p>
                @endif
                <h5>Thank You!</h5>

                @if ($company_approval_status->company_logo == 'in process')
                <div class="btn-group btn-group-solid">
                    <a href="javascript:;" class="btn btn-primary btn-sm close_current_modal" data-toggle="modal" data-target="#companyLogoModal">Upload Another Logo</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif


@endif
