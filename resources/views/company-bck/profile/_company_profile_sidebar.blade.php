<div class="col-sm-3">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white mb-0">Company Level & Status Information</h3>
                </div>
                <div class="card-body">
                    <div class="text-left">
                        <p class="text-muted ">
                            <strong>Level:</strong>
                            <span>Free Preview Trial</span>
                        </p>

                        <p class="text-muted ">
                            <strong>Status:</strong>
                            <span
                                class="badge {{ (($company_detail->company_subscribe_status == 'subscribed') ? 'badge-success' : 'badge-danger') }}">{{ ucfirst($company_detail->company_subscribe_status) }}</span>
                        </p>

                        <p class="text-center">
                            @if ($company_detail->company_subscribe_status == 'subscribed')
                            <a href="javascript:;" data-type="unsubscribe"
                                class="btn btn-sm btn-primary btn-rounded width-sm change_subscription">Unsubscribe</a>
                            @else
                            <a href="javascript:;" data-type="subscribe"
                                class="btn btn-sm btn-primary btn-rounded width-sm change_subscription">Resubscribe</a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title text-white mb-0">Company Profile Page</h3>
                </div>
                <div class="card-body">
                    <div class="text-left">
                        <p class="text-muted ">
                            <strong>Profile 50% Complete</strong>
                            <span>
                                <a href="{{ url('profile') }}"
                                    class="btn btn-sm btn-primary btn-rounded width-sm waves-effect waves-light">Edit
                                    Profile</a>
                            </span>
                        </p>

                        <p class="text-muted ">
                            <strong>Page Views</strong>
                            <span>#0</span>
                        </p>

                        <p class="text-center ">
                            <a href="#"
                                class="btn btn-sm btn-info btn-rounded width-sm waves-effect waves-light">View</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-orange">
                    <h3 class="card-title text-white mb-0">Leads</h3>
                </div>
                <div class="card-body">
                    <div class="text-left">
                        <p class="text-muted ">
                            <strong>New Leads</strong>
                            <span>#0</span>
                        </p>

                        <p class="text-muted ">
                            <strong>New Leads This Month</strong>
                            <span>#0</span>
                        </p>

                        <p class="text-muted ">
                            <strong>System Leads</strong>
                            <span>#0</span>
                        </p>

                        <p class="text-muted ">
                            <strong>Company Page Leads</strong>
                            <span>#0</span>
                        </p>

                        <p class="text-muted ">
                            <strong>Total Number of Leads To Date</strong>
                            <span>#0</span>
                        </p>

                        <p class="text-center ">
                            <a href="#" class="btn btn-sm btn-primary btn-rounded width-sm waves-effect waves-light">Go
                                To
                                Leads Archive Inbox</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-purple">
                    <h3 class="card-title text-white mb-0">Feedback</h3>
                </div>
                <div class="card-body">
                    <div class="text-left">
                        <p class="text-muted ">
                            <strong>Review Summary</strong>
                        </p>

                        <p class="text-muted ">
                            <span>0.0</span>
                        </p>

                        <p class="text-muted ">
                            <span>0 Reviews</span>
                        </p>

                        <p class="text-center ">
                            <a href="#"
                                class="btn btn-sm btn-primary btn-rounded width-sm waves-effect waves-light">Read
                                All Reviews</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{!! Form::open(['url' => url('update-company-subscription'), 'id' => 'udpate_company_subscription_form']) !!}
{!! Form::hidden('sub_type', 0, ['id' => 'sub_type']) !!}
{!! Form::close() !!}
