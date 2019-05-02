<div class="row margin_top">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12">
              <h4>Staff Members</h4>
              <div class="row">
                <div class="col-md-12">
                    <select name="statuse_staff[]" class="form-control multi-selector" multiple = "multiple">
                        @if(is_null($savedMembers))
                            @foreach($members as $member)
                                <option value="{{$member->id}}">{{$member->fullname}}</option>
                            @endforeach
                        @else
                            @foreach($members as $member)
                                <option value="{{$member->id}}" {{ is_null($savedMembers->where('user_id', $member->id)->first()) ? '' : 'selected' }}>{{$member->fullname}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="status_select_statuses" class="col-md-2 control-label margin_top">Statuses</label>
            <div class="col-md-9">
                <div class="alert alert-info margin_top">Select the statuses you'd like this specific team to see. if none is selected then all statuses below apply. If some are selected only those stautses will show up.</div>
                @foreach($statuses as $status)
                    <div class="checkbox">
                      <label>
                        @if(is_null($savedStatuses))
                            <input type="checkbox" name="statuses[{{$status->id}}]" value="{{$status->id}}">
                        @else
                            <input type="checkbox" {{ is_null($savedStatuses->where('status_id', $status->id)->first()) ? '' : 'checked' }} name="statuses[{{$status->id}}]" value="{{$status->id}}">
                        @endif
                        {{$status->descrip}}
                      </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="team_phone" class="col-md-3 control-label margin_top">Flags</label>
            <div class="col-md-9">
                <div class="alert alert-info margin_top">Select the flags this specific team will be able to see. if none is selected then all the flags below will be visible. If some are selected only orders with those flags will be visible in status.</div>
                @foreach($flags as $id => $value)
                    <div class="checkbox">
                        <label>
                            @if(is_null($savedFlags))
                                <input type="checkbox" name="flags[{{$id}}]" value="{{$id}}">
                            @else
                                <input type="checkbox" {{ is_null($savedFlags->where('flag_key', $id)->first()) ? '' : 'checked' }} name="flags[{{$id}}]" value="{{$id}}">
                            @endif
                            {{$value}}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="team_phone" class="col-md-3 control-label">Loan Types</label>
            <div class="col-md-9">
                <div class="alert alert-info">Select the apparisal loan types that this team will see. if none is selected they will see all. if some are selected they will see only the appraisals with those loan types.</div>
                @foreach($loanTypes as $loanType)
                    <div class="checkbox">
                        <label>
                            @if(is_null($savedLoanTypes))
                                <input type="checkbox" name="loan_types[{{$loanType->id}}]" value="{{$loanType->id}}">
                            @else
                                <input type="checkbox" {{ is_null($savedLoanTypes->where('loan_id', $loanType->id)->first()) ? '' : 'checked' }} name="loan_types[{{$loanType->id}}]" value="{{$loanType->id}}">
                            @endif
                            {{$loanType->descrip}}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label for="status_select_sort" class="col-md-3 control-label">Sort</label>
            <div class="col-md-9">
                <div class="alert alert-info">Enter a number between 0-100 that the orders for this team will be sorted by. The Higher the number the higher orders will show up in status.</div>
                <div class="col-md-9">
                    <input type="text" name="status_select_sort" id="status_select_sort" value="{{is_null($adminTeam) ? '' : $adminTeam->status_select_sort}}" class="form-control" placeholder="Sort Order">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="is_in_status_select" class="col-md-3 control-label margin_top">Show In Status Select?</label>
            <div class="col-md-9">
                <select id="is_in_status_select" name="is_in_status_select" class="form-control margin_top">
                    <option value="0">No</option>
                    @if(isset($adminTeam))
                        <option value="1" {{$adminTeam->is_in_status_select ? 'selected' : ''}}>Yes</option>
                    @else
                        <option value="1">Yes</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <link rel="stylesheet" type="text/css" href="{{ masset('js/multiselect/css/multi-select.css')}}">
    <script type="text/javascript" src="{{ masset('js/multiselect/js/jquery.multi-select.js')}}"></script>
    <script type="text/javascript" src="{{ masset('js/quicksearch/jquery.quicksearch.js')}}"></script>
    <script type="text/javascript" src="{{ masset('js/management/admin_teams_manager/associations.js') }}"></script>
@endpush
