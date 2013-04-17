// JavaScript Document
var $ = jQuery;
function addUser() {
	
}

function editUser(id) {
	$('#editClient').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/users/edit?uid='+id,
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editUsersForm').html(data);
					});
				}else {
					jAlert('There was an error finding the user in our system. Please try again.','View Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function editUserInfo(id) {
	$('#editUserInfo').remove();
	$.ajax({
		type:'GET',
		url:'/admin/users/edit_details_form?uid='+id,
		success:function(data) {
			if(data) {
				$('#editUserDetailsForm').html(data);	
			}else {
				jAlert('There was an error finding the user in our system. Please try again.','View Error');
			}
		}
	});
}

function viewUser(id) {
	$('#editClient').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/users/view_popup?uid='+id,
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editUsersForm').html(data);
					});
				}else {
					jAlert('There was an error finding the user in our system. Please try again.','View Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function load_user_table() {
	
}

function changeMyPass(id) {
	$('#changePassword').remove();
	$.ajax({
		type:"GET",
		url:'/admin/users/change_pass_form?uid='+id,
		success:function(data) {
			if(data) {
				$('#passwordForms').html(data);
			}else {
				jAlert('There was an error finding the user in our system. Please try again.','View Error');
			}
		}
	});
}

function resetMyPass(id) {
	jConfirm('Are you sure you want to reset this users password?','Confirmation Password Reset',function(r) {
		if(r) {
			$.ajax({
				type:"POST",
				url:'/admin/users/reset_user_password?uid='+id,
				success:function(data) {
					if(data != '0') {
						jAlert('You have reset the users password to ' + data,'Alert');
					}
				}
			});
		}
	});
}