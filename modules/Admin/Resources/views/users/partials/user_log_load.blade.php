<hr />
<div class="row">
    <div class="span8">

        <div>
            <p>
                <button type="button" class="btn btn-success btn-mini" id="load-user-logs" data-id="{{ $user->id }}" value="Load User Logs">Load User Logs</button>
                <button type="button" class="btn btn-danger btn-mini" id="remove-user-logs" value="Remove User Logs">Remove User Logs</button>
            </p>
        </div>

        <!-- Current -->
        <div class="alert alert-info user-logs-list">
            Click 'Load User Logs' in order for them to show up.
        </div>
        <div id="user-logs-list-div">

        </div>
        <!-- Current -->

    </div>
</div>