$(function() {
    var firstRow;
    var table = $app.datatables('#datatable', 'home-page-panels/data', {
        columns: [
            {data: 'move', name: 'move', orderable: false, searchable: false},
            {data: 'image', orderable: false},
            {data: 'title', orderable: false},
            {data: 'active', orderable: false},
            {data: 'created_by', orderable: false},
            {data: 'created_at', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr({'data-id': data.id, 'data-sort': data.sort_ord});
            $( row ).find('td:eq(0)').html($('<div />').html(data.move).text());
            $( row ).find('td:eq(1)').html($('<div />').html(data.image).text());
            setTimeout(function(){ 
                firstRow = $('#datatable tr:has(td)')[0];
            }, 200);
        },
    });
           
    $('input[type=search]').attr('onkeydown', 'offMove()');

    offMove = function () {
        setTimeout(function(){ 
            if($('input[type=search]').val() == '') {
                $( "#datatable tbody" ).sortable( "destroy" );
                _sortable();
            }else {
                $( "#datatable tbody" ).sortable('disable');                    
            }
        }, 400);
    }

    $( "#datatable tbody" ).disableSelection();

    var _sortable = function() {
        $( "#datatable tbody" ).sortable({
            stop: function( event, ui ) {
                var sortId = $(firstRow).find('td:eq(0)').data('sort');
                var tbl = $('#datatable tr:has(td)').map(function(i, v) {
                    var $td =  $('td', this);
                        return {
                                    sort_id: sortId++,
                                    id: $td.eq(0).data('id')
                               };
                }).get();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                  type: "POST",
                  url: 'home-page-panels/reorder',
                  data: {data: tbl, _token: _token}
                });
            }
        });
    };
    _sortable();
});