@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Accounting payable Revert', 'url' => '#']
    ],
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">                        
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group col-md-6">
                                        <label name="payment_id" class="control-label col-lg-3 col-xs-12">Payable ID
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::number('payment_id', null, ['class' => 'form-control', 'id' => 'payment_id']) !!}
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group col-md-6">
                                        <div class="col-lg-12 col-xs-12">
                                            <button type="button" class="btn btn-primary show_payment">Show</button>  
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row report-list-show hidden">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="report-result-datatable">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="checked_all"></th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Zip</th>
                                    <th>Check Number </th>
                                    <th>Check Amount </th>
                                    <th>Pay Date </th>
                                    <th>Order ID </th>
                                    <th>Split </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-danger hidden danger-zone-revert">
                <p><b><span id="count_records"></span></b> Records are selected. Please double check before clicking the button below. there will be no more confirmation screens or messages.</p>
                <p></p>
                <button type="button" class="btn btn-danger delete-records">Revert</button>
            </div>
        </div>
    </div>    
@stop
@push('scripts')
<script> 

    function notificationErrorMessage(message) {
    toastr['error'](message, 'error'.toUpperCase());
    };

    function notificationSuccessMessage(message) {
        toastr['success'](message, 'success'.toUpperCase());
    };

    $(document).on('click', '.delete-records', function() {
        var $payId = $("#payment_id").val();

        var $records = $(".record_id_checkbox:checked").map(function() {return $(this).val();}).get();

        $.ajax({
          url: '/admin/accounting/payable-revert/revert',
          data: {'id': $payId, 'records': $records},
          dataType: 'json',
          type: 'POST',
          success: function(data) {
                $('#checked_all').prop('checked', false);
                showRevert(false);
                if (data.success) {
                    $(".show_payment" ).trigger("click");
                    notificationSuccessMessage(data.message);
                } else {
                    $(".show_payment" ).trigger("click");
                    notificationErrorMessage(data.message);
                }

          }
        });
     });    

    $(document).on('change', '#checked_all', function() {
       
        if(this.checked) {
            $('.record_id_checkbox').prop('checked', true);
            countItems();
            $("#count_records").html($checked);           
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        } else {
            showRevert(false);
            $('.record_id_checkbox').prop('checked', false);
        }
    });

    $(document).on('change', '.record_id_checkbox', function() {
        countItems();
        $("#count_records").html($checked);
    });   
    
    function showRevert(checked){
        if (checked) {
            $('.danger-zone-revert').removeClass('hidden');
        } else {
            $('.danger-zone-revert').addClass('hidden');
        }
    };

    $checked = 0;
    countItems = function() {
        $checked = 0;

        $('.record_id_checkbox').each(function() {
            if($(this).is(':checked')) {
                $checked++;
            }
        });

        if ($checked == 0 ) {
            showRevert(false);
            
        } else {
            showRevert(true);
            
        }
    };

    $(document).on("click",".show_payment",function() {
        
        $('.report-list-show').removeClass('hidden');
        var payId = $('#payment_id').val();

        var requestData = {
            type: 'POST',
            url: '/admin/accounting/payable-revert/data',
            data: {payment_id: payId}
        };

        $app.datatables('#report-result-datatable', requestData, {
            columns: [
                {data: 'checkbox'},
                {data: 'name'},
                {data: 'address'},
                {data: 'city'},
                {data: 'state'},
                {data: 'zip'},
                {data: 'check_number'},
                {data: 'check_amount'},
                {data: 'pay_date'},
                {data: 'orderid'},
                {data: 'split'}
            ],
            iDisplayLength: 50,
            lengthMenu: [ 10, 25, 50, 75, 100 ],
            order : false,
            orderable: false,
            retrieve: false,
            destroy: true,
            searchable: false,
            searching: false,
            ordering: false
        });
    });

</script>
@endpush