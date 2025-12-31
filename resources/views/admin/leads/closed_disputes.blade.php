@extends('admin.layout')
@section('title', $admin_page_title)



@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm') 
@include('admin.includes._add_button', ['disable_add' => true, 'disable_reorder' => true])

<div class="card-box">
    <div class="table-responsive list-page">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Service Category</th>
                    <th>Zipcode</th>
                    <th>Request Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>
                    <td>
                        {{ $row->full_name }} <br />
                        <small><i>{{ $row->email }}</i></small> <br />
                        <small><i>Company Name: {{ $row->company->company_name }}</i></small>
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
                        <div class="btn-group btn-group-solid">
                            <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal" data-target="#detailModal_{{ $row->id }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>

                            @if ($row->dispute_status == 'in process')
                            <a title="Change Dispute Status" href="javascript:;" data-toggle="modal" data-target="#changeDisputeStatus" data-id="{{ $row->id }}" class="btn btn-secondary btn-xs change_dispute_status"><i class="fas fa-wrench"></i></a>
                            @endif
                        </div>

                        <div class="modal fade" id="detailModal_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title w-100 font-weight-bold text-left">Lead Details - {{ $row->created_at->format(env('DATE_FORMAT')) }}</h4>
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
                                                    <td><b>Service Category Type: </b> {{ 
                                                        $row->service_category_type->title }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Main Category: </b> {{ $row->main_category->title }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Service Category: </b> {{ $row->service_category->title }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Timeframe: </b> {{ $row->timeframe }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Project Address: </b> {{ $row->project_address.', '.$row->state->name.', '.$row->city.' - '.$row->zipcode }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Project Info: </b> {!! $row->content !!}</td>
                                                </tr>

                                                @if (!is_null($row->dispute_status))
                                                <tr>
                                                    <th><h5 class="m-0">Dispute Details:</h5></th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Company Name: </b>
                                                        {!! $row->company->company_name !!}
                                                    </td>
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
                                                        <span class="badge badge-info">{{ ucwords($row->dispute_status) }}</span>
                                                        @elseif ($row->dispute_status == 'approved')
                                                        <span class="badge badge-primary">{{ ucwords($row->dispute_status) }}</span>
                                                        @elseif ($row->dispute_status == 'declined')
                                                        <span class="badge badge-danger">{{ ucwords($row->dispute_status) }}</span>
                                                        @elseif ($row->dispute_status == 'cancelled')
                                                        <span class="badge badge-warning">{{ ucwords($row->dispute_status) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="companyLeadsModal_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
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
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($row->company_lead AS $company_item)
                                                    @if (!is_null($company_item->company_detail))
                                                    <tr>
                                                        <td>{{ $company_item->company_detail->company_name }}</td>
                                                        <td>{{ $company_item->company_detail->email }}</td>
                                                        <td>{{ $company_item->company_detail->main_company_telephone }}</td>
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
                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
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
</div>


<div id="changeDisputeStatus" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white mt-0">Change Dispute Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => url('admin/leads/change-dispute-status'), 'class' => 'module_form', 'id' => 'lead_dispute_form']) !!}

            {!! Form::hidden('lead_id', null, ['id' => 'lead_id']) !!}

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Dispute Status') !!}
                    {!! Form::select('dispute_status', ['in process' => 'In Process', 'approved' => 'Approved', 'declined' => 'Declined'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
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

@stop


@section('page_js')
<script type="text/javascript">
    $(function () {
        $(".reset_button").attr("href", '{{ url("admin/leads/closed-disputes") }}');
        
        $(".change_dispute_status").on("click", function () {
            var lead_id = $(this).data("id");

            $("#changeDisputeStatus #lead_id").val(lead_id);
        });


        $('#main_category_id').change(function () {
            $('#service_category_id').html('');
            $.ajax({
                type: 'post',
                url: '{{ url("admin/service_categories/get_options") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "main_category_id": $(this).val(),
                },
                success: function (data) {
                    data = '<option value="">All</option>' + data;
                    $('#service_category_id').html(data);
                    $('#service_category_id').trigger('change');
                },
                error: function () {
                    alert('error please refresh a page and try again!');
                },
            });
        });
    });
</script>
@stop
