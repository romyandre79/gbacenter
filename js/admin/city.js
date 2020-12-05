if ("undefined" === typeof jQuery)
	throw new Error("City's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'city/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='cityid']").val(data.cityid);
				$("input[name='provinceid']").val('');
				$("input[name='citycode']").val('');
				$("input[name='cityname']").val('');
				$("input[name='recordstatus']").prop('checked', true);
				$("input[name='provincename']").val('');
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedata($id) {
	jQuery.ajax({'url': 'city/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='cityid']").val(data.cityid);
				$("input[name='provinceid']").val(data.provinceid);
				$("input[name='citycode']").val(data.citycode);
				$("input[name='cityname']").val(data.cityname);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false)
				}
				$("input[name='provincename']").val(data.provincename);
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
	jQuery.ajax({'url': 'city/save',
		'data': {
			'cityid': $("input[name='cityid']").val(),
			'provinceid': $("input[name='provinceid']").val(),
			'citycode': $("input[name='citycode']").val(),
			'cityname': $("input[name='cityname']").val(),
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
		jQuery.ajax({'url': 'city/delete',
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
		jQuery.ajax({'url': 'city/purge', 'data': {'id': $id},
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
			'cityid': $id,
			'provincename': $("input[name='dlg_search_provincename']").val(),
			'citycode': $("input[name='dlg_search_citycode']").val(),
			'cityname': $("input[name='dlg_search_cityname']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'cityid=' + $id
		+ '&provincename=' + $("input[name='dlg_search_provincename']").val()
		+ '&citycode=' + $("input[name='dlg_search_citycode']").val()
		+ '&cityname=' + $("input[name='dlg_search_cityname']").val();
	window.open('city/downpdf?' + array);
}