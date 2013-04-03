function addGroup() {
	jQuery.ajax({
		type:'GET',
		url:'/admin/group/add',
		success:function(code) {
			jQuery('#addGroupPop').html(code);	
		}
	});
}

function editGroup(gid) {
	jQuery.ajax({
		type:'GET',
		url:'/admin/groups/edit?gid='+gid,
		success:function(code) {
			if(code == '0') {
				jAlert('The Group can not be found. Please try again','Error');
			}else {
				jQuery('#addGroupPop').html(code);
			}
		}
	});
}

function viewGroup(gid) {
	jQuery.ajax({
		type:'GET',
		url:'/admin/groups/view?gid='+gid,
		success:function(code) {
			jQuery('#viewGroupPop').html(code);	
		}
	});
}

function groupListTable() {
  jQuery('#loader_block').slideDown('fast',function() {
	jQuery.ajax({
	  type:"GET",
	  url:'/admin/groups/load_table',
	  success:function(data) {
		if(data) {
		  jQuery('#groupTable').html(data);
		  jQuery('#loader_block').slideUp('fast',function() {
			jQuery('#groupTable').slideDown('fast');
		  });
		}else {
		  jQuery('#groupTable').html('<p>No groups found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
		}
	  }
	});
  });
}
