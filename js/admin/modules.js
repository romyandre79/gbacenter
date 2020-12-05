if("undefined"===typeof jQuery)throw new Error("Module's JavaScript requires jQuery");
function running(id,param2) {
	jQuery.ajax({'url':'modules/running',
		'data':{
			'id':param2
		},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status === "success")
			{
				location.reload();
				toastr.info(data.msg);
			}
			else
			{
				toastr.error(data.msg);
			}
		},
		'cache':false});
}
function Uninstall(elmnt) {
  jQuery.ajax({'url':'modules/uninstall',
    'data':{'module':elmnt},
    'type':'post','dataType':'json',
    'success':function(data) {
      if (data.status === "success")
      {
        toastr.info(data.msg);
        location.reload();
      }
      else
      {
        toastr.error(data.msg);
      }
    },
    'cache':false});
}
function searchdata($id = 0) {
	$('#SearchDialog').modal('hide');
	$.fn.yiiListView.update("GridList", {data: {
			'createdby': $("input[name='dlg_search_createdby']").val(),
			'moduleversion': $("input[name='dlg_search_moduleversion']").val(),
			'modulename': $("input[name='dlg_search_modulename']").val(),
			'description': $("input[name='dlg_search_description']").val(),
		}});
	return false;
}