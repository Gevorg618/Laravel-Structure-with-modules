<div class="col-md-10">
    <h2>AMC Settings</h2>
    <div class="form-group row">
        <label for="allow_amc_submit" class="col-md-2">Allow Submit to AMCs</label>
        <div class="col-md-4">
            <select name="allow_amc_submit" id="allow_amc_submit" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->allow_amc_submit ? 'selected' : ''}}>Yes</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="allow_amc_submit_individual" class="col-md-2">Allow Submit to Individuals</label>
        <div class="col-md-4">
            <select name="allow_amc_submit_individual" id="allow_amc_submit_individual" class="form-control">
                <option value="0">No</option>
                <option value="1" {{$client->allow_amc_submit_individual ? 'selected' : ''}} >Yes</option>
            </select>
        </div>
    </div>
</div>
