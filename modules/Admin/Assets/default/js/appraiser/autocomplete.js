$( document ).ready(function() {
    $( "#autocomplete" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                type: 'GET',
                url: actions.managers,
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
            
            var symbol = ui.item.label;
            var value = ui.item.value;
            setTimeout(function() {
                $('#autocomplete').val(symbol);
                $('#managerid').val(value);
            },0);
        }
    });
});