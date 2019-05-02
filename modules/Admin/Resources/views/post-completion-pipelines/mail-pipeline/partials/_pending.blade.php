<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="panel-body panel-body-table">
                    <span class="label label-info">Represents records ready to be mailed</span> &nbsp; <span class="label label-success">Represents records that have an image label</span>
                    <br /><br />
                    <div class="table-responsive" style="padding-bottom: 30px;">
                        <table class="table table-striped table-bordered table-hover" id="panding-datatable" style="width: 100%!important;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="check-all-labels" id="check-all-labels" class="check-all" /></th>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Borrower</th>
                                    <th>Requested Date</th>
                                    <th>Requested By</th>
                                    <th>Marked Sent Date</th>
                                    <th>Marked Sent By</th>
                                    <th>Delivered Date</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <button class='btn btn-primary' id="print-selected-shipping-labels">Print Selected Labels</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    var $pendingTable;
    $(function() {
        $pendingTable = $('#panding-datatable').dataTable({
            "dom": 	"Bfrtip",
            buttons: [
                {
                    extend: 'colvis',
                    text: 'Change Columns',
                    columns:[1,2,3,4,5,6,7],
                }
            ],
            colReorder: true,
            order : [ [ 1, 'asc' ] ],
            "columnDefs": [
                    { visible: false, targets: [5,6,7] },
                    { sortable: false, targets: [2, 8] }
            ],
            "iDisplayLength": 50,
            "processing": true,
            "serverSide": true,

            "ajax":{
                     "url": "mail-pipeline/pending-data",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "checkbox", "orderable": false },
                { "data": "id" },
                { "data": "type" },
                { "data": "borrower" },
                { "data": "requested_date" },
                { "data": "requested_by" },
                { "data": "marked_sent_date" },
                { "data": "marked_sent_by" },
                { "data": "delivered_date" },
                { "data": "options" }
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).addClass(data.checkbox != '' ? "success item-row" : '').attr('id', data.id);
            },
        });
    });
</script>
@endpush
