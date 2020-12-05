if ("undefined" === typeof jQuery)
	throw new Error("Group Access's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'groupaccess/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='groupaccessid']").val(data.groupaccessid);
				$("input[name='actiontype']").val(0);
				$("input[name='groupname']").val('');
				$("input[name='description']").val('');
				$("input[name='recordstatus']").prop('checked', true);
				$.fn.yiiGridView.update('groupmenuList', {data: {'groupaccessid': data.groupaccessid}});
				$.fn.yiiGridView.update('userdashList', {data: {'groupaccessid': data.groupaccessid}});

				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function newdatagroupmenu() {
	jQuery.ajax({'url': 'groupaccess/creategroupmenu', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='groupmenuid']").val('');
				$("input[name='menuaccessid']").val('');
				$("input[name='isread']").prop('checked', true);
				$("input[name='iswrite']").prop('checked', true);
				$("input[name='ispost']").prop('checked', true);
				$("input[name='isreject']").prop('checked', true);
				$("input[name='ispurge']").prop('checked', true);
				$("input[name='isupload']").prop('checked', true);
				$("input[name='isdownload']").prop('checked', true);
				$("input[name='menuname']").val('');
				$('#InputDialoggroupmenu').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function newdatauserdash() {
	jQuery.ajax({'url': 'groupaccess/createuserdash', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='userdashid']").val('');
				$("input[name='widgetid']").val('');
				$("input[name='widgetmenuaccessid']").val('');
				$("input[name='position']").val(data.position);
				$("input[name='webformat']").val(data.webformat);
				$("input[name='dashgroup']").val(data.dashgroup);
				$("input[name='widgetname']").val('');
				$("input[name='widgetmenuname']").val('');
				$('#InputDialoguserdash').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedata($id) {
	jQuery.ajax({'url': 'groupaccess/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='groupaccessid']").val(data.groupaccessid);
				$("input[name='actiontype']").val(1);
				$("input[name='groupname']").val(data.groupname);
				$("input[name='description']").val(data.description);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false)
				}
				$.fn.yiiGridView.update('groupmenuList', {data: {'groupaccessid': data.groupaccessid}});
				$.fn.yiiGridView.update('userdashList', {data: {'groupaccessid': data.groupaccessid}});

				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedatagroupmenu($id) {
	jQuery.ajax({'url': 'groupaccess/updategroupmenu', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='groupmenuid']").val(data.groupmenuid);
				$("input[name='menuaccessid']").val(data.menuaccessid);
				if (data.isread === "1") {
					$("input[name='isread']").prop('checked', true);
				} else {
					$("input[name='isread']").prop('checked', false);
				}
				if (data.iswrite === "1") {
					$("input[name='iswrite']").prop('checked', true);
				} else {
					$("input[name='iswrite']").prop('checked', false);
				}
				if (data.ispost === "1") {
					$("input[name='ispost']").prop('checked', true);
				} else {
					$("input[name='ispost']").prop('checked', false);
				}
				if (data.isreject === "1") {
					$("input[name='isreject']").prop('checked', true);
				} else {
					$("input[name='isreject']").prop('checked', false);
				}
				if (data.ispurge === "1") {
					$("input[name='ispurge']").prop('checked', true);
				} else {
					$("input[name='ispurge']").prop('checked', false);
				}
				if (data.isupload === "1") {
					$("input[name='isupload']").prop('checked', true);
				} else {
					$("input[name='isupload']").prop('checked', false);
				}
				if (data.isdownload === "1") {
					$("input[name='isdownload']").prop('checked', true);
				} else {
					$("input[name='isdownload']").prop('checked', false);
				}
				$("input[name='menuname']").val(data.menuname);
				$('#InputDialoggroupmenu').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedatauserdash($id) {
	jQuery.ajax({'url': 'groupaccess/updateuserdash', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='userdashid']").val(data.userdashid);
				$("input[name='widgetid']").val(data.widgetid);
				$("input[name='widgetmenuaccessid']").val(data.menuaccessid);
				$("input[name='position']").val(data.position);
				$("input[name='webformat']").val(data.webformat);
				$("input[name='dashgroup']").val(data.dashgroup);
				$("input[name='widgetname']").val(data.widgetname);
				$("input[name='widgetmenuname']").val(data.menuname);
				$('#InputDialoguserdash').modal();
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
	jQuery.ajax({'url': 'groupaccess/save',
		'data': {
			'actiontype': $("input[name='actiontype']").val(),
			'groupaccessid': $("input[name='groupaccessid']").val(),
			'groupname': $("input[name='groupname']").val(),
			'description': $("input[name='description']").val(),
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
function savedatagroupmenu() {
	var isread = 0;
	if ($("input[name='isread']").prop('checked')) {
		isread = 1;
	} else {
		isread = 0;
	}
	var iswrite = 0;
	if ($("input[name='iswrite']").prop('checked')) {
		iswrite = 1;
	} else {
		iswrite = 0;
	}
	var ispost = 0;
	if ($("input[name='ispost']").prop('checked')) {
		ispost = 1;
	} else {
		ispost = 0;
	}
	var isreject = 0;
	if ($("input[name='isreject']").prop('checked')) {
		isreject = 1;
	} else {
		isreject = 0;
	}
	var ispurge = 0;
	if ($("input[name='ispurge']").prop('checked')) {
		ispurge = 1;
	} else {
		ispurge = 0;
	}
	var isupload = 0;
	if ($("input[name='isupload']").prop('checked')) {
		isupload = 1;
	} else {
		isupload = 0;
	}
	var isdownload = 0;
	if ($("input[name='isdownload']").prop('checked')) {
		isdownload = 1;
	} else {
		isdownload = 0;
	}
	jQuery.ajax({'url': 'groupaccess/savegroupmenu',
		'data': {
			'groupaccessid': $("input[name='groupaccessid']").val(),
			'groupmenuid': $("input[name='groupmenuid']").val(),
			'menuaccessid': $("input[name='menuaccessid']").val(),
			'isread': isread,
			'iswrite': iswrite,
			'ispost': ispost,
			'isreject': isreject,
			'ispurge': ispurge,
			'isupload': isupload,
			'isdownload': isdownload
		},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$('#InputDialoggroupmenu').modal('hide');
				toastr.info(data.msg);
				$.fn.yiiGridView.update("groupmenuList");
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
}
function savedatauserdash() {
	jQuery.ajax({'url': 'groupaccess/saveuserdash',
		'data': {
			'groupaccessid': $("input[name='groupaccessid']").val(),
			'userdashid': $("input[name='userdashid']").val(),
			'widgetid': $("input[name='widgetid']").val(),
			'menuaccessid': $("input[name='widgetmenuaccessid']").val(),
			'position': $("input[name='position']").val(),
			'webformat': $("input[name='webformat']").val(),
			'dashgroup': $("input[name='dashgroup']").val()
		},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$('#InputDialoguserdash').modal('hide');
				toastr.info(data.msg);
				$.fn.yiiGridView.update("userdashList");
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
}
function deletedata($id) {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'groupaccess/delete',
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
		jQuery.ajax({'url': 'groupaccess/purge', 'data': {'id': $id},
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
function purgedatagroupmenu() {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'groupaccess/purgegroupmenu', 'data': {'id': $.fn.yiiGridView.getSelection("groupmenuList")},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status === "success") {
					toastr.info(data.msg);
					$.fn.yiiGridView.update("groupmenuList");
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
	};
	return false;
}
function purgedatauserdash() {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'groupaccess/purgeuserdash', 'data': {'id': $.fn.yiiGridView.getSelection("userdashList")},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status === "success") {
					toastr.info(data.msg);
					$.fn.yiiGridView.update("userdashList");
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
			'groupaccessid': $id,
			'groupname': $("input[name='dlg_search_groupname']").val(),
			'description': $("input[name='dlg_search_description']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'groupaccessid=' + $id
		+ '&groupname=' + $("input[name='dlg_search_groupname']").val()
		+ '&description=' + $("input[name='dlg_search_description']").val();
	window.open('groupaccess/downpdf?' + array);
}
function getdetail($id) {
	$('#ShowDetailDialog').modal('show');
	var array = 'groupaccessid=' + $id;
	$.fn.yiiGridView.update("DetailgroupmenuList", {data: array});
	$.fn.yiiGridView.update("DetailuserdashList", {data: array});
}