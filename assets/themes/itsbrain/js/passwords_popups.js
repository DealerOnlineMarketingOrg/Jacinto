
	function editPasswords(pwdid,link) {
		jQuery.ajax({
			type:'GET',
			url:link+'passwords/edit?pwdid=' + pwdid,
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					jQuery('#editPasswordsInfo').html(data);
				}else {
					jAlert('There was a problem finding the client you needed. Please try again.','Edit Passwords Error');
				}
			}
		})
	}
	
	function viewPasswords(id) {
		alert(id);
	}
	