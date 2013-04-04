function addAgency() {
	jQuery('#addAgency').remove();
	jQuery('#editAgency').remove();
	jQuery('.ui-dialog').remove();

	jQuery.ajax({
		type:'GET',
		url:'/admin/agency/add',
		success:function(code) {
			jQuery('#editAgencyPop').html(code);	
		}
	});
}

function editAgency(aid) {
	jQuery('#addAgency').remove();
	jQuery('#editAgency').remove();
	jQuery('.ui-dialog').remove();

	jQuery.ajax({
		type:'GET',
		url:'/admin/agency/edit?aid='+aid,
		success:function(code) {
			jQuery('#editAgencyPop').html(code);	
		}
	});
}

function agencyListTable() {
	jQuery('#addAgency').dialog('close');
	jQuery('#editAgency').dialog('close');
	jQuery('.ui-dialog').remove();

  jQuery('#editAgency').dialog('destroy');
  jQuery('#loader_block').slideDown('fast',function() {
	jQuery.ajax({
	  type:"GET",
	  url:'/admin/agency/load_table',
	  success:function(data) {
		if(data) {
		  jQuery('#agencyTable').html(data);
		  jQuery('#loader_block').slideUp('fast',function() {
			jQuery('#agencyTable').slideDown('fast');
				jQuery('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"sDom": '<""f>t<"F"lp>',
					'iDisplayLength':1000,
					"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
				});
		  });
		}else {
		  jQuery('#agencyTable').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
		}
	  }
	});
  });
}
