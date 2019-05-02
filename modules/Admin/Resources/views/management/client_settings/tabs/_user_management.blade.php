<div class="col-md-10">
    <h2>Quick Add Users</h2>
    <div class="form-group">
        <div class="col-md-4">
            <label for="quick_add_firstname">First Name</label>
            <input type="text" name="quick_add_firstname" id="quick_add_firstname"
                   value="{{$client->quick_add_firstname}}" class="form-control"/>
        </div>
        <div class="col-md-4">
            <label for="quick_add_lastname">Last Name</label>
            <input type="text" name="quick_add_lastname" id="quick_add_lastname"
                   value="{{$client->quick_add_lastname}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4">
            <label for="quick_add_email">Email Address</label>
            <input type="text" name="quick_add_email" id="quick_add_email"
                   value="{{$client->quick_add_email}}" class="form-control"/>
            <div>
                <p>Password is set to 'landmark'</p>
            </div>
        </div>
        <div class="col-md-3">
            <label for="quick_add_phone">Phone</label>
            <input type="text" name="quick_add_phone" id="quick_add_phone"
                   value="{{$client->quick_add_phone}}" class="form-control input-medium bfh-phone"
                   data-format=" (ddd) ddd-dddd"/>
        </div>
        <div class="col-md-1">
            <input type="text" name="quick_add_phoneext" id="quick_add_phoneext"
                   value="{{$client->quick_add_phoneext}}" class="form-control input-medium bfh-phone"
                   data-format="dddddd" style="margin-top: 23px"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-8" style="text-align:center;">
            <button type="button" class="btn btn-success" id="quick_add_user">Add</button>
            <button type="button" class="btn btn-default" id="reset_quick_input">Reset</button>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4">
            <label for="has_default_lender">Has default lender</label>
            <select name="has_default_lender" id="has_default_lender" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->has_default_lender ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4">
            <h3>Users In Group</h3>
            <label for="quick_add_user_search">Search For Users:</label>
            <input type="text" name="quick_add_user_search" id="quick_add_user_search"
                   class="form-control user_search" data-name="users"/>
        </div>
        <div class="col-md-4">
            <h3>Group Supervisor</h3>
            <label for="quick_add_mananger_search">Search For Supervisors</label>
            <input type="text" name="quick_add_mananger_search" id="quick_add_mananger_search"
                   class="form-control user_search" data-name="supervisor"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4">
            <div id="user_content">
                <h4 class="users_count_h4">Total Users {{count($client->users)}}</h4>
                <table class="table table-bordered" id="users_count">
                    <tbody id="users_tbody">
                    @if(count($client->users ))
                        @foreach($client->users as $item)
                            <tr id="{{$item->id}}">
                                <td>
                                    {{$item->userData->firstname}}  {{$item->userData->lastname}}
                                    <br>{{$item->email}}
                                    <i class="fa fa-trash remove_users"
                                       aria-hidden="true" style="float: right; color:red; cursor: pointer;" data-remove="users_data"></i>
                                    <input value="{{$item->id}}" name="users_data[]" type="hidden">
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="app_not_found_user">
                            <td>None Found.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <h4 class="managers_count_h4">Total Managers {{count($client->userGroupRelations)}}</h4>
            <table class="table table-bordered" id="managers_count">
                <tbody id="managers_tbody">
                @if(count($client->userGroupRelations ))
                    @foreach($client->userGroupRelations as $item)
                        <tr id="{{$item->user_id}}">
                            <td>
                                {{$item->userData->firstname}}  {{$item->userData->lastname}}
                                <br>{{$item->user->email}}
                                <i class="fa fa-trash remove_users"
                                   aria-hidden="true" style="float: right; color:red; cursor: pointer;" data-remove="managers_data"></i>
                                <input value="{{$item->user_id}}" name="managers_data[]" type="hidden">
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="app_not_found_managers">
                        <td>None Found.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4">
            <h3>Preferred Appraisers</h3>
            <label for="quick_add_appr_search">Search For Appraisers:</label>
            <input type="text" name="quick_add_appr_search" id="quick_add_appr_search"
                   class="form-control appr_search" data-name="appr_search"/>
        </div>
        <div class="col-md-4">
            <h3>Excluded Appraisers</h3>
            <label for="quick_add_exclude_search">Search For Appraisers:</label>
            <input type="text" name="quick_add_exclude_search" data-name="exclude_search" id="quick_add_exclude_search"
                   class="form-control appr_search"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4">
            <h4 class="appr_count_h4">Total Preferred {{count($client->preferAppr )}}</h4>
            <table class="table table-bordered" id="appr_count">
                <tbody id="preferred">
                @if(count($client->preferAppr ))
                    @foreach($client->preferAppr as $item)
                        <tr id="{{$item->apprid}}">
                            <td>
                                {{$item->userData->firstname}}  {{$item->userData->lastname}}
                                <br>{{$item->user->email}}
                                <i class="fa fa-trash remove_appr" aria-hidden="true"
                                   style="float: right; color:red; cursor: pointer;" data-remove="prefer_appr"></i>
                                <br>Since:{{$item->dts}}
                                <input value="{{$item->apprid}}" name="prefer_appr[]" type="hidden">
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="app_not_found">
                        <td>None Found.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h4 class="appr_excluded_count_h4">Total Excluded {{count($client->excludeAppr )}}</h4>
            <table class="table table-bordered" id="appr_count_excluded">
                <tbody id="excluded">
                @if(count($client->excludeAppr ))
                    @foreach($client->excludeAppr as $item)
                        <tr id="{{$item->apprid}}">
                            <td>
                                {{$item->userData->firstname}}  {{$item->userData->lastname}}
                                <br>{{$item->user->email}}
                                <i class="fa fa-trash remove_appr" aria-hidden="true"
                                   style="float: right; color:red; cursor: pointer;" data-remove="excluded_appr"></i>
                                <br>Since:{{$item->dts}}
                                <input value="{{$item->apprid}}" name="excluded_appr[]" type="hidden">
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="app_not_found_excluded">
                        <td>None Found.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" value="{{route('admin.management.client.quick.add.user')}}" id="add_user_url">
    <input type="hidden" value="{{$client->id}}" id="add_user_group_id">
    <input type="hidden" value="{{route('admin.management.client.appraisers')}}" id="search_appraisers_url">
    <input type="hidden" value="{{route('admin.management.client.users')}}" id="search_users_url">
</div>
@push('scripts')
    <script src="{{ masset('js/management/client_settings/user_management.js') }}"></script>
    <script src="{{ masset('js/management/client_settings/users_search.js') }}"></script>
@endpush
