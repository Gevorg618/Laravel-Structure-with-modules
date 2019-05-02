$(function() {
    var table = $app.datatables("#datatable", "lenders/data", {
        columns: [
            { data: "checkbox", orderable: false, searchable: false },
            { data: "lender", orderable: false },
            { data: "address", orderable: false },
            { data: "lender_city", orderable: false, searchable: false },
            { data: "lender_state", orderable: false, searchable: false },
            { data: "lender_zip", orderable: false, searchable: false },
            { data: "send_email", orderable: false, searchable: false },
            { data: "clients", orderable: false, searchable: false },
            { data: "states", orderable: false, searchable: false },
            { data: "action", orderable: false, searchable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row)
                .find("td:eq(0)")
                .html(
                    $("<div />")
                    .html(data.checkbox)
                    .text()
                );
            $(row)
                .find("td:eq(6)")
                .html(
                    $("<div />")
                    .html(data.send_email)
                    .text()
                );
            $(row)
                .find("td:eq(7)")
                .html(
                    $("<div />")
                    .html(data.clients)
                    .text()
                );
            $(row)
                .find("td:eq(8)")
                .html(
                    $("<div />")
                    .html(data.states)
                    .text()
                );
        },
        searching: true,
        iDisplayLength: 200
    });

    $("body").on("click", ".delete-row", function() {
        var id = $(this).data("id");

        $.ajax({
            url: "/admin/management/lenders/delete/" + id,
            success: function(data) {
                table
                    .row($(this).parents("tr"))
                    .remove()
                    .draw();
            }
        });
    });

    $("body").on("click", ".admin_groups_delete_button", function() {
        var id = $(this).data("id");
        $(".delete_id").val(id);
    });
});