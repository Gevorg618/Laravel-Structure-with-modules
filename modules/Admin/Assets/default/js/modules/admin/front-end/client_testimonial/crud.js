+$(function () {
    CKEDITOR.replace('content');

    $(document).on('submit', '#clientTestimonialForm', function () {
        $(this).find(':input[type=submit]')
            .attr('disabled', true)
            .removeClass('btn-success')
            .addClass('btn-danger');
    });
});