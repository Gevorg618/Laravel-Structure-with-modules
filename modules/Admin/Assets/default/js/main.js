$app.updateTimeSinceCreated = function () {
  $.each($('.time-show-since'), function () {
    var $time = $(this).attr('data-time');
    var $_moment = moment.tz($time, $landmark.settings.timezone);
    var $now = moment().tz($landmark.settings.timezone);
    var $seconds = $now.diff($_moment, 'minutes');
    var $minutes = $now.diff($_moment, 'minutes');
    var $hours = $now.diff($_moment, 'hours');
    var $days = $now.diff($_moment, 'days');
    var $dur = moment.duration(($now.unix() - $_moment.unix()), 'seconds');
    var $duration = moment.duration($dur.asSeconds() - 1, 'seconds');
    var $text = '';
    if ($duration.years() > 0) {
      $text += $duration.years() + 'y:';
    }
    if ($duration.months() > 0) {
      $text += $duration.months() + 'm:';
    }
    if ($duration.days() > 0) {
      $text += $duration.days() + 'd:';
    }
    if ($duration.hours() > 0) {
      $text += $duration.hours() + 'h:';
    }
    if ($duration.minutes() > 0) {
      $text += $duration.minutes() + 'm:';
    }
    if ($duration.seconds() > 0) {
      $text += $duration.seconds() + 's';
    }
    $(this).removeClass('label-default').removeClass('label-info').removeClass('label-warning').removeClass('label-danger');
    if ($hours >= 3) {
      $(this).addClass('label-danger');
    } else if ($hours >= 2) {
      $(this).addClass('label-warning');
    } else if ($minutes > 30) {
      $(this).addClass('label-info');
    } else {
      $(this).addClass('label-default');
    }
    if ($_moment.isValid()) {
      $(this).html($text);
    } else {
      $(this).html('');
    }
  });
}

$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#show_orders_div').on('click', '#select_all_orders', function() {
        var $checked = $(this).is(':checked');
        // Find the checkbox in this tr
        $('.order-checkbox').each(function() {
            $(this).prop('checked', $checked);
            if($checked) {
                $(this).parent().parent().addClass('mark-order-row');
            } else {
                $(this).parent().parent().removeClass('mark-order-row');
            }
        });

        // Update count
        updateSelectedOrdersCount();
    }).on('click', '.order-tr-row', function (event) {
        event.stopPropagation();
        // Find the checkbox in this tr
        var $checkBox = $(this).find('.order-checkbox');
        modifyClassOnOrderCheckbox($checkBox);

        // Update count
        updateSelectedOrdersCount();
    }).on('click', '.order-checkbox', function (event) {
        //event.stopPropagation();
        // Find the checkbox in this tr
        modifyClassOnOrderCheckbox($(this));

        // Update count
        updateSelectedOrdersCount();
    }).on('change', '.amount-charge-val', function () {
        updateSelectedOrdersCount();
    }).on('submit', '#apply_batch_check_docuvault_form', function (event) {
        event.preventDefault();
        // Make sure we have at least one selected
        $totalSelected = updateSelectedOrdersCount();
        if(!$totalSelected) {
            alert('Please Select at least one record.');
            return false;
        }

        $failed = false;
        $ids = getIdsFromOrderCheckbox();

        if($failed) {
            return false;
        }

        $.post('/admin/accounting/batch-docuvault-check/apply-batch-check', {
            'date': $('#date').val(),
            'check_number': $('#check_number').val(),
            'from': $('#from').val(),
            'type': $('#type').val(),
            'ordertype': $('#ordertype').val(),
            'ids': $ids
        }).done(function (data) {
            $('#show_orders_div').html("<h2>Check Applied! Please Wait, Reloading...</h2>");
            setTimeout("$('#show_orders').trigger('click')", 1500);
        }).error(function (errors) {
            alert(errors.responseJSON.message);
        })
    }).on('submit', '#apply_batch_docuvault_check_cc_form', function (e) {
        e.preventDefault();
        // Make sure we have at least one selected
        var $totalSelected = updateSelectedOrdersCount();
        if(!$totalSelected) {
            alert('Please Select at least one record.');
            return false;
        }

        var $failed = false;
        var $ids = getIdsFromOrderCheckbox();
        
        $.each($('#credit_card_tab').find('input[type=text]'), function(i, input) {
            if(input.value == '' || !input.value) {
                var $label = $("label[for='"+input.id+"']");
                if($label) {
                    alert("Please fill out the " + $label.html() + "field.");
                } else {
                    alert('All fields are required, Please make sure you have filled out all of them.');
                }
                $failed = true;
                return false;
            }
        });

        if($failed) {
            return false;
        }

        // Check first name and lastname if it has period (.) or one letter
        var $firstname = $.trim($('#first_name').val());
        var $lastname = $.trim($('#last_name').val());
        if($firstname.search(/\./g) > 0) {
            alert('Sorry, It appears that you entered an abbreviation in the First Name field ('+$firstname+') Please enter your Full First Name (one word, no spaces, no periods and no abbreviation. First Name should be at least two characters longer).');
            return false;
        }

        if($firstname.replace(/\./g, '').length <= 1) {
            alert('Sorry, It appears that you entered an abbreviation in the First Name field ('+$firstname+') Please enter your Full First Name (one word, no spaces, no periods and no abbreviation. First Name should be at least two characters longer).');
            return false;
        }

        if($lastname.search(/\./g) > 0) {
            alert('Sorry, It appears that you entered an abbreviation in the Last Name field ('+$lastname+') Please enter your Full Last Name (one word, no spaces, no periods and no abbreviation. Last Name should be at least two characters longer).');
            return false;
        }

        if($lastname.replace(/\./g, '').length <= 1) {
            alert('Sorry, It appears that you entered an abbreviation in the First Name field ('+$firstname+') Please enter your Full First Name (one word, no spaces, no periods and no abbreviation. Last Name should be at least two characters longer).');
            return false;
        }

        if($('#card_number').val().match(/^6/)) {
            $('#credit-error').show();
            $('#card_number').addClass('invalid-input');
            return false;
        } else {
            $('#credit-error').hide();
            $('#card_number').removeClass('invalid-input');
        }

        // Disable the submit button
        $('#entire_batch').css({'opacity': '0.5'});
        $('#loading').show();
        var url = '/admin/accounting/batch-docuvault-check/apply-batch-cc-check';
        $.post(url, {
            client: $('#client').val(),
            firstname: $firstname,
            lastname: $lastname,
            address: $('#address').val(),
            city: $('#city').val(),
            state: $('#state').val(),
            zip: $('#zip').val(),
            card_number: $('#card_number').val(),
            card_exp_month: $('#card_exp_month').val(),
            card_exp_year: $('#card_exp_year').val(),
            card_cvv: $('#card_cvv').val(),
            ids: $ids,
            ordertype: $('#ordertype').val()
        }).done(function (data) {
            if (data.error) {
                alert(data.error);
            } else {
                $('#show_orders_div').html("<h2>"+data.html+"</h2>");

                setTimeout("$('#show_orders_form').trigger('submit');", 1500);
            }
        }).error(function (errors) {
            alert(errors.responseJSON.message);
        });
    });

    if ($('#batch_show_orders_form').length) {
        $('#batch_show_orders_form').submit(function (event) {
            $("#loading").show();
            event.preventDefault();
            var url = '/admin/accounting/batch-docuvault-check/show-orders';
            var dateFrom = $('#date_from').val();
            var dateTo = $('#date_to').val();
            var orderType = $('#ordertype').val();
            var clients = $('#clients').val();
            $.post(url, {
                date_from: dateFrom,
                date_to: dateTo,
                ordertype: orderType,
                clients: clients
            }).done(function (data) {
                $('#show_orders_div').html(data.html);
                $('.datepicker').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                $("#loading").hide();
            }).error(function (errors) {
                $("#loading").hide();
                var response = JSON.parse(errors.responseText);
                var errorString = '<ul>';
                $.each( response.errors, function( key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                toastr['error'](errorString, 'error'.toUpperCase());
            });
        })
    }
    $('#category').on('change', function() {
        var $val = $(this).val();

        if(!$val) {
            return;
        }
        $("#loading").show();
        var url = '/admin/appr_qc_checklist/parent_questions_for_category/' + $val;
        $.post(url).done(function (data) {
            
            if(data.error && data.error != '') {
                alert(data.error);
                return false;
            }

            $('#parent_question_div').html(data.html);
        });
    });

    toggleQuestion();

    if ($('#checklist_tab').length) {
        $('[data-toggle="tabajax"]').click(function(e) {
            e.preventDefault();
            var loadurl = $(this).data('url');
            var target = $(this).attr('href');
            $.post(loadurl, function(data) {
                $(target).html(data.html);
                sortable('.questions_' + target.replace('#', '') + '_div');
                toggleQuestion();
            });
            $(this).tab('show');
        });
    }

    sortable('.questions_div');

    if ($('#ap_calendar').length) {
        $('#ap_calendar').fullCalendar({
            editable: false,
            height: 1200,
            contentHeight: 800,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            eventSources: [
                {
                    url: '/admin/ap_calendar/load',
                    type: 'GET',
                    error: function (data) {
                        console.log('ERROR!' + data);
                    }
                }
            ],
            eventClick: function (calEvent, jsEvent, view) {

            }
        });
    }
    if ($('.datepicker').length) {
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            ignoreReadonly: true,
            widgetPositioning: {
                horizontal: 'auto',
                vertical: 'auto'
            }
        });
    }

    if ($('.number_total').length) {
        $('.number_total').html($('.order-row').length);
    }

    if ($('#accounts_payable_show').length) {
        $('#accounts_payable_show').click(function (e) {
            e.preventDefault();
            var dateFrom = $('#date_from').val();
            var dateTo = $('#date_to').val();
            var clients = $('#clients').val();
            var states = $('#states').val();
            var url = '/admin/accounting/payable-reports/data';
            $.post(url, {
                date_from: dateFrom,
                date_to: dateTo,
                clients: clients,
                states: states
            }).done(function (data) {
                $('#payables_data').html(data.html);
            }).error(function (errors) {
                alert(errors.responseJSON.message);
            })
        })
    }

    if ($('#batch_check_form').length) {
        $('#batch_check_form').submit(function (e) {
            e.preventDefault();
            var dateFrom = $('#date_from').val();
            var dateTo = $('#date_to').val();
            var clients = $('#clients').val();
            var url = '/admin/accounting/batch-check/data';
            var type = $('#type').val();
            $.post(url, {
                date_from: dateFrom,
                date_to: dateTo,
                clients: clients,
                type: type
            }).done(function (data) {
                $('#show_orders_div').html(data.html);
                $('#date').datetimepicker({
                    format: "YYYY-MM-DD"
                });
            }).error(function(errors) {
                var response = JSON.parse(errors.responseText);
                var errorString = '<ul>';
                $.each( response.errors, function( key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul>';
                
                toastr['error'](errorString, 'error'.toUpperCase());
            })
        });
        $('#show_orders_div').on('submit', '#apply_batch_check_form', function (e) {
            e.preventDefault();
            $totalSelected = updateSelectedOrdersCount();
            if(!$totalSelected) {
                alert('Please Select at least one record.');
                return false;
            }

            $ids = getIdsFromOrderCheckbox();

            if(!$ids) {
                return false;
            }

            // Make sure check number and date are entered
            if(!$('#check_number').val() || !$('#date').val()) {
                alert('Please make sure to enter the check number and the date it was received.');
                return false;
            }
            var date = $('#date').val();
            var checkNumber = $('#check_number').val();
            var from = $('#from').val();
            var type = $('#check_type').val();
            var additional = $('#additional').prop('checked');
            var url = '/admin/accounting/batch-check/apply-batch-check';
            $.post(url, {
                date: date,
                check_number: checkNumber,
                from: from,
                type: type,
                additional: additional,
                ids: $ids
            }).done(function (data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    $('#show_orders_div').html("<h2>Check Applied! Please Wait, Reloading...</h2>");
                    setTimeout("$('#batch_check_form').trigger('submit');", 1500);
                }
            }).error(function (errors) {
                alert(errors.responseJSON.message);
            })
        }).on('submit', '#apply_batch_cc_form', function (e) {
            e.preventDefault();
            // Make sure we have at least one selected
            var $totalSelected = updateSelectedOrdersCount();
            if(!$totalSelected) {
                alert('Please Select at least one record.');
                return false;
            }

            var $ids = getIdsFromOrderCheckbox();

            $.each($('#credit_card_tab').find('input[type=text]'), function(i, input) {
                if(input.value == '' || !input.value) {
                    var $label = $("label[for='"+input.id+"']");
                    if($label) {
                        alert("Please fill out the " + $label.html() + "field.");
                    } else {
                        alert('All fields are required, Please make sure you have filled out all of them.');
                    }
                    return false;
                }
            });

            // Check first name and lastname if it has period (.) or one letter
            var $firstname = $.trim($('#firstname').val());
            var $lastname = $.trim($('#lastname').val());
            if($firstname.search(/\./g) > 0) {
                alert('Sorry, It appears that you entered an abbreviation in the First Name field ('+$firstname+') Please enter your Full First Name (one word, no spaces, no periods and no abbreviation. First Name should be at least two characters longer).');
                return false;
            }

            if($firstname.replace(/\./g, '').length <= 1) {
                alert('Sorry, It appears that you entered an abbreviation in the First Name field ('+$firstname+') Please enter your Full First Name (one word, no spaces, no periods and no abbreviation. First Name should be at least two characters longer).');
                return false;
            }

            if($lastname.search(/\./g) > 0) {
                alert('Sorry, It appears that you entered an abbreviation in the Last Name field ('+$lastname+') Please enter your Full Last Name (one word, no spaces, no periods and no abbreviation. Last Name should be at least two characters longer).');
                return false;
            }

            if($lastname.replace(/\./g, '').length <= 1) {
                alert('Sorry, It appears that you entered an abbreviation in the First Name field ('+$firstname+') Please enter your Full First Name (one word, no spaces, no periods and no abbreviation. Last Name should be at least two characters longer).');
                return false;
            }

            if($('#card_number').val().match(/^6/)) {
                $('#credit-error').show();
                $('#card_number').addClass('invalid-input');
                return false;
            } else {
                $('#credit-error').hide();
                $('#card_number').removeClass('invalid-input');
            }

            // Disable the submit button
            $('#entire_batch').css({'opacity': '0.5'});
            $('#loading').show();
            var url = '/admin/accounting/batch-check/apply-batch-cc';
            $.post(url, {
                client: $('#client').val(),
                firstname: $firstname,
                lastname: $lastname,
                address: $('#address').val(),
                city: $('#city').val(),
                state: $('#state').val(),
                zip: $('#zip').val(),
                card_number: $('#card_number').val(),
                card_exp_month: $('#card_exp_month').val(),
                card_exp_year: $('#card_exp_year').val(),
                card_cvv: $('#card_cvv').val(),
                ids: $ids
            }).done(function (data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    $('#show_orders_div').html("<h2>Credit Card Payment Applied! Please Wait, Reloading...</h2>");

                    setTimeout("$('#show_orders_form').trigger('submit');", 1500);
                }
            }).error(function (errors) {
                alert(errors.responseJSON.message);
            });
        })
    }
    
    if ($('#accounts_receivable_show').length) {
        
        $('#invoiced_form').submit(function (e) {
            $('#loading').show();
            e.preventDefault();
            var filter = $('#filter').val();
            var credits = $('#credits').val();
            var url = '/admin/accounting/receivable-reports/invoiced';
            $.post(url, {
                filter: filter,
                credits: credits
            }).done(function (data) {
                $('#invoiced_data').html(data.html);
                $('#loading').hide();
            }).error(function (errors) {
                $('#loading').hide();
                alert(errors.responseJSON.message);
            })
        })
        $('[data-toggle="tabajax"]').click(function(e) {
            $('#loading').show();
            e.preventDefault();
            var loadurl = $(this).data('url');
            var target = $(this).attr('href');
            var filter = $('#filter').val();
            var credits = $('#credits').val();
            $.post(loadurl, {
                filter: filter,
                credits: credits
            }).done(function(data) {
                $(target).html(data.html);
                $('#loading').hide();
            }).error(function (errors) {
                $('#loading').hide();
                $(target).html("<p>There is no data</p>");
                alert(errors.responseJSON.message);
            });
            $(this).tab('show');
        });
        $('div#invoiced').on('click', 'ul.pagination li a', function (e) {
            e.preventDefault();
            dailyBatchPaginate('/admin/accounting/receivable-reports/invoiced', '#invoiced', e);
        });
        $('div#invoiced').on('click', '#check-all', function () {
            checkAllClients();
        });
        $('div#noninvoiced').on('click', '#check-all', function () {
            checkAllClients();
        });
        $('div#noninvoiced').on('click', 'ul.pagination li a', function (e) {
            e.preventDefault();
            dailyBatchPaginate('/admin/accounting/receivable-reports/noninvoiced', '#noninvoiced', e);
        });
        $('div#invoiced').on('submit', '#view_clients_form', function (e) {
            var $checked = 0;
            var ids = [];
            $('.client-checkbox').each(function(i, item) {
                if($(item).is(':checked')) {
                    ids.push($(item).val());
                    $checked++;
                }
            });
            $('#ids').val(JSON.stringify(ids));
            $('#filter_hidden').val($('#filter').val());
            $('#credits_hidden').val($('#credits').val());

            if(!$checked) {
                alert('Select at least one client to view.');
                return false;
            }

            return true;
        })
        runActiveTab();
    }

    if ($('#view_clients_report_form').length) {

        $('#check-all').click(function() {

            var $parent = $(this).parent().parent().parent().parent().parent().parent();
            var $value = $(this).is(':checked') ? true : false;
            
            $parent.find('.order-checkbox').prop('checked', $value);
            
            updateCount();
        });

        $('.order-checkbox').click(function() {
            updateCount();
        });

        updateCount();

        $('#view_clients_report_form').submit(function () {

            var $checked = 0;
            $('.order-checkbox').each(function(i, item) {
                if($(item).is(':checked')) {
                    $checked++;
                }
            });

            if(!$checked) {
                alert('Select at least one client to report.');
                return false;
            }

            return true;
        })
    }
    
    if ($('#reset_locate_payments').length) {
        $('#reset_locate_payments').click(function () {
            $('#term').val('');
        })
    }

    if ($('#add-note').length) {
        $('#add-note').on('click', function() {
            console.log(CKEDITOR.instances.user_note.getData());
            var $userNote = CKEDITOR.instances.user_note.getData();
            var id = $(this).data('id');
            if(!$userNote || $userNote.length < 3) {
                alert('Please enter a user note. At least 3 characters long.');
                return false;
            }
            $.ajax({
                url: '/admin/users/'+id+'/add-note',
                type: 'POST',
                data: {
                    'note': $userNote
                },
                dataType: 'json',
                success: function(data) {
                    if(data.error) {
                        alert(data.error);
                        return false;
                    }

                    $('#groupnotes').html(data.html);
                }
            });
        });
    }

    $('#additional-document-type').on('change', function() {
        if($(this).val()) {
            $('#additional-document').prop('disabled', false);
        } else {
            $('#additional-document').prop('disabled', true);
        }
    });

    $('#backgroundfile').ajaxfileupload({
        'action': '/admin/users/' + $('#user_id').val() + '/backgroundcheck-upload',
        'params': {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        'valid_extensions': ['pdf'],
        'onComplete': function(response) {
            if(response.error) {
                alert(response.error);
            } else {
                $('#backgroundcheck-row-div').html(response.html);
                $('#backgroundfile').val('');
            }
        },
        'onStart': function() {
            // Show Loading Icon
            $('#backgroundcheck-row-div').html('Loading...');
        }
    });

    $('#additional-document').ajaxfileupload({
        'action': '/admin/users/' + $('#user_id').val() + '/additional-document',
        'params': {
            'documentType':  function() {return $('#additional-document-type').val()},
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        'valid_extensions': ['pdf', 'xml', 'jpeg', 'doc', 'png', 'gif', 'txt'],
        'onComplete': function(response) {

            if(response.error) {
                alert(response.error);
            } else {
                $('#additional-document-row-div').html(response.html);
                $('#additional-document').val('');
            }
        },
        'onStart': function() {
            // Show Loading Icon
            $('#additional-document-row-div').html('Loading...');
        }
    });

    $('#eandofile').ajaxfileupload({
        'action': '/admin/users/' + $('#user_id').val() + '/eando-upload',
        'params': {
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        'valid_extensions': ['pdf'],
        'onComplete': function(response) {
            if(response.error) {
                alert(response.error);
            } else {
                $('#eando-row-div').html(response.html);
                $('#eandofile').val('');
            }
        },
        'onStart': function() {
            // Show Loading Icon
            $('#eando-row-div').html('Loading...');
        }
    });

    $('#licensefile').ajaxfileupload({
        'action': '/admin/user.php?action=license-upload',
        'params': {
            'userId': $('#user_id').val()
        },
        'valid_extensions': ['pdf'],
        'onComplete': function(response) {
            if(response.error) {
                alert(response.error);
            } else {
                $('#license-row-div').html(response);
                $('#licensefile').val('');
            }
        },
        'onStart': function() {
            // Show Loading Icon
            $('#license-row-div').html('Loading...');
        }
    });

    $('.update-documents-cache').click(function() {
        Ladda.create( document.querySelector( '.ladda-button' ) ).start();

        $.ajax({
            url: '/admin/user.php',
            data: {
                'action': 'refresh-documents-cache',
                'id': $('#user_id').val()
            },
            dataType: 'json',
            success: function(data) {
                if(data.error) {
                    Ladda.create( document.querySelector( '.ladda-button' ) ).stop();
                    alert(data.error);
                    return false;
                }
                window.location.reload();
            }
        });

    });

    $('#new_license_file').ajaxfileupload({
        'action': '/admin/user.php?action=new-state-license',
        'params': {
            'userId': $('#user_id').val(),
            'new_license_state': function() {return $('#new_license_state').val()},
            'new_license_number': function() {return $('#new_license_number').val()},
            'new_license_expire': function() {return $('#new_license_expire').val()},
        },
        'valid_extensions': ['pdf'],
        'submit_button': $('#add_state_license'),
        'empty_file': true,
        'onComplete': function(response) {
            console.log(response);
            if(response.error) {
                $('#add_state_license').show();
                $('#loading_new_state').hide();
                alert(response.error);
            } else {
                $('#new_license_state_div').html(response);

                $('#new_license_state, #new_license_number, #new_license_expire, #new_license_file').val('');
                $('#add_state_license').show();
                $('#loading_new_state').hide();
            }

        },
        'onStart': function() {
            // Show Loading Icon
            $('#add_state_license').hide();
            $('#loading_new_state').show();
        }
    });

    if ($('#add-card').length) {
        $('#add-card').on('click', function() {
            var $fields = {
                'cc_firstname': 'First Name',
                'cc_lastname': 'Last Name',
                'cc_address': 'Billing Address',
                'cc_city': 'Billing City',
                'cc_state': 'Billing State',
                'cc_zip': 'Billing Zip',
                'cc_number': 'Credit Card Number',
                'cc_exp_month': 'Credit Card Expiration Month',
                'cc_exp_year': 'Credit Card Expiration Year',
                'cc_cvv': 'Credit Card CVV Number'
            };
            var $values = {};
            var $hasError = false;

            $.each($fields, function(k, v) {
                if(!$('#' + k).val()) {
                    $('#' + k).focus();
                    $hasError = true;
                    alert('Sorry, You must fill out the ' + v + ' Field.');
                    return false;
                }
                $values[k] = $('#' + k).val();
            });

            if($hasError) {
                return false;
            }

            // Set the button disabled and loadin
            $('#add-card').attr('disabled', 'disabled');
            $('#add-card').html('Loading, Please Wait...');
            var $userId = $('#user_id').val();

            $.ajax({
                url: '/admin/users/'+$userId+'/add-cc-card',
                data: {
                    'action': 'add-cc-card',
                    'userId': $userId,
                    'ccInfo': $values
                },
                type: 'POST',
                dataType: 'json',
                success: function(data) {

                    // Set the button disabled and loadin
                    $('#add-card').removeAttr('disabled');
                    $('#add-card').html('Add Card');

                    if(data.error) {
                        alert(data.error);
                        return false;
                    }

                    // Update current card
                    $('#current-cc-card').html(data.html);

                    // Reset all fields
                    $('.cc_info').val('');

                    alert('Credit Card Added Successfully.');
                }
            });

        });
    }

    if ($('#load-user-logs').length) {
        $('#load-user-logs').on('click', function() {
            var userId = $(this).data('id');
            if(!userId) {
                return false;
            }


            $.ajax({
                url: '/admin/users/' + userId + '/load-user-logs',
                type: 'POST',
                dataType: 'json',
                success: function(data) {

                    if(data.error && data.error != '') {
                        alert(data.error);
                        return false;
                    }

                    if(data.html) {
                        // Remove the tr rows
                        $('#user-logs-list-div').html(data.html);
                    }
                }
            });
            $('.user-logs-list').hide();
        });

        $('div#userlog').on('click', 'ul.pagination li a', function (e) {
            var userId = $('#user_id').val();
            e.preventDefault();
            dailyBatchPaginate('/admin/users/' + userId + '/load-user-logs', '#user_log_records', e);
        });

        $('#remove-user-logs').on('click', function() {
            var userId = $('#user_id').val();
            if(!userId) {
                return false;
            }


            $.ajax({
                url: '/admin/users/' + userId + '/delete-user-logs',
                type: 'POST',
                dataType: 'json',
                success: function(data) {

                    if(data.error && data.error != '') {
                        alert(data.error);
                        return false;
                    }
                    $('.user-logs-list').hide();
                    $('#user-logs-list-div').html('');
                    $('.user-logs-list').show();
                }
            });
        });
    }

    $('#send-email').on('click', function() {
        var $subject = $('#email_subject').val();
        var $message = CKEDITOR.instances.email_content.getData();
        var userId = $('#user_id').val();

        if(!$subject || $subject.length <= 3) {
            alert("Please enter a subect.");
            return false;
        }

        if(!$message || $message.length <= 3) {
            alert("Please enter a message.");
            return false;
        }

        // Set the button disabled and loadin
        $('#send-email').attr('disabled', 'disabled');
        $('#send-email').html('Loading, Please Wait...');

        $.ajax({
            url: '/admin/users/' + userId + '/send-email',
            data: {
                'subject': $subject,
                'message': $message
            },
            dataType: 'json',
            type: 'POST',
            success: function(data) {

                // Set the button disabled and loadin
                $('#send-email').removeAttr('disabled');
                $('#send-email').html('Send Email');

                if(data.error) {
                    alert(data.error);
                    return false;
                }

                // Reset
                $('#email_subject').val('');

                var editorInstance = CKEDITOR.instances.email_content;
                editorInstance.setData('');

                alert('Email Sent.');
            }
        });
    });



    // Update email template
    $('#email_template').on('change', function() {
        var $id = $(this).val();
        if(!$id) {
            return false;
        }
        var userId = $('#user_id').val();
        if(!userId) {
            return false;
        }

        $.ajax({
            url: '/admin/users/' + userId + '/get-email-template',
            type: 'POST',
            data: {
                'templateId': $id
            },
            dataType: 'json',
            success: function(data) {
                if(data.error && data.error != '') {
                    alert(data.error);
                    return;
                }

                var editorInstance = CKEDITOR.instances.email_content;
                // Set Content
                editorInstance.setData( data.html );

            }
        });
    });

    if ($('#accounting_reports_show').length) {
        $('#accounting_reports_show').click(function (e) {
            e.preventDefault();
            $('#loading').show();
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            var date_type = $('#date_type').val();
            var report = $('#report').val();
            var url = '/admin/accounting/reports/data';
            $.post(url, {
                date_from: date_from,
                date_to: date_to,
                date_type: date_type,
                report: report
            }).done(function (data) {
                $('#accounting_reports_data').html(data.html);
                $('#loading').hide();
            }).error(function (errors) {
                $('#loading').hide();
                alert(errors.responseJSON.message);
            })
        })
    }
});

function sortable(selector) {
    $( selector ).sortable({
        placeholder: "ui-state-highlight",
        handle: ".question_drag",
        containment: selector,
        start: function( event, ui ) { },
        stop: function( event, ui ) {},
        update: function( event, ui ) {
            var sorted = $(selector).sortable("toArray");

            $(selector).css({'opacity': '0.5'});
            $('#loading').show();

            $.post('/admin/appr_qc_checklist/sort_questions', {
                sort: sorted
            }).done(function (data) {
                if(data.error && data.error != '') {
                    $(selector).css({'opacity': '1'});
                    $('#loading').hide();
                    alert(data.error);
                    return false;
                }

                $(selector).css({'opacity': '1'});
                $('#loading').hide();
            });
        }
    });
    $( selector + " .question_drag" ).disableSelection();
}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function toggleQuestion() {
    $('.question-view-div-content').on('click', function(e) {
        e.preventDefault();
        var $id = $(this).attr('id').replace('question-view-div-content-', '');
        $('#question_div_hidden_content_' + $id).toggle();
    });
}

function updateSelectedOrdersCount() {
    var $total = 0;
    var $paid = 0;
    var $invoice = 0;
    $('.order-checkbox').each(function() {
        if($(this).is(':checked')) {
            $total++;

            // Add the total invoice
            /*if($(this).find('.invoice')) {
                $invoice += parseFloat($(this).parent().parent().find('.invoice').html().replace('$', ''));
            }*/

            // Add the total paid
            if($(this).find('.invoice')) {
                $val = parseFloat($(this).parent().parent().find('.amount-charge-val').val());
                $paid += parseFloat($val.toFixed(2));
            }

        }
    });

    // Set the counter
    $('#selectd-orders').html($total);
    //$('#selectd-orders-invoice').html(addCommas($invoice));
    $('#selectd-orders-paid').html(addCommas($paid.toFixed(2)));

    return $total;
}

function receivablesPaginate(url, selector, event) {
    var filter = $('#filter').val();
    var credits = $('#credits').val();
    $.post(url, {
        filter: filter,
        credits: credits,
        page: event.target.innerHTML
    }).done(function(data) {
        $(selector).html(data.html);
    }).error(function (errors) {
        $(selector).html("<p>There is no data</p>");
        alert(errors.responseJSON.message);
    });
}

function updateCount() {
    var $totalUnchecked = countTotalUnchecked();
    var $totalChecked = countTotalChecked();
    $('.total_unchecked').html('Total Unchecked ' + parseInt($totalUnchecked));
    $('.total_checked').html('Total Checked ' + parseInt($totalChecked));
}

function runActiveTab()
{
     $('.nav-tabs > .active').find('a').trigger('click');
}

function countTotalUnchecked() {
    var $total = 0;
    $.each($('.order-checkbox'), function () {
        if (!$(this).is(':checked')) {
            $total++;
        }
    });

    return $total;
}

function countTotalChecked() {
    var $total = 0;
    $.each($('.order-checkbox'), function () {
        if ($(this).is(':checked')) {
            $total++;
        }
    });

    return $total;
}

function modifyClassOnOrderCheckbox($checkbox) {
    if($checkBox.is(':checked')) {
        $checkBox.prop('checked', false);
        $(this).removeClass('mark-order-row');
    } else {
        $checkBox.prop('checked', true);
        $(this).addClass('mark-order-row');
    }
}

function checkAllClients() {
    var $value = $('#check-all').is(':checked') ? true : false;
    $('.client-checkbox').prop('checked', $value);
}

function getIdsFromOrderCheckbox() {
    var $ids = [];
    $('.order-checkbox').each(function() {
        if($(this).is(':checked')) {
            var $amountField = $('#orders_amount_' + $(this).val()).val();
            if($amountField <= 0) {
                $('#orders_amount_' + $(this).val()).focus();
                alert('Please specify a valid amount.');
                return false;
            }
            $row = {'id': $(this).val(), 'amount': $amountField};
            $ids.push($row);
        }
    });
    return $ids;
}

function removePreferredGroup($groupId, $userId) {
    $.ajax({
        url: '/admin/users/delete-preferred-group',
        data: {'groupId': $groupId, 'userId': $userId},
        dataType: 'json',
        type: 'POST',
        success: function(data) {
            if(data.error && data.error != '') {
                alert(data.error);
                return false;
            }

            if(data.html) {
                $('#group_manager_table').html(data.html);
            }
        }
    });
}
