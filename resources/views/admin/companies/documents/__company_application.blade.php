@if (!is_null($company_application_file))
<div class="col-md-4">
    <div class="card card-border card-primary">
        <div class="card-header border-primary bg-transparent text-left">
            <h3 class="card-title text-primary mb-0">Company Application</h3>
        </div>

        <div class="card-body">
            <a data-fancybox="gallery_application_file" 
            href="{{ route('secure.file', ['path' => 'media/'.$company_application_file->media->file_name]) }}">
                <i class="far fa-file-pdf font-50"></i>
            </a>
        </div>

        <div class="card-footer bg-transparent border-0">
            <div class="btn-group btn-group-solid">     
                <a class="btn btn-primary btn-sm" href="{{ route('secure.file', ['path' => 'media/'.$company_application_file->media->file_name]) }}" download> <i class="far fa-file-pdf"></i> &nbsp; Download </a>
            </div>
        </div>
    </div>
</div>
@endif