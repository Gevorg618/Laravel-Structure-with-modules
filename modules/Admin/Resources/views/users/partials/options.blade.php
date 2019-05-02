<br />
<div class="row">
    <div class="span8">

        @if($user->user_type == 4)

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Enable Payment Email Notifications?</label>
            {!! Form::select('payment_email_notification', $yesNo, $user->payment_email_notification, ['class' => 'form-control']) !!}
            <span class="help-block">Select Yes if this vendor will receive Email notifications when we cut checks for him.</span>
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class=" control-label">Enable Payment SMS Notifications?</label>
            {!! Form::select('payment_sms_notification', $yesNo, $user->payment_sms_notification, ['class' => 'form-control']) !!}
            <span class="help-block">Select Yes if this vendor will receive SMS notifications when we cut checks for him.</span>
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">New Construction Expert</label>
            {!! Form::select('new_construction_expert', $yesNo, $user->new_construction_expert, ['class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Landmark Priority Appraiser</label>
            @if(checkPermission($adminPermissionCategory, 'user_allow_change_state_priority_appraiser'))
                {!! Form::select('is_priority_appr', $yesNo, $user->is_priority_appr, ['class' => 'form-control']) !!}
	      @else
            <p style="margin-top: 10px;">{{ $user->is_priority_appr ? 'Yes' : 'No' }}</p>
            <input type='hidden' name='is_priority_appr' id='is_priority_appr' value='{{ $user->is_priority_appr }}' />
            @endif
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Software Transaction Fee</label>
            {!! Form::select('software_charge', $yesNo, $user->software_charge, ['class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Enable AutoSelect Invites</label>
            {!! Form::select('autoselect_enabled', $yesNo, $user->autoselect_enabled, ['class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Enable Text Message Invites</label>
            {!! Form::select('enable_text_invites', $yesNo, $user->enable_text_invites, ['class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">AutoSelect Priority</label>
            {!! Form::select('is_auto_select_priority', $yesNo, $user->is_auto_select_priority, ['class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">In House Appraiser</label>
            {!! Form::select('is_in_house', $yesNo, $user->is_in_house, ['class' => 'form-control']) !!}
        </div>

        @if(in_array($user->comp_state, $stateComplianceTakenStates->toArray()))
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Approved To Accept Orders</label>
            @if(checkPermission($adminPermissionCategory, 'user_allow_change_state_compliance'))
                {!! Form::select('appr_state_compliance_approved', $yesNo, $user->appr_state_compliance_approved, ['class' => 'form-control']) !!}
		  		@else
            <p style="margin-top: 10px;">{{ $userExcludeTitle }}</p>
            <input type='hidden' name='appr_state_compliance_approved' id='appr_state_compliance_approved' value='{{ $user->appr_state_compliance_approved }}' />
            @endif
        </div>
        @endif

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Accept COD Orders</label>
            {!! Form::select('accept_cod', $yesNo, $user->accept_cod, ['class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Daily Digest Email</label>
            {!! Form::select('daily_digest', $yesNo, $user->daily_digest, ['class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Apprasier Is Away</label>
            {!! Form::select('is_away', $yesNo, $user->is_away, ['class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Away Date Start</label>
            {!! Form::text('away_start_date', $user->away_start_date ? date('Y-m-d', $user->away_start_date) : null, ['class' => 'datepicker form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Away Date End</label>
            {!! Form::text('away_end_date', $user->away_end_date ? date('Y-m-d', $user->away_end_date) : null, ['class' => 'datepicker form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Appraisal Software</label>
            {!! Form::select('appr_software', $appraisalSoftwareList, $user->appr_software, ['class' => 'form-control', 'placeholder' => 'Choose Software']) !!}
        </div>

        @endif

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Exclude</label>
            @if(checkPermission($adminPermissionCategory, 'user_can_toggle_appr_exclude'))
                {!! Form::select('exclude', $service->excludeOptions(), $user->exclude ?? 'N', ['class' => 'form-control']) !!}
  		@else
            <p style="margin-top: 10px;">{{ $userExcludeTitle }}</p>
            <input type='hidden' name='exclude' id='exclude' value='{{ $user->exclude }}' />
            @endif
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Order Capacity</label>
            {!! Form::text('capacity', $user->capacity, ['class' => 'form-control cap']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Phone Type</label>
            {!! Form::select('phone_type', $phoneTypes, $user->phone_type, ['class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Phone Provider</label>
            {!! Form::select('phone_provider', $phoneProviders, $user->phone_provider, ['class' => 'form-control', 'placeholder' => 'Choose Phone Provider']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Bypass License Check</label>
            {!! Form::select('is_allowed_license_bypass', $yesNo, $user->is_allowed_license_bypass, ['class' => 'form-control']) !!}
            <span class="help-block">If turned on, For appraisal types who have this setting enabled as well, This appraiser will be able to accept work in states they do not have ASC/FHA licenses. Only for those appraisal types who have this turned On as well.</span>
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Set Latitude<Br /><small>Latitude: {{ $user->pos_lat }}</small></label>
            {!! Form::text('pos_lat', $user->pos_lat, ['size' => 20, 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Set Longitude<Br /><small>Longitude: {{ $user->pos_long }}</small></label>
            {!! Form::text('pos_long', $user->pos_long, ['size' => 20, 'class' => 'form-control']) !!}
        </div>

    </div>
</div>

<div class="row">
    <div class="span8">
        <h2>Languages</h2>
        <div class="row language-div">
            @foreach($languages as $row)
            <div class="span2">

                @php
                $options = [];
                if(in_array($row->id, $selectedLanguages->toArray())) {
                    $options['checked'] = 'checked';
                }
                @endphp
                {!! Form::checkbox('languages['.$row->id.']', $row->id, $options) !!}
                <label class="control-label" for="languages_{{ $row->id }}">{{ $row->name }}</label>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row">&nbsp;</div>


<div class="row">
    <div class="span8">
        <h2>Appraisal Types</h2>
        <div class="row language-div">
            @foreach($apprTypes as $typeId => $typeName)
            <div class="span4">
                @php
                $options = [];
                if(in_array($typeId, $selectedApprTypes->toArray())) {
                    $options['checked'] = 'checked';
                }
                @endphp
                {!! Form::checkbox('user_appr_types['.$typeId.']', $typeId, $options) !!}
                <label for="user_appr_types_{{ $typeId }}">{{ $typeName }}</label>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row">&nbsp;</div>

<div class="row">
    <div class="span8">
        <h2>Loan Types</h2>
        <div class="row language-div">
            @foreach($loanTypes as $typeId => $typeName)
            <div class="span4">
                @php
                $options = [];
                if(in_array($typeId, $selectedLoanTypes->toArray())) {
                    $options['checked'] = 'checked';
                }
                @endphp
                {!! Form::checkbox('user_loan_types['.$typeId.']', $typeId, $options) !!}
                <label for="user_loan_types_{{ $typeId }}">{{ $typeName }}</label>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row">&nbsp;</div>

<div class="row">
    <div class="span8">
        <h2>Loan Purpose</h2>
        <div class="row language-div">
            @foreach($loanPurposes as $typeId => $typeName)
            <div class="span4">
                @php
                $options = [];
                if(in_array($typeId, $selectedLoanPurposes->toArray())) {
                    $options['checked'] = 'checked';
                }
                @endphp
                {!! Form::checkbox('user_loan_purpose['.$typeId.']', $typeId, $options) !!}
                <label for="user_loan_purpose_{{ $typeId }}">{{ $typeName }}</label>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row">&nbsp;</div>

<div class="row">
    <div class="span8">
        <h2>References</h2>
        <div class="row">
            @for($i=1; $i<=3; $i++)
            <div class="span8">
                <div class="control-group" style="margin-bottom: 0px;">
                    <label class="long control-label">{{ $service->getReferrenceNameByNumber($i) }}</label>
                </div>

                <div class="control-group" style="margin-bottom: 0px;">
                    {!! Form::text('references['.$i.'][firstname]', $prefs[$i]['firstname'] ?? null, ['class' => 'input-small form-control', 'placeholder' => 'Firstname']) !!}
                    {!! Form::text('references['.$i.'][lastname]', $prefs[$i]['lastname'] ?? null, ['class' => 'input-small form-control', 'placeholder' => 'Lastname']) !!}
                    {!! Form::text('references['.$i.'][company]', $prefs[$i]['company'] ?? null, ['class' => 'input-medium form-control', 'placeholder' => 'Company']) !!}
                    {!! Form::text('references['.$i.'][phone]', $prefs[$i]['phone'] ?? null, ['class' => 'input-small phone form-control', 'placeholder' => 'Phone']) !!}
                </div>

            </div>
            @endfor
        </div>
    </div>
</div>
