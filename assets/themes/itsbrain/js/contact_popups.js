
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

function editContact(uid) {
	$('#addContact').remove();
	$('#editContact').remove();
	$('#viewContact').remove();
	$.ajax({
		type:'GET',
		url:'/admin/contacts/edit?uid='+uid,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#editContactPop').html(code);
			}
		}
	});
}

function viewContact(uid) {
	$('#addContact').remove();
	$('#editContact').remove();
	$('#viewContact').remove();
	$.ajax({
		type:'GET',
		url:'/admin/contacts/view?uid='+uid,
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

function addPhone(uid) {
	$('#addContactPhone').remove();
	$('#editContactPhone').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/PhoneAdd?uid='+uid,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#addContactPhonePop').html(code);
			}
		}
	});
}

function editPhone(uid, type, value) {
	$('#addContactPhone').remove();
	$('#editContactPhone').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/PhoneEdit?uid='+uid+'&type='+type+'&value='+value,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#editContactPhonePop').html(code);
			}
		}
	});
}

function addEmail(uid) {
	$('#addContactEmail').remove();
	$('#editContactEmail').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/EmailAdd?uid='+uid,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#addContactEmailPop').html(code);
			}
		}
	});
}

function editEmail(uid, type, value) {
	$('#addContactEmail').remove();
	$('#editContactEmail').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/EmailEdit?uid='+uid+'&type='+type+'&value='+value,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#editContactEmailPop').html(code);
			}
		}
	});
}

function updatePrimaries(uid, phonePrimary, emailPrimary) {
	$('#addContactEmail').remove();
	$('#editContactEmail').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/FormPrimary?uid='+uid+'&phone='+phonePrimary+'&email='+emailPrimary,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				jAlert('The primary phone and email have been saved.','Primary');
			}
		}
	});
}
