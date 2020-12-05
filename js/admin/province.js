if ("undefined" === typeof jQuery)
	throw new Error("Province's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'province/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='provinceid']").val(data.provinceid);
				$("input[name='countryid']").val('');
				$("input[name='provincecode']").val('');
				$("input[name='provincename']").val('');
				$("input[name='recordstatus']").prop('checked', true);
				$("input[name='countryname']").val('');
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedata($id) {
	jQuery.ajax({'url': 'province/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='provinceid']").val(data.provinceid);
				$("input[name='countryid']").val(data.countryid);
				$("input[name='provincecode']").val(data.provincecode);
				$("input[name='provincename']").val(data.provincename);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false)
				}
				$("input[name='countryname']").val(data.countryname);
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
	jQuery.ajax({'url': 'province/save',
		'data': {
			'provinceid': $("input[name='provinceid']").val(),
			'countryid': $("input[name='countryid']").val(),
			'provincecode': $("input[name='provincecode']").val(),
			'provincename': $("input[name='provincename']").val(),
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
		jQuery.ajax({'url': 'province/delete',
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
		jQuery.ajax({'url': 'province/purge', 'data': {'id': $id},
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
			'provinceid': $id,
			'countryname': $("input[name='dlg_search_countryname']").val(),
			'provincecode': $("input[name='dlg_search_provincecode']").val(),
			'provincename': $("input[name='dlg_search_provincename']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'provinceid=' + $id
		+ '&countryname=' + $("input[name='dlg_search_countryname']").val()
		+ '&provincecode=' + $("input[name='dlg_search_provincecode']").val()
		+ '&provincename=' + $("input[name='dlg_search_provincename']").val();
	window.open('province/downpdf?' + array);
}