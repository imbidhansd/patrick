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
            @if(isset($complaints) && count($complaints) > 0)
            @foreach($complaints as $complaint_item)
            <tr>
                <td>{{ $complaint_item->customer_name }}</td>
                <td>{{ $complaint_item->customer_email }}</td>
                <td>{{ $complaint_item->complaint_status }}</td>
                <td>
                    <div class="btn-group btn-group-solid">
                        <a title="View Complaint" href="javascript:;" data-toggle="modal"
                            data-target="#detailModal_{{ $complaint_item->id }}" class="btn btn-info btn-xs"><i
                                class="fas fa-eye"></i></a>

                        @if ($complaint_item->complaint_status != 'Posted')
                        <a title="Edit Complaint"
                            href="{{ route('complaints.edit', ['complaint' => $complaint_item->id]) }}"
                            class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                        @endif

                        <a title="Delete Complaint"
                            href="{{ route('complaints.destroy', ['complaint' => $complaint_item->id]) }}"
                            class="btn btn-danger delete_btn btn-xs" data-id="{{ $complaint_item->id}}"><i
                                class="fas fa-trash-alt"></i></a>
                    </div>

                    <div class="modal fade" id="detailModal_{{ $complaint_item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content ">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title w-100 font-weight-bold text-left">Complaint Details</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="table-responsive111">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <td>
                                                    <b>Complaint Status: </b> {{ $complaint_item->complaint_status }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Customer Name: </b> {{ $complaint_item->customer_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Customer Email: </b> {{ $complaint_item->customer_email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Customer Phone: </b> {{ $complaint_item->customer_phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Complaint</b> <br />
                                                    {!! $complaint_item->content !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Image</b> <br />
                                                    @if (count($complaint_item->complaint_files) > 0)
                                                    <div class="form-group">
                                                        <div class="row">
                                                            @foreach($complaint_item->complaint_files AS $files)
                                                            @if(!is_null($files->media))
                                                            <div class="col-md-2">
                                                                <div class="media_box">
                                                                    <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}"
                                                                        data-fancybox="gallery">
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
                                    <button type="button" class="btn btn-secondary waves-effect"
                                        data-dismiss="modal">Close</button>
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
    {!! $complaints->appends(['complaints' => $complaints->currentPage()])->render() !!}
</div>

@push('_feedback_js')
<script type="text/javascript">
    @if (Request::has('complaints'))
    $("#complaint_list #complaints").addClass("show");
    $('html, body').animate({
        scrollTop: 0
    }, 0);
    $('html, body').animate({
        scrollTop: $("#complaint_list").offset().top - 130
    }, 2000);
    @endif
</script>
@endpush
