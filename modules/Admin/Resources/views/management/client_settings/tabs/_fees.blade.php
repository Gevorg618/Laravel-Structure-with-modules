<div class="col-md-10">
    <h2>Fees</h2>
    <div class="form-group row">
        <label for="warr_addfee" class="col-md-4">Warranty Additional Fee</label>
        <div class="col-md-4">
            <input type="text" name="warr_addfee" id="warr_addfee"
                   value="{{$client->warr_addfee}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="mail_appr_addfee" class="col-md-4">Mail Final Appraisal Additional Fee</label>
        <div class="col-md-4">
            <input type="text" name="mail_appr_addfee" id="mail_appr_addfee"
                   value="{{$client->mail_appr_addfee}}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group row">
        <label for="investment_docs_add" class="col-md-4">Investment Docs Additional Fee</label>
        <div class="col-md-4">
            <input type="text" name="investment_docs_add" id="investment_docs_add"
                   value="{{$client->investment_docs_add}}"
                   class="form-control">
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>This will adjust the additional fee that the 1007 and 216 combined add on to the fee when ordering.
                    ($100
                    is default)</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="valuclear_discount" class="col-md-4">MarkIt Value Discount</label>
        <div class="col-md-4">
            <input type="text" name="valuclear_discount" id="valuclear_discount"
                   value="{{$client->valuclear_discount}}"
                   class="form-control">
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>This will discount orders that are converted from a MarkIt Value product to an Appraisal Product from
                    the MarkIt <br> Value order details page.</p>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="pricing_version" class="col-md-4">Pricing Version</label>
        <div class="col-md-4">
            <select name="pricing_version" id="pricing_version" class="form-control">
                @foreach($statePricingVersionList as $key => $value)
                    <option {{$client->pricing_version == $key ? 'selected' : ''}}  @if($key == 1)disabled="disabled"
                            @elseif($key == 2)disabled="disabled"
                            @endif value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <div class="span7">
                <p>Please select which pricing version this group has agreed to.</p>
            </div>
        </div>
    </div>
</div>
