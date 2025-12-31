<li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-layers"></i>
                        <span>Site Content</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        @canany(array_merge($module_with_permissions['pages'],
                        $module_with_permissions['post_categories'], $module_with_permissions['posts'],
                        $module_with_permissions['testimonials']))

                        <li>
                            <a href="javascript: void(0);" class="waves-effect waves-light">
                                <span>Post & Pages</span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">
                                @canany($module_with_permissions['pages'])
                                <li><a href="{{ url('admin/pages') }}">Pages</a></li>
                                @endcan

                                @canany($module_with_permissions['post_categories'])
                                <li><a href="{{ url('admin/post_categories') }}">Post Categories</a></li>
                                @endcan

                                @canany($module_with_permissions['posts'])
                                <li><a href="{{ url('admin/posts') }}">Posts</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <li>
                            <a href="javascript: void(0);" class="waves-effect waves-light">
                                <span>Other Content</span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="{{ url('admin/news') }}">News</a></li>

                                @canany($module_with_permissions['testimonials'])
                                <li><a href="{{ url('admin/testimonials') }}">Testimonials</a></li>
                                @endcan

                                <li><a href="{{ url('admin/faqs') }}">Faqs</a></li>
                                <li><a href="{{ url('admin/company_faq_questions') }}">Company Questions</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="waves-effect waves-light">
                                <span>Emails</span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">
                                <?php /* <li><a href="{{ url('admin/default_emails') }}">Default Emails</a></li> */ ?>
                                <li><a href="{{ url('admin/emails') }}">Emails</a></li>
                                <li>
                                    <a href="{{ url('admin/follow_up_mail_categories') }}">
                                        Follow Up <br />
                                        Email Categories
                                    </a>
                                </li>
                                <li><a href="{{ url('admin/follow_up_emails') }}">Follow Up Emails</a></li>
                                <li><a href="{{ url('admin/new_emails') }}">New Emails</a></li>
                                <li><a href="{{ url('admin/broadcast_emails') }}">Broadcast Emails</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-chart-bar"></i>
                        <span>Members</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        @if (isset($top_menu_membership_levels) && count($top_menu_membership_levels) > 0)
                        @foreach ($top_menu_membership_levels as $membership_level_item)
                        <li>
                            <a href="{{ route('company_by_membership_level', ['membership_level' => $membership_level_item->slug]) }}">
                                <span class="badge badge-{{ (($membership_level_item->id == '4' || $membership_level_item->id == '6') ? 'primary' : $membership_level_item->color) }} badge-pill float-right">{{  $membership_level_item->companies_count }}</span>
                                {{ $membership_level_item->title }}
                                
                            </a>
                        </li>
                        @endforeach
                        @endif

                        <hr />
                        <li><a href="{{ url('admin/companies/pending-approval') }}">Pending Approvals</a></li>
                        <li>
                            <a href="{{ url('admin/companies/company-galleries') }}">
                                Company Gallery <br />
                                Approvals
                            </a>
                        </li>
                        <li><a href="{{ url('admin/company_users') }}">Company Owners</a></li>
                        <li><a href="{{ url('admin/site_logos') }}">Site Logo</a></li>
                        <li><a href="{{ url('admin/company_priority') }}">Company Priority</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="mdi mdi-diamond-stone"></i>
                        <span>Lead Categories</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        @canany($module_with_permissions['trades'])
                        <li><a href="{{ url('admin/trades') }}">Trades</a></li>
                        @endcan

                        @canany($module_with_permissions['top_level_categories'])
                        <li><a href="{{ url('admin/top_level_categories') }}">Top Level Categories</a></li>
                        @endcan

                        @canany($module_with_permissions['main_categories'])
                        <li><a href="{{ url('admin/main_categories') }}">Main Categories</a></li>
                        @endcan

                        @canany($module_with_permissions['service_category_types'])
                        <li><a href="{{ url('admin/service_category_types') }}">Service Category Types</a></li>
                        @endcan

                        @canany($module_with_permissions['service_categories'])
                        <li><a href="{{ url('admin/service_categories') }}">Service Categories</a></li>
                        @endcan
                    </ul>
                </li>

                <li>
                    <a href="{{ url('admin/leads') }}" class="waves-effect waves-light">
                        <i class="fas fa-handshake"></i>
                        <span>Leads</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/roles') }}">User Roles</a></li>
                        <li><a href="{{ url('admin/users') }}">Admin Users</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="fas fa-envelope"></i>
                        <span>Feedback</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/feedback') }}">Feedback</a></li>
                        <li><a href="{{ url('admin/complaints') }}">Complaints</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ url('admin/video_podcasts') }}" class="waves-effect waves-light">
                        <i class="mdi mdi-television-play"></i>
                        <span>Video Podcasts</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect waves-light">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Shopping Cart</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li><a href="{{ url('admin/packages') }}">Packages</a></li>
                        <li><a href="{{ url('admin/products') }}">Products</a></li>
                        <?php //<li><a href="{{ url('admin/membership_types') }}">Membership Types</a></li> ?>
                        <li><a href="{{ url('admin/membership_levels') }}">Membership Levels</a></li>
                        <li><a href="{{ url('admin/membership_statuses') }}">Membership Statuses</a></li>
                        <li>
                            <a href="{{ url('admin/membership_level_statuses') }}">
                                Membership Level <br />
                                Videos
                            </a>
                        </li>
                        <li><a href="{{ url('admin/pre_screen_settings') }}">Pre-Screen Setting</a></li>
                        <li><a href="{{ url('admin/professional_affiliations') }}">Professional Affiliations</a></li>
                        <?php /* <li><a href="{{ url('admin/coupon_types') }}">Coupon Types</a></li>
                          <li><a href="{{ url('admin/coupons') }}">Coupons</a></li> */ ?>
                    </ul>
                </li>