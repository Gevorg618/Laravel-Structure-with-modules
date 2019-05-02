<div class="col-md-12">
    <div class="row" style="margin-top:10px;">
        <div class="col-md-12">
            <form class="form-inline" role="form">
                <div class="form-group filters_form">
                    @include('admin::ticket.manager.templates._filters_form')
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <form class="form-inline form-legend" role="form">
                <label>Legend:</label>
                <span class="label label-default">0 minutes to 30 minutes</span>
                <span class="label label-info">0:31 minutes to 2:00 hours</span>
                <span class="label label-warning">2:01 hours to 3:00 hours</span>
                <span class="label label-danger">3:01 + hours</span>
                <span class="label label-success">Closed Date & Time</span>
            </form>
        </div>
    </div>
    <hr/>
    <div class="table-responsive">
        <table width="100%" class="table table-striped table-hover" id="tickets_table">
            <thead>
            <tr>
                <th style="padding-left:10px;">
                {!! Form::checkbox('select_all', 1, false, ['id' => 'select_all', 'class' => 'regular-checkbox']) !!}
                <th>ID</th>
                <th>&nbsp;</th>
                <th>Created</th>
                <th>Last Comment</th>
                <th>Subject</th>
                <th>From</th>
                <th>Order</th>
                <th>Status</th>
                <th>Category</th>
                <th>Assigned</th>
                <th>Time</th>
                <th>Borrower</th>
                <th>Options</th>
            </tr>
            </thead>
        </table>
    </div>
    <hr/>
    <div class="row" style="margin-top:10px;">
        <div class="col-md-12">
            <form class="form-inline" role="form">
                <div class="form-group filters_form">
                    @include('admin::ticket.manager.templates._moderation')
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
      var $ticketsTable;

      $(document).ready(function () {
        $ticketsTable = $('#tickets_table').dataTable({
          "dom": "<'row'<'col-md-6'Cl><'col-md-6'f>r>" +
          "t" +
          "<'row'<'col-md-6'i><'col-md-6'p>>",
          "oLanguage": {
            "sLoadingRecords": "Please wait - loading...",
            "sProcessing": "Please wait - loading...",
            "sSearch": "Search: _INPUT_",
            "sLengthMenu": "Show _MENU_ records per page",
            "sEmptyTable": "There are no records to display at this time."
          },
          "oSearch": {"sSearch": "{{ $request->search['value'] }}"},
          "iDisplayLength": 25,
          "processing": true,
          "serverSide": true,
          stateSave: true,
          "ajax": {
            "url": "{{ route('admin.ticket.manager.get_tickets') }}",
            "data": function (d) {
              $.extend(d, getFilterConditions());
            }
          },
          "fnDrawCallback": function (oSettings, json) {
            $app.updateTimeSinceCreated();
            registerInlineEdits();
          },
          "colVis": {
            "buttonText": "Change Columns",
            "exclude": [0, 2, 13]
          },
          "columnDefs": [
            {"width": "1%", "targets": [0, 2, 13]},
            {"width": "2%", "targets": [2]},
            {"width": "20%", "targets": [5]},
            {visible: false, targets: [1, 4, 7, 8, 9, 12]},
            {sortable: false, targets: [0, 2, 13]}
          ],
          "columns": [
            {"data": "id"},
            {"data": "id"},
            {"data": "helper"},
            {"data": "created"},
            {"data": "last_comment"},
            {"data": "subject"},
            {"data": "from_content"},
            {"data": "order"},
            {"data": "status"},
            {"data": "category"},
            {"data": "assigned"},
            {"data": "time"},
            {"data": "borrower"},
            {"data": "options"}
          ],
          "order": [[4, "desc"]],
          "pagingType": "full_numbers"
        });
      });
    </script>
@endpush