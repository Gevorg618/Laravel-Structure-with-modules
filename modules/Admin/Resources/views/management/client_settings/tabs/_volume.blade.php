<div class="col-md-10">
    <h2>Estimated Total Monthly Volume</h2>
    <div class="form-group">
        <label for="estimated_total_monthly_volume" class="col-md-4">Total Monthly Volume</label>
        <div class="col-md-4">
            <input type="text" name="estimated_total_monthly_volume" id="estimated_total_monthly_volume"
                   value="{{$client->estimated_total_monthly_volume}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_total_monthly_volume_one" class="col-md-4">Month 1:</label>
        <div class="col-md-4">
            <input type="text" name="estimated_total_monthly_volume_one" id="estimated_total_monthly_volume_one"
                   value="{{$client->estimated_total_monthly_volume_one}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_total_monthly_volume_two" class="col-md-4">Month 2:</label>
        <div class="col-md-4">
            <input type="text" name="estimated_total_monthly_volume_two" id="estimated_total_monthly_volume_two"
                   value="{{$client->estimated_total_monthly_volume_two}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_total_monthly_volume_three" class="col-md-4">Month 3:</label>
        <div class="col-md-4">
            <input type="text" name="estimated_total_monthly_volume_three" id="estimated_total_monthly_volume_three"
                   value="{{$client->estimated_total_monthly_volume_three}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_total_monthly_volume_six" class="col-md-4">Month 6:</label>
        <div class="col-md-4">
            <input type="text" name="estimated_total_monthly_volume_six" id="estimated_total_monthly_volume_six"
                   value="{{$client->estimated_total_monthly_volume_six}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_start_date" class="col-md-4">Estimated Start Date</label>
        <div class="col-md-4">
            <input type="text" name="estimated_start_date" id="estimated_start_date"
                   value="{{$client->estimated_start_date}}" class="form-control datepicker"/>
        </div>
    </div>

    <h2>Estimated Product Volume</h2>

    <div class="form-group">
        <label for="estimated_product_volume_conv" class="col-md-4">% Conventional</label>
        <div class="col-md-4">
            <input type="text" name="estimated_product_volume_conv" id="estimated_product_volume_conv"
                   value="{{$client->estimated_product_volume_conv}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_product_volume_fha" class="col-md-4">% FHA</label>
        <div class="col-md-4">
            <input type="text" name="estimated_product_volume_fha" id="estimated_product_volume_fha"
                   value="{{$client->estimated_product_volume_fha}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_product_volume_forward" class="col-md-4">% Forward</label>
        <div class="col-md-4">
            <input type="text" name="estimated_product_volume_forward" id="estimated_product_volume_forward"
                   value="{{$client->estimated_product_volume_forward}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_product_volume_reverse" class="col-md-4">% Reverse</label>
        <div class="col-md-4">
            <input type="text" name="estimated_product_volume_reverse" id="estimated_product_volume_reverse"
                   value="{{$client->estimated_product_volume_reverse}}" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="estimated_product_volume_altval" class="col-md-4">% ALTVAL</label>
        <div class="col-md-4">
            <input type="text" name="estimated_product_volume_altval" id="estimated_product_volume_altval"
                   value="{{$client->estimated_product_volume_altval}}" class="form-control"/>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });
    </script>
@endpush


