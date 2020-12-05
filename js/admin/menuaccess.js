if ("undefined" === typeof jQuery)
	throw new Error("Menu Access's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'menuaccess/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='menuaccessid']").val(data.menuaccessid);
				$("input[name='menuname']").val('');
				$("input[name='menutitle']").val('');
				$("textarea[name='description']").val('');
				$("input[name='moduleid']").val('');
				$("input[name='parentid']").val('');
				$("input[name='menuurl']").val('');
				$("input[name='sortorder']").val('');
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
	jQuery.ajax({'url': 'menuaccess/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='menuaccessid']").val(data.menuaccessid);
				$("input[name='menuname']").val(data.menuname);
				$("input[name='menutitle']").val(data.menutitle);
				$("textarea[name='description']").val(data.description);
				$("input[name='moduleid']").val(data.moduleid);
				$("input[name='parentid']").val(data.parentid);
				$("input[name='menuurl']").val(data.menuurl);
				$("input[name='sortorder']").val(data.sortorder);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false)
				}
				$("input[name='modulename']").val(data.modulename);
				$("input[name='parentname']").val(data.parentname);
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
	jQuery.ajax({'url': 'menuaccess/save',
		'data': {
			'menuaccessid': $("input[name='menuaccessid']").val(),
			'menuname': $("input[name='menuname']").val(),
			'menutitle': $("input[name='menutitle']").val(),
			'description': $("textarea[name='description']").val(),
			'moduleid': $("input[name='moduleid']").val(),
			'parentid': $("input[name='parentid']").val(),
			'menuurl': $("input[name='menuurl']").val(),
			'sortorder': $("input[name='sortorder']").val(),
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
		jQuery.ajax({'url': 'menuaccess/delete',
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
		jQuery.ajax({'url': 'menuaccess/purge', 'data': {'id': $id},
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
			'menuaccessid': $id,
			'menuname': $("input[name='dlg_search_menuname']").val(),
			'menutitle': $("input[name='dlg_search_menutitle']").val(),
			'modulename': $("input[name='dlg_search_modulename']").val(),
			'parentname': $("input[name='dlg_search_parentname']").val(),
			'menuurl': $("input[name='dlg_search_menuurl']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'menuaccessid=' + $id
		+ '&menuname=' + $("input[name='dlg_search_menuname']").val()
		+ '&menutitle=' + $("input[name='dlg_search_menutitle']").val()
		+ '&modulename=' + $("input[name='dlg_search_modulename']").val()
		+ '&parentname=' + $("input[name='dlg_search_parentname']").val()
		+ '&menuurl=' + $("input[name='dlg_search_menuurl']").val();
	window.open('menuaccess/downpdf?' + array);
}