<div class="row">
    <div class="span8">
        <h4 class="text-info">Vendor Settings</h4>

        <div class="control-group" style="margin-bottom: 0px;">
            <label>AMC Account</label>
            {!! Form::select('amc_api_account', $amcList, optional($user->userData)->amc_api_account, ['class' => 'form-control', 'placeholder' => 'Choose AMC']) !!}
        </div>
        <div class="hint">Select an AMC account if this user is associated to an AMC that wishes to integrate with Landscape. This will allow the API to send push notifications when a new order is assigned to an AMC.</div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label>AMC API Account</label>
            {!! Form::select('amc_api_account_api_id', $apiAccountsList, optional($user->userData)->amc_api_account_api_id, ['class' => 'form-control', 'placeholder' => 'Choose API account']) !!}
        </div>
        <div class="hint">Select an API account if this user is associated to an AMC that wishes to integrate with Landscape. This will allow the API to send push notifications when a new order is assigned to an AMC.</div>
    </div>
</div>

