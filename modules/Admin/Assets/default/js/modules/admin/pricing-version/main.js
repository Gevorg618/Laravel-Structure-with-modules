$(document).ready(function() {

    ajaxRequest();

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        var target = $(e.target).attr("data-type"); // activated tab
        $("#request_type").val(target);

        ajaxRequest();

        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    }); 

    $('#add_pricing_version_btn').on( 'click', function (e) {

        $.get("/admin/autoselect-pricing/versions/pricing-view", function(data, status){
            $('.over_modal').html(data);
            $('.under_modal').html('');
            $('.without_submit').removeClass('hidden');
            $('.modal-title').html('New Version');
            $('#loan_reason').multiselect({maxWidth: '400px', buttonWidth: '250px'});
            $('#myModal').modal('show');
            $('#loan_reason').show();   
            $('.save_data').attr('data-url', '/admin/autoselect-pricing/versions/pricing-store');
            $('.save_data').attr('data-type', 'add_new_pricing');
            
        });

        e.preventDefault();

    });

    $(document).on("click",".save_data",function() {

        let requestUrl = $(this).attr('data-url');

        let dataType = $(this).attr('data-type');

        
        if (dataType == 'update_pricing') {

            let title = $('#title').val();
        
            if (title == '') {
                $('#title').css("border-color", "red");
            } else {

                let loanReson = $('#loan_reason').val();
                let pos = $('#pos').val();

                $.post(requestUrl, {
                    loan_reason: loanReson,
                    title: title,
                    pos: pos

                },
                function(data, status){

                    if (data) {
                        $('#myModal').modal('hide');
                        ajaxRequest();
                        toastr['success']('New Pricing Version was successfully Updated', 'success'.toUpperCase());
                    } else {
                        $('#myModal').modal('hide');
                        toastr['error']('Error !', 'success'.toUpperCase());
                    }

                });
            }

        } else if (dataType == 'view_addenda') {

            
            let data = $("#addenda_view").serializeArray();
            var obj = [];


            var addendaAmounts = $('input[name^=amounts]').map(function(idx, elem) {
                let addendaId = $(this).attr('data-index');
                 obj[addendaId] = $(elem).val();
            }).get();

            $.post(requestUrl, {
                addendas: obj
            },

            function(data, status){

                if (data) {
                    $('#myModal').modal('hide');
                    ajaxRequest();
                    toastr['success']('Pricing Addenda was successfully created', 'success'.toUpperCase());
                } else {
                    $('#myModal').modal('hide');
                    toastr['error']('Error !', 'success'.toUpperCase());
                }

            });
        } else if (dataType == 'add_new_pricing'){
           
            let title = $('#title').val();
        
            if (title == '') {
                $('#title').css("border-color", "red");
            } else {

                let loanReson = $('#loan_reason').val();
                let pos = $('#pos').val();

                $.post(requestUrl, {
                    loan_reason: loanReson,
                    title: title,
                    pos: pos

                },
                function(data, status){

                    if (data) {
                        $('#myModal').modal('hide');
                        ajaxRequest();
                        toastr['success']('New Pricing Version was successfully Created', 'success'.toUpperCase());
                    } else {
                        $('#myModal').modal('hide');
                        toastr['error']('Error !', 'success'.toUpperCase());
                    }

                });
            }
        } else if (dataType == 'view_pricing') {
            alert('sds');
        }
        
        
    });
    
    $(document).on("click",".actions_request",function() {
        
        let requestUrl = $(this).attr('data-attr');
        let dataType = $(this).attr('data-type');

        $.get($(this).attr('data-attr'), function(data, status) {

            $('.over_modal').html(data);
            $('.under_modal').html('');
            $('.modal-title').html('Edit Version');
            $('#loan_reason').multiselect({maxWidth: '400px', buttonWidth: '300px'});
            $('#myModal').modal('show');
            $('#loan_reason').show();

            let pricingId = $('#pricing_id').val();
            
            $('.save_data').attr('data-url', requestUrl)  
            $('.save_data').attr('data-type', dataType);

            switch (dataType) {

                    case 'add_new_pricing':
                            $('.without_submit').removeClass('hidden');
                       break;
                    case 'update_pricing':
                            $('.without_submit').removeClass('hidden');
                       break;
                    case 'add_client':
                            $('.without_submit').addClass('hidden');
                       break;
                    case 'view_pricing':
                            $('.without_submit').addClass('hidden');
                       break;
                    case 'view_addenda':
                            $('.without_submit').removeClass('hidden');
                       break;
                   default:

                    break;
            };

            
        });

    });


    $(document).on("click",".custom_request_option",function() {
        
        let requestUrl = $(this).attr('data-attr');
        let dataType = $(this).attr('data-type');
        $('.without_submit').addClass('hidden');

        $.get($(this).attr('data-attr'), function(data, status) {

            $('.over_modal').html(data);
            $('.under_modal').html('');
            $('.modal-title').html('Edit Version');
            $('#loan_reason').multiselect({maxWidth: '400px', buttonWidth: '300px'});
            $('#myModal').modal('show');
            $('#loan_reason').show();
            
            $('.save_data').attr('data-url', requestUrl)  
            $('.save_data').attr('data-type', dataType);
            
        });

    });

    $(document).on("click",".state_request",function() {
            
        let requestUrl = $(this).attr('data-url');

        $.get(requestUrl, function(data, status) {
            
            $('.under_modal').html(data);
        });

    });

    $(document).on("change",".copy-from",function() {
            
            var $id = $(this).val();
            var $this = $(this).attr('data-id');

            if(!$id) {
                return false;
            }

            var $source = $('.loan-type-record-' + $id);
            var $dest = $('.loan-type-record-' + $this);

            $.each($source, function(i, item) {
                var $value = $(this).find('.price-value').val();
                var $appraisalTypeId = $(this).find('.price-value').attr('data-appr-id');
                // Set to our column
                $dest.find('.appr-type-id-' + $appraisalTypeId).val($value);
            })
    });

    function ajaxRequest() {
            
            var request_type = $("#request_type").val();

            var requestData = {
                url: '/admin/autoselect-pricing/versions/data',
                data: {request_type:request_type}
            };
            
            switch (request_type) {

                    case 'pricing':

                            $app.datatables('#pricing-datatable', requestData, {
                                columns: [
                                     {data: 'title'},
                                     {data: 'position'},
                                     {data: 'clients'},
                                     {data: 'set_records'},
                                     {data: 'empty_records'},
                                     {data: 'suppose_records'},
                                     {data: 'records'},
                                     {data: 'options'}
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
                       break;
                    case 'custom_pricing':
                            $app.datatables('#pricing-custom-datatable', requestData, {
                                columns: [
                                     {data: 'client'},
                                     {data: 'set_records'},
                                     {data: 'empty_records'},
                                     {data: 'suppose_records'},
                                     {data: 'records'},
                                     {data: 'options'}
                                ],
                                iDisplayLength: 50,
                                lengthMenu: [ 10, 25, 50, 75, 100 ],
                                order : false,
                                orderable: false,
                                retrieve: false,
                                destroy: true,
                                searchable: false,
                                searching: false,
                                bPaginate: false,
                                ordering: false
                            });
                       break;  
                   default:
                    
                
                    break;
            }
    }
});