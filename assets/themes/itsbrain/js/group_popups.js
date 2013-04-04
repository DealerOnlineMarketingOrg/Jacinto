
var $ = jQuery;

function addGroup() {
	$('#addGroup').remove();
	$('#editGroup').remove();
	$('#viewGroup').remove();
	
	$.ajax({
		type:'GET',
		url:'/admin/groups/add',
		success:function(code) {
			if(code == '0') {
				jAlert('The Group can not be found. Please try again','Error');
			}else {
				$('#editGroupPop').html(code);
			}
		}
	});
}

function editGroup(gid) {
	$('#addGroup').remove();
	$('#editGroup').remove();
	$('#viewGroup').remove();
	$.ajax({
		type:'GET',
		url:'/admin/groups/edit?gid='+gid,
		success:function(code) {
			if(code == '0') {
				jAlert('The Group can not be found. Please try again','Error');
			}else {
				$('#editGroupPop').html(code);
			}
		}
	});
}

function viewGroup(gid) {
	$('#addGroup').remove();
	$('#editGroup').remove();
	$('#viewGroup').remove();
	$.ajax({
		type:'GET',
		url:'/admin/groups/view?gid='+gid,
		success:function(code) {
			$('#editGroupPop').html(code);	
		}
	});
}

function groupListTable() {	
	$('#addGroup').remove();
	$('#editGroup').remove();
	$('#viewGroup').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/groups/load_table',
			success:function(data) {
				if(data) {
					$('#groupTable').html(data);
					$('#loader_block').slideUp('fast',function() {
						$('#example').dataTable({
							"bJQueryUI": true,
							"sPaginationType": "full_numbers",
							"sDom": '<""f>t<"F"lp>',
							'iDisplayLength':1000,
							"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
						});
	
						$('#groupTable').slideDown('fast');
					});
				}else {
					$('#groupTable').html('<p>No groups found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
				}
			}
		});
	});
}
