$(function() {
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