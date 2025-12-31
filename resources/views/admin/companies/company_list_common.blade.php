<div class="modal fade" id="salesRepresentativeModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Company Sales Representative</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url("admin/companies/assign-sales-representative"), 'class' => 'module_form', 'id' => 'assign_sales_representative_form']) !!}
            {!! Form::hidden('company_id', null, ['id' => 'company_id', 'required' => true]) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Sales Representative') !!}
                    {!! Form::select('sales_representative_id', $users, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Sales Representative', 'required' => true]) !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


@push('company_list_common_js')
<script type="text/javascript">
    $(function(){
        $('.expand_link').click(function(){
            $($(this).data('id')).fadeToggle('slow');
        });
        
        $(".assign_sales_representative").on("click", function (){
            var company_id = $(this).data("company_id");

            $("#salesRepresentativeModel #assign_sales_representative_form #company_id").val(company_id);
        });

        $("#membership_level_id").on("change", function (){
            var membership_level_id = $(this).val();

            $.ajax({
                url: '{{ url("admin/companies/get-membership-status-from-level") }}',
                type: 'POST',
                data: {'membership_level_id': membership_level_id, '_token': '{{ csrf_token() }}'},
                success: function (data){
                    if (typeof data.success !== 'undefined'){
                        Swal.fire({
                            title: "Error",
                            text: "No status found with selected level.",
                            type: "warning",
                        });
                    } else {
                        $("#membership_status").html(data);
                    }
                }
            });
        });


        //$('.select2').select2();
        $('#top_level_category_id').change(function(){
            $('#main_category_id').html('');
            $('#service_category_id').html('');
            
            $.ajax({
                type: 'post',
                url: '{{ url("admin/main_categories/get_options") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "top_level_category_id": $(this).val(),
                },
                success: function(data){
                    data = '<option value="">All</option>' + data;

                    $('#main_category_id').html(data);
                        $('#main_category_id').trigger('change');
                    },
                error: function(){
                    alert ('error');
                },
            });
        });

        $('#main_category_id').change(function(){
            $('#service_category_id').html('');
            $('#service_category_type_id').trigger("change");
        });

        $('#service_category_type_id').change(function(){
            $('#service_category_id').html('');
            
            $.ajax({
                type: 'post',
                url: '{{ url("admin/service_categories/get_service_options") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "top_level_category_id": $('#top_level_category_id').val(),
                    "service_category_type_id": $('#service_category_type_id').val(),
                    "main_category_id": $('#main_category_id').val(),
                },
                success: function(data){
                    data = '<option value="">All</option>' + data;

                    $('#service_category_id').html(data);
                    $('#service_category_id').trigger('change');
                },
                error: function(){
                    alert ('error');
                },
            });
            
        });
    });
</script>
@endpush