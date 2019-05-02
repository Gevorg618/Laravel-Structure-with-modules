//Preferred Appraisers and Excluded Appraisers
var appraisersUrl = $("#search_appraisers_url").val();
var groupid = $('#add_user_group_id').val()
$('.appr_search').on('input', function () {
    searchType = $(this).attr('data-name');
    if (searchType == 'appr_search') {
        keyData = 'preferred';
        appendTbody = $("#preferred");
    } else if (searchType == 'exclude_search') {
        keyData = 'excluded'
        appendTbody = $("#excluded");
    }

    $(this).autocomplete({
        source: function (request, response) {
            $.ajax({
                type: 'GET',
                url: appraisersUrl,
                dataType: 'json',
                data: {
                    input: request.term,
                    key: keyData,
                    groupid: groupid,
                },
                success: function (data) {
                    var data = data.users;
                    response($.map(data, function (item) {

                        if (item.user_data) {
                            var exitintg_user = $(appendTbody).find('#' + item.id);
                            if (exitintg_user.text() == '') {
                                return {
                                    label: item.user_data.firstname + " " + item.user_data.lastname + " ( " + item.email + " )",
                                    value: item.id
                                }
                            }

                        }

                    }));


                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            var symbol = ui.item.label;
            var value = ui.item.value;
            var name = symbol.substring(0, symbol.indexOf('(') - 1);
            var email = symbol.substring(symbol.indexOf('(') + 2, symbol.indexOf(')') - 1);
            var date = new Date();
            var y = date.getFullYear();
            var m = date.getMonth();
            var d = date.getDate();
            var h = date.getHours();
            var i = date.getMinutes();
            var dateTime = y + '/' + m + '/' + d + '/ ' + h + ':' + i;

            if (searchType == 'appr_search') {
                apprNotFound = $(".app_not_found");
                appendTbody = $("#preferred");
                inpName = 'prefer_appr';
            } else if (searchType == 'exclude_search') {
                apprNotFound = $('.app_not_found_excluded');
                appendTbody = $("#excluded");
                inpName = 'excluded_appr';
            }

            if ($(apprNotFound)) {
                $(apprNotFound).remove();

            }
            $(appendTbody).append(
                '<tr id="' + value + '">' +
                '<td>' + name +
                '<br>' + email + '<i class="fa fa-trash remove_appr" aria-hidden="true" ' +
                'style="float: right; color:red; cursor: pointer;" data-remove="' + inpName + '"></i>' +
                '<br>' + 'Since:' + dateTime +
                '</td> ' +
                '<input value="' + value + '" name="' + inpName + '[]" type="hidden">' +
                '</tr>'
            )
            setTimeout(function () {
                $('.appr_search').val('');
            }, 0)


            if (searchType == 'appr_search') {
                var rowCount = $('#appr_count tr').length;
                apprH4 = $('.appr_count_h4')

            } else if (searchType == 'exclude_search') {
                var rowCount = $('#appr_count_excluded tr').length;
                apprH4 = $('.appr_excluded_count_h4');
            }

            $(apprH4).text('Total Preferred  ' + rowCount);
        },
    });
});

$(document).on('click', '.remove_appr', function () {
    $(this).parent().parent().remove()
    var remove = $(this).attr('data-remove')
    if (remove == 'prefer_appr') {
        var rowCountR = $('#appr_count tr').length;
        var apprH4R = $('.appr_count_h4')
        var appendTbodyR = $("#preferred");
        var className = 'app_not_found';
    } else if (remove == 'excluded_appr') {
        var rowCountR = $('#appr_count_excluded tr').length;
        var apprH4R = $('.appr_excluded_count_h4');
        var appendTbodyR = $("#excluded");
        var className = 'app_not_found_excluded';
    }

    $(apprH4R).text('Total Preferred  ' + rowCountR);
    if (rowCountR == 0) {
        $(apprH4R).text('Total Preferred 0');
        $(appendTbodyR).append(
            '<tr class="' + className + '">' +
            '<td>None Found.</td>' +
            '</tr>'
        )
    }
});


//Users In Group  and  Supervisors
$('.user_search').on('input', function () {
    var usersUrl = $("#search_users_url").val();
    userType = $(this).attr('data-name');
    if (userType == 'users') {
        keyData = 'user';
        appendTbody = $("#users_tbody");
    } else if (userType == 'supervisor') {
        keyData = 'manager'
        appendTbody = $("#managers_tbody");
    }

    $(this).autocomplete({
        source: function (request, response) {
            $.ajax({
                type: 'GET',
                url: usersUrl,
                dataType: 'json',
                data: {
                    input: request.term,
                    key: keyData,
                    groupid: groupid,
                },
                success: function (data) {
                    var data = data.users;
                    response($.map(data, function (item) {

                        if (item.user_data) {

                            var exclude_users = $(appendTbody).find('#' + item.id);
                            if (exclude_users.text() == '') {
                                return {
                                    label: item.user_data.firstname + " " + item.user_data.lastname + " ( " + item.email + " )",
                                    value: item.id

                                }
                            }
                        }

                    }));


                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            var symbol = ui.item.label;
            var value = ui.item.value;
            var name = symbol.substring(0, symbol.indexOf('(') - 1);
            var email = symbol.substring(symbol.indexOf('(') + 2, symbol.indexOf(')') - 1);

            if (userType == 'users') {
                apprNotFound = $(".app_not_found_user");
                appendTbody = $("#users_tbody");
                inpName = 'users_data';
            } else if (userType == 'supervisor') {
                apprNotFound = $('.app_not_found_managers');
                appendTbody = $("#managers_tbody");
                inpName = 'managers_data';
            }

            if ($(apprNotFound)) {
                $(apprNotFound).remove();

            }

            $(appendTbody).append(
                '<tr id = "' + value + '">' +
                '<td>' + name +
                '<br>' + email + ' ' +
                '<i class="fa fa-trash remove_users" aria-hidden="true" ' +
                'style="float: right; color:red; cursor: pointer;" data-remove="' + inpName + '"></i>' +
                '</td> ' +
                '<input value="' + value + '" name="' + inpName + '[]" type="hidden">' +
                '</tr>'
            )
            setTimeout(function () {
                $('.user_search').val('');
            }, 0)


            if (userType == 'users') {
                $.ajax({
                    type: 'GET',
                    url: usersUrl,
                    dataType: 'json',
                    data: {
                        input: value,
                        key: 'user-check',
                        groupid: groupid,

                    },
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                        }

                    }
                });

                var rowCount = $('#users_count tr').length;
                apprH4 = $('.users_count_h4')

            } else if (userType == 'supervisor') {
                var rowCount = $('#managers_count tr').length;
                apprH4 = $('.managers_count_h4');
            }

            $(apprH4).text('Total Users  ' + rowCount);

        },
    });
});


$(document).on('click', '.remove_users', function () {
    $(this).parent().parent().remove()
    var remove = $(this).attr('data-remove')
    if (remove == 'users_data') {
        var rowCountR = $('#users_count tr').length;
        var usersH4R = $('.users_count_h4')
        var appendTbodyR = $("#users_tbody");
        var className = 'app_not_found_user';
    } else if (remove == 'managers_data') {
        var rowCountR = $('#managers_count tr').length;
        var usersH4R = $('.managers_count_h4');
        var appendTbodyR = $("#managers_tbody");
        var className = 'app_not_found_managers';
    }

    $(usersH4R).text('Total Users  ' + rowCountR);
    if (rowCountR == 0) {
        $(usersH4R).text('Total Users 0');
        $(appendTbodyR).append(
            '<tr class="' + className + '">' +
            '<td>None Found.</td>' +
            '</tr>'
        )
    }
});
