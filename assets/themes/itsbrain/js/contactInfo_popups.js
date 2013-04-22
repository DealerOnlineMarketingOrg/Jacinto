function getPhoneEmailName(type) {
	switch (type) {
		case 'cid': return 'Client';
		case 'gid': return 'Contact';
		case 'uid': return 'User';
		case 'vid': return 'Vendor';
		default: return 'Contact';
	}
}

function addPhone(id, type) {
	$('#addContactInfoPhone').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/PhoneAdd?id='+id,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#addContactInfoPhonePop').html(code);
					});
				}
			}
		});
	});
}

function editPhone(id, type, value) {
	$('#editContactInfoPhone').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/PhoneEdit?id='+id+'&type='+type+'&value='+value,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactInfoPhonePop').html(code);
					});
				}
			}
		});
	});
}

function addEmail(id, type) {
	$('#addContactInfoEmail').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/EmailAdd?id='+id,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#addContactInfoEmailPop').html(code);
					});
				}
			}
		});
	});
}

function editEmail(id, type, value) {
	$('#editContactInfoEmail').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/EmailEdit?id='+id+'&type='+type+'&value='+value,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactInfoEmailPop').html(code);
					});
				}
			}
		});
	});
}

function updatePrimaries(id, type, phonePrimary, emailPrimary) {
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/FormPrimary?id='+id+'&phone='+phonePrimary+'&email='+emailPrimary,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						jAlert('The primary phone and email have been saved.','Primary');
					});
				}
			}
		});
	});
}
