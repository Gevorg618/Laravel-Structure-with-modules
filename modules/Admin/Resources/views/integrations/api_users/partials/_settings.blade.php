<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="contact_email_as_additional" class="col-md-3 control-label form_style">Contact Email As Additional Email</label>
            <div class="col-md-9 form_style">
                <select name="contact_email_as_additional" class="form-control">
                    <option value="0">No</option>
                    <option value="1" {{isset($apiUser) ? $apiUser->contact_email_as_additional ? 'selected' : '' : ''}}>Yes</option>
                </select>
                <span class="help-block">Set contact for entry email as additional email when the additional email is blank</span>
            </div>
        </div>
    </div>
</div>
