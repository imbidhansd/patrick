
@if (isset($feedback) && count($feedback) > 0)
<div class="row">
    @foreach ($feedback AS $feedback_item)

    @php
    $cardTitleCls = "text-white";
    @endphp
    
    @if ($feedback_item->feedback_status == 'Member Rejected')
    @php
    $cardTitleCls = "";
    @endphp
    @endif

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-{{\App\Models\Custom::feedback_status_color($feedback_item->feedback_status) }}">
                <h3 class="card-title {{ $cardTitleCls }} mb-0">Feedback: {{ $feedback_item->feedback_id }}</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group bs-ui-list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Review Date</b>
                        <span>{{ $feedback_item->created_at->format(env('DATE_FORMAT')) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Customer Name</b>
                        <span>{{ $feedback_item->customer_name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Customer Email</b>
                        <span>{{ $feedback_item->customer_email }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Customer Phone</b>
                        <span>{{ $feedback_item->customer_phone }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Rating</b>
                        <span>{{ $feedback_item->ratings }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Status</b>
                        <span class="badge badge-{{\App\Models\Custom::feedback_status_color($feedback_item->feedback_status) }}">{{ $feedback_item->feedback_status }}</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <div class="btn-group btn-group-solid float-right">
                    <a title="View Feedback" href="javascript:;" data-toggle="modal" data-target="#detailModal_{{ $feedback_item->id }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>    
                </div>

                <div class="modal fade" id="detailModal_{{ $feedback_item->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content ">
                            <div class="modal-header text-center">
                                <h4 class="modal-title w-100 font-weight-bold text-left">Feedback
                                Details</h4>
                                <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="table-responsive111">
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        <td>
                                            <b>Feedback Status: </b>
                                            {{ $feedback_item->feedback_status }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Customer Name: </b>
                                            {{ $feedback_item->customer_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Customer Email: </b>
                                            {{ $feedback_item->customer_email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Customer Phone: </b>
                                            {{ $feedback_item->customer_phone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Ratings: </b> {{ $feedback_item->ratings }}
                                            &nbsp;
                                            <div id="starHalf"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Review: </b> <br />
                                            {!! $feedback_item->content !!}
                                        </td>
                                    </tr>
                                    @if (count($feedback_item->feedback_files) > 0)
                                    <tr>
                                        <td>
                                            <b>File(s): </b> <br />

                                            <div class="form-group">
                                                <div class="row">
                                                    @foreach($feedback_item->feedback_files AS
                                                        $files)
                                                        @if(!is_null($files->media))
                                                        <div class="col-md-1">
                                                            <div class="media_box">
                                                                <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}" data-fancybox="gallery">

                                                                    @if ($files->media->file_type == 'application/pdf')
                                                                    <i class="far fa-file-pdf font-40"></i>
                                                                    @else
                                                                    <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $files->media->file_name }}" class='img-thumbnail' />
                                                                    @endif

                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="float-left">
    {!! $feedback->appends(['feedback' => $feedback->currentPage(), 'complaint' => $complaints->currentPage()])->render() !!}
</div>
@else
<div class="text-left">No review found.</div>
@endif
