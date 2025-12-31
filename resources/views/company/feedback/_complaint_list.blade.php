
@if (isset($complaints) && count($complaints) > 0)
<div class="row">
    @foreach ($complaints AS $complaint_item)
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-{{ \App\Models\Custom::complaint_status_color($complaint_item->complaint_status) }}">
                <h3 class="card-title text-white mb-0">Complaint: {{ $complaint_item->complaint_id }}</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group bs-ui-list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Complaint Date</b>
                        <span>{{ $complaint_item->created_at->format(env('DATE_FORMAT')) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Customer Name</b>
                        <span>{{ $complaint_item->customer_name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Customer Email</b>
                        <span>{{ $complaint_item->customer_email }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Customer Phone</b>
                        <span>{{ $complaint_item->customer_phone }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <b>Status</b>
                        <span class="badge badge-{{ \App\Models\Custom::complaint_status_color($complaint_item->complaint_status) }}">{{ $complaint_item->complaint_status }}</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <div class="text-right">
                    <div class="btn-group btn-group-solid">
                        <a title="Complaint Responses"
                        href="{{ url('feedback/complaint-responses', ['complaint_id' => $complaint_item->id]) }}"
                        class="btn btn-success btn_response btn-xs"><i class="fas fa-list"></i></a>

                        <a title="View Feedback" href="javascript:;" data-toggle="modal"
                        data-target="#complaint_detailModal_{{ $complaint_item->id }}"
                        class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                    </div>    
                </div>

                <div class="modal fade" id="complaint_detailModal_{{ $complaint_item->id }}"
                    tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content ">
                            <div class="modal-header text-center">
                                <h4 class="modal-title w-100 font-weight-bold text-left">Complaint
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
                                            <b>Complaint Status: </b>
                                            {{ $complaint_item->complaint_status }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Customer Name: </b>
                                            {{ $complaint_item->customer_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Customer Email: </b>
                                            {{ $complaint_item->customer_email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Customer Phone: </b>
                                            {{ $complaint_item->customer_phone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Complaint:</b> <br />
                                            {!! $complaint_item->content !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Have A Copy Of The Contract, Proposal Or Agreement Describing The Service To Be Completed Available For Upload?: </b> <br />
                                            
                                            @if ($complaint_item->have_contract_agreement == 'yes')
                                            <span class="badge badge-primary">
                                                {{ ucfirst($complaint_item->have_contract_agreement) }}
                                            </span>
                                            @elseif ($complaint_item->have_contract_agreement == 'no')
                                            <span class="badge badge-danger">
                                                {{ ucfirst($complaint_item->have_contract_agreement) }}
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($complaint_item->have_contract_agreement == 'yes' && !is_null($complaint_item->contract_agreement_file))
                                    <tr>
                                        <td>
                                            <b>Copy Of The Contract, Proposal Or Agreement: </b>
                                            <br />
                                            <a href="{{ asset('/') }}uploads/media/{{ $complaint_item->contract_agreement_file->file_name }}" data-fancybox="gallery">
                                                @if ($complaint_item->contract_agreement_file->file_type == 'application/pdf')
                                                <i class="far fa-file-pdf font-40"></i>
                                                @else
                                                <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $complaint_item->contract_agreement_file->file_name }}" class='img-thumbnail' />
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if (count($complaint_item->complaint_files) > 0)
                                    <tr>
                                        <td>
                                            <b>File(s):</b> <br />
                                            <div class="form-group">
                                                <div class="row">
                                                    @foreach($complaint_item->complaint_files AS
                                                        $files)
                                                        @if(!is_null($files->media))
                                                        <div class="col-md-1">
                                                            <div class="media_box">
                                                                <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}"
                                                                    data-fancybox="gallery">

                                                                    @if ($files->media->file_type == 'application/pdf')
                                                                    <i class="far fa-file-pdf font-40"></i>
                                                                    @else
                                                                    <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $files->media->file_name }}"
                                                                    class='img-thumbnail' />
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
                                <button type="button" class="btn btn-secondary waves-effect"
                                data-dismiss="modal">Close</button>
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
    {!! $complaints->appends(['complaints' => $complaints->currentPage(), 'feedback' => $feedback->currentPage()])->render() !!}
</div>
@else
<div class="text-left">No complaint found.</div>
@endif
