
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
	
	function addWebsiteForm(id) {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/form?cid='+id,
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
	
	function editWebsiteForm(id,cid) {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/form?cid='+cid+'&wid='+id,
			//data:{client_id:id},
			success:function(data) {
				if(data){
					jQuery('#editWebsite').html(data);
				}else {
					jAlert('There was a problem finding the client you needed. Please try again.','Add Website Error');
				}
			}
		})
	}

	
	function editWebsite(wid,cid) {
		var formData = jQuery('#web').serialize();
		jQuery.ajax({
			type:'POST',
			url:'/admin/websites/edit?cid='+cid+'&wid='+wid,
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


	function loadWebsiteTable(id) {
		var loader = '<div id="loader" style="text-align:center"><img src="/assets/themes/itsbrain/imgs/loaders/loader2.gif" alt="Loading Website Table" /></div>';
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/load_table?cid='+id,
			success:function(data) {
				if(data) {
					if(jQuery('#websites').length) {
						jQuery('#websites').slideUp('fast',function() {
							//remove the table
							jQuery('#websites').empty();
							//add the loader
							jQuery('#websites').append(loader);
							//load the table
							jQuery('#websites').apppend(data);
						})
					}
					
					
				}
			}
		})
	}
