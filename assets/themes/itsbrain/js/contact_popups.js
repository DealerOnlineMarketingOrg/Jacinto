
var $ = jQuery.noConflict();

function addContact() {
	$('#addContact').remove();
	$('#editContact').remove();
	$('#viewContact').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/add',
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#editContactPop').html(code);
			}
		}
	});
}

function editContact(gid) {
	$('#addContact').remove();
	$('#editContact').remove();
	$('#viewContact').remove();
	$.ajax({
		type:'GET',
		url:'/admin/contacts/edit?gid='+gid,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#editContactPop').html(code);
			}
		}
	});
}

function viewContact(gid) {
	$('#addContact').remove();
	$('#editContact').remove();
	$('#viewContact').remove();
	$.ajax({
		type:'GET',
		url:'/admin/contacts/view?gid='+gid,
		success:function(code) {
			$('#editContactPop').html(code);	
		}
	});
}

function contactListTable() {	
	$('#addContact').remove();
	$('#editContact').remove();
	$('#viewContact').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/contacts/load_table',
			success:function(data) {
				if(data) {
					$('#contactTable').html(data);
					$('#loader_block').slideUp('fast',function() {
						$('#example').dataTable();
						
						$('#contactTable').slideDown('fast');
					});
				}else {
					$('#contactTable').html('<p>No contacts found at this level. Please use the Dealer Dropdown to change to a different contact.</p>');
				}
			}
		});
	});
}
