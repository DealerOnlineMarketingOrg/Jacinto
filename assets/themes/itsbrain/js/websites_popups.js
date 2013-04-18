
	function addWebsiteForm(id,type) {
		$.ajax({
			type:'GET',
			url:'/admin/websites/form?'+type+'='+id,
			//data:{client_id:id},
			success:function(data) {
				if(data){
					$('#addWebsiteForm').html(data);
				}else {
					$('There was a problem finding the client you needed. Please try again.','Add Website Error');
				}
			}
		})
	}

	function disableWebsite(wid) {
		jConfirm('Are you sure you would like to disable this website?','Disable Confirmation',function(r) {
			if(r) {
				jQuery.ajax({
					type:'GET',
					url:'/admin/websites/disable?wid='+wid,
					success:function(id) {
						if(id) {
							loadWebsiteTable(id);
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
					success:function(id) {
						if(id) {
							loadWebsiteTable(id);
						}else {
							jAlert('There was an error enabling the website you requested. Please try again.','Enable Error');	
						}
					}
				});
			}
		});
	}

	
	function addWebsiteForm(id,type) {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/form?'+type+'='+id,
			//data:{client_id:id},
			success:function(data) {
				if(data){
					jQuery('#addWebsiteForm').html(data);
				}else {
					jAlert('There was a problem finding the client you needed. Please try again.','Add Website Error');
				}
			}
		})
	}
	
	function editWebsiteForm(id,type,wid) {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/form?'+type+'='+id+'&wid='+wid,
			success:function(data) {
				if(data){
					jQuery('#addWebsiteForm').empty();
					jQuery('#addWebsiteForm').html(data);
				}else {
					jAlert('There was a problem finding the client you needed. Please try again.','Add Website Error');
				}
			}
		})
	}
	
	function editWebsite(id,type,wid) {
		var formData = jQuery('#web').serialize();
		jQuery.ajax({
			type:'POST',
			url:'/admin/websites/edit?'+type+'='+id+'&wid='+wid,
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

	function submitWebsiteForm(id,type,formData,cUrl,msg) {
		jQuery.ajax({
			type:'POST',
			url:cUrl,
			data:formData,
			success:function(data) {
				if(data == '1') {
					jAlert(msg,'Success',function() {
						reloadWebsites(id,type);	
						jQuery('#editWebsite').empty();
						jQuery('#addWebsite').empty();
					});
				}else {
					jAlert('There was a problem with processing your change. Please try again.','Error',function() {
						reloadWebsites(id,type);
					});	
				}
			}
		});
	}

	function loadWebsiteTable(id,type) {
		jQuery('#editClient').remove();
		//jQuery('#addWebsiteForm').dialog('close');	
		jQuery('#websites').slideUp('fast',function() {
			jQuery('#websites').empty();
			jQuery('#loader').fadeIn('fast',function() {
				jQuery('#loader').fadeIn('fast',function() {
					jQuery.ajax({
						type:'GET',
						url:'/admin/websites/load_table?'+type+'='+id,
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

	function reloadWebsites(id,type) {
		jQuery('#editClient').remove();
		jQuery('#addWebsiteForm').dialog('close');	
		jQuery('#websites').slideUp('fast',function() {
			jQuery('#websites').empty();
			jQuery('#loader').fadeIn('fast',function() {
				jQuery('#loader').fadeIn('fast',function() {
					jQuery.ajax({
						type:'GET',
						url:'/admin/websites/load_table?'+type+'='+id,
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

