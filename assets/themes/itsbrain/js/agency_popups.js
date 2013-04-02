function addAgency() {
	jQuery.ajax({
		type:'GET',
		url:'/admin/agency/add',
		success:function(code) {
			jQuery('#addAgencyPop').html(code);	
		}
	});
}

function editAgency(aid) {
	jQuery.ajax({
		type:'GET',
		url:'/admin/agency/edit?aid='+aid,
		success:function(code) {
			jQuery('#addAgencyPop').html(code);	
		}
	});
}

function disableAgency(aid) {
	alert(aid);
}

function agencyListTable() {
  jQuery('#loader_block').slideDown('fast',function() {
	jQuery.ajax({
	  type:"GET",
	  url:'/admin/agency/load_table',
	  success:function(data) {
		if(data) {
		  jQuery('#agencyTable').html(data);
		  jQuery('#loader_block').slideUp('fast',function() {
			jQuery('#agencyTable').slideDown('fast');
		  });
		}else {
		  jQuery('#agencyTable').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
		}
	  }
	});
  });
}
