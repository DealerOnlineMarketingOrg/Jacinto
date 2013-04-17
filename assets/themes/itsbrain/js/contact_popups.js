
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

function addPhone() {
	$('#addContactPhone').remove();
	$('#editContactPhone').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/PhoneAdd',
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#addContactPhonePop').html(code);
			}
		}
	});
}

function editPhone(uid, type) {
	$('#addContactPhone').remove();
	$('#editContactPhone').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/PhoneEdit?uid='+uid+'&type='+type,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#editContactPhonePop').html(code);
			}
		}
	});
}

function addEmail() {
	$('#addContactEmail').remove();
	$('#editContactEmail').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/EmailAdd',
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#addContactEmailPop').html(code);
			}
		}
	});
}

function editEmail(uid, type) {
	$('#addContactEmail').remove();
	$('#editContactEmail').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contacts/EmailEdit?uid='+uid+'&type='+type,
		success:function(code) {
			if(code == '0') {
				jAlert('The Contact can not be found. Please try again','Error');
			}else {
				$('#editContactEmailPop').html(code);
			}
		}
	});
}

function disableWebsite(wid) {
	jConfirm('Are you sure you would like to disable this website?','Disable Confirmation',function(r) {
		if(r) {
			jQuery.ajax({
				type:'GET',
				url:'/admin/websites/disable?wid='+wid,
				success:function(uid) {
					if(uid) {
						loadWebsiteTable(uid);
					}else {
						jAlert('There was an error disabling the website you requested. Please try again.','Disable Error');	
					}
				}
			});
		}
	});
}

function enableWebsite(wid) {
	jConfirm('Are you sure you would like to enable this website?','Enable Confirmation',function(r) {
		if(r) {
			jQuery.ajax({
				type:'GET',
				url:'/admin/websites/enable?wid='+wid,
				success:function(uid) {
					if(uid) {
						loadWebsiteTable(uid);
					}else {
						jAlert('There was an error enabling the website you requested. Please try again.','Enable Error');	
					}
				}
			});
		}
	});
}


function addWebsiteForm(uid) {
	jQuery.ajax({
		type:'GET',
		url:'/admin/websites/form?uid='+uid,
		//data:{contact_id:id},
		success:function(data) {
			if(data){
				jQuery('#addWebsiteForm').html(data);
			}else {
				jAlert('There was a problem finding the contact you needed. Please try again.','Add Website Error');
			}
		}
	})
}

function editWebsiteForm(uid,wid) {
	jQuery.ajax({
		type:'GET',
		url:'/admin/websites/form?uid='+uid+'&wid='+wid,
		success:function(data) {
			if(data){
				jQuery('#addWebsiteForm').empty();
				jQuery('#addWebsiteForm').html(data);
			}else {
				jAlert('There was a problem finding the contact you needed. Please try again.','Add Website Error');
			}
		}
	})
}

function editWebsite(uid,wid) {
	var formData = jQuery('#web').serialize();
	jQuery.ajax({
		type:'POST',
		url:'/admin/websites/edit?uid='+uid+'&wid='+wid,
		data:formData,
		success:function(data) {
			if(data) {
				jAlert('The website has been successfully changed.','Success',function() {
					window.location.reload();
				})
			}else {
				jAlert('There was an error editing the website','Error',function() {
					window.location.reload();
				})
			}
		}
	})
}

function addWebsite(id) {
	jQuery.ajax({
		type:'POST',
		url:'/admin/websites/edit',
		data:{wid:id},
		success:function(data) {
			if(data) {
				jAlert('The website has been successfully changed.','Success',function() {
					window.location.reload();
				})
			}else {
				jAlert('There was an error editing the website','Error',function() {
					window.location.reload();
				})
			}
		}
	})
}

function submitWebsiteForm(uid,formData,cUrl,msg) {
	jQuery.ajax({
		type:'POST',
		url:cUrl,
		data:formData,
		success:function(data) {
			if(data == '1') {
				jAlert(msg,'Success',function() {
					reloadWebsites(uid);	
					jQuery('#editWebsite').empty();
					jQuery('#addWebsite').empty();
				});
			}else {
				jAlert('There was a problem with processing your change. Please try again.','Error',function() {
					reloadWebsites(uid);
				});	
			}
		}
	});
}

function loadWebsiteTable(id) {
	jQuery('#editContact').remove();
	//jQuery('#addWebsiteForm').dialog('close');	
	jQuery('#websites').slideUp('fast',function() {
		jQuery('#websites').empty();
		jQuery('#loader').fadeIn('fast',function() {
			jQuery('#loader').fadeIn('fast',function() {
				jQuery.ajax({
					type:'GET',
					url:'/admin/websites/load_table?uid='+id,
					success:function(data) {
						jQuery('#websites').html(data);
						jQuery('#loader').delay(2000).fadeOut('fast',function() {
							jQuery('#websites').slideDown('fast',function() {
							});
						});
					}
				});
			});
		});
	});					
}

function reloadWebsites(uid) {
	jQuery('#editContact').remove();
	jQuery('#addWebsiteForm').dialog('close');	
	jQuery('#websites').slideUp('fast',function() {
		jQuery('#websites').empty();
		jQuery('#loader').fadeIn('fast',function() {
			jQuery('#loader').fadeIn('fast',function() {
				jQuery.ajax({
					type:'GET',
					url:'/admin/websites/load_table?uid='+uid,
					success:function(data) {
						jQuery('#websites').html(data);
						jQuery('#loader').delay(2000).fadeOut('fast',function() {
							jQuery('#websites').slideDown('fast');
						});
					}
				});
			});
		});
	});					
}
