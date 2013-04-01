
	function itemSelect(link) {
		jQuery.ajax({
			type:'GET',
			url:link+'itemSelect/add',
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					jQuery('#addPasswordsInfo').html(data);
				}else {
					jAlert('There was a problem finding the passwords you needed. Please try again.','Add Passwords Error');
				}
			}
		})
	}
	