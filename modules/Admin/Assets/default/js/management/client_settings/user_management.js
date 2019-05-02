// Quick Add User
$('#quick_add_user').on('click', function () {
    var firstname = $('#quick_add_firstname').val();
    var lastname = $('#quick_add_lastname').val();
    var email = $('#quick_add_email').val();
    var phone = $('#quick_add_phone').val();
    var ext = $('#quick_add_phoneext').val();
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
    var url = $('#add_user_url').val();
    var groupId = $("#add_user_group_id").val();
    // Validate
    if (!firstname) {
        alert('Please fill out the first name field.');
        return false;
    } else if (!lastname) {
        alert('Please fill out the last name field.');
        return false;
    } else if (!email) {
        alert('Please fill out the email address field.');
        return false;
    } else if (email) {
        if (!pattern.test(email)) {
            alert('not a valid e-mail address');
            return false;
        }
    }


    $.ajax({
        url: url,
        data: {
            'groupId': groupId,
            'firstname': firstname,
            'lastname': lastname,
            'email': email,
            'phone': phone,
            'ext': ext
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        success: function (data) {
            $('#quick_add_lastname').val('');
            $('#quick_add_email').val('');
            $('#quick_add_phone').val('');
            $('#quick_add_phoneext').val('');
            $('#user_content').html(data)
        },
        error: function (data) {
            var errors = data.responseJSON;
            if (errors && errors != '') {
                alert('Sorry, That email is already in use');

            }


        }

    });
});

$("#reset_quick_input").on('click', function () {
    $('#quick_add_firstname').val('');
    $('#quick_add_lastname').val('');
    $('#quick_add_email').val('');
    $('#quick_add_phone').val('');
    $('#quick_add_phoneext').val('');
})
