<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="panel-body panel-body-table">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sent-datatable" style="width: 100%!important;">
                            <thead>
                                <tr>
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
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    var $pendingTable;
    $(function() {
        $pendingTable = $('#sent-datatable').dataTable( {
            "dom": 	"Bfrtip",
            buttons: [
                {
                    extend: 'colvis',
                    text: 'Change Columns',
                    columns:[2,3,4,5,6,7],
                }
            ],
            colReorder: true,
            order : [ [ 0, 'asc' ] ],
            "columnDefs": [
                    { visible: false, targets: [7] },
                    { sortable: false, targets: [2, 8] }
            ],
            "iDisplayLength": 50,
            "processing": true,
            "serverSide": true,

            "ajax":{
                     "url": "mail-pipeline/sent-data",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "id" },
                { "data": "type" },
                { "data": "borrower" },
                { "data": "requested_date" },
                { "data": "requested_by" },
                { "data": "marked_sent_date" },
                { "data": "marked_sent_by" },
                { "data": "delivered_date" },
                { "data": "options" }
            ]
        });
    });
</script>
@endpush
