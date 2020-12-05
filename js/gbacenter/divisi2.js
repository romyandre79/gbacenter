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

function savedatapeserta() {
	
	jQuery.ajax({'url': 'divisi/savepeserta',
		'data': {
			'divisiid': $("input[name='divisiid']").val(),
			'pesertaid': $("input[name='pesertaid']").val(),
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

function purgedata($id) {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'divisi/purge', 'data': {'id': $id},
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

function downpdf($id = 0) {
	var array = 'divisiid=' + $id
		+ '&kodedivisi=' + $("input[name='dlg_search_kodedivisi']").val()
		+ '&namadivisi=' + $("input[name='dlg_search_namadivisi']").val();
	window.open('divisi/downpdf?' + array);
}

function searchdata($id = 0) {
	$('#SearchDialog').modal('hide');
	$.fn.yiiGridView.update("GridList", {data: {
			'divisiid': $id,
			'kodedivisi': $("input[name='dlg_search_kodedivisi']").val(),
			'namadivisi': $("input[name='dlg_search_namadivisi']").val()
		}});
	return false;
}