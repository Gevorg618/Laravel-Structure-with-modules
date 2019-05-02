<div class="row">
    <div class="span8">
        <br />
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Heads Up!</strong> To upload a new file just click the 'Choose File' button and select the file you wish to upload from your computer. Once you do that the file will be uploaded and saved immediately. You'll see a link to the new file right below the 'Choose File' button.
            There is no need to click the 'Save User' if the only change made was a file upload.
        </div>

        @if($user->user_type == 4)
        <h4 class="text-info">Background Check</h4>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">Has Background Check Date</label>
            {!! Form::select('has_background_check', [0 => 'No', 1 => 'Yes'], optional($user->userData)->has_background_check, ['class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">Background Check Date</label>
            {!! Form::text('background_check_date', $user->userData->background_check_date ? date('Y-m-d', $user->userData->background_check_date) : null, ['class' => 'datepicker form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">Background Check Document</label>
            {!! Form::file('backgroundfile', ['class' => 'form-control', 'id' => 'backgroundfile']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;" id="backgroundcheck-row-div">
            @include('users.partials.background_row')
        </div>

        <hr />
        @endif
        @if($user->user_type == 4)
        <h4 class="text-info">E & O</h4>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">E & O Document</label>
            {!! Form::file('eandofile', ['class' => 'form-control', 'id' => 'eandofile']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;" id="eando-row-div">
            @include('users.partials.eando_row')
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">E & O Company</label>
            {!! Form::text('ins_company', optional($user->userData)->ins_company, ['class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">E & O Amount (Each)<span class="required"></span></label>
            {!! Form::text('ins_amt', optional($user->userData)->ins_amt, ['class' => 'large_number form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">E & O Amount (Aggregate)<span class="required"></span></label>
            {!! Form::text('ins_amt_agg', optional($user->userData)->ins_amt_agg, ['class' => 'large_number form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">E & O Expiration</label>
            {!! Form::text('ins_expire', optional($user->userData)->ins_expire, ['class' => 'datepicker form-control']) !!}
        </div>
        <hr />

        <div id="additional-documents-div">
            <h4 class="text-info">Additional Documents</h4>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="long">Document Type</label>
                {!! Form::select('additional-document-type', $userDocumentTypes, null, ['id' => 'additional-document-type', 'class' => 'form-control', 'placeholder' => 'Choose additional document type']) !!}
            </div>
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="long">Select File</label>
                {!! Form::file('additional-document', ['disabled' => 'disabled', 'id' => 'additional-document']) !!}
            </div>
            <div class="control-group" style="margin-bottom: 0px;" id="additional-document-row-div">
                @include('users.partials.additional_docs')
            </div>
        </div>

        <hr />

        <h4 class="text-info">Certifications <button type="button" class="ladda-button update-documents-cache" data-color="blue" data-style="zoom-in" data-size="xs"><span class="ladda-label">Refresh</span></button></h4>
        <div class="control-group" style="margin-bottom: 0px;display:none;">
            <label class="long">Certification Level</label>
            <p style="margin-top:5px;">
                @if($user->license_type == 'C')
                Certified Residential
                @elseif($user->license_type == 'G')
                Certified General
                @else
                Licensed
                @endif
            </p>
        </div>
        <div class="control-group" style="margin-bottom: 0px;display:none;">
            <label class="long">FHA</label>
            {!! Form::select('fha', ['Y' => 'Yes', 'N' => 'No'], $user->fha, ['class' => 'form-control', 'placeholder' => 'Choose FHA']) !!}
        </div>

        <h5 class="text-info">FHA Licenses</h5>
        <table class="table table-condensed">
            <tr>
                <th>License #</th>
                <th>Type</th>
                <th>Expiration</th>
                <th>Status</th>
            </tr>
            @if($userOldHUDLicenses)
            @foreach($userOldHUDLicenses as $license)
            <tr>
                <td>{{ $license->license_number }}></td>
                <td>{{ $license->license_type }}</td>
                <td>{{ date("M j, Y", $license->license_exp_unix) }}</td>
                <td>{{ $license->license_exp_unix <= time() ? "<span class='text-error'>Expired</span>" : "<span class='text-success'>Active</span>" }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4">No Licenses Found! Please make sure the user has a state license record.</td>
            </tr>
            @endif
        </table>

        <h5 class="text-info">FHA Cached Licenses</h5>
        <table class="table table-condensed">
            <tr>
                <th>License #</th>
                <th>State</th>
                <th>Expiration</th>
                <th>Status</th>
            </tr>
            @if($cachedFhaLicenses)
            @foreach($cachedFhaLicenses as $license)
            <tr>
                <td>{{ $license->license_number }}</td>
                <td>{{ $license->state }}</td>
                <td>{{ date("M j, Y", $license->expiration) }}</td>
                <td>{{ $license->expiration <= time() ? "<span class='text-error'>Expired</span>" : "<span class='text-success'>Active</span>" }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4">No FHA Cached Licenses Found!</td>
            </tr>
            @endif
        </table>

        <h5 class="text-info">ASC Licenses</h5>
        <table class="table table-condensed">
            <tr>
                <th>State</th>
                <th>License #</th>
                <th>Type</th>
                <th>Expiration</th>
                <th>Status</th>
            </tr>
            @if($userOldASCLicenses)
            @foreach($userOldASCLicenses as $license)
            <tr>
                <td>{{ $license->st_abbr }}</td>
                <td>{{ $license->lic_number }}</td>
                <td>{{ $service->getAppraiserLicenseTypeName($license->lic_type) }}</td>
                <td>{{ date("M j, Y", strtotime($license->exp_date)) }}</td>
                <td>{{ strtotime($license->exp_date) <= time() ? "<span class='text-error'>Expired</span>" : "<span class='text-success'>Active</span>" }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5">No Licenses Found! Please make sure the user has a state license record.</td>
            </tr>
            @endif
        </table>

        <h5 class="text-info">ASC Cached Licenses</h5>
        <table class="table table-condensed">
            <tr>
                <th>License #</th>
                <th>State</th>
                <th>Expiration</th>
                <th>Status</th>
            </tr>
            @if($appraiserCachedASCLicenses)
            @foreach($appraiserCachedASCLicenses as $license)
            <tr>
                <td>{{ $license->license_number }}</td>
                <td>{{ $license->state }}</td>
                <td>{{ date("M j, Y", $license->expiration) }}</td>
                <td>{{ $license->expiration <= time() ? "<span class='text-error'>Expired</span>" : "<span class='text-success'>Active</span>" }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4">No ASC Cached Licenses Found!</td>
            </tr>
            @endif
        </table>


        <div class="control-group" style="margin-bottom: 0px;" id="state_licenses">
            @include('users.partials.state_licenses')
        </div>

        @endif

        @if($user->user_type == 14)
        <h4 class="text-info">License Information</h4>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">License Document</label>
            {!! Form::file('licensefile', ['class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;" id="license-row-div">
            @include('users.partials.license_row')
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">License State</label>
            {!! Form::select('license_state', getStates(), optional($user->userData)->license_state, ['class' => 'form-control', 'placeholder' => 'Choose a State']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">License Number</label>
            {!! Form::text('license_num', optional($user->userData)->license_num, ['class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">License Expiration</label>
            {!! Form::text('license_exp_date', optional($user->userData)->license_exp_date, ['class' => 'datepicker form-control']) !!}
        </div>

        @endif

    </div>
</div>
