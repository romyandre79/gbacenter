if ("undefined" === typeof jQuery)
	throw new Error("Divisi JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'divisi/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='divisiid']").val(data.divisiid);
				$("input[name='kodedivisi']").val('');
				$("input[name='namadivisi']").val('');
				$("input[name='parentid']").val('');
				$("input[name='notes']").val('');
				$("input[name='recordstatus']").prop('checked', true);
				$.fn.yiiGridView.update('groupmenuList', {data: {'pesertaid': data.pesertaid}});
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function newdatapeserta() {
	jQuery.ajax({'url': 'divisi/createpeserta', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='pesertaid']").val('');
				$('#InputDialoggroupmenu').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function newdatajabatan() {
	jQuery.ajax({'url': 'divisi/createjabatan', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='jabatanid']").val('');
				$('#InputDialoggroupmenu').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedata($id) {
	jQuery.ajax({'url': 'divisi/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='divisiid']").val(data.divisiid);
				$("input[name='kodedivisi']").val(data.kodedivisi);
				$("input[name='namadivisi']").val(data.namadivisi);
				$("input[name='parentid']").val(data.parentid);
				$("input[name='notes']").val(data.notes);
				$("input[name='recordstatus']").prop('checked', true);
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
	jQuery.ajax({'url': 'divisi/save',
		'data': {
			'divisiid': $("input[name='divisiid']").val(),
			'kodedivisi': $("input[name='kodedivisi']").val(),
			'namadivisi': $("input[name='namadivisi']").val(),
			'parentid': $("input[name='parentid']").val(),
			'notes': $("input[name='notes']").val(),
			'recordstatus': recordstatus,
			'pesertaid': $.fn.yiiGridView.getSelection("groupmenuList"),
			'jabatanid': $.fn.yiiGridView.getSelection("userdashList")
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
function savedatapeserta() {
	
	jQuery.ajax({'url': 'divisi/savepeserta',
		'data': {
			'divisiid': $("input[name='divisiid']").val(),
			'pesertaid': $("input[name='pesertaid']").val()
			'jabatanid': $("input[name='jabatanid']").val()
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