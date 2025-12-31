@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title text-center">{{ $admin_page_title }}</h4>
        </div>
    </div>
</div>
<!-- end page title end breadcrumb -->

<!-- row -->

<div class="row">
    <div class="col-md-4 offset-md-4">
        @include('admin.includes.formErrors')
        @include('flash::message')
        <div class="card-box">
            {!! Form::open(['url' => url('admin/change-password'), 'class' => 'module_form', 'files' => true, ]) !!}
            <div class="form-body">

                <div class="form-group">
                    {!! Form::label('Old Password') !!}

                    <div class="input-group">
                        {!! Form::password('old_password', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'data-parsley-minlength' => '6']) !!}
                        <span class="input-group-append view-password">
                            <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('New Password') !!}
                    <div class="input-group">
                        {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password', 'placeholder' => '', 'required' => true, 'data-parsley-minlength' => '6', 'data-parsley-type' => 'alphanum']) !!}

                        <span class="input-group-append view-password">
                            <button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('Confirm Password') !!}
                    {!! Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => '', 'required'
                    => 'required', 'data-parsley-minlength' => '6' , 'data-parsley-type' =>
                    'alphanum', 'data-parsley-equalto' => '#new_password']) !!}
                </div>

                <hr />
                <button type="submit" class="btn btn-info float-right waves-effect waves-light">Change Password</button>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>

    {!! Form::close() !!}

</div> <!-- end row -->

@stop


@section ('page_js')
<script type="text/javascript">
    $(function (){
        $('.view-password').mousedown(function(){
            $(this).closest('.input-group').find('input').attr('type','text');
        });
        
        $('.view-password').mouseup(function(){
            $(this).closest('.input-group').find('input').attr('type','password');
        });
    });
</script>
@endsection