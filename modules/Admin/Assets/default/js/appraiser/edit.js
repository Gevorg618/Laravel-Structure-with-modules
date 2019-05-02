$(function() {
    $( "#appraisers" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                type: 'GET',
                url: actions.appraisers,
                dataType: 'json',
                data: {
                    input: request.term
                },
                success: function(data) {
                    var data = data.users;
                    response( $.map(data, function(item) {

                        if (item.user_data) {
                            return {
                                label: item.user_data.firstname + " " + item.user_data.lastname + " ( " + item.email + " )",
                                value: item.id
                            }
                        }

                    }));
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
            var symbol = ui.item.label,
                value = ui.item.value,
                token = $('input[name="_token"]').val();
            setTimeout(function() {
                var html = '<tr id="tr-'+value+'">'+
                           '<td>'+symbol+'</td>'+
                           '<td style="text-align: center"><i class="fa fa-remove delete-appraiser" data-id="'+value+'" style="cursor: pointer"></i></td>'+
                           '</tr>';
                $('#appraiser-table').append(html);
                $('#empty-appraisers').remove();
                $('#appraisers').val('')
            },0);
            $.ajax({
                type: 'POST',
                url: actions.storeAppraisers,
                data: {
                    group_id: actions.groupId,
                    appraiser_id: value,
                    _token: token
                },
                success:function (data) {
                    console.log(data);
                }
            })
        }
    });

    $(document).on('click', '.delete-appraiser', function () {
        var appraiserId = $(this).attr('data-id'),
            token = $('input[name="_token"]').val();
        $('#tr-'+appraiserId).remove();
        $.ajax({
            type: 'DELETE',
            url: actions.destroyAppraiser,
            data: {
                group_id: actions.groupId,
                appraiser_id: appraiserId,
                _token: token
            },
            success:function (data) {
                if (data.success == '1') {
                    if (data.data.count == 0) {
                       var html = '<tr id="empty-appraisers">'+
                                  '<td colspan="2">no records</td>'+
                                  '</tr>';
                       $('#appraiser-table').append(html);
                    }
                }
            }
        })
    })

});