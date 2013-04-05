
	function editClient(id,link) {
		jQuery('#editClient').remove();
		jQuery.ajax({
			type:'GET',
			url:'/admin/clients/edit?cid=' + id,
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					jQuery('#editClientsForm').html(data);
				}else {
					jAlert('There was a problem finding the client you needed.Please try again.','Edit Client Error');
				}
			}
		})
	}
	
	function viewClient(id) {
		jQuery('#editClient').remove();
		jQuery.ajax({
			type:"GET",
			url:'/admin/clients/view?cid='+id,
			success:function(data) {
				if(data) {
					jQuery('#editClientsForm').html(data);
				}else {
					jAlert('There was an error finding the client in our system. Please try again.','View Error');
				}
			}
		});
	}
	
	function clientListTable() {
	  jQuery('#editClient').remove();
	  jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
		  type:"GET",
		  url:'/admin/clients/load_table',
		  success:function(data) {
			if(data) {
			  jQuery('#clientTableHolder').html(data);
			  jQuery('#loader_block').slideUp('fast',function() {
						jQuery('#example').dataTable({
							"bJQueryUI": true,
							"sPaginationType": "full_numbers",
							"sDom": '<""f>t<"F"lp>',
							'iDisplayLength':1000,
							"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
						});
				jQuery('#dataClient').slideDown('fast');
			  });
			}else {
			  jQuery('#dataClient').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
			}
		  }
		});
	  });
	}
	
	function disableWebsite(wid) {
		jConfirm('Are you sure you would like to disable this website?','Disable Confirmation',function(r) {
			if(r) {
				jQuery.ajax({
					type:'GET',
					url:'/admin/websites/disable?wid='+wid,
					success:function(cid) {
						if(cid) {
							loadWebsiteTable(cid);
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
					success:function(cid) {
						if(cid) {
							loadWebsiteTable(cid);
						}else {
							jAlert('There was an error enabling the website you requested. Please try again.','Enable Error');	
						}
					}
				});
			}
		});
	}

	
	function addWebsiteForm(cid) {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/form?cid='+cid,
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
	
	function editWebsiteForm(cid,wid) {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/form?cid='+cid+'&wid='+wid,
			success:function(data) {
				if(data){
					jQuery('#editWebsite').empty();
					jQuery('#editWebsite').html(data);
				}else {
					jAlert('There was a problem finding the client you needed. Please try again.','Add Website Error');
				}
			}
		})
	}
	
	function editWebsite(cid,wid) {
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

	function submitWebsiteForm(cid,formData,cUrl,msg) {
		jQuery.ajax({
			type:'POST',
			url:cUrl,
			data:formData,
			success:function(data) {
				if(data == '1') {
					jAlert(msg,'Success',function() {
						reloadWebsites(cid);	
						jQuery('#editWebsite').empty();
						jQuery('#addWebsite').empty();
					});
				}else {
					jAlert('There was a problem with processing your change. Please try again.','Error',function() {
						reloadWebsites(cid);
					});	
				}
			}
		});
	}

	function loadWebsiteTable(id) {
		jQuery('#editClient').remove();
		//jQuery('#addWebsiteForm').dialog('close');	
		jQuery('#websites').slideUp('fast',function() {
			jQuery('#websites').empty();
			jQuery('#loader').fadeIn('fast',function() {
				jQuery('#loader').fadeIn('fast',function() {
					jQuery.ajax({
						type:'GET',
						url:'/admin/websites/load_table?cid='+id,
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

	function reloadWebsites(cid) {
		jQuery('#editClient').remove();
		jQuery('#addWebsiteForm').dialog('close');	
		jQuery('#websites').slideUp('fast',function() {
			jQuery('#websites').empty();
			jQuery('#loader').fadeIn('fast',function() {
				jQuery('#loader').fadeIn('fast',function() {
					jQuery.ajax({
						type:'GET',
						url:'/admin/websites/load_table?cid='+cid,
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

function addClient() {
  jQuery.ajax({
    type:'GET',
    url:'/admin/clients/add_form',
    success:function(data) {
      if(data) {
        jQuery('#editClientsForm').html(data);
      }else {
        jAlert('There was an error with your request. Please Try Again.','Error');
      }
    }
  });
}