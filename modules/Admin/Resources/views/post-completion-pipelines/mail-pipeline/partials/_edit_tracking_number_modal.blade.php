<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="edit_tracking_number_title">Edit Tracking Number</h4>
        </div>
        <div class="modal-body" id="edit_tracking_number_content">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" style="display:none;" id="log_error_block"></div>
                    <div class="alert alert-success" style="display:none;" id="log_ok_block"></div>
                    <div class="form-group">
                        <label for="tracking_number" class="col-md-3 control-label">Tracking Number</label>
                        <div class="col-md-9">
                            <input type="hidden" name="rowid" id="rowid" value="{{$row->id}}"/>
                            <input type="text" name="tracking_number" id="tracking_number" class="form-control", value="{{$row->tracking_number}}" placeholder="Tracking Number" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="do_edit_tracking_number">Submit</button>
        </div>
    </div>
</div>
