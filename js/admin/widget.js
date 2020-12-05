if ("undefined" === typeof jQuery)
	throw new Error("Widget's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'widget/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='widgetid']").val(data.widgetid);
				$("input[name='widgetname']").val('');
				$("input[name='widgettitle']").val('');
				$("input[name='widgetversion']").val('');
				$("input[name='widgetby']").val('');
				$("textarea[name='description']").val('');
				$("input[name='widgeturl']").val('');
				$("input[name='moduleid']").val('');
				$("input[name='installdate']").val('');
				$("input[name='recordstatus']").prop('checked', true);
				$("input[name='modulename']").val('');
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedata($id) {
	jQuery.ajax({'url': 'widget/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='widgetid']").val(data.widgetid);
				$("input[name='widgetname']").val(data.widgetname);
				$("input[name='widgettitle']").val(data.widgettitle);
				$("input[name='widgetversion']").val(data.widgetversion);
				$("input[name='widgetby']").val(data.widgetby);
				$("textarea[name='description']").val(data.description);
				$("input[name='widgeturl']").val(data.widgeturl);
				$("input[name='moduleid']").val(data.moduleid);
				$("input[name='installdate']").val(data.installdate);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false)
				}
				$("input[name='modulename']").val(data.modulename);
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function savedata() {
	var recordstatus = 0;
	if ($("input[name='recordstatus']").prop('checked')) {
		recordstatus = 1;
	} else {
		recordstatus = 0;
	}
	jQuery.ajax({'url': 'widget/save',
		'data': {
			'widgetid': $("input[name='widgetid']").val(),
			'widgetname': $("input[name='widgetname']").val(),
			'widgettitle': $("input[name='widgettitle']").val(),
			'widgetversion': $("input[name='widgetversion']").val(),
			'widgetby': $("input[name='widgetby']").val(),
			'description': $("textarea[name='description']").val(),
			'widgeturl': $("input[name='widgeturl']").val(),
			'moduleid': $("input[name='moduleid']").val(),
			'installdate': $("input[name='installdate']").val(),
			'recordstatus': recordstatus
		},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$('#InputDialog').modal('hide');
				toastr.info(data.msg);
				$.fn.yiiGridView.update("GridList");
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
}
function deletedata($id) {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'widget/delete',
			'data': {'id': $id},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status === "success") {
					toastr.info(data.msg);
					$.fn.yiiGridView.update("GridList");
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
	};
	return false;
}
function purgedata($id) {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'widget/purge', 'data': {'id': $id},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status === "success") {
					toastr.info(data.msg);
					$.fn.yiiGridView.update("GridList");
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
	};
	return false;
}
function searchdata($id = 0) {
	$('#SearchDialog').modal('hide');
	$.fn.yiiGridView.update("GridList", {data: {
			'widgetid': $id,
			'widgetname': $("input[name='dlg_search_widgetname']").val(),
			'widgettitle': $("input[name='dlg_search_widgettitle']").val(),
			'widgetversion': $("input[name='dlg_search_widgetversion']").val(),
			'widgetby': $("input[name='dlg_search_widgetby']").val(),
			'widgeturl': $("input[name='dlg_search_widgeturl']").val(),
			'modulename': $("input[name='dlg_search_modulename']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'widgetid=' + $id
		+ '&widgetname=' + $("input[name='dlg_search_widgetname']").val()
		+ '&widgettitle=' + $("input[name='dlg_search_widgettitle']").val()
		+ '&widgetversion=' + $("input[name='dlg_search_widgetversion']").val()
		+ '&widgetby=' + $("input[name='dlg_search_widgetby']").val()
		+ '&widgeturl=' + $("input[name='dlg_search_widgeturl']").val()
		+ '&modulename=' + $("input[name='dlg_search_modulename']").val();
	window.open('widget/downpdf?' + array);
}