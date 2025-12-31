<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                @if (isset($breadCrumbArray) && count ($breadCrumbArray) > 0)
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin') }}"><i class="fas fa-home"></i></a></li>
                    @foreach ($breadCrumbArray as $itemKey=>$itemVal)
                    @if ($itemVal != '')
                    <li class="breadcrumb-item"><a href="{{ url($itemVal) }}">{!! $itemKey !!}</a></li>
                    @else
                    <li class="breadcrumb-item active">
                        {!! $itemKey !!}
                    </li>
                    @endif
                    @endforeach
                </ol>
                @endif
            </div>
            <h4 class="page-title">{!! $admin_page_title !!}</h4>
        </div>
    </div>
</div>
