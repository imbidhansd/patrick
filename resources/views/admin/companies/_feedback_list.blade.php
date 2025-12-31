<div class="table-responsive111">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Customer Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($feedback) && count($feedback) > 0)
                @foreach($feedback as $feedback_item)
            <tr>
                <td>{{ $feedback_item->customer_name }}</td>
                <td>{{ $feedback_item->customer_email }}</td>
                <td>{{ $feedback_item->feedback_status }}</td>
                <td>
                    <div class="btn-group btn-group-solid">
                        <a title="View Feedback" href="javascript:;" data-toggle="modal" data-target="#detailFeedbackModal_{{ $feedback_item->id }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                         
                        <a title="Edit Feedback"
                            href="{{ route('feedback.edit', ['feedback' => $feedback_item->id]) }}"
                            class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>

                        <a title="Delete Feedback"
                            href="{{ route('feedback.destroy', ['feedback' => $feedback_item->id]) }}"
                            class="btn btn-danger delete_btn btn-xs" data-id="{{ $feedback_item->id}}"><i class="fas fa-trash-alt"></i></a>
                    </div>

                    <div class="modal fade" id="detailFeedbackModal_{{ $feedback_item->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content ">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title w-100 font-weight-bold text-left">Feedback Details</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="table-responsive111">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <td>
                                                    <b>Company Name: </b> {{ $feedback_item->company->company_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Feedback Status: </b> {{ $feedback_item->feedback_status }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Customer Name: </b> {{ $feedback_item->customer_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Customer Email: </b> {{ $feedback_item->customer_email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Customer Phone: </b> {{ $feedback_item->customer_phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Ratings: </b> {{ $feedback_item->ratings }} &nbsp;
                                                    <div id="starHalf"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Review</b> <br />
                                                    {!! $feedback_item->content !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Image</b> <br />
                                                    @if (count($feedback_item->feedback_files) > 0)
                                                        <div class="form-group">
                                                            <div class="row">
                                                            @foreach($feedback_item->feedback_files AS $files)
                                                                @if(!is_null($files->media))
                                                                <div class="col-md-2">
                                                                    <div class="media_box">
                                                                        <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}" data-fancybox="gallery">
                                                                            <img src="{{ asset('/') }}uploads/media/fit_thumbs/100x100/{{ $files->media->file_name }}"
                                                                                class='img-thumbnail' />
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
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
                <td colspan="5">No Records Found.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="float-left">
    {!! $feedback->appends(['feedback' => $feedback->currentPage()])->render() !!}
</div>

@push('_edit_company_profile_js')
<!-- rating js -->
<script src="{{ asset('/themes/admin/assets/libs/ratings/jquery.raty-fa.js') }}"></script>

<script type="text/javascript">
    $(function (){
        @if(isset($feedback) && count($feedback) > 0)
            @foreach($feedback AS $row)
            var row_id = '{{ $row->id }}';
            $("#detailFeedbackModal_"+row_id+" #starHalf").raty({
                readOnly: !0,
                half: !0,
                starHalf: "fas fa-star-half text-success",
                starOff: "far fa-star text-muted",
                starOn: "fas fa-star text-success",
                score: "{{ $row->ratings }}",
            });
            @endforeach
        @endif
    });

    @if (Request::has('feedback'))
    $("#feedback_list #feedback").addClass("show");
    $('html, body').animate({
        scrollTop: 0
    }, 0);
    $('html, body').animate({
        scrollTop: $("#feedback_list").offset().top - 130
    }, 2000);
    @endif
</script>
@endpush