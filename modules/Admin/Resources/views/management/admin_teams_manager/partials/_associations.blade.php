<div class="row margin_top">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12">
                <h4>Team Members</h4>
                <div class="row">
                    <div class="col-md-12">
                        <select name="members_selection[]" class="form-control multi-selector" multiple = "multiple">
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

<div class="row margin_top">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12">
                <h4>Clients</h4>
                <div class="row">
                    <div class="col-md-12">
                        <select name="client_selection[]" class="form-control multi-selector" multiple = "multiple">
                            @if(is_null($savedClients))
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{$client->descrip}}</option>
                                @endforeach
                            @else
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}" {{ is_null($savedClients->where('user_group_id', $client->id)->first()) ? '' : 'selected' }}>{{$client->descrip}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row margin_top">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12">
                <h4>States</h4>
                <div class="row">
                    <div class="col-md-12">
                        <select name="state_selection[]" class="form-control multi-selector" multiple = "multiple">
                            @if(is_null($savedStates))
                                @foreach($states as $state)
                                    <option value="{{$state->abbr}}">{{$state->state}}</option>
                                @endforeach
                            @else
                                @foreach($states as $state)
                                    <option value="{{$state->abbr}}" {{ is_null($savedStates->where('state', $state->abbr)->first()) ? '' : 'selected' }}>{{$state->state}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
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
