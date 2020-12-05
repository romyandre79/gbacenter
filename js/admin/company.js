if ("undefined" === typeof jQuery)
	throw new Error("Company's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'company/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='companyid']").val(data.companyid);
				$("input[name='companyname']").val('');
				$("input[name='companycode']").val('');
				$("textarea[name='address']").val('');
				$("input[name='cityid']").val('');
				$("input[name='zipcode']").val('');
				$("input[name='taxno']").val('');
				$("input[name='currencyid']").val(data.currencyid);
				$("input[name='faxno']").val('');
				$("input[name='phoneno']").val('');
				$("input[name='webaddress']").val('');
				$("input[name='email']").val('');
				$("input[name='leftlogofile']").val('');
				$("input[name='rightlogofile']").val('');
				$("input[name='isholding']").prop('checked', true);
				$("textarea[name='billto']").val('');
				$("input[name='lat']").val('');
				$("input[name='lng']").val('');
				$("input[name='filelayout']").val('');
				$("input[name='recordstatus']").prop('checked', true);
				$("input[name='cityname']").val('');
				$("input[name='currencyname']").val(data.currencyname);
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedata($id) {
	jQuery.ajax({'url': 'company/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='companyid']").val(data.companyid);
				$("input[name='companyname']").val(data.companyname);
				$("input[name='companycode']").val(data.companycode);
				$("textarea[name='address']").val(data.address);
				$("input[name='cityid']").val(data.cityid);
				$("input[name='zipcode']").val(data.zipcode);
				$("input[name='taxno']").val(data.taxno);
				$("input[name='currencyid']").val(data.currencyid);
				$("input[name='faxno']").val(data.faxno);
				$("input[name='phoneno']").val(data.phoneno);
				$("input[name='webaddress']").val(data.webaddress);
				$("input[name='email']").val(data.email);
				$("input[name='leftlogofile']").val(data.leftlogofile);
				$("input[name='rightlogofile']").val(data.rightlogofile);
				if (data.isholding === "1") {
					$("input[name='isholding']").prop('checked', true);
				} else {
					$("input[name='isholding']").prop('checked', false);
				}
				$("textarea[name='billto']").val(data.billto);
				$("input[name='lat']").val(data.lat);
				$("input[name='lng']").val(data.lng);
				$("input[name='filelayout']").val(data.filelayout);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false);
				}
				$("input[name='cityname']").val(data.cityname);
				$("input[name='currencyname']").val(data.currencyname);
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function savedata() {
	var isholding = 0;
	if ($("input[name='isholding']").prop('checked')) {
		isholding = 1;
	} else {
		isholding = 0;
	}
	var recordstatus = 0;
	if ($("input[name='recordstatus']").prop('checked')) {
		recordstatus = 1;
	} else {
		recordstatus = 0;
	}
	jQuery.ajax({'url': 'company/save',
		'data': {
			'companyid': $("input[name='companyid']").val(),
			'companyname': $("input[name='companyname']").val(),
			'companycode': $("input[name='companycode']").val(),
			'address': $("textarea[name='address']").val(),
			'cityid': $("input[name='cityid']").val(),
			'zipcode': $("input[name='zipcode']").val(),
			'taxno': $("input[name='taxno']").val(),
			'currencyid': $("input[name='currencyid']").val(),
			'faxno': $("input[name='faxno']").val(),
			'phoneno': $("input[name='phoneno']").val(),
			'webaddress': $("input[name='webaddress']").val(),
			'email': $("input[name='email']").val(),
			'leftlogofile': $("input[name='leftlogofile']").val(),
			'rightlogofile': $("input[name='rightlogofile']").val(),
			'isholding': isholding,
			'billto': $("textarea[name='billto']").val(),
			'lat': $("input[name='lat']").val(),
			'lng': $("input[name='lng']").val(),
			'filelayout': $("input[name='filelayout']").val(),
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
		jQuery.ajax({'url': 'company/delete',
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
		jQuery.ajax({'url': 'company/purge', 'data': {'id': $id},
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
			'companyid': $id,
			'companyname': $("input[name='dlg_search_companyname']").val(),
			'companycode': $("input[name='dlg_search_companycode']").val(),
			'cityname': $("input[name='dlg_search_cityname']").val(),
			'zipcode': $("input[name='dlg_search_zipcode']").val(),
			'taxno': $("input[name='dlg_search_taxno']").val(),
			'currencyname': $("input[name='dlg_search_currencyname']").val(),
			'faxno': $("input[name='dlg_search_faxno']").val(),
			'phoneno': $("input[name='dlg_search_phoneno']").val(),
			'webaddress': $("input[name='dlg_search_webaddress']").val(),
			'email': $("input[name='dlg_search_email']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'companyid=' + $id
		+ '&companyname=' + $("input[name='dlg_search_companyname']").val()
		+ '&companycode=' + $("input[name='dlg_search_companycode']").val()
		+ '&cityname=' + $("input[name='dlg_search_cityname']").val()
		+ '&zipcode=' + $("input[name='dlg_search_zipcode']").val()
		+ '&taxno=' + $("input[name='dlg_search_taxno']").val()
		+ '&currencyname=' + $("input[name='dlg_search_currencyname']").val()
		+ '&faxno=' + $("input[name='dlg_search_faxno']").val()
		+ '&phoneno=' + $("input[name='dlg_search_phoneno']").val()
		+ '&webaddress=' + $("input[name='dlg_search_webaddress']").val()
		+ '&email=' + $("input[name='dlg_search_email']").val();
	window.open('company/downpdf?' + array);
}