

<table class="table">
    <tr>
        <td width="30%">Question</td>
        <td>{{ $formObj->question }}</td>
    </tr>
    <tr>
        <td width="30%">Additional Information</td>
        <td>{{ $formObj->content }}</td>
    </tr>
</table>
<hr/>
<div class="clearfix">&nbsp;</div>


<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            {!! Form::select('status', ['new' => 'New', 'pending' => 'Pending', 'resolved' => 'Resolved'], null, ['class' =>
            'form-control custom-select', 'required' => true]) !!}
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>


@push('form_js')
<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });
</script>
@include('admin.faqs._js')
@endpush