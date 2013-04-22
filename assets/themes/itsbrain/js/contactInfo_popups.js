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
	
	$.ajax({
		type:'GET',
		url:'/admin/contactInfo/PhoneAdd?id='+id,
		success:function(code) {
			if(code == '0') {
				jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error');
			}else {
				$('#addContactInfoPhonePop').html(code);
			}
		}
	});
}

function editPhone(id, type, value) {
	$('#editContactInfoPhone').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contactInfo/PhoneEdit?id='+id+'&type='+type+'&value='+value,
		success:function(code) {
			if(code == '0') {
				jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error');
			}else {
				$('#editContactInfoPhonePop').html(code);
			}
		}
	});
}

function addEmail(id, type) {
	$('#addContactInfoEmail').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contactInfo/EmailAdd?id='+id,
		success:function(code) {
			if(code == '0') {
				jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error');
			}else {
				$('#addContactInfoEmailPop').html(code);
			}
		}
	});
}

function editEmail(id, type, value) {
	$('#editContactInfoEmail').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/contactInfo/EmailEdit?id='+id+'&type='+type+'&value='+value,
		success:function(code) {
			if(code == '0') {
				jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error');
			}else {
				$('#editContactInfoEmailPop').html(code);
			}
		}
	});
}

function updatePrimaries(id, type, phonePrimary, emailPrimary) {
	$.ajax({
		type:'GET',
		url:'/admin/contactInfo/FormPrimary?id='+id+'&phone='+phonePrimary+'&email='+emailPrimary,
		success:function(code) {
			if(code == '0') {
				jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error');
			}else {
				jAlert('The primary phone and email have been saved.','Primary');
			}
		}
	});
}
