if ("undefined" === typeof jQuery)
	throw new Error("Parameter's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'team/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='teamid']").val(data.teamid);
				$("input[name='kodeteam']").val('');
				$("input[name='namateam']").val('');
				$("input[name='koordinator']").val('');
				$("input[name='wakilkoordinator']").val('');
				$("input[name='keterangan']").val('');
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
	jQuery.ajax({'url': 'team/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='teamid']").val(data.teamid);
				$("input[name='kodeteam']").val(data.kodeteam);
				$("input[name='namateam']").val(data.namateam);
				$("input[name='koordinator']").val(data.koordinator);
				$("input[name='wakilkoordinator']").val(data.wakilkoordinator);
				$("input[name='keterangan']").val(data.keterangan);
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
	jQuery.ajax({'url': 'team/save',
		'data': {
			'teamid': $("input[name='teamid']").val(),
			'kodeteam': $("input[name='kodeteam']").val(),
			'namateam': $("input[name='namateam']").val(),
			'koordinator': $("input[name='koordinator']").val(),
			'wakilkoordinator': $("input[name='wakilkoordinator']").val(),
			'keterangan': $("input[name='keterangan']").val(),
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
		jQuery.ajax({'url': 'team/delete',
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
		jQuery.ajax({'url': 'team/purge', 'data': {'id': $id},
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
			'teamid': $id,
			'kodeteam': $("input[name='dlg_search_kodeteam']").val(),
			'namateam': $("input[name='dlg_search_namateam']").val(),
			'koordinator': $("input[name='dlg_search_koordinator']").val(),
			'wakilkoordinator': $("input[name='dlg_search_wakilkoordinator']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'teamid=' + $id
		+ '&kodeteam=' + $("input[name='dlg_search_kodejabatan']").val()
		+ '&namateam=' + $("input[name='dlg_search_namajabatan']").val()
		+ '&koordinator=' + $("input[name='dlg_search_koordinator']").val()
		+ '&wakilkoordinator=' + $("input[name='dlg_search_wakilkoordinator']").val()
		+ '&istelegram=' + $("input[name='dlg_search_istelegram']").val();
	window.open('jabatan/downpdf?' + array);
}