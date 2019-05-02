$(function() {
    $('input[name="date_from"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false,
            showDropdowns: true,
            locale: {
                format: "MM/DD/YYYY"
            }
        },
        function(start, end, label) {}
    );

    $('input[name="date_from"]').on("apply.daterangepicker", function(
        ev,
        picker
    ) {
        $(this).val(picker.startDate.format("MM/DD/YYYY"));
    });

    $('input[name="date_to"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false,
            showDropdowns: true,
            locale: {
                format: "MM/DD/YYYY"
            }
        },
        function(start, end, label) {}
    );

    $('input[name="date_to"]').on("apply.daterangepicker", function(
        ev,
        picker
    ) {
        $(this).val(picker.startDate.format("MM/DD/YYYY"));
    });

    createBootstrapSelect();
    createBootstrapSelectSmall();
    createBootstrapSelectUp();
});

function createBootstrapSelect() {
    $(".bootstrap-multiselect").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
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
        includeSelectAllOption: true,
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
        includeSelectAllOption: true,
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