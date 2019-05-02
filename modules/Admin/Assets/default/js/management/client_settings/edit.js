$(function() {
    CKEDITOR.replace("notify_order_placed_content", { height: "250px", width: "700px" });
    CKEDITOR.replace("admin_notes", { height: "250px", width: "700px" });
    CKEDITOR.replace("standard_guidelines", { height: "250px", width: "700px" });
});

$(function() {
    $(".bootstrap-multiselect").multiselect({
        enableFiltering: true,
        filterBehavior: "both",
        numberDisplayed: 1,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 250,
        templates: {
            divider: '<div class="divider" data-role="divider"></div>'
        }
    });
});
