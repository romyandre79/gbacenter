if ("undefined" === typeof jQuery)
	throw new Error("Widget's JavaScript requires jQuery");
function searchdata($id = 0) {
	$('#SearchDialog').modal('hide');
	$.fn.yiiListView.update("GridList", {data: {
			'themeid': $id,
			'themename': $("input[name='dlg_search_themename']").val(),
			'description': $("input[name='dlg_search_description']").val(),
			'themeversion': $("input[name='dlg_search_themeversion']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'widgetid=' + $id
		+ '&themename=' + $("input[name='dlg_search_themename']").val()
		+ '&description=' + $("input[name='dlg_search_description']").val()
		+ '&themeversion=' + $("input[name='dlg_search_themeversion']").val();
	window.open('theme/downpdf?' + array);
}