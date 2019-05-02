// Get autoslect version group fee data by group_id and state abbr
$(document).on("click","#accordion1 a",function() {

  	if($(this).hasClass("panelisopen")) {
     	$(this).removeClass("panelisopen");
    } else {
    	var href = this.hash;
        $(this).addClass("panelisopen");

        var stateAbbr = $(this).attr('data-state');
        var groupId = $('#group_id').val();

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            url: '/admin/autoselect-pricing/pricing-fees/group/state/'+groupId+'/'+stateAbbr,
            type: 'GET',
            success: function(data)
            {
            	$(href).html('<div class="panel-body">'+data+'</div>');
            }
        });
    }
});


$(document).on("click","#update_state",function() {
	$('.form-body').addClass('loader_mode');
	$('.load_box').css('display','block');
	var stateAbbr = $(this).attr('state-abbr');
	var groupId = $(this).attr('group-id');
	var formData = $("#collapse_"+stateAbbr+" :input").serializeArray();

	$.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    $.ajax({
        url: '/admin/autoselect-pricing/pricing-fees/group/state/'+groupId+'/'+stateAbbr,
        method: 'PUT',
        data:formData,
        success: function(data)
        {
        	$('.form-body').removeClass('loader_mode');
			$('.load_box').css('display','none');
        	if (data.success) {
        		toastr['success'](data.message, 'success'.toUpperCase());
        	} else {
        		toastr['error'](data.message, 'error'.toUpperCase());
        	}
        	
        }
    })
});

