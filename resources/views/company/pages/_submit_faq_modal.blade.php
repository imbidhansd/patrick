<div class="modal fade" id="emailUsYourQuestion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Email Us Your Question</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => route('submit-faq'), 'class' => 'module_form', 'id' => 'submit_faq_form']) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Question') !!}
                    {!! Form::text('question', null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('More Information') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control', 'required' => true]) !!}
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light submit_faq_btn">Submit Question</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@push('_submit_faq_js')
<script type="text/javascript">
    $(function (){
        $("#submit_faq_form").on("submit", function (){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".submit_faq_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".submit_faq_btn").attr('disabled', true);
            } else {
                $(".submit_faq_btn").html('Submit Question');
                $(".submit_faq_btn").attr('disabled', false);
            }
        });
    });
</script>
@endpush