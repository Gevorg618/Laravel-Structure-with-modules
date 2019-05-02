<div class="row">
    <div class="span8">
        <h4 class="text-info">Basic Information</h4>
    </div>
    <div class="span4">
        <div class="control-group" style="margin-bottom: 0px;">
            <label>User Type</label>
            {!! Form::select('user_type', $userTypes, $user->user_type, ['placeholder' => 'Choose User Type', 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>User Group</label>
            {!! Form::select('user_group_id', $userGroups, $user->user_group_id, ['placeholder' => 'Choose User Group', 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>First Name</label>
            {!! Form::text('firstname', $user->userData->firstname, ['size' => 20, 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Middle Name</label>
            {!! Form::text('middlename', $user->userData->middlename, ['size' => 20, 'class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label>Last Name</label>
            {!! Form::text('lastname', $user->userData->lastname, ['size' => 20, 'class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label>Email Address</label>
            {!! Form::email('email', $user->email, ['size' => 20, 'class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label>Twitter</label>
            {!! Form::text('twitter', $user->twitter, ['size' => 20, 'class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label>Referral</label>
            {!! Form::text('referral', $user->referral, ['size' => 20, 'readonly' => 'readonly', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="span4">
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Title</label>
            {!! Form::text('title', $user->userData->title, ['size' => 20, 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Suffix</label>
            {!! Form::select('suffix', $suffixList, $user->userData->suffix, ['placeholder' => 'Choose suffix', 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Phone</label>
            {!! Form::text('phone', $user->userData->phone, ['size' => 20, 'class' => 'form-control phone']) !!}
            {!! Form::text('phoneext', $user->userData->phoneext, ['size' => '4', 'style' => 'margin-left:5px;width:30px;', 'class' => 'form-control phoneext']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Mobile Phone</label>
            {!! Form::text('nobile', $user->userData->mobile, ['size' => 20, 'class' => 'form-control phone']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Fax</label>
            {!! Form::text('fax', $user->userData->fax, ['size' => 20, 'class' => 'form-control phone']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label>LinkedIn</label>
            {!! Form::text('linkedin', $user->linkedin, ['size' => 20, 'class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="span8">
        <h4 class="text-info">Company Information</h4>
        <div class="alert alert-warning">
            <p>Please make sure to indicate the same address as is on file with HUD and/or the Appraisal Subcommittee. A mismatch will negatively affect your ability to receive and accept orders. You may update your mailing address by clicking on the Tax Info tab on the left to ensure your payments are sent to the correct address.</p>
        </div>
    </div>
    <div class="span4">
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Company Name</label>
            {!! Form::text('company', $user->userData->company, ['size' => 20, 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Company Address</label>
            {!! Form::text('comp_address', $user->userData->comp_address, ['size' => 20, 'class' => 'form-control']) !!}
            {!! Form::text('comp_address1', $user->userData->comp_address1, ['size' => 20, 'class' => 'form-control']) !!}
        </div>
    </div>

    <div class="span4">
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Company City</label>
            {!! Form::text('comp_city', $user->userData->comp_city, ['size' => 20, 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label style="width:170px;">Company State, Zip</label>
            <div class="row">
                <div class="span2">
                    {!! Form::select('comp_state', getStates(), $user->userData->comp_state, ['class' => 'form-control', 'style' => 'width:130px;float:left', 'placeholder' => 'Choose state']) !!}
                </div>
                <div class="span1">
                    {!! Form::text('comp_zip', $user->userData->comp_zip, ['size' => '20', 'style' => 'width:85px;margin-left:5px;', 'class' => 'zipcode form-control']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="span8"> <hr /></div>
    <div class="span4">
        <h4 class="text-info">Client Group</h4>

        <div class="control-group" style="margin-bottom: 0px;">
            {!! Form::select('groupid', $clientsList, $user->groupid, ['class' => 'form-control', 'placeholder' => 'Choose client']) !!}
            @if($user->groupid)
            <p><a href='/admin/group.php?action=view-group&id={{ $user->groupid }}' target='blank'>{{ $user->userGroup->title }}</a></p>
            @endif

            @if($lenderManager)

            <p><b>Wholesale Manager</b> <br /><a href='/admin/lenders.php?action=update&id={{ $lenderManager->id }}' target='blank'>{{ $lenderManager->title }}</a></p>
            @endif
        </div>
    </div>

    <div class="span5">
        @if(checkPermission($adminPermissionCategory, 'can_change_user_group_supervisor'))
        <h4 class="text-info">Multi Client Group Supervisor</h4>

        <div class="control-group" style="margin-bottom: 0px;">
            {!! Form::select('additional_groups[]', $clientsList, $user->groups->pluck('id'), ['size' => 15, 'multiple' => 'multiple','class' => 'multiselect']) !!}
        </div>

        @endif

    </div>
</div>

<div class="row">
    <div class="span8">
        <hr />
        @if($user)
        <h4 class="text-error">Password Reset</h4>
        @else
        <h4 class="text-error">Password</h4>
        @endif
        <div class="control-group" style="margin-bottom: 0px;">
            <label>New Password</label>
            {!! Form::password('new_password', ['size' => 20, 'class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Confirm Password</label>
            {!! Form::password('confirm_password', ['size' => 20, 'class' => 'form-control']) !!}
        </div>
    </div>
</div>
