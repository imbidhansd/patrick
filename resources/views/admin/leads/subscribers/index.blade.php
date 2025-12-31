@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button', ['disable_reorder' => true, 'disable_add' => true])

<div class="card-box">
    <div class="table-responsive list-page">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>
                    <td>{{ $row->full_name }}</td>
                    <td>{{ $row->email }}</td>
                    <td>
                        @if ($row->lead_activated == 'no')
                        <p class="text-danger">Confirmation Pending</p>
                        @elseif ($row->subscribe == 'yes')
                        <p>
                            Subscribed
                            @if (!is_null($row->subscribe_at))
                            {{ \App\Models\Custom::date_formats($row->subscribe_at, env('DB_DATETIME_FORMAT'), env('DATETIME_FORMAT')) }}
                            @endif
                        </p>
                        @elseif ($row->subscribe == 'no')
                        <p>
                            Unsubscribe
                            @if (!is_null($row->unsubscribe_at))
                            {{ \App\Models\Custom::date_formats($row->unsubscribe_at, env('DB_DATETIME_FORMAT'), env('DATETIME_FORMAT')) }}
                            @endif
                        </p>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="View" href="javascript:;" data-toggle="modal" data-target="#detailModal_{{ $row->id }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                        </div>
                        
                        <div class="modal fade" id="detailModal_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title w-100 font-weight-bold text-left">Subscriber Information</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <h4>Basic Info</h4>
                                                
                                                @php
                                                    $url = url('find-a-pro');
                                                    if (!is_null($row->lead_generate_for)){
                                                        $url = url('/', ['company_slug' => $row->lead_generate_for_company->slug]);
                                                    }
                                                @endphp
                                                
                                                
                                                {!! Form::model($row, ['url' => 'admin/manage_subscribers/update-basic-info', 'class' => 'module_form']) !!}
                                                {!! Form::hidden('lead_id', $row->id) !!}
                                                <div class="form-group">
                                                    {!! Form::label('Name') !!}
                                                    {!! Form::text('full_name', null, ['class' => 'form-control', 'readonly' => true]) !!}
                                                </div>
                                                
                                                <div class="form-group">
                                                    {!! Form::label('Email Address') !!}
                                                    {!! Form::email('email', null, ['class' => 'form-control', 'readonly' => true]) !!}
                                                </div>
                                                
                                                <div class="form-group">
                                                    {!! Form::label('Ad Tracking') !!}
                                                    {!! Form::text('ad_tracking', null, ['class' => 'form-control', 'placeholder' => 'Ad Tracking', 'required' => true]) !!}
                                                </div>
                                                
                                                <div class="form-group">
                                                    {!! Form::label('Signup url') !!}
                                                    {!! Form::text('url', $url, ['class' => 'form-control', 'readonly' => true]) !!}
                                                </div>
                                                
                                                <div class="form-group">
                                                    {!! Form::label('Additional Notes') !!}
                                                    {!! Form::text('additional_notes', null, ['class' => 'form-control', 'placeholder' => 'Additional Notes']) !!}
                                                </div>
                                                
                                                <button type="submit" class="btn btn-primary waves-effect">Save</button>
                                                {!! Form::close() !!}
                                            </div>
                                            <div class="col-md-7">
                                                <h4>Last Follow Up Received</h4>
                                                
                                                @if ($row->lead_activated == 'no')
                                                <p class="text-danger">Confirmation Pending</p>
                                                @else
                                                    @php
                                                        $last_followup = \App\Models\LeadFollowUpEmail::where([['lead_id', $row->id], ['status', 'sent']])->orderBy('id', 'DESC')->first();
                                                    @endphp
                                                    
                                                    @if (!is_null($last_followup))
                                                    <p>Sent: {{ \App\Models\Custom::date_formats($last_followup->send_at, env('DB_DATETIME_FORMAT'), env('DATETIME_FORMAT')) }}</p>
                                                    @endif
                                                @endif
                                                
                                                <hr />
                                                <p>
                                                    <b>Date Added: </b> {{ $row->created_at->format(env('DATE_FORMAT')) }}
                                                </p>
                                                <hr />
                                                <p>
                                                    <b>Subscription Source</b> <br />
                                                    {{ $url }} <br />
                                                    @if ($row->lead_activated == 'yes')
                                                    Verified on {{ \App\Models\Custom::date_formats($row->lead_active_date, env('DB_DATETIME_FORMAT'), env('DATETIME_FORMAT')) }}
                                                    @endif
                                                </p>
                                                <hr />
                                                <p>
                                                    <b>Location</b> <br />
                                                    @if (!is_null($row->project_address))
                                                    {{ $row->project_address }},
                                                    @endif
                                                    
                                                    @if (!is_null($row->state_id))
                                                    {{ $row->state->name }},
                                                    @endif
                                                    
                                                    @if (!is_null($row->city))
                                                    {{ $row->city }},
                                                    @endif
                                                    
                                                    @if (!is_null($row->zipcode))
                                                    {{ $row->zipcode }}
                                                    @endif
                                                    
                                                    <br />
                                                    
                                                    @if (!is_null($row->ip_address))
                                                    IP Address: {{ $row->ip_address }}
                                                    @endif
                                                </p>
                                                <hr />
                                                
                                                <h5>Subscriber Activity</h5>
                                                <div class="table-responsive111">
                                                    <table class="table">
                                                        <tr>
                                                            <td>
                                                                <b>Sent Confirmation: </b>
                                                                <br />
                                                                {{ $row->created_at->format(env('DATETIME_FORMAT')) }}
                                                            </td>
                                                            <td>
                                                                @if ($row->lead_activated == 'no')
                                                                <a href="javascript:;" class="send_confirmation_email" data-lead_id="{{ $row->id }}">Confirmation Email</a>
                                                                @else
                                                                Confirmation Email
                                                                @endif
                                                                
                                                                @if (!is_null($row->lead_generate_for))
                                                                - {{ $row->lead_generate_for_company->company_name }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @if (count($row->lead_follow_up_emails) > 0)
                                                        @foreach ($row->lead_follow_up_emails AS $followup_email_item)
                                                        <tr>
                                                            <td>
                                                               @if ($followup_email_item->status == 'sent')
                                                               <b>Sent Followup: </b><br />
                                                               @else
                                                               <b>To be send Followup: </b><br />
                                                               @endif
                                                               
                                                               {{ \App\Models\Custom::date_formats($followup_email_item->send_at, env('DB_DATETIME_FORMAT'), env('DATETIME_FORMAT')) }}
                                                            </td>
                                                            <td>
                                                                <?php /* @if ($followup_email_item->status == 'pending') */ ?>
                                                                <a href="javascript:;" class="send_followup_email" data-followup_email_id="{{ $followup_email_item->id }}">Follow Up Email</a>
                                                                
                                                                <?php /* @else
                                                                Follow Up Email
                                                                @endif */ ?>
                                                                
                                                                @if (!is_null($row->lead_generate_for))
                                                                - {{ $row->lead_generate_for_company->company_name }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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

    <div class="row">
        <div class="col-md-12">
            <div class="pagination-area text-right">
                {!! $rows->render() !!}
            </div>
        </div>
    </div>
</div>
@stop


@section('page_js')
<script type="text/javascript">
    $(function (){
        $(".send_confirmation_email").on("click", function (){
            var lead_id = $(this).data("lead_id");
            Swal.fire({
                title: 'Are you sure?',
                type: 'question',
                text: 'Are you sure you want to send Confirmation Email?',
                showCancelButton: !0,
                cancelButtonColor: "#ff0000",
                confirmButtonText: "Send Email",
                confirmButtonColor: "#003E74",
            }).then(function (t){
                if (typeof t.value !== 'undefined') {
                    $(this).html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                    $(this).attr('disabled', true);
                    
                    $.ajax({
                        context: this,
                        url: '{{ url("admin/manage_subscribers/send_confirmation_email") }}',
                        type: 'POST',
                        data: {'lead_id': lead_id, '_token': '{{ csrf_token() }}'},
                        success: function (data){
                            $(this).html('Confirmation Email');
                            $(this).attr('disabled', false);
                            
                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                text: data.message,
                            }).then(function (t){
                                if (typeof t.value !== 'undefined') {
                                    window.location.reload();
                                }
                            });
                        }
                    });
                }
            });
        });
        
        
        $(".send_followup_email").on("click", function (){
            var followup_email_id = $(this).data("followup_email_id");
            
            Swal.fire({
                title: 'Are you sure?',
                type: 'question',
                text: 'Are you sure you want to send Follow Up Email?',
                showCancelButton: !0,
                cancelButtonColor: "#ff0000",
                confirmButtonText: "Send Email",
                confirmButtonColor: "#003E74",
            }).then(function (t){
                if (typeof t.value !== 'undefined') {
                    $(this).html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                    $(this).attr('disabled', true);
                    
                    $.ajax({
                        context: this,
                        url: '{{ url("admin/manage_subscribers/send_followup_email") }}',
                        type: 'POST',
                        data: {'followup_email_id': followup_email_id, '_token': '{{ csrf_token() }}'},
                        success: function (data){
                            $(this).html('Follow Up Email');
                            $(this).attr('disabled', false);
                            
                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                text: data.message,
                            }).then(function (t){
                                if (typeof t.value !== 'undefined') {
                                    window.location.reload();
                                }
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@stop
