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
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Name', $url_key.
                                '.full_name',
                                $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                                $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>Service Category</th>
                    <th>Zipcode</th>
                    <th>Request Date</th>
                    <th>Company Lead count</th>
                    <th>Affiiate</th>
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
                                {{ $row->full_name }} <br />
                                <small><i>{{ $row->email }}</i></small>
                            </label>
                        </div>
                    </td>
                    <td>
                        {{ $row->service_category->title }} <br />
                        <small><i>{{ $row->main_category->title }}</i></small> <br />

                        @if ($row->timeframe == 'Ready To Go - 0 to 2 Weeks')
                        <span class="badge badge-danger">{{ $row->timeframe }}</span>
                        @elseif ($row->timeframe == 'No Urgency - 3 to 6 Weeks')
                        <span class="badge badge-warning">{{ $row->timeframe }}</span>
                        @elseif ($row->timeframe == 'Price Shopping - Price Comparing')
                        <span class="badge badge-info">{{ $row->timeframe }}</span>
                        @endif
                    </td>
                    <td>{{ $row->zipcode }}</td>
                    <td>
                        {{ $row->created_at->format(env('DATE_FORMAT')) }} <br />
                        {{ $row->created_at->format(env('TIME_FORMAT')) }}
                    </td>
                    <td>
                        {{ count($row->company_lead) }} <br />

                        @if (!is_null($row->lead_generate_for))
                        <span class="badge badge-primary">Company Page</span>
                        @else
                        <span class="badge badge-info">Find a pro lead</span>
                        @endif
                    </td>
                    <td>
                        @if (!is_null($row->affiliate))
                        <div class="ava-circle">
                            <span class="ava-initials">{{ $row->affiliate->domain_abbr }}</span>
                        </div>
                        @endif                        
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal"
                                data-target="#detailModal_{{ $row->id }}" class="btn btn-info btn-xs"><i
                                    class="fas fa-eye"></i></a>

                            @if ($row->dispute_status == 'in process')
                            <a title="Change Dispute Status" href="javascript:;" data-toggle="modal"
                                data-target="#changeDisputeStatus" data-id="{{ $row->id }}"
                                class="btn btn-orange btn-xs change_dispute_status"><i class="fas fa-wrench"></i></a>
                            @endif

                            @if (count($row->company_lead) > 0)
                            <a href="javascript:;" data-toggle="modal" data-target="#companyLeadsModal_{{ $row->id }}"
                                title="Company Leads" class="btn btn-teal btn-xs"><i class="fas fa-users"></i></a>
                            @endif

                            <a title="Edit {{ $module_singular_name}}"
                                href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>

                            <a title="View Logs"
                                href="javascript:;" data-name="{{ $row->full_name }}" data-email="{{ $row->email }}" data-correlationid="{{ $row->correlation_id }}" data-toggle="modal" data-target="#companyLeadsLogsModal"
                                class="btn btn-primary btn-xs btn-view-logs"><i class="fas fa-file-alt"></i></a>

                            <a title="Delete {{ $module_singular_name}}"
                                href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i
                                    class="fas fa-trash-alt"></i></a>
                        </div>

                        <div class="modal fade" id="detailModal_{{ $row->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title w-100 font-weight-bold text-left">Lead Details -
                                            {{ $row->created_at->format(env('DATE_FORMAT')) }}</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="table-responsive111">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <td><b>Name: </b> {{ $row->full_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Email: </b> {{ $row->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Phone: </b> {{ $row->phone }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Service Category Type: </b>
                                                        {{ $row->service_category_type->title }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Main Category: </b> {{ $row->main_category->title }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Service Category: </b> {{ $row->service_category->title }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Timeframe: </b> {{ $row->timeframe }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Project Address: </b>
                                                        {{ $row->project_address.', '.$row->state->name.', '.$row->city.' - '.$row->zipcode }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Project Info: </b> {!! $row->content !!}</td>
                                                </tr>

                                                @if (!is_null($row->dispute_status))
                                                <tr>
                                                    <th>
                                                        <h5 class="m-0">Dispute Details:</h5>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Dispute Content: </b>
                                                        {!! $row->dispute_content !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Get call?</b> {{ ucfirst($row->is_phone) }}
                                                        @if ($row->is_phone == 'yes')
                                                        <br />
                                                        <b>How many time?</b> {{ $row->no_of_phone }}
                                                        @endif

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Get email?</b> {{ ucfirst($row->is_email) }}
                                                        @if ($row->is_email == 'yes')
                                                        <br />
                                                        <b>How many time?</b> {{ $row->no_of_email }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Dispute Status:</b>

                                                        @if ($row->dispute_status == 'in process')
                                                        <span
                                                            class="badge badge-info">{{ ucwords($row->dispute_status) }}</span>
                                                        @elseif ($row->dispute_status == 'approved')
                                                        <span
                                                            class="badge badge-primary">{{ ucwords($row->dispute_status) }}</span>
                                                        @elseif ($row->dispute_status == 'declined')
                                                        <span
                                                            class="badge badge-danger">{{ ucwords($row->dispute_status) }}</span>
                                                        @elseif ($row->dispute_status == 'cancelled')
                                                        <span
                                                            class="badge badge-warning">{{ ucwords($row->dispute_status) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="companyLeadsModal_{{ $row->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title w-100 font-weight-bold text-left">Company get lead</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        @if (count($row->company_lead) > 0)
                                        <div class="table-responsive111">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Company Name</th>
                                                        <th>Level</th>
                                                        <?php /* <th>Email</th> */?>
                                                        <th>Phone</th>
                                                        <th>Lead Generate Date</th>
                                                        <th>Read</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($row->company_lead AS $company_item)
                                                    @if (!is_null($company_item->company_detail))
                                                    <tr>
                                                        <td>{{ $company_item->company_detail->company_name }}</td>
                                                        <td>{{ $company_item->company_detail->membership_level->title }}
                                                        </td>
                                                        <?php /* <td>{{ $company_item->company_detail->email }}</td> */?>
                                                        <td>{{ $company_item->company_detail->main_company_telephone }}
                                                        </td>
                                                        <td>{{ $company_item->created_at->format(env('DATE_FORMAT')) }}
                                                        </td>
                                                        <td>
                                                            @if ($company_item->is_checked == 'yes')
                                                            <span
                                                                class="badge badge-success">{{ ucfirst($company_item->is_checked) }}</span>
                                                            @elseif ($company_item->is_checked == 'no')
                                                            <span
                                                                class="badge badge-danger">{{ ucfirst($company_item->is_checked) }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @else
                                        No one company get lead yet!
                                        @endif
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6">No Records Found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            @include ('admin.includes._tfoot', ['options' => ['delete' => 'Delete']])
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


<div id="changeDisputeStatus" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Change Dispute Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => url('admin/leads/change-dispute-status'), 'class' => 'module_form', 'id' =>
            'lead_dispute_form']) !!}

            {!! Form::hidden('lead_id', null, ['id' => 'lead_id']) !!}

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Dispute Status') !!}
                    {!! Form::select('dispute_status', ['in process' => 'In Process', 'approved' => 'Approved',
                    'declined' => 'Declined'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect change_region_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="companyLeadsLogsModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                <h5 class="modal-title text-white mt-0">Lead Logs</h5>
                <label for="chk_9164">
                    <span id="lbl_name"></span>
                    <small><i id="lbl_email"></i></small>
                </label>
                </div>               
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="logsContainer">               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@stop


@section('page_js')
<script type="text/javascript">
$(function() {
    $(".change_dispute_status").on("click", function() {
        var lead_id = $(this).data("id");
        $("#changeDisputeStatus #lead_id").val(lead_id);
    });

    $(".btn-view-logs").on("click", function() {   
    
        // Clear the logs container and show a loading symbol
        var logsContainer = $('#logsContainer');
        logsContainer.empty().append('<div class="loading">Loading...</div>');
        

        let correlationid = $(this).data("correlationid");
        let name = $(this).data("name");
        let email = $(this).data("email");
        $('#lbl_name').html(name);
        $('#lbl_email').html(email);

        $.ajax({
            type: 'get',
            url: '{{ url("admin/leads/get-logs") }}',
            data: {
                "_token": "{{ csrf_token() }}",
                "correlationid": correlationid,
            },
            success: function(data) {   
                displayLogs(data);
            },
            error: function() {
                alert('error please refresh a page and try again!');
            },
        });
    });

    $('#main_category_id').change(function() {
        $('#service_category_id').html('');
        $.ajax({
            type: 'post',
            url: '{{ url("admin/service_categories/get_options") }}',
            data: {
                "_token": "{{ csrf_token() }}",
                "main_category_id": $(this).val(),
            },
            success: function(data) {
                data = '<option value="">All</option>' + data;
                $('#service_category_id').html(data);
                $('#service_category_id').trigger('change');
            },
            error: function() {
                alert('error please refresh a page and try again!');
            },
        });
    });

    function displayLogs(logs) {
        var logsContainer = $('#logsContainer');
        logsContainer.empty();
        if (!Array.isArray(logs)) {
            // If logs are not in the expected format, display a message
            logsContainer.empty().append('<div class="error">Logs data is not available.</div>');
            return;
        }
        logs.forEach(function(log) {
            var logEntry = $('<div>').addClass('log-entry');
            
            // Check if log level is 400 and apply styling if true
            if (log.level === '400') {
                logEntry.addClass('log-level-400');
            }
            
            var logMessage = $('<div>').addClass('log-message').text(log.message);
            var logContextContainer = $('<div>').addClass('log-context-container');
            var logContextToggle = $('<button>').addClass('log-context-toggle').text('Expand');
            var logContextFormatted = $('<pre>')
                .addClass('log-context')
                .text(JSON.stringify(JSON.parse(log.context), null, 2)); // Format with 2 spaces for indentation
            
            logContextToggle.on('click', function() {
                logContextFormatted.slideToggle(); // Toggle animation for context section
                $(this).text($(this).text() === 'Expand' ? 'Collapse' : 'Expand');
            });
            
            logContextContainer.append(logContextToggle, logContextFormatted.hide());
            logEntry.append(logMessage, logContextContainer);
            logsContainer.append(logEntry);
        });
    }


});
</script>
<style>
    .log-entry {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
        .log-level {
            font-weight: bold;
        }
        .log-message {
            margin-top: 5px;
            margin-bottom: 10px;
        }
        .log-context {
            font-style: italic;
            color: #666;
        }
        .log-level-400 {
            background-color: #ffcccc; /* Light shade of red */
            border-left: 4px solid red; /* Highlighted left border for emphasis */
            padding-left: 10px; /* Add left padding to align with border */
        }
        /* Styling for the Expand Button */
        .log-context-toggle {
            background-color: #3498db; /* Blue color for the button */
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .log-context-toggle:hover {
            background-color: #2980b9; /* Slightly darker blue on hover */
        }
</style>
@stop