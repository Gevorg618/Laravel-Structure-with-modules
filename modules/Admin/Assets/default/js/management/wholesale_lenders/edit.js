$(function() {
    $("#btn_contact_info").click(function(event) {
        event.preventDefault();
        var data = {
            uw_fullname: $("#uw_fullname").val(),
            email: $("#uw_email").val(),
            uw_phone: $("#uw_phone").val(),
            lenderid: $("#lenderid").val(),
            _token: $('meta[name="csrf-token"]').attr("content")
        };
        if ($(this).val() == "Add") {
            data.action = "add";
            $.post("/admin/management/lenders/add-uw", data, function(
                response
            ) {
                if (response.message == "success") {
                    $("table").append(
                        "<tr><td>" +
                        response.data.full_name +
                        "</td><td>" +
                        response.data.email +
                        "</td><td>" +
                        response.data.phone +
                        "</td><td>" +
                        response.data.created_at +
                        "</td><td> <a href='#uw' data-contact-id='" +
                        response.data.id +
                        "' data-full_name='" +
                        response.data.full_name +
                        "' data-email='" +
                        response.data.email +
                        "' data-phone='" +
                        response.data.phone +
                        "' class='edit-uw-contact'> Edit </a> </td> <td> <a href='#uw' data-contact-id='" +
                        response.data.id +
                        "' class='delete-uw-contact'> Delete  </a></td> </tr>"
                    );
                    $("#uw_fullname").val("");
                    $("#uw_email").val("");
                    $("#uw_phone").val("");
                    $("#uw_fullname").focus();
                }
            }).fail(function(reject) {
                var errors = Object.values(reject.responseJSON.errors);
                errors.forEach(function(item, value) {
                    $(".error_message").append(
                        '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        item[0] +
                        "</div>"
                    );
                });
            });
        }
        if ($(this).val() == "Update") {
            data.action = "update";
            $.post(
                "/admin/management/lenders/update-uw/" +
                $(this).data("contact-id"),
                data,
                function(response) {
                    if (response.message == "success") {
                        location.reload();
                    }
                }
            ).fail(function(reject) {
                var errors = Object.values(reject.responseJSON.errors);
                errors.forEach(function(item, value) {
                    $(".error_message").append(
                        '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        item[0] +
                        "</div>"
                    );
                });
            });
        }
    });

    $("#user_manager").autocomplete({
        source: "/admin/management/lenders/get-client-names",
        minLength: 2,
        select: function(event, ui) {
            var $email = ui.item.value;
            var $id = ui.item.id;
            if (!$id) {
                alert("User ID was not found.");
                return false;
            }
            $.ajax({
                url: "/admin/management/lenders/add-user-manager",
                data: {
                    lenderId: $("#lenderid").val(),
                    userId: $id,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                type: "POST",
                success: function(response) {
                    // Empty Value
                    $("#user_manager").val("");
                    if (response.error && response.error != "") {
                        alert(response.error);
                        return false;
                    }
                    $("#user_manager_table tbody").append(
                        `<tr><td>
                        ${response.data.name} (${response.data.email})
                        </td><td style="text-align:center; width: 3%;"> <a href="#" onclick="removeUserManager(
                        ${response.data.lenderid},
                        ${response.data.userid}, this)" style="color: red;"><i class="fa fa-minus-circle"></i></a></td></tr>`
                    );
                }
            });
        }
    });

    // Add note
    $(document).on("click", '#add-note', function() {
        var $userNote = $('#user_note').val();
        var $lenderId = $("#user_note").data('id');

        if (!$userNote || $userNote.length < 3) {
            alert('Please enter a user note. At least 3 characters long.');
            return false;
        }

        $.ajax({
            url: '/admin/management/lenders/add-user-note',
            type: 'POST',
            data: {
                'note': $userNote,
                'id': $lenderId
            },
            dataType: 'json',
            success: function(response) {
                if (response.message == 'success') {
                    $("#notes-table tbody").append(
                        "<tr><td>" +
                        response.data.dts +
                        "</td><td>" +
                        response.data.adminid +
                        "</td><td>" +
                        response.data.notes +
                        "</td></tr>");
                    $('#user_note').val('');
                    $('.notes-count').html(response.data.count)
                }
            }
        });
    });

    $("#excluded_appraiser").autocomplete({
        source: "/admin/management/lenders/get-appraiser-names",
        minLength: 2,
        select: function(event, ui) {
            // Run an ajax to try and add the user to our table
            var $email = ui.item.value;
            var $id = ui.item.id;

            $.ajax({
                url: "/admin/management/lenders/add-excluded-appraiser",
                data: {
                    lenderId: $("#lenderid").val(),
                    userId: $id,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                type: "POST",
                success: function(response) {
                    // Empty Value
                    $("#excluded_appraiser").val("");
                    if (response.error && response.error != "") {
                        alert(response.error);
                        return false;
                    }
                    $("#excluded_appraiser_table tbody").append(`<tr><td>
                        ${response.data.name} (${response.data.email})
                        </td><td style="text-align:center; width: 3%;"> <a href="#" onclick="removeExcludedApprasier(
                        ${response.data.lenderid},
                        ${response.data.userid}, this)" style="color: red;"><i class="fa fa-minus-circle"></i></a></td></tr>`);
                }
            });
        }
    });

    $(document).on("click", ".edit-uw-contact", function() {
        $("#uw_fullname").val($(this).data("full_name"));
        $("#uw_email").val($(this).data("email"));
        $("#uw_phone").val($(this).data("phone"));
        $("#btn_contact_info").val("Update");
        $("#btn_contact_info").attr(
            "data-contact-id",
            $(this).data("contact-id")
        );
    });

    $(document).on("click", ".delete-uw-contact", function() {
        $("#uw_fullname").val('');
        $("#uw_email").val("");
        $("#uw_phone").val("");
        $("#btn_contact_info").val("Add");
        var $row = $(this).closest("tr");
        $.post(
            "/admin/management/lenders/delete-uw/" + $(this).data("contact-id"),
            function(response) {
                if (response.message == "success") {
                    $row.fadeOut("slow");
                }
            }
        );
    });

    createBootstrapSelect();
    createBootstrapSelectSmall();
    createBootstrapSelectUp();
    CKEDITOR.replace("comments", { height: "250px", width: "700px" });
    CKEDITOR.replace("admin_notes", { height: "250px", width: "700px" });
    CKEDITOR.replace("signup_note", { height: "250px", width: "700px" });
});

function createBootstrapSelect() {
    $(".bootstrap-multiselect").multiselect({
        enableFiltering: true,
        filterBehavior: "both",
        numberDisplayed: 1,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        templates: {
            divider: '<div class="divider" data-role="divider"></div>'
        }
    });
}

function createBootstrapSelectSmall() {
    $(".bootstrap-multiselect-small").multiselect({
        enableFiltering: true,
        filterBehavior: "both",
        numberDisplayed: 1,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 100,
        templates: {
            divider: '<div class="divider" data-role="divider"></div>'
        }
    });
}

function createBootstrapSelectUp() {
    $(".bootstrap-multiselect-up").multiselect({
        enableFiltering: true,
        filterBehavior: "both",
        numberDisplayed: 1,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        templates: {
            divider: '<div class="divider" data-role="divider"></div>'
        },
        buttonContainer: '<div class="btn-group dropup" />'
    });
}

function removeUserManager($lenderId, $userId, that) {
    $.ajax({
        url: "/admin/management/lenders/delete-user-manager",
        data: {
            lenderId: $lenderId,
            userId: $userId,
            _token: $('meta[name="csrf-token"]').attr("content")
        },
        dataType: "json",
        type: "POST",
        success: function(response) {
            if (response.error && response.error != "") {
                alert(response.error);
                return false;
            }
            $(that).parents("tr").remove();
        }
    });
}

function removeExcludedApprasier($lenderId, $userId, that) {
    $.ajax({
        url: "/admin/management/lenders/delete-excluded-appraiser",
        data: {
            lenderId: $lenderId,
            userId: $userId,
            _token: $('meta[name="csrf-token"]').attr("content")
        },
        dataType: "json",
        type: "POST",
        success: function(response) {
            if (response.error && response.error != "") {
                alert(response.error);
                return false;
            }
            $(that).parents("tr").remove();
        }
    });
}