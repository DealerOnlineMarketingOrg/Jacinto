
	function editClient(id,link) {
		jQuery.ajax({
			type:'GET',
			url:link+'clients/edit?cid=' + id,
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					jQuery('#editClientInfo').html(data);
				}else {
					jAlert('There was a problem finding the client you needed.Please try again.','Edit Client Error');
				}
			}
		})
	}
	
	function viewClient(id) {
		alert(id);
	}
	
	function disableClient(id) {
		alert(id);	
	}
	
	function enableClient(id) {
		alert(id);
	}
	
	function addWebsite(id) {
		jQuery.ajax({
			type:'GET',
			url:'/admin/clients/add_website_form?cid='+id,
			//data:{client_id:id},
			success:function(data) {
				if(data){
					jQuery('#addWebsite').html(data);
				}else {
					jAlert('There was a problem finding the client you needed. Please try again.','Add Website Error');
				}
			}
		})
	}
