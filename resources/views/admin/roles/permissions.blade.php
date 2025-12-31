@extends('admin.layout')
@section('title', $admin_page_title)


@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

<div class="card-box">

    {!! Form::open() !!}
    @if (isset($module_categories) && !is_null($module_categories) > 0)
    <ul class="nav nav-tabs tabs-bordered" role="tablist">
        @foreach ($module_categories as $k=>$module_category_item)
        <li class="nav-item">
            <a class="nav-link {{ $k==0 ? 'active' : '' }}" id="tab_{{ $k }}_id" data-toggle="tab" href="#tab_{{ $k }}"
                role="tab" aria-selected="{{ $k==0 ? 'true' : 'false' }}">
                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                <span class="d-none d-sm-block">{{ $module_category_item->title }}</span>
            </a>
        </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach ($module_categories as $k=>$module_category_item)
        <div class="tab-pane {{ $k==0 ? 'active' : '' }}" id="tab_{{ $k }}" role="tabpanel">


            <div class="row">

                @if (!is_null($module_category_item->modules))
                @foreach ($module_category_item->modules as $module_item)

                <?php
                    $permissions = Spatie\Permission\Models\Permission::where('module_id', $module_item->id)->get();
                ?>

                @if (!is_null($permissions))
                <div class="col-3">
                    <h5>{{ $module_item->title }}</h5>
                    
                    <div class="checkbox checkbox-primary">
                        <input class="chk_all" id="chk_module_{{ $module_item->id }}" type="checkbox">
                        <label for="chk_module_{{ $module_item->id }}">
                            <strong class="text-success">All</strong>
                        </label>
                    </div>
                        

                    @foreach ($permissions as $permission_item)
                    <div class="checkbox checkbox-primary">
                        <input
                            {{ is_array($current_permissions) && in_array($permission_item->id, $current_permissions) ? 'checked="checked"' : '' }}
                            name="permissions[]" value="{{ $permission_item->id }}" class="chk_item"
                            id="chk_{{ $permission_item->id }}" type="checkbox">
                        <label for="chk_{{ $permission_item->id }}">
                            {{ $permission_item->title }}
                        </label>
                    </div>
                    @endforeach
                    
                </div>
                @endif
                @endforeach
                @endif

            </div>

        </div>
        @endforeach

    </div>
    @endif

    <div class="clearfix"></div>
    <hr />
    <div class="clearfix"></div>
    <button class="btn btn-info float-right">Submit</button>

    {!! Form::close() !!}

</div>

@stop


@section ('page_js')
<script type="text/javascript">
    $(function(){
        $('.chk_all').change(function(){
            $(this).closest('ul').find('.chk_item').prop('checked', $(this).is(':checked'));
        })
    });
</script>
@endsection
