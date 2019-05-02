$(function() {
    $('.avgshow').on('click', function() {
        var $id = $(this).attr('id').replace('avgshow_', '');

        if ($('#avghide_' + $id).is(':visible')) {
            $('#avghide_' + $id).hide();
        } else {
            $('.avghide').hide();
            $('#avghide_' + $id).show();
        }
    });

    $('input[name="daterange"]').daterangepicker({
        timePicker: false,
        autoUpdateInput: false,
        locale: {
            format: 'MM/DD/YYYY'
        },
        dateLimit: { days: 30 }
    });
    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
    createBootstrapSelect();
    createBootstrapSelectSmall();
    createBootstrapSelectUp();
});

function createBootstrapSelect() {
    $(".bootstrap-multiselect").multiselect({
        enableFiltering: true,
        filterBehavior: 'both',
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
        filterBehavior: 'both',
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
        filterBehavior: 'both',
        numberDisplayed: 1,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        templates: {
            divider: '<div class="divider" data-role="divider"></div>'
        },
        buttonContainer: '<div class="btn-group dropup" />'
    });
}