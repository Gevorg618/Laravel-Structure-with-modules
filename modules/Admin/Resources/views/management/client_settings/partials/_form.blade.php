<div class="panel-body">
    @if(!isset($client))
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="text-info">Basic Information</h4>
                    <div class="form-group">
                        {{ Form::label('team_assigned', 'Team Assigned', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'team_assigned', 'N/A', ['class' => 'form-control', 'readonly'=>"readonly"]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('descrip', 'Group Title', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'descrip', null, ['class' => 'form-control', ]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-xs-12 ">Client Type</label>
                        <div class="col-lg-12 col-xs-12">
                            <select name="user_group_type" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="broker" @if(isset($client)){{  $client->user_group_type== 'broker'  ? ' selected' : '' }}@endif>Broker</option>
                                <option value="lender" @if(isset($client)){{  $client->user_group_type == 'lender' ? ' selected' : '' }}@endif>Lender</option>
                            </select>
                        </div>
                    </div>
                    <h4 class="text-info">Company Information</h4>
                    <div class="form-group">
                        {{ Form::label('company', 'Company name', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'company', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('company_address', 'Company Address', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'address1', null, ['class' => 'form-control']) }}
                            {{ Form::input('text', 'address2', null, ['class' => 'form-control', 'placeholder'=>"Suite, Floor, etc..."]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('city', 'Company City', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'city', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class='col-lg-3 col-xs-12'>Company State</label>
                        <div class="col-lg-12 col-xs-12">
                            <select id="state" name="state" class="form-control">
                                <option value="">-- State --</option>
                                @foreach($states as $state)
                                    <option value="{{$state->abbr}}" @if(isset($client)) {{($client->state == $state->abbr) ? ' selected' : '' }} @endif>
                                        {{$state->state}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('zip', 'Company Zip', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'zip', null, ['class' => 'form-control input-medium bfh-phone',
                            'data-format'=>"ddddd-dddd"]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('corporate_phone', 'Corporate Phone', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'corporate_phone', null, ['class' => 'form-control input-medium bfh-phone',
                             'data-format'=>" (ddd) ddd-dddd"]) }}
                        </div>
                    </div>
                    <div class="control-group" style="margin-bottom: 0px;">
                        <div class="checkbox">
                            <label for="update_user_address">
                                <input type="checkbox" name="update_user_address" id="update_user_address" value="1"> Update
                                Users address
                            </label>
                        </div>
                    </div>
                    <div class="control-group" style="margin-bottom: 0px;">
                        <p class="muted">
                            Check this if you would like to update ALL users<br>
                            addresses who associated to this client with the address above.
                        </p>
                    </div>
                    <h4 class="text-info">Social Info</h4>
                    <div class="form-group">
                        {{ Form::label('twitter', 'Twitter', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'twitter', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('linkedin', 'Linkedin', ['class' => 'col-lg-3 col-xs-12 ']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('text', 'linkedin', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
