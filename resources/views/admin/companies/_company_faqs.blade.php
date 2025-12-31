@if (isset($company_faqs) && count($company_faqs) > 0)
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Created By</th>
                <th width="18%">Created At</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($company_faqs AS $company_faq_item)
            <tr>
                <td>
                    {{ $company_faq_item->company_user->first_name.' '.$company_faq_item->company_user->last_name }}
                </td>
                <td>{{ $company_faq_item->created_at->format(env('DATETIME_FORMAT')) }}</td>
                <td>
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-info btn-xs" data-toggle="modal" data-target="#displayNoteModal_{{ $company_faq_item->id }}" title="View Note"><i class="fa fa-list"></i></a>
                    </div>

                    <div class="modal fade" id="displayNoteModal_{{ $company_faq_item->id }}" tabindex="-1"
                         role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content ">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title w-100 font-weight-bold text-left">
                                        {{ $company_faq_item->question }}
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    {!! $company_faq_item->content !!}
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
        </tbody>
    </table>
</div>
@endif

