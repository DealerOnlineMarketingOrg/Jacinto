
	function addPasswords(link) {
		jQuery.ajax({
			type:'GET',
			url:link+'passwords/add',
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					jQuery('#addPasswordsInfo').html(data);
				}else {
					jAlert('There was a problem finding the contact you needed. Please try again.','Add Passwords Error');
				}
			}
		})
	}
	
	function editPasswords(pwdid,link) {
		jQuery.ajax({
			type:'GET',
			url:link+'passwords/edit?pwdid=' + pwdid,
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					jQuery('#editPasswordsInfo').html(data);
				}else {
					jAlert('There was a problem finding the contact you needed. Please try again.','Edit Passwords Error');
				}
			}
		})
	}