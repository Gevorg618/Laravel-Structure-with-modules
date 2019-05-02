<table class="table table-striped table-bordered table-hover" id="awaiting_approval_table">
    <thead>
        <tr>
            <th>Client</th>
            <th>Address</th>
            <th>Due Date</th>
            <th>CU Score</th>
            <th>RV Score</th>
            <th>EAD</th>
            <th>UW Requested</th>
            <th>Last Uploaded</th>
            <th>Assigned To</th>
            <th>Open By</th>
        </tr>
    </thead>
</table>

@push('scripts')
<script>
    $(function() {
        $app.datatables('#awaiting_approval_table', '/admin/post-completion-pipelines/appr-uw-pipeline/awaiting-approval-data', {
            columns: [
                { data: 'company', orderable: false, searchable: false },
                { data: 'address', orderable: false, searchable: false },
                { data: 'due_date', orderable: false, searchable: false },
                { data: 'uc_risk_score', orderable: false, searchable: false },
                { data: 'rv_overall', orderable: false, searchable: false },
                { data: 'ead', orderable: false, searchable: false },
                { data: 'created_date', orderable: false, searchable: false },
                { data: 'last_uploaded', orderable: false, searchable: false },
                { data: 'assigned_to', orderable: false, searchable: false },
                { data: 'locked_by', orderable: false, searchable: false }
            ],
            "searching": false,
            createdRow: function( row, data, dataIndex ) {
                $( row ).find('td:eq(1)').html($('<div />').html(data.address).text());
                $( row ).find('td:eq(3)').html($('<div />').html(data.uc_risk_score).text());
                $( row ).find('td:eq(5)').html($('<div />').html(data.ead).text());
            },
        });
    });
</script>
@endpush
