<table class="table table-striped table-bordered table-hover" id="pending_corrections_table">
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
        </tr>
    </thead>
</table>

@push('scripts')
<script>
    $(function() {
        $app.datatables('#pending_corrections_table', '/admin/post-completion-pipelines/appr-uw-pipeline/pending-corrections-data', {
            columns: [
                { data: 'company', orderable: false, searchable: false },
                { data: 'address', orderable: false, searchable: false },
                { data: 'due_date', orderable: false, searchable: false },
                { data: 'uc_risk_score', orderable: false, searchable: false },
                { data: 'rv_overall', orderable: false, searchable: false },
                { data: 'ead', orderable: false, searchable: false },
                { data: 'created_date', orderable: false, searchable: false },
                { data: 'last_uploaded', orderable: false, searchable: false },
            ],
            "searching": false,
            createdRow: function( row, data, dataIndex ) {
                $(row).addClass("view-conditions").attr('id', `condition-${data.id}`);
                $( row ).find('td:eq(1)').html($('<div />').html(data.address).text());
                $( row ).find('td:eq(5)').html($('<div />').html(data.ead).text());
            },
        });
    });
</script>
@endpush
