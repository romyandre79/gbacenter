if ("undefined" === typeof jQuery)
	throw new Error("Divisi JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'bacaan/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='bukubacaanid']").val(data.bukubacaanid);
				$("input[name='kodebuku']").val('');
				$("input[name='namabuku']").val('');
				$("input[name='jumlah']").val('');
				$("input[name='total']").val('');
				$("input[name='notes']").val('');
				$("input[name='recordstatus']").prop('checked', true);
				$.fn.yiiGridView.update('groupmenuList', {data: {'bukubacaanid': data.bukubacaanid}});
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}

function newdatapeserta() {
	jQuery.ajax({'url': 'bacaan/createbacaan', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='bukubacaan']").val('');
				$('#InputDialoggroupmenu').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}

function savedatamenuharian() {
	
	jQuery.ajax({'url': 'bacaan/savemenuharian',
		'data': {
			'bukubacaanid': $("input[name='bukubacaanid']").val(),
			'hari': $("input[name='hari']").val(),
			'menuharian': $("input[name='menuharian']").val(),
			'url': $("input[name='url']").val()
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
	jQuery.ajax({'url': 'bacaan/save',
		'data': {
			'bukubacaanid': $("input[name='bukubacaanid']").val(),
			'kodebuku': $("input[name='kodebuku']").val(),
			'namabuku': $("input[name='namabuku']").val(),
			'jumlah': $("input[name='jumlah']").val(),
			'total': $("input[name='total']").val(),
			'notes': $("input[name='notes']").val(),
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

function purgedata($id) {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'bacaan/purge', 'data': {'id': $id},
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
	var array = 'bukubacaanid=' + $id
		+ '&kodebuku=' + $("input[name='dlg_search_kodebuku']").val()
		+ '&namabuku=' + $("input[name='dlg_search_namabuku']").val();
	window.open('bacaan/downpdf?' + array);
}

function searchdata($id = 0) {
	$('#SearchDialog').modal('hide');
	$.fn.yiiGridView.update("GridList", {data: {
			'bukubacaanid': $id,
			'kodebuku': $("input[name='dlg_search_kodebuku']").val(),
			'namabuku': $("input[name='dlg_search_namabuku']").val()
		}});
	return false;
}

function getdetail($id) {
	$('#ShowDetailDialog').modal('show');
	var array = 'bukubacaanid=' + $id;
	$.fn.yiiGridView.update("DetailgroupmenuList", {data: array});
}

function updatedatagroupmenu($id) {
	jQuery.ajax({'url': 'bacaan/updategroupmenu', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='bukubacaanid']").val(data.bukubacaanid);
				$("input[name='bukubacaandetailid']").val(data.bukubacaandetailid);
				$("input[name='hari']").val(data.hari);
				$("input[name='menuharian']").val(data.menuharian);
				$('#InputDialoggroupmenu').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}

function updatedata($id) {
	jQuery.ajax({'url': 'bacaan/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='bukubacaanid']").val(data.divisiid);
				$("input[name='kodebuku']").val(data.kodebuku);
				$("input[name='namabuku']").val(data.namabuku);
				$("input[name='jumlah']").val(data.jumlah);
				$("input[name='total']").val(data.total);
				$("input[name='notes']").val(data.notes);
				$("input[name='recordstatus']").prop('checked', true);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false)
				}
				$.fn.yiiGridView.update('groupmenuList', {data: {'bukubacaanid': data.bukubacaanid}});

				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}