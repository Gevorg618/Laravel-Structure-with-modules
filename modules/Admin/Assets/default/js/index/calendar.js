$(document).ready(function () {
    $(document).on('click', '#add_calendar_event', function () {
        $.ajax({
            url: 'admin/calendar/add-event-form',
            method: 'GET'
        }).done((data) => {
            $('#modal_add_event_title').html('Add Calendar Event');
            $('#modal_add_event_content').html(data.html);
            $('#modal_add_event').modal({backdrop: 'static', keyboard: false}).css({'top': '1%'});

            $('#modal_add_event').find('.modal-body').css({'max-height': ($(window).outerHeight() - 110) + 'px'});

            $('.date-time-picker').datetimepicker({
                ignoreReadonly: true,
                format : 'YYYY-MM-DD hh:mm A'
            });

            createBootstrapSelect();
            $('#event_private').trigger('change');

            var editorInstance = CKEDITOR.instances['event_content'];
            if (editorInstance) {
                CKEDITOR.remove(editorInstance);
            }
            editorInstance = CKEDITOR.replace('event_content', {height: '150px', toolbar: 'Full'});
        }).fail((err) => {
            console.log(err);
        })
    });

    $(document).on('click', '#edit_event', function () {
        const $id = $(this).attr('data-id');

        $('#modal_view_event').modal('hide');
        $.ajax({
            url: 'admin/calendar/edit-event-form/' + $id,
            method: "GET",
        }).done((data) => {
            if (data.html) {
                $('#modal_add_event_title').html('Edit Calendar Event');
                $('#modal_add_event_content').html(data.html);
                $('#modal_add_event').modal({backdrop: 'static', keyboard: false}).css({'top': '1%'});
                $('#modal_add_event').find('.modal-body').css({
                    width: 'auto',
                    height: 'auto',
                    'max-height': ($(window).outerHeight() - 110) + 'px'
                });

                $('.date-time-picker').datetimepicker({
                    ignoreReadonly: true,
                    format : 'YYYY-MM-DD hh:mm A'
                });

                $('#event_private').trigger('change');

                createBootstrapSelect();

                var editorInstance = CKEDITOR.instances['event_content'];
                if (editorInstance) {
                    CKEDITOR.remove(editorInstance);
                }
                editorInstance = CKEDITOR.replace('event_content', {height: '150px', toolbar: 'Full'});
            }
        }).fail((err) => {
            console.log(err)
        });
    });

    // Do add event
    $(document).on('click', '#do_add_calendar_event', function () {
        // Hide messages
        $('#event_error_msg, #event_ok_msg').html();
        $('#event_error_msg, #event_ok_msg').hide();

        // Loading
        $('#do_add_calendar_event').attr('disabled', 'disabled');
        $('#do_add_calendar_event').html('Loading...');

        // Check if this is an update or new event
        let $isUpdate = false;
        const $eventId = $('#form_event_id').val();
        if ($eventId) {
            $isUpdate = true;
        }

        // Set value for event content
        $('#event_content').val(CKEDITOR.instances['event_content'].getData());

        const $fields = $("#add_event_form").serialize();

        $.ajax({
            url: $isUpdate ? 'admin/calendar/edit-event/' + $eventId : 'admin/calendar/add-event',
            data: $fields,
            dataType: 'json',
            type: $isUpdate ? "PUT" : "POST",
        }).done(data => {
            $('#do_add_calendar_event').removeAttr('disabled');
            $('#do_add_calendar_event').html('Submit');
            if (data.html) {
                $('#event_ok_msg').html(data.html);
                $('#event_ok_msg').show();
                $('#event_calendar').fullCalendar('refetchEvents');
                $('#modal_add_event').modal('hide');
            }
        }).fail(err => {
            $('#event_error_msg').html(err);
            $('#event_error_msg').show();
        })
    });

    // Do delete event
    $(document).on('click', '#delete_event', function () {
        const $confirm = confirm("Are you sure you would like to delete this event?");

        if (!$confirm) {
            return false;
        }

        const $id = $(this).attr('data-id');
        $.ajax({
            url: 'admin/calendar/delete-event/' + $id,
            method: 'delete',
        }).done(data => {
            if (data.html) {
                $('#modal_view_event_content').html(data.html);
                $('#event_calendar').fullCalendar('refetchEvents');
                setTimeout(function () {
                    $('#modal_view_event').modal('hide');
                }, 1000);
            }
        }).fail(err => {
            alert(err);
        });
    });

    // Private/Public
    $(document).on('change', '#event_private', function () {
        if ($(this).val() > 0) {
            // Hide public settings
            $('.public_event_settings').hide();
        } else {
            // Show
            $('.public_event_settings').show();
        }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($($(e)[0].target).attr('data-toggle-name') === 'calendar') {
            $('#event_calendar').fullCalendar({
                editable: false,
                height: 400,
                contentHeight: 400,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                eventSources: [
                    {
                        url: 'admin/calendar/load-events',
                        type: 'GET',
                        error: function (data) {
                            console.log('ERROR!', data);
                        }
                    }
                ],
                eventClick: function (calEvent, jsEvent, view) {
                    if (calEvent.url) {
                        window.open(calEvent.url);
                        return false;
                    }
                    var $isEvent = calEvent.className.length;
                    if ($isEvent) {
                        var $id = calEvent.id.replace('event_', '');

                        if (!$id) {
                            alert('Sorry, Event ID was not found.');
                            return;
                        }

                        // View the event in a modal
                        $.ajax({
                            url: 'admin/calendar/view-event/' + $id,
                            dataType: 'json',
                            type: 'GET',
                            success: function (data) {
                                if (data.error) {
                                    // Show error
                                    alert(data.error);
                                    return;
                                }
                                if (data.html) {
                                    $('#modal_view_event_title').html(data.title);
                                    $('#modal_view_event_content').html(data.html);
                                    $('#modal_view_event').modal({
                                        backdrop: 'static',
                                        keyboard: false
                                    }).css({'top': '1%'});
                                    $('#modal_view_event').find('.modal-body').css({
                                        width: 'auto',
                                        height: 'auto',
                                        'max-height': ($(window).outerHeight() - 110) + 'px'
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }
    });

    $('#event_private').trigger('change');
});