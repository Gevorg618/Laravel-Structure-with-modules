$(function () {
    CKEDITOR.replace('content');

    $('#image').change(function (e) {
        if ($(this).val() !== '') {
            preview_image(e, 'image');
        }
    });

    function preview_image(event, id) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById(`${id}_img_container`);
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    $(document).on('submit', '#latestNewForm', function () {
        $(this).find(':input[type=submit]')
            .attr('disabled', true)
            .removeClass('btn-success')
            .addClass('btn-danger');
    })
});