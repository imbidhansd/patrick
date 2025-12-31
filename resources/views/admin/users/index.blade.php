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
                                '.first_name',
                                $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                                $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Username', $url_key. '.username',
                        $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                        $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Email', $url_key. '.email',
                        $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                        $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>User Role</th>
                    <th class="col-md-1 col-lg-1 col-sm-1">Status</th>
                    <th class="col-2">Action</th>
                </tr>
            </thead>

            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>

                    <td>
                        <div class="checkbox checkbox-primary">
                            <input name="ids[]" class="{{ Auth::id() == $row->id ? '' : 'ids' }}" value="{{ $row->id}}"
                                ng-checked="all" id="chk_{{ $row->id}}" type="checkbox"
                                {{ Auth::id() == $row->id ? 'disabled="disabled"' : '' }}>
                            <label for="chk_{{ $row->id}}">
                                {{ $row->first_name}} {{ $row->last_name}}
                            </label>
                        </div>
                    </td>

                    <td>{{ $row->username }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->role->name }}</td>
                    <td>
                        @if ($row->status == 'active')
                        <span class="label label-info">{{ ucfirst($row->status)}}</span>
                        @else
                        <span class="label label-danger">{{ ucfirst($row->status)}}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <span class="resetPasswordBtn" data-id="{{ $row->id }}"
                                data-user="{{ $row->first_name }} {{ $row->last_name }}" data-toggle="modal"
                                data-target="#myModal">
                                <a data-toggle="tooltip" data-placement="top" data-original-title="Reset Password"
                                    class="btn btn-warning btn-xs"><i class="fas fa-redo"></i></a>
                            </span>

                            <?php /*
                            <a title="Send 2FA Auth Link"
                                href="{{ route('send-2FA-auth-link', [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-success send_2FA_auth_link btn-xs"><i class="fas fa-qrcode"></i></a>
                            */ ?>

                            <a title="Edit {{ $module_singular_name}}"
                                href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>

                            @if (Auth::id() != $row->id)
                            <a title="Delete {{ $module_singular_name}}"
                                href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i
                                    class="fas fa-trash-alt"></i></a>
                            @endif
                        </div>
                    </td>

                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5">No Records Found.</td>
                </tr>
                @endif

            </tbody>
        </table>



    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            @include ('admin.includes._tfoot', ['options' => ['active' => 'Active', 'inactive' => 'Inactive', 'delete'
            =>
            'Delete']])
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


<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    {!! Form::open(['method' => 'post','url' => url('admin/users/change-password'),'id' =>
    'change_password_form' , 'class' => 'module_form']) !!}
    {!! Form::hidden('user_id', null, ['id' => 'user_id']) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('New Password') !!}
                    {!! Form::password('new_password', ['class' => 'form-control', 'required' => 'required', 'minlength'
                    => 6, 'data-parsley-type' => 'alphanum']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    {!! Form::close() !!}
</div>


@stop

@section ('page_js')
<script type="text/javascript">
    $(function () {
      $('#change_password_form').parsley();

      $('.resetPasswordBtn').click(function () {
          $id = $(this).data('id');
          $user = $(this).data('user');
          $('#change_password_form').find('label').html('New Password for <span class="text-danger">' + $user + '</span>');
          $('#change_password_form').find('#user_id').val($id);
          $('#change_password_form').find('.alert').addClass('hide');

      });


      // Send 2fa Auth Link ['Start']
      $('.send_2FA_auth_link').click(function(){
        var href = $(this).attr('href');
            Swal.fire({
                title: "Are you sure to send link for 2FA setup?",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#ff9800",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, Send it!"
            }).then(function (t) {
                if (typeof t.value != 'undefined'){
                    window.location.href = href;
                }
            })
            return false;
      })
      // Send 2fa Auth Link ['End']

      $('#change_password_form').submit(function (e) {
          $.ajax({
              type: 'post',
              url: $('#change_password_form').attr('action'),
              data: $('#change_password_form').serialize(),
              success: function (data) {
                  $('#change_password_form')[0].reset();
                    if (data.status == '1'){
                        $('#myModal').modal('toggle');

                        $.toast({
                            heading: 'Success',
                            text: data.message,
                            icon: 'info',
                            loader: true, // Change it to false to disable loader
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            loaderBg: '#9EC600'  // To change the background
                        });
                    }else{
                        $.toast({
                            heading: 'Danger',
                            text: data.message,
                            icon: 'danger',
                            loader: true, // Change it to false to disable loader
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            loaderBg: '#ff0000'  // To change the background
                        });
                    }
              },
              error: function () {
                  $('#change_password_form').find('.alert').html(data.message).removeClass('hide').addClass('alert-danger');
              }
          });
          e.preventDefault();
          return false;
      })
  })
</script>
@endsection
