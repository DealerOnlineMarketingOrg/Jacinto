// JavaScript Document

var $ = jQuery;

function addVendor() {
	$('#editVendor').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/vendors/add',
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editVendorForm').html(data);
					});
				}else {
					$('There was an error with your request. Please Try Again.','Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function editVendor(vid) {
	$('#editVendor').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/vendors/edit?vid=' + vid,
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editVendorForm').html(data);
					});
				}else {
					jAlert('There was a problem finding the vendor requested. Please try again.','Edit Vendor Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		})
	});
}

function viewVendor(vid) {
	$('#editVendor').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/vendors/view?vid=' + vid,
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editVendorForm').html(data);
					});
				}else {
					jAlert('There was a problem finding the vendor requested. Please try again.','Edit Vendor Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		})
	});
}

function vendorTable() {
	jQuery('#editVendor').remove();
	jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
			type:"GET",
			url:'/admin/vendors/load_table',
			success:function(data) {
				if(data) {
					jQuery('#vendorTable').html(data);
					jQuery('#loader_block').slideUp('fast',function() {
						jQuery('#example').dataTable({
							"bJQueryUI": true,
							"sPaginationType": "full_numbers",
							"sDom": '<""f>t<"F"lp>',
							'iDisplayLength':1000,
							"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
						});
						jQuery('#vendorTable').slideDown('fast');
					});
				}else {
					jQuery('#vendorTable').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
				}
			}
		});
	});
}

function addWebsiteForm(vid) {
	jQuery.ajax({
		type:'GET',
		url:'/admin/websites/form?vid='+vid,
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
	
function editWebsiteForm(cid,wid) {
	jQuery.ajax({
		type:'GET',
		url:'/admin/websites/form?cid='+cid+'&wid='+wid,
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
