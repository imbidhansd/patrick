@extends('admin.layout')
@section('title', $admin_page_title)



@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button', ['disable_reorder' => true])

<div class="card-box">
    {!! Form::open(['route' => 'update-status', 'class' => 'module_form list-form']) !!}
    {!! Form::hidden ('cur_url', url()->full()) !!}
    {!! Form::hidden ('url_key', $url_key) !!}

    <div class="table-responsive list-page">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        <div class="checkbox checkbox-primary">
                            <input id="chk_all" ng-model="all" type="checkbox">
                            <label for="chk_all">
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Title', $url_key. '.title',
                                $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                                $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>Company</th>
                    <th>Package Code</th>
                    <th>Membership Level</th>
                    <th>Due Today</th>
                    <th>Due On Approval</th>
                    <?php //<th>Setup Fee</th> ?>
                    <?php //<th>BG Screen Fee</th> ?>
                    <?php //<th>Total Amount</th> ?>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>
                    <td>
                        <div class="checkbox checkbox-primary">
                            <input name="ids[]" class="ids" value="{{ $row->id}}" ng-checked="all"
                                id="chk_{{ $row->id}}" type="checkbox">
                            <label for="chk_{{ $row->id}}">
                                {{ $row->title }}
                            </label>
                        </div>
                    </td>
                    <td>{{ $row->company->company_name }}</td>
                    <td>
                        <span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $row->id }}" data-clipboard-target="#var_{{ $row->id }}" class="badge badge-info badge-label variable">{{ $row->package_code }}</span>
                    </td>
                    <td>{{ $row->membership_level->title }}</td>
                    <td>{{ '$'.number_format($row->todays_total_fee, 2) }}</td>
                    <?php //<td>{{ '$'.$row->setup_fee }}</td> ?>
                    <?php //<td>{{ '$'.$row->bg_pre_screen_fee }}</td> ?>
                    <td>{{ '$'.number_format($row->final_total_fee, 2) }}</td>
                    <td>
                        @if ($row->status == 'active')
                        <span class="label label-info">{{ ucfirst($row->status) }}</span>
                        @else
                        <span class="label label-danger">{{ ucfirst($row->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="Send {{ $module_singular_name }} Code Via Email" href="{{ route('send-package-email', ['package' => $row->id]) }}" class="btn btn-success btn-xs"><i class="fa fa-at"></i></a>
                            <a title="{{ $module_singular_name }} Service categories" href="{{ route('package-service-categories', ['slug' => $row->slug]) }}" class="btn btn-info btn-xs"><i class="fa fa-list"></i></a>

                            <a title="Edit {{ $module_singular_name}}"
                                href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            <a title="Delete {{ $module_singular_name}}"
                                href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i
                                    class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>

                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="8">No Records Found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            @include ('admin.includes._tfoot', ['options' => ['active' => 'Active', 'inactive' => 'Inactive',
            'delete' => 'Delete']])

            <?php /*
            <br />
            <a href="{{ url('admin/generate-invoice') }}" class="btn btn-primary btn-sm">Generate Invoice</a> */ ?>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="pagination-area text-center">
                {!! $rows->appends($list_params)->render() !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@include('admin.includes._global_delete_form')

@stop


@section('page_js')
<link href="{{ asset('/') }}/themes/admin/assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />

<script src="{{ asset('/') }}/themes/admin/assets/libs/select2/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
<script type="text/javascript">
    $(function (){
        $('.select2').select2();
        $('.select2').css({'width': '100%'});
        
        var clipboard = new ClipboardJS('.variable');
        clipboard.on('success', function(e) {
            $.toast({
                text: 'Copied to clipboard!',
                icon: 'info',
            })
            e.clearSelection();
        });
    });
</script>
@stop
