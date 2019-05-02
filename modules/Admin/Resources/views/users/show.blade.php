@extends('admin::layouts.master')
@section('title', 'View User')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Users', 'url' => route('admin.users.index')],
        ['title' => 'Edit', 'url' => '']
    ]
])
@endcomponent

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="col-lg-8 col-lg-offset-2 col-md-12 data_collection">
                        <a class="btn btn-sm btn-primary"
                           href="{{ route('admin.users.index') }}"><< Back</a>

                        @if (count($alerts) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($alerts as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="span12 offset1">

                                <h1><a href="{{ route('admin.users.index') }}"><img src="/html/img/arrow.jpg"
                                                                                    alt="Back"></a> {{ sprintf("Viewing User '%s'", $user->fullName) }}
                                </h1>
                                @if ($user->active == 'Y')
                                    <p>Status: <strong class="text-success">Active</strong></p>
                                @else
                                    <p>Status: <strong class="text-error">Inactive</strong></p>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="span12 offset1">
                                @if ($user->is_away)
                                    <span class="label label-important">{{ sprintf("Appraiser Is Away Between %s - %s", ($user->away_start_date ? date('m/d/Y', $user->away_start_date) : 'N/A'), ($user->away_end_date ? date('m/d/Y', $user->away_end_date) : 'N/A')) }}</span>
                                @endif

                                @if ($user->exclude == 'Y')
                                    <span class="label label-important">USER IS ON EXCLUDE LIST - DO NOT USE</span>
                                @elseif ($user->exclude == 'P')
                                    <span class="label label-important">USER UNDER REVIEW</span>
                                @elseif ($user->exclude == 'AH')
                                    <span class="label label-important">USER ON ACCOUNTING HOLD</span>
                                @endif

                                @if ($user->is_priority_appr)
                                    <span class="label label-success">{{ \App\Models\Tools\Setting::getSetting('company_name') }}
                                        Priority Appraiser</span>
                                @endif

                                @if ($user->user_type == 4)
                                    @if ($completed < 5)
                                        <span class="label label-warning">New Appraiser: Less than 5 orders completed.</span>
                                    @endif

                                    @if ($apprGroup)
                                        <span class="label label-warning">{{ sprintf("Appraiser Group: %s", $apprGroup->title) }}</span>
                                    @endif
                                @endif

                                @if ($user->is_state_compliance_marked)
                                    <span class="label label-warning">Appraiser Marked For State Compliance</span>
                                @endif
                            </div>
                        </div>

                        <br/><br/>

                        <form autocomplete="off" enctype='multipart/form-data' class="form-horizontal" id="user-form"
                              method="post"
                              action='{{ route('admin.users.update', [$user->id]) }}'>
                            {!! csrf_field() !!}
                            {!! Form::hidden('user_id', $user->id, ['id' => 'user_id']) !!}

                            <div class="row">
                                <div class="span12">
                                    <div class="tabbable tabs-left">

                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#info" data-toggle="tab">Basic Info</a></li>

                                            @if (in_array($user->user_type, array(4, 14)))
                                                <li><a href="#documents" data-toggle="tab">Documents</a></li>
                                                <li><a href="#tax" data-toggle="tab">Tax Info</a></li>
                                                <li><a href="#documenthistory" data-toggle="tab">Document History</a>
                                                </li>
                                                <li><a href="#options" data-toggle="tab">Options</a></li>
                                                <li><a href="#splits" data-toggle="tab">Splits</a></li>
                                                <li><a href="#accounting" data-toggle="tab">Accounting</a></li>
                                                <li><a href="#invites" data-toggle="tab">Invites</a></li>
                                                <li><a href="#contactinfo" data-toggle="tab">Contact Info</a></li>
                                                <li><a href="#diversity" data-toggle="tab">Diversity</a></li>
                                            @endif

                                            <li><a href="#orders" data-toggle="tab">Orders</a></li>
                                            <li><a href="#usernotes" data-toggle="tab">User Notes</a></li>
                                            <li><a href="#userlog" data-toggle="tab">User Log</a></li>
                                            <li><a href="#stats" data-toggle="tab">Statistics</a></li>
                                            <li><a href="#emailform" data-toggle="tab">Email</a></li>
                                            <li><a href="#special" data-toggle="tab">Special Instructions</a></li>
                                            <li><a href="#integrations" data-toggle="tab">Integrations</a></li>
                                            <li><a href="#payment" data-toggle="tab">Payment Options</a></li>
                                            <li><a href="#activity" data-toggle="tab">Activity</a></li>
                                            @if (checkPermission($adminPermissionCategory, 'can_manage_user_client_permissions'))
                                                <li><a href="#permissions" data-toggle="tab">Permissions</a></li>
                                            @endif

                                            @if (admin() && $user->user_type == 1)
                                                <li><a href="#adminoptions" data-toggle="tab">Admin Options</a></li>
                                            @endif

                                            @if ($user->user_type == 1)
                                                <li><a href="#adminsignature" data-toggle="tab">Admin Signature</a></li>
                                            @endif


                                            @if (in_array($user->admin_priv, ['S', 'O']))
                                                <li><a href="#salesoptions" data-toggle="tab">Sales Options</a></li>
                                            @endif
                                        </ul>

                                        <div class="tab-content">

                                            <div id="info" class="tab-pane active">
                                                @include('users.partials.basic_info')
                                            </div>

                                            @if (in_array($user->user_type, [4, 14]))
                                                <div id="documents" class="tab-pane">
                                                    @include('users.partials.documents')
                                                </div>
                                                <div id="tax" class="tab-pane">
                                                    @include('users.partials.tax_info')
                                                </div>
                                                <div id="documenthistory" class="tab-pane">
                                                    @include('users.partials.document_history')
                                                </div>
                                                <div id="options" class="tab-pane">
                                                    @include('users.partials.options')
                                                </div>
                                                <div id="splits" class="tab-pane">
                                                    @include('users.partials.splits')
                                                </div>
                                                <div id="accounting" class="tab-pane">
                                                    @include('users.partials.accounting')
                                                </div>
                                                <div id="invites" class="tab-pane">
                                                    @include('users.partials.invites')
                                                </div>
                                                <div id="contactinfo" class="tab-pane">
                                                    @include('users.partials.contact_info')
                                                </div>
                                                <div id="diversity" class="tab-pane">
                                                    @include('users.partials.diversity_info')
                                                </div>
                                            @endif

                                            <div id="orders" class="tab-pane">
                                                @if ($user->user_type == 4)
                                                    @include('users.partials.appr_user_orders')
                                                @elseif ($user->user_type == 14)
                                                    @include('users.partials.agent_user_orders')
                                                @else
                                                    @include('users.partials.user_orders')
                                                @endif
                                            </div>

                                            <div id="usernotes" class="tab-pane">
                                                @include('users.partials.user_notes')
                                            </div>

                                            <div id="userlog" class="tab-pane">
                                                @include('users.partials.user_log_load')
                                            </div>

                                            <div id="stats" class="tab-pane">
                                                @include('users.partials.stats')
                                            </div>

                                            <div id="emailform" class="tab-pane">
                                                @include('users.partials.email')
                                            </div>

                                            <div id="special" class="tab-pane">
                                                @include('users.partials.special')
                                            </div>

                                            <div id="payment" class="tab-pane">
                                                @include('users.partials.payment')
                                            </div>

                                            <div id="activity" class="tab-pane">
                                                @include('users.partials.activity')
                                            </div>

                                            @if (checkPermission($adminPermissionCategory, 'can_manage_user_client_permissions'))
                                                <div id="permissions" class="tab-pane">
                                                    @include('users.partials.permissions')
                                                </div>
                                            @endif

                                            <div id="integrations" class="tab-pane">
                                                @include('users.partials.integrations')
                                            </div>

                                            @if (isAdmin() && $user->user_type == 1)
                                                <div id="adminoptions" class="tab-pane">
                                                    @include('users.partials.admin_options')
                                                </div>
                                            @endif

                                            @if ($user->user_type == 1)
                                                <div id="adminsignature" class="tab-pane">
                                                    @include('users.partials.admin_signature')
                                                </div>
                                            @endif

                                            @if (isSalesPerson())
                                                <div id="salesoptions" class="tab-pane">
                                                    @include('users.partials.sales_options')
                                                </div>
                                            @endif

                                        </div><!-- /.tab-content -->
                                    </div><!-- /.tabbable -->
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="span12 offset3 bottom-button-options">
                                    <button type="submit" id="submit" name="submit" value="Save User"
                                            class="btn btn-primary">Save User
                                    </button>

                                    @if (canLoginAsUser($user))
                                    <button type="button" id="login-as-user" name="login-as-user"
                                            class="btn btn-inverse">Login As User
                                    </button>
                                    @endif

                                    @if (checkPermission($adminPermissionCategory, 'user_can_toggle_active_status'))
                                    @if ($user->active == 'Y')
                                    <button type="button" id="disable-user" name="disable-user" class="btn btn-danger">
                                        Disable User
                                    </button>
                                    @else
                                    <button type="button" id="enable-user" name="enable-user" class="btn btn-danger">
                                        Enable User
                                    </button>
                                    @endif

                                    @endif

                                    <a href='{{ route('admin.users.reset_password_link', [$user->id]) }}'
                                       class="btn btn-info">Send
                                        Reset Password Link</a>

                                    @if (checkPermission($adminPermissionCategory, 'user_can_mark_approve_users'))

                                    @if ($user->is_pending == 1)
                                    <button type="button" id="mark-pending-approved" name="mark-pending-approved"
                                            class="btn btn-success">Mark Approved
                                    </button>
                                    @endif

                                    @endif

                                    @if ($user->user_type == 1 && checkPermission($adminPermissionCategory, 'can_edit_user_permissions'))
                                    <button type="button" id="user-permissions" name="user-permissions"
                                            class="btn btn-inverse">View User
                                        Permissions
                                    </button>
                                    @endif

                                    @if (isAdminUser() && $user->user_type == 4 && !$user->is_priority_appr && !$service->getActivePriorityInviteByUserId($user->id))
                                    <button type="button" id="priority_invite" name="priority_invite"
                                            value="Priority Invite"
                                            class="btn btn-info">Priority Invite
                                    </button>
                                    @endif
                                </div>
                            </div>

                        </form>

                        <div id="order_modal" class="modal hide fade" tabindex="-1" role="dialog"
                             aria-labelledby="order_modalLabel"
                             aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h3 id="order_modalLabel"></h3>
                            </div>
                            <div class="modal-body" id="order_modal_content"></div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                <button class="btn btn-primary hidden show-submit-button">Submit</button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/adapters/jquery.js') }}"></script>
@endpush