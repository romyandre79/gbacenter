if ("undefined" === typeof jQuery)
	throw new Error("Currency's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'currency/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='currencyid']").val(data.currencyid);
				$("input[name='countryid']").val('');
				$("input[name='currencyname']").val('');
				$("input[name='symbol']").val('');
				$("input[name='i18n']").val('');
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
	jQuery.ajax({'url': 'currency/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='currencyid']").val(data.currencyid);
				$("input[name='countryid']").val(data.countryid);
				$("input[name='currencyname']").val(data.currencyname);
				$("input[name='symbol']").val(data.symbol);
				$("input[name='i18n']").val(data.i18n);
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
	jQuery.ajax({'url': 'currency/save',
		'data': {
			'currencyid': $("input[name='currencyid']").val(),
			'countryid': $("input[name='countryid']").val(),
			'currencyname': $("input[name='currencyname']").val(),
			'symbol': $("input[name='symbol']").val(),
			'i18n': $("input[name='i18n']").val(),
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
		jQuery.ajax({'url': 'currency/delete',
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
		jQuery.ajax({'url': 'currency/purge', 'data': {'id': $id},
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
			'currencyid': $id,
			'countryname': $("input[name='dlg_search_countryname']").val(),
			'currencyname': $("input[name='dlg_search_currencyname']").val(),
			'symbol': $("input[name='dlg_search_symbol']").val(),
			'i18n': $("input[name='dlg_search_i18n']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'currencyid=' + $id
		+ '&countryname=' + $("input[name='dlg_search_countryname']").val()
		+ '&currencyname=' + $("input[name='dlg_search_currencyname']").val()
		+ '&symbol=' + $("input[name='dlg_search_symbol']").val()
		+ '&i18n=' + $("input[name='dlg_search_i18n']").val();
	window.open('currency/downpdf?' + array);
}