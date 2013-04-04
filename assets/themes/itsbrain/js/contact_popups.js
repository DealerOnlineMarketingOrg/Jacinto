function addContact() {
	jQuery('#addContact').remove();
	jQuery('#editContact').remove();
	jQuery('.ui-dialog').remove();

	jQuery.ajax({
		type:'GET',
		url:'/admin/contacts/add',
		success:function(code) {
			jQuery('#editContactPop').html(code);	
		}
	});
}

function editContact(aid) {
	jQuery('#addContact').remove();
	jQuery('#editContact').remove();
	jQuery('.ui-dialog').remove();

	jQuery.ajax({
		type:'GET',
		url:'/admin/contacts/edit?aid='+aid,
		success:function(code) {
			jQuery('#editContactPop').html(code);	
		}
	});
}

function contactListTable() {
	jQuery('#addContact').dialog('close');
	jQuery('#editContact').dialog('close');
	jQuery('.ui-dialog').remove();

  jQuery('#editContact').dialog('destroy');
  jQuery('#loader_block').slideDown('fast',function() {
	jQuery.ajax({
	  type:"GET",
	  url:'/admin/contacts/load_table',
	  success:function(data) {
		if(data) {
		  jQuery('#contactTable').html(data);
		  jQuery('#loader_block').slideUp('fast',function() {
			jQuery('#contactTable').slideDown('fast');
				jQuery('#example').dataTable({
					"bJQueryUI": true,
					"sPaginationType": "full_numbers",
					"sDom": '<""f>t<"F"lp>',
					'iDisplayLength':1000,
					"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
				});
		  });
		}else {
		  jQuery('#contactTable').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
		}
	  }
	});
  });
}
