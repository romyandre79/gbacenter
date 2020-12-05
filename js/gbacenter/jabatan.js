if ("undefined" === typeof jQuery)
	throw new Error("Parameter's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'jabatan/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='jabatanid']").val(data.jabatanid);
				$("input[name='kodejabatan']").val('');
				$("input[name='namajabatan']").val('');
				$("input[name='jobdesk']").val('');
				$("input[name='istelegram']").prop('checked', true);
				$("input[name='recordstatus']").prop('checked', true);
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedata($id) {
	jQuery.ajax({'url': 'jabatan/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='jabatanid']").val(data.jabatanid);
				$("input[name='kodejabatan']").val(data.kodejabatan);
				$("input[name='namajabatan']").val(data.namajabatan);
				$("input[name='jobdesk']").val(data.jobdesk);
				if (data.istelegram === "1") {
					$("input[name='istelegram']").prop('checked', true);
				} else {
					$("input[name='istelegram']").prop('checked', false)
				}
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false)
				}

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
var istelegram = 0;
	if ($("input[name='istelegram']").prop('checked')) {
		istelegram = 1;
	} else {
		istelegram = 0;
	}
	jQuery.ajax({'url': 'jabatan/save',
		'data': {
			'jabatanid': $("input[name='jabatanid']").val(),
			'kodejabatan': $("input[name='kodejabatan']").val(),
			'namajabatan': $("input[name='namajabatan']").val(),
			'jobdesk': $("input[name='jobdesk']").val(),
			'istelegram': istelegram,
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
		jQuery.ajax({'url': 'jabatan/delete',
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
		jQuery.ajax({'url': 'jabatan/purge', 'data': {'id': $id},
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
			'jabatanid': $id,
			'kodejabatan': $("input[name='dlg_search_kodejabatan']").val(),
			'namajabatan': $("input[name='dlg_search_namajabatan']").val(),
			'jobdesk': $("input[name='dlg_search_jobdesk']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'jabatanid=' + $id
		+ '&kodejabatan=' + $("input[name='dlg_search_kodejabatan']").val()
		+ '&namajabatan=' + $("input[name='dlg_search_namajabatan']").val()
		+ '&jobdesk=' + $("input[name='dlg_search_jobdesk']").val()
		+ '&istelegram=' + $("input[name='dlg_search_istelegram']").val();
	window.open('jabatan/downpdf?' + array);
}