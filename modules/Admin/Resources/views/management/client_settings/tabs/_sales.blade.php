<div class="col-md-10">
    <h2>Sales Information</h2>
    <div class="row">
        <div class="form-group">
            <div class="col-md-6">
                <label for="salesid">Account Executive</label>
                <select id="salesid" name="salesid" class="form-control col-md-4">
                    <option value="">-- Select --</option>
                    <optgroup label="Sales">
                        {{$client->id}}
                        @foreach($sales as $key => $value)
                            <option @if($client->salesid == $key) selected @endif value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Super Users">
                        @foreach($superUsers as $key => $value)
                            <option @if($client->salesid == $key) selected @endif  value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="col-md-6">
                <label for="salesid2">SDR</label>
                <select id="salesid2" name="salesid2" class="form-control col-md-4">
                    <option value="">-- Select --</option>
                    <optgroup label="Sales">
                        @foreach($sales as $key => $value)
                            <option @if($client->salesid2 == $key) selected @endif value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Super Users">
                        @foreach($superUsers as $key => $value)
                            <option @if($client->salesid2 == $key) selected @endif  value="{{$key}}"
                                    value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
        </div>
        @if(isAdmin())
            <div class="form-group">
                <div class="col-md-6">
                    <label for="salesid_com">Appraisal Account Executive Commission</label>
                    <div class="input-group col-md-3">
                        <input type="text" value="{{$client->salesid_com}}" class="form-control" name="salesid_com"
                               id="salesid_com">
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-search">%</button>
                    </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="salesid2_com">SDR Commission</label>
                    <div class="input-group col-md-3">
                        <input type="text" class="form-control" value="{{$client->salesid2_com}}" name="salesid2_com"
                               id="salesid2_com">
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-search">%</button>
                    </span>
                    </div>
                </div>
            </div>
        @endif
        @if(isAdmin())
            <div class="form-group">
                <div class="col-md-6">
                    <label for="salesid_alt_com">Alternative Account Executive Commission</label>
                    <div class="input-group col-md-3">
                        <input type="text" class="form-control" value="{{$client->salesid_alt_com}}"
                               name="salesid_alt_com"
                               id="salesid_alt_com">
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-search">%</button>
                    </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="salesid2_alt_com">Alternative SDR Commission</label>
                    <div class="input-group col-md-3">
                        <input type="text" class="form-control" value="{{$client->salesid2_alt_com}}"
                               name="salesid2_alt_com" id="salesid2_alt_com">
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-search">%</button>
                    </span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row" style="border-top: 1px solid #eee; margin-top:20px">
        <div class="form-group" style="margin-top:30px">
            <div class="col-md-6">
                <label for="manager">Manager</label>
                <select id="manager" name="manager" class="form-control col-md-4">
                    <option value="">-- Select --</option>
                    <optgroup label="Sales">
                        @foreach($sales as $key => $value)
                            <option @if($client->manager == $key) selected @endif value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Super Users">
                        @foreach($superUsers as $key => $value)
                            <option @if($client->manager == $key) selected @endif value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div class="col-md-2">
                <label for="software_fee">Software Fee</label>
                <input type="text" class="form-control" value="{{$client->software_fee}}" name="software_fee"
                       id="software_fee">
            </div>
        </div>
        <div class="form-group">
            @if(isAdmin())
                <div class="col-md-6">
                    <label for="manager_com">Appraisal Manager Commission</label>
                    <div class="input-group col-md-3">
                        <input type="text" value="{{$client->manager_com}}" class="form-control" name="manager_com"
                               id="manager_com">
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-search">%</button>
                    </span>
                    </div>
                </div>
            @endif
            <div class="col-md-6">
                <label for="sales_com_deduct_amount">Additional Fee To Deduct</label>
                <div class="input-group col-md-3">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-search">$</button>
                    </span>
                    <input type="text" class="form-control" value="{{$client->sales_com_deduct_amount}}"
                           name="sales_com_deduct_amount"
                           id="sales_com_deduct_amount">

                </div>
            </div>
        </div>
        @if(isAdmin())
            <div class="form-group">
                <div class="col-md-6">
                    <label for="manager_alt_com">Alternative Manager Commission</label>
                    <div class="input-group col-md-3">
                        <input type="text" class="form-control" value="{{$client->manager_alt_com}}"
                               name="manager_alt_com"
                               id="manager_alt_com">
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-search">%</button>
                    </span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
