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
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Title', $url_key. '.title', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>Top Level Category</th>
                    <th>Main Categories</th>
                    <th>Type</th>
                    <th>Task ID</th>
                    <th>Task Name</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>
                    <td>{{ $row->title }}</td>
                    <td>{{ $row->top_level_category->title }}</td>
                    <td>{{ $row->main_category->title }}</td>
                    <td>{{ $row->service_category_type->title }}</td>
                    <td>
                        <label class="networx_task">{{ $row->networx_task_id }}</label>
                        
                        <div class="networx_task_div" style="display: none;">
                            {!! Form::open(['url' => url('admin/service_categories/update-networx-details'), 'class' => 'module_form networx_task_form']) !!}
                            {!! Form::hidden('category_id', $row->id) !!}
                            {!! Form::hidden('ajax_form', 'yes') !!}
                            <div class="form-group">
                                {!! Form::text('networx_task_id', $row->networx_task_id, ['class' => 'form-control networx_task_id', 'placeholder' => 'Networx Task ID']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </td>
                    <td class="networx_task_name">
                        @if (!is_null($row->networx_task_id))
                        {{ $row->networx_task->task_name }}
                        @else
                        <label class="text-danger">No Networx Category Available</label>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="Edit {{ $module_singular_name }}" href="javascript:;" class="btn btn-primary btn-xs update_networx_task"><i class="fas fa-edit"></i></a>
                            
                            <a title="Save {{ $module_singular_name }}" href="javascript:;" class="btn btn-success btn-xs save_networx_task" style="display:none;"><i class="fas fa-save"></i></a>
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
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="pagination-area text-center">
                {!! $rows->appends($list_params)->render() !!}
            </div>
        </div>
    </div>
</div>
@stop

@section('page_js')
@include ('admin.service_categories._js')
<script type="text/javascript">
    $(function (){
        $(".update_networx_task").on("click", function (){
            //$(".networx_task_div, .save_networx_task").hide();
            
            $(this).parents("tr").find(".networx_task, .networx_task_div, .save_networx_task").toggle();
        });
        
        $(document).on("keyup", ".networx_task_id", function (){
            var task_id = $(this).val();
            
            if (typeof task_id !== 'undefined' && task_id != ''){
                $.ajax({
                    context: this,
                    url: '{{ url("admin/networx_tasks/get-task-detail") }}',
                    type: 'POST',
                    data: {'task_id': task_id, '_token': '{{ csrf_token() }}'},
                    success: function (data){
                        if (data.success == 1){
                            $(this).parents("tr").find(".networx_task_name").text(data.task_name);
                        } else {
                            /*Swal.fire({
                                title: data.title,
                                type: data.type,
                                text: data.message
                            });*/
                            $(this).parents("tr").find(".networx_task_name").html('<label class="text-danger">No Networx Category Available</label>');
                        }
                    }
                });
            }
        });
        
        $(document).on("click", ".save_networx_task", function (){
            var networx_task_id = $(this).parents("tr").find(".networx_task_id").val();
            
            if (typeof networx_task_id !== 'undefined' && networx_task_id != ''){
                //$(this).parents("tr").find(".networx_task_form").submit();
                $.ajax({
                    context: this,
                    url: '{{ url("admin/service_categories/update-networx-details") }}',
                    type: 'POST',
                    data: $(this).parents("tr").find(".networx_task_form").serialize(),
                    success: function (data){
                        if (data.success == 0){
                            /*Swal.fire({
                                title: data.title,
                                type: data.type,
                                text: data.message
                            });*/
                            $.toast({
                                heading: 'Error',
                                text: data.message,
                                icon: 'error',
                                loader: true, // Change it to false to disable loader
                                showHideTransition: 'slide',
                                position: 'bottom-right',
                                loaderBg: '#9EC600'  // To change the background
                            });
                        } else {
                            $(this).parents("tr").find(".networx_task").text(networx_task_id);
                            $(this).parents("tr").find(".networx_task, .networx_task_div, .save_networx_task").toggle();
                            $.toast({
                                heading: 'Success',
                                text: 'Networx detail in service category updated successfully.',
                                icon: 'info',
                                loader: true, // Change it to false to disable loader
                                showHideTransition: 'slide',
                                position: 'bottom-right',
                                loaderBg: '#9EC600'  // To change the background
                            });
                        }
                    }
                });
            } else {
                /*Swal.fire({
                    title: "Warning",
                    type: "warning",
                    text: "Networx Task ID not found."
                });*/
            
                $.toast({
                    heading: 'Warning',
                    text: 'Networx Task ID not found.',
                    icon: 'warning',
                    loader: true, // Change it to false to disable loader
                    showHideTransition: 'slide',
                    position: 'bottom-right',
                    loaderBg: '#9EC600'  // To change the background
                });
            }
        });
    });
</script>
@stop
