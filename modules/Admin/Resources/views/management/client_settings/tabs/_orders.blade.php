@push('style')
    <style>
        .borderless tr, .borderless td, .borderless th {
            border: none !important;
        }

    </style>
@endpush
<div class="col-md-10" style="height: 500px">
    <h2>Search For Orders</h2>
    <div class="form-group">
        <label for="orders_date_from" class="col-md-4">Date Range</label>
        <div class="col-md-2">
            <input type="text" name="orders_date_from" id="orders_date_from"
                   class="form-control datepicker" placeholder="From"/>
        </div>

        <div class="col-md-2">
            <input type="text" name="orders_date_to" id="orders_date_to"
                   class="form-control datepicker" placeholder="To"/>
        </div>
    </div>
    <div>
        <div class="form-group ">
            <label for="orders_types" class="col-md-4">Appraisal Type</label>
        </div>
        <div class="form-group ">
            <div class="col-md-4">
                <select name="orders_types[]" id="orders_types"
                        class="form-control multiselect bootstrap-multiselect multiselect-apprTypeList"
                        multiple="multiple">
                    @foreach($apprTypeList as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div id="search_orders_content">

    </div>
    <div class="col-md-12 _footer">
        <button type="button" id="search_orders" class="btn btn-success">Search</button>
    </div>
    <input type="hidden" value="{{$client->id}}" id="group_id">
    <input type="hidden" value="{{route('admin.management.client.search.orders')}}" id="search_orders_url">

</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        });
    </script>
    <script src="{{ masset('js/management/client_settings/orders_search.js') }}"></script>
@endpush


