$(function () {
    $('#logo_image').change(function (e) {
        if ($(this).val() !== '') {
            preview_image(e, 'logo_image');
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
});