<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="title" class="col-md-3 control-label form_style">Title</label>
            <div class="col-md-9 form_style">
                <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{isset($apiUser) ? $apiUser->title : ''}}">
            </div>
        </div>

        <div class="form-group">
            <label for="company" class="col-md-3 control-label form_style">Company</label>
            <div class="col-md-9 form_style">
                <input type="text" name="company" id="company" class="form-control" placeholder="Company" value="{{isset($apiUser) ? $apiUser->company : ''}}">
            </div>
        </div>

        <div class="form-group">
            <label for="groups" class="col-md-3 control-label form_style">Clients</label>
            <div class="col-md-9 form_style">
                <select class="form-control multiselect bootstrap-multiselect" multiple="multiple" name="groups[]">
                    @foreach($clients as $client)
                        @if(empty($savedClients))
                            <option value="{{$client->id}}">{{$client->descrip}}</option>
                        @else
                            <option value="{{$client->id}}" {{is_null($savedClients->where('api_id', $apiUser->id)->where('group_id', $client->id)->first()) ? '' : 'selected'}}>{{$client->descrip}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="in_production" class="col-md-3 control-label form_style">Mode</label>
            <div class="col-md-9 form_style">
                <select class="form-control" name="in_production">
                    <option value="0">Test</option>
                    <option value="1" {{isset($apiUser) ? $apiUser->in_production ? 'selected' : '' : ''}}>Production</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="is_active" class="col-md-3 control-label form_style">Active</label>
            <div class="col-md-9 form_style">
                <select class="form-control" name="is_active">
                    <option value="0">No</option>
                    <option value="1" {{isset($apiUser) ? $apiUser->is_active ? 'selected' : '' : ''}}>Yes</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="is_visible_all" class="col-md-3 control-label form_style">Full Access</label>
            <div class="col-md-9 form_style">
                <select class="form-control" name="is_visible_all">
                    <option value="0">No</option>
                    <option value="1" {{isset($apiUser) ? $apiUser->is_visible_all ? 'selected' : '' : ''}}>Yes</option>
                </select>
                <span class="help-block">Select Yes if you would like for this API User to access all data rather then just the one that he created.</span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="day_limit" class="col-md-3 control-label form_style">Day Limit</label>
            <div class="col-md-9 form_style">
                <input type="text" name="day_limit" id="day_limit" class="form-control" value="{{isset($apiUser) ? $apiUser->day_limit : 0}}">
                <span class="help-block">Number of API Calls allowed a day. 0 Means no limit.</span>
            </div>
        </div>
        <div class="form-group">
            <label for="month_limit" class="col-md-3 control-label form_style">Month Limit</label>
            <div class="col-md-9 form_style">
                <input type="text" name="month_limit" id="month_limit" class="form-control" value="{{isset($apiUser) ? $apiUser->month_limit : 0}}">
                <span class="help-block">Number of API Calls allowed a month. 0 Means no limit.</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/integrations/api_users/information_tab.js') }}"></script>
@endpush
