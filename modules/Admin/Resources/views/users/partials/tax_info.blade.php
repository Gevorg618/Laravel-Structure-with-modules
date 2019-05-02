<div class="row" id="tax_form_div">
    <div class="span8">
        <br />
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Heads Up!</strong> To upload a new file just click the 'Choose File' button and select the file you wish to upload from your computer. Once you do that the file will be uploaded and saved immediately. You'll see a link to the new file right below the 'Choose File' button.
            There is no need to click the 'Save User' if the only change made was a file upload.
        </div>

        <h4 class="text-info">Tax Classification</h4>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">Company Type</label>
            {!! Form::select('tax_class', $userTaxClasses, $user->tax_class, ['class' => 'form-control', 'placeholder' => 'Choose Tax Class']) !!}
        </div>

        <h4 class="text-info">EIN / SSN</h4>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long ocntrol-label">EIN / SSN</label>
            {!! Form::text('ein', $user->ein, ['class' => 'form-control']) !!}
        </div>

        <h4 class="text-info">W-9</h4>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long control-label">W-9 Document</label>
            {!! Form::file('w9file', ['class' => 'form-control']) !!}
        </div>
        <div class="control-group" style="margin-bottom: 0px;" id="w9-row-div">
            @include('users.partials.w9_row')
        </div>

        <hr />
        <div class="row">
            <div class="span4">
                <button type="button" class="btn btn-mini" id="copy_company_to_tax">Copy From Company Info</button>
                <h4 class="text-info">Payable Information</h4>
                <div class="control-group" style="margin-bottom: 0px;display:none;" id="company_name_div">
                    <label class="control-label">Payee Name</label>
                    {!! Form::text('payable_company', $user->payable_company, ['class' => 'form-control', 'size' => 20]) !!}
                </div>
                <div class="control-group" style="margin-bottom: 0px;">
                    <label class="control-label">Payee Address</label>
                    {!! Form::text('payable_address', $user->payable_address, ['class' => 'form-control', 'size' => 20]) !!}
                    {!! Form::text('payable_address1', $user->payable_address1, ['class' => 'form-control', 'size' => 20, 'placeholder' => 'Suite, Floor, etc...']) !!}
                </div>
            </div>
            <div class="span4">
                <h4 class="text-info">&nbsp;</h4>

                <div class="control-group" style="margin-bottom: 0px;">
                    <label class="form-control">Payee City</label>
                    {!! Form::text('payable_city', $user->payable_city, ['class' => 'form-control', 'size' => 20]) !!}
                </div>
                <div class="control-group" style="margin-bottom: 0px;">
                    <label style="width:170px;" class="control-label">Payee State, Zip</label>
                    <div class="row">
                        <div class="span2">
                            {!! Form::select('payable_state', getStates(), $user->payable_state, ['class' => 'form-control', 'placeholder' => 'Choose a State']) !!}
                        </div>
                        <div class="span2">
                            {!! Form::text('payable_zip', $user->payable_zip, ['class' => 'form-control zipcode', 'size' => 20]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

{{--<script>--}}
    {{--var $isAllowedToChange = "{!! intval(checkPermission($adminPermissionCategory, 'can_update_user_tax_info')) !!}";--}}
    {{--$().ready(function() {--}}
        {{--$('#w9file').ajaxfileupload({--}}
            {{--'action': '/admin/user.php?action=w9-upload',--}}
            {{--'params': {--}}
                {{--'userId': $userId--}}
            {{--},--}}
            {{--'valid_extensions': ['pdf'],--}}
            {{--'onComplete': function(response) {--}}
                {{--if(response.error) {--}}
                    {{--alert(response.error);--}}
                {{--} else {--}}
                    {{--$('#w9-row-div').html(response);--}}
                    {{--$('#w9file').val('');--}}
                {{--}--}}
            {{--},--}}
            {{--'onStart': function() {--}}
                {{--if(!$('#ein').val()) {--}}
                    {{--alert("Sorry, You must enter the user EIN / SSN number and save before uploading the W9 document.");--}}
                    {{--return false;--}}
                {{--}--}}
                {{--// Show Loading Icon--}}
                {{--$('#w9-row-div').html('Loading...');--}}
            {{--}--}}
        {{--});--}}

        {{--$('#tax_class').live('change', function() {--}}
            {{--validateSSNNumber();--}}
            {{--showCompanyNameDiv();--}}
        {{--}).trigger('change');--}}

        {{--// If we are not allowed to change--}}
        {{--// then disable everything--}}
        {{--if(!$isAllowedToChange) {--}}
            {{--$.each($('input, select, button', $('#tax_form_div')), function(i, item) {--}}
                {{--$(this).attr('disabled', 'disabled');--}}
            {{--});--}}
        {{--}--}}

    {{--});--}}

    {{--function showCompanyNameDiv() {--}}
        {{--$('#company_name_div').show();--}}
        {{--return;--}}

        {{--var $type = $('#tax_class').val();--}}
        {{--if($type == 'sole' || $type == 'quickbooksssn' || $type == '') {--}}
            {{--$('#company_name_div').hide();--}}
        {{--} else {--}}
            {{--$('#company_name_div').show();--}}
        {{--}--}}
    {{--}--}}

    {{--function validateSSNNumber() {--}}
        {{--$('#ein').removeClass('ein').removeClass('ssn');--}}
        {{--var $type = $('#tax_class').val();--}}
        {{--if($type == 'sole' || $type == 'quickbooksssn' || $type == '') {--}}
            {{--$('#ein').addClass('ssn');--}}
            {{--$(".ssn").mask("999-99-9999");--}}
        {{--} else {--}}
            {{--$('#ein').addClass('ein');--}}
            {{--$(".ein").mask("99-9999999");--}}
        {{--}--}}
    {{--}--}}
{{--</script>--}}

<style>
    input[type=file] {
        border: 0px;
        padding:0px;
    }
</style>