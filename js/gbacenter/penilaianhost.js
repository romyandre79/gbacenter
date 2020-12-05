if ("undefined" === typeof jQuery)
	throw new Error("Divisi JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'penilaianhost/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='penilaianid']").val(data.penilaianid);
				$("input[name='grupid']").val('');
				$("input[name='startdate']").val('');
				$("input[name='enddate']").val('');
				$("input[name='notes']").val('');
				$.fn.yiiGridView.update('groupmenuList', {data: {'penilaianid': data.penilaianid}});
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}

function newdatagruppenilaian() {
	jQuery.ajax({'url': 'penilaianhost/creategruppenilaian', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='pesertaid']").val('');
				$("input[name='rating']").val('');
				$("textarea[name='notes']").val('');
				$('#InputDialoggroupmenu').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}

function savedatamenupenilaian() {
	jQuery.ajax({'url': 'penilaianhost/savegruppenilaian',
		'data': {
			'penilaianid': $("input[name='penilaianid']").val(),
			'pesertaid': $("input[name='pesertaid']").val(),
			'rating': $("input[name='rating']").val(),
			'notes': $("textarea[name='notesdetail']").val()
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
	jQuery.ajax({'url': 'penilaianhost/save',
		'data': {
			'penilaianid': $("input[name='penilaianid']").val(),
			'grupbacaid': $("input[name='grupbacaid']").val(),
			'stardate': $("input[name='startdate']").val(),
			'enddate': $("input[name='enddate']").val(),
			'notes': $("textarea[name='notes']").val()
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
		jQuery.ajax({'url': 'penilaianhost/purge', 'data': {'id': $id},
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
	var array = 'grupbacaid=' + $id
		+ '&kodegrup=' + $("input[name='dlg_search_kodegrup']").val()
		+ '&namagrup=' + $("input[name='dlg_search_namagrup']").val();
	window.open('grupbaca/downpdf?' + array);
}

function searchdata($id = 0) {
	$('#SearchDialog').modal('hide');
	$.fn.yiiGridView.update("GridList", {data: {
			'grupbacaid': $id,
			'kodegrup': $("input[name='dlg_search_kodegrup']").val(),
			'namagrup': $("input[name='dlg_search_namagrup']").val()
		}});
	return false;
}

function getdetail($id) {
	$('#ShowDetailDialog').modal('show');
	var array = 'bukubacaanid=' + $id;
	$.fn.yiiGridView.update("DetailgroupmenuList", {data: array});
}

function updatedatagroupmenu($id) {
	jQuery.ajax({'url': 'penilaianhost/updategroupmenu', 'data': {'id': $id},
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
	jQuery.ajax({'url': 'penilaianhost/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='grupbacaid']").val(data.grupbacaid);
				$("input[name='kodegrup']").val(data.kodegrup);
				$("input[name='namagrup']").val(data.namagrup);
				$("input[name='divisiid']").val(data.divisiid);
				$("input[name='startdate']").val(data.startdate);
				$("input[name='bukubacaanid']").val(data.bukubacaanid);
				$("input[name='notes']").val(data.notes);
				$("input[name='recordstatus']").prop('checked', true);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false)
				}
				$.fn.yiiGridView.update('groupmenuList', {data: {'grupbacaid': data.grupbacaid}});
				$("input[name='namadivisi']").val(data.namadivisi);
				$("input[name='namabuku']").val(data.namabuku);
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}