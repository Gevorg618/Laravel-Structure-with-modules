$(document).ready(function () {
  createBootstrapSelect();
  createBootstrapSelectSmall();
  createBootstrapSelectUp();
});

function createBootstrapSelect(form) {
  var select = $('.bootstrap-multiselect');

  if (form !== undefined) {
    select = form.find('.bootstrap-multiselect');
  }

  select.multiselect({
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

function createBootstrapSelectSmall(form) {
  var select = $('.bootstrap-multiselect-small');

  if (form !== undefined) {
    select = form.find('.bootstrap-multiselect-small');
  }

  select.multiselect({
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

function createBootstrapSelectUp(form) {
  var select = $('.bootstrap-multiselect-up');

  if (form !== undefined) {
    select = form.find('.bootstrap-multiselect-up');
  }

  select.multiselect({
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