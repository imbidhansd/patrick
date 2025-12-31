{!! Form::open(['url' => route('post-background-check-step6'),'id' => 'step6_form', 'class' => 'module_form', 'files' => true]) !!}

<h4>Release:</h4>
<p>I authorize Datasource Background Screening Services to perform a nationwide investigation of my criminal history and other information as
needed for the purpose of membership screening. The source of the information may come from, but is not limited to: federal, state,
county, municipal and other governmental entities public records, i.e., criminal, civil, motor vehicle, and other public records; or
other sources as required. It is understood that a photocopy or facsimile copy of this form, or an electronic request by the Company /
Organization listed on Application attached above will serve as authorization. By signing below, I authorize the release of all
information to the Company / Organization listed on Application attached above, and shall hold Datasource Background Screening Services
harmless from any liability or damages for furnishing such information to this Company / Organization.</p>


<div class="alert alert-warning p-3">
    <div class="form-group">
        {!! Form::label('I acknowledge receipt of this Disclosure and certify that I have read and understand this document') !!}
        <div class="row" >
            <div class="col-md-4 col-sm-4">
                {!! Form::text('signature', null, ['class' => 'form-control', 'placeholder' => 'Type Full Name', 'required' => true]) !!}        
            </div>
        </div>
    </div>
</div>
<hr/>

<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right step6_submit last_input">Submit Backgroud Check Request</button>
{!! Form::close() !!}

@push('page_scripts')
<script type="text/javascript">
    $(function(){
        $('#step6_form').submit(function(){
            $(".step6_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');
        });
    });
</script>

@endpush
