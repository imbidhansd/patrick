@if (!is_null($company_licensing) && $company_licensing->income_tax_filling != 'Sole Proprietor' && $company_licensing->articles_of_incorporation == 'yes')
<div class="col-md-6 col-lg-4">
    <div class="card card-border card-primary">
        <div class="card-header border-primary bg-transparent text-left">
            <h3 class="card-title text-primary mb-0">Income Tax Filling</h3>
        </div>

        <div class="card-body">
            @if (!is_null($company_licensing->articles_of_incorporation_file_id))
            <a data-fancybox="gallery_articles_of_incorporation" href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->articles_of_incorporation_file->media->file_name]) }}">
                <i class="far fa-file-pdf font-50"></i>
            </a>

            @else
            Not Yet Received
            @endif
        </div>

        @if (!is_null($company_licensing->articles_of_incorporation_file_id))
        <div class="card-footer bg-transparent border-0">
            <div class="btn-group btn-group-solid">
                <a class="btn btn-primary btn-sm waves-effect waves-light" href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->articles_of_incorporation_file->media->file_name]) }}" download>
                    <i class="far fa-file-pdf"></i> &nbsp; Download
                </a>
                <button class="btn btn-primary btn-sm waves-effect waves-light">Approved</button>
            </div>
        </div>
        @endif
    </div>
</div>
@endif