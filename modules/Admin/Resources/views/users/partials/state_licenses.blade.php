<hr />
<div class="row">
    <div class="span8">


        <!-- New Row -->
        <div id="new_license_state_div">
            @include('users.partials.state_license_new')
        </div>
        <!-- New Row -->

        <h4 class="text-info">State Licenses</h4>

        <div class="alert alert-info">
            <strong>Note:</strong> Once you have uploaded all necessary state license documents, to select your county coverage, click on the state. Then checkmark all of the counties you cover. If you only cover specific zip codes within a county you can click on the county name to specify the zip codes you cover. When done, click the blue save button to update.
        </div>

        <div>
            <p>
                <button type="button" class="btn btn-success btn-mini hidden" id="load-state-licenses" value="Load Licenses">Load Licenses</button>
                <button type="button" class="btn btn-danger btn-mini hidden" id="remove-state-licenses" value="Remove Licenses">Remove Licenses</button>
            </p>
        </div>

        <!-- Current -->
        <div class="alert alert-info licenses-note hidden">
            Click 'Load Licenses' in order for them to show up.
        </div>
        <div id="current_license_state_div">
            @include('users.partials.current_state_licenses')
        </div>
        <!-- Current -->

    </div>
</div>