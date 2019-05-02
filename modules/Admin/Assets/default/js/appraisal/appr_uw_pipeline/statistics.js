$(function() {
    var start_date = new Date();
    start_date.setHours(0, 0, 0);
    $('input[name="date_from"]').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            autoUpdateInput: true,
            showDropdowns: true,
            timePickerSeconds: true,
            startDate: start_date,
            locale: {
                format: "MM/DD/YYYY HH:mm:ss"
            }
        },
        function(start, end, label) {}
    );

    var end_date = new Date();
    end_date.setHours(23, 59, 59);
    $('input[name="date_to"]').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            autoUpdateInput: true,
            showDropdowns: true,
            timePickerSeconds: true,
            startDate: end_date,
            locale: {
                format: "MM/DD/YYYY HH:mm:ss"
            }
        },
        function(start, end, label) {}
    );

    $(".view-user-stats").on("click", function() {
        var $id = $(this).data("id"),
            $from = $(this).data("from"),
            $to = $(this).data("to");
        if (!$id) {
            return false;
        }

        $.ajax({
            url: "/admin/post-completion-pipelines/appr-uw-pipeline/get-user-info",
            method: "POST",
            data: { from: $from, to: $to, userId: $id },
            dataType: "json",
            success: function(data) {
                if (data.error && data.error != "") {
                    alert(data.error);
                    return;
                }
                if (data.html) {
                    $("#view_user_stats_title").html(data.title);
                    $("#view_user_stats_content").html(data.html);
                    $("#view_user_stats").modal();
                }
            }
        });
    });

    $(".view-user-condition-stats").on("click", function() {
        var $id = $(this).data("id"),
            $from = $(this).data("from"),
            $to = $(this).data("to");
        if (!$id) {
            return false;
        }

        $.ajax({
            url: "/admin/post-completion-pipelines/appr-uw-pipeline/get-user-condition-info",
            method: "POST",
            data: { from: $from, to: $to, userId: $id },
            dataType: "json",
            success: function(data) {
                if (data.error && data.error != "") {
                    alert(data.error);
                    return;
                }

                if (data.html) {
                    $("#view_user_stats_title").html(data.title);
                    $("#view_user_stats_content").html(data.html);
                    $("#view_user_stats").modal();
                }
            }
        });
    });
});