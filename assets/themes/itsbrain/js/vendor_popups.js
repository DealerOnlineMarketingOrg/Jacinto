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
	$('#editVendor').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/vendors/load_table',
			success:function(data) {
				if(data) {
					$('#vendorTable').html(data);
					$('#loader_block').slideUp('fast',function() {
						$('#example').dataTable({
							"bJQueryUI": true,
							"sPaginationType": "full_numbers",
							"sDom": '<""f>t<"F"lp>',
							'iDisplayLength':1000,
							"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
						});
						$('#vendorTable').slideDown('fast');
					});
				}else {
					$('#vendorTable').html('<p>No vendors found.</p>');
				}
			}
		});
	});
}

function addWebsiteForm(vid) {
	$.ajax({
		type:'GET',
		url:'/admin/websites/form?vid='+vid,
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
	
function editWebsiteForm(vid,wid) {
	$.ajax({
		type:'GET',
		url:'/admin/websites/form?vid='+vid+'&wid='+wid,
		success:function(data) {
			if(data){
				$('#addWebsiteForm').empty();
				$('#addWebsiteForm').html(data);
			}else {
				$('There was a problem finding the client you needed. Please try again.','Add Website Error');
			}
		}
	})
}

function editWebsite(vid,wid) {
	var formData = $('#web').serialize();
	$.ajax({
		type:'POST',
		url:'/admin/websites/edit?vid='+vid+'&wid='+wid,
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
	$.ajax({
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
	$.ajax({
		type:'POST',
		url:cUrl,
		data:formData,
		success:function(data) {
			if(data == '1') {
				jAlert(msg,'Success',function() {
					reloadWebsites(cid);	
					$('#editWebsite').empty();
					$('#addWebsite').empty();
				});
			}else {
				jAlert('There was a problem with processing your change. Please try again.','Error',function() {
					reloadWebsites(cid);
				});	
			}
		}
	});
}
