
<div class="row">
    <div class="span8">
        <h4 class="sub-title">Supplier Diversity</h4>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="control-label">Diversity Status</label>
            {!! Form::select('diversity_status', $yesNo, $user->diversity_status, ['class' => 'form-control']) !!}
            <span class="help-block">Is your business 51% owned and controlled/operated by a minority or women?</span>
        </div>

        <div class="diversity_status_info" id="diversity_status_info" style="display:none;">
            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Diversity Information</label>
                {!! Form::select('diversity_type', $service->getDiversityStatusInformation(), $user->diversity_type, ['class' => 'form-control', 'placeholder' => 'Diversity type']) !!}
            </div>

            <div class="control-group" id="diversity_type_other_info" style="display:none;margin-bottom: 0px;">
                <label class="control-label">Diversity Type Other</label>
                {!! Form::text('diversity_agency_type_other', $user->diversity_agency_type_other, ['class' => 'form-control', 'placeholder' => 'Diversity type name']) !!}
            </div>

            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Agency Type</label>
                {!! Form::select('diversity_agency_type', $service->getDiversityAgencyType(), $user->diversity_agency_type, ['class' => 'form-control', 'placeholder' => 'Diveristy Agency Type']) !!}
            </div>

            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Certifying Agency</label>
                {!! Form::text('diversity_agency_certify_agency', $user->diversity_agency_certify_agency, ['class' => 'form-control', 'placeholder' => 'Certifying Agency Name']) !!}
            </div>

            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Certificate Number</label>
                {!! Form::text('diversity_agency_certificate_number', $user->diversity_agency_certificate_number, ['class' => 'form-control', 'placeholder' => 'Certification Number']) !!}
            </div>

            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Effective Date</label>
                {!! Form::text('diversity_agency_effective_date', $user->diversity_agency_effective_date, ['class' => 'form-control datepicker disabled', 'placeholder' => 'Certificate Effective Date']) !!}
            </div>

            <div class="control-group" style="margin-bottom: 0px;">
                <label class="control-label">Expiration Date</label>
                {!! Form::text('diversity_agency_expiration_date', $user->diversity_agency_expiration_date, ['class' => 'form-control datepicker validate-future-date disabled', 'placeholder' => 'Certificate Expiration Date']) !!}
            </div>

            <div class="control-group" style="margin-bottom: 0px;">
                <label>Certificate Document</label>
                {!! Form::file('diversity_agency_document', ['class' => 'form-control', 'placeholder' => 'Certificate Document']) !!}
            </div>
        </div>
    </div>
</div>

{{--<script>--}}
    {{--$().ready(function () {--}}
        {{--// Diversity status info--}}
        {{--$('#diversity_status').live('change', function() {--}}
            {{--if($(this).val() == 1) {--}}
                {{--$('#diversity_status_info').show();--}}
            {{--} else {--}}
                {{--$('#diversity_status_info').hide();--}}
            {{--}--}}
        {{--}).trigger('change');--}}

        {{--$('#diversity_agency_type').live('change', function() {--}}
            {{--if($(this).val() == 'self') {--}}
                {{--$('#diversity_agency_certify_agency, #diversity_agency_certificate_number, #diversity_agency_effective_date, #diversity_agency_expiration_date, #diversity_agency_document').parent().hide();--}}
            {{--} else {--}}
                {{--$('#diversity_agency_certify_agency, #diversity_agency_certificate_number, #diversity_agency_effective_date, #diversity_agency_expiration_date, #diversity_agency_document').parent().show();--}}
            {{--}--}}
        {{--}).trigger('change');--}}

        {{--// Diversity status info--}}
        {{--$('#diversity_type').live('change', function() {--}}
            {{--if($(this).val() == 'other') {--}}
                {{--$('#diversity_type_other_info').show();--}}
            {{--} else {--}}
                {{--$('#diversity_type_other_info').hide();--}}
            {{--}--}}
        {{--}).trigger('change');--}}
    {{--});--}}
{{--</script>--}}


