// JavaScript Document
var $ = jQuery;

function editEntry(id) {
	$.ajax({
		type:'GET',
		url:'/admin/masterlist/edit_entry?cid='+id,
		success:function(data) {
			if(data) {
				$('#editEntry').html(data);	
			}
		}
	});
}

function refreshTable() {
	
}