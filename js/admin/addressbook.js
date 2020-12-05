if ("undefined" === typeof jQuery)
	throw new Error("Address Book's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'addressbook/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='addressbookid']").val(data.addressbookid);
				$("input[name='fullname']").val('');
				$("input[name='iscustomer']").prop('checked', true);
				$("input[name='isemployee']").prop('checked', true);
				$("input[name='isvendor']").prop('checked', true);
				$("input[name='ishospital']").prop('checked', true);
				$("input[name='recordstatus']").prop('checked', true);
        $("input[name='fullname']").val('');
        $("input[name='iscustomer']").prop('checked',true);
        $("input[name='isemployee']").prop('checked',true);
        $("input[name='isvendor']").prop('checked',true);
        $("input[name='currentlimit']").val(data.currentlimit);
        $("input[name='currentdebt']").val(data.currentdebt);
        $("input[name='taxno']").val('');
        $("input[name='creditlimit']").val(data.creditlimit);
        $("input[name='isstrictlimit']").prop('checked',true);
        $("input[name='bankname']").val('');
        $("input[name='bankaccountno']").val('');
        $("input[name='accountowner']").val('');
        $("input[name='salesareaid']").val('');
        $("input[name='pricecategoryid']").val('');
        $("input[name='overdue']").val('');
        $("input[name='invoicedate']").val(data.invoicedate);
        $("input[name='logo']").val('');
        $("input[name='url']").val('');
        $("input[name='recordstatus']").prop('checked',true);
        $("input[name='areaname']").val('');
        $("input[name='categoryname']").val('');
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedata($id) {
	jQuery.ajax({'url': 'addressbook/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='addressbookid']").val(data.addressbookid);
				$("input[name='fullname']").val(data.fullname);
        if (data.iscustomer === "1") {
          $("input[name='iscustomer']").prop('checked',true);
        }
        else {
          $("input[name='iscustomer']").prop('checked',false)
        }
        if (data.isemployee === "1") {
          $("input[name='isemployee']").prop('checked',true);
        }
        else {
          $("input[name='isemployee']").prop('checked',false)
        }
        if (data.isvendor === "1") {
          $("input[name='isvendor']").prop('checked',true);
        }
        else {
          $("input[name='isvendor']").prop('checked',false)
        }
        $("input[name='currentlimit']").val(data.currentlimit);
        $("input[name='currentdebt']").val(data.currentdebt);
        $("input[name='taxno']").val(data.taxno);
        $("input[name='creditlimit']").val(data.creditlimit);
        if (data.isstrictlimit === "1") {
          $("input[name='isstrictlimit']").prop('checked',true);
        }
        else {
          $("input[name='isstrictlimit']").prop('checked',false)
        }
        $("input[name='bankname']").val(data.bankname);
        $("input[name='bankaccountno']").val(data.bankaccountno);
        $("input[name='accountowner']").val(data.accountowner);
        $("input[name='salesareaid']").val(data.salesareaid);
        $("input[name='pricecategoryid']").val(data.pricecategoryid);
        $("input[name='overdue']").val(data.overdue);
        $("input[name='invoicedate']").val(data.invoicedate);
        $("input[name='logo']").val(data.logo);
        $("input[name='url']").val(data.url);
        if (data.recordstatus === "1")  {
          $("input[name='recordstatus']").prop('checked',true);
        }
        else {
          $("input[name='recordstatus']").prop('checked',false)
        }
        $("input[name='areaname']").val(data.areaname);
        $("input[name='categoryname']").val(data.categoryname);
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function savedata() {
	var iscustomer = 0;
	if ($("input[name='iscustomer']").prop('checked')) {
		iscustomer = 1;
	} else {
		iscustomer = 0;
	}
  var isstrictlimit = 0;
	if ($("input[name='isstrictlimit']").prop('checked')) {
		isstrictlimit = 1;
	}
	else {
		isstrictlimit = 0;
	}
	var isemployee = 0;
	if ($("input[name='isemployee']").prop('checked')) {
		isemployee = 1;
	} else {
		isemployee = 0;
	}
	var isvendor = 0;
	if ($("input[name='isvendor']").prop('checked')) {
		isvendor = 1;
	} else {
		isvendor = 0;
	}
	var recordstatus = 0;
	if ($("input[name='recordstatus']").prop('checked')) {
		recordstatus = 1;
	} else {
		recordstatus = 0;
	}
	jQuery.ajax({'url': 'addressbook/save',
		'data': {
			'addressbookid':$("input[name='addressbookid']").val(),
			'fullname':$("input[name='fullname']").val(),
      'iscustomer':iscustomer,
      'isemployee':isemployee,
      'isvendor':isvendor,
      'currentlimit':$("input[name='currentlimit']").val(),
      'currentdebt':$("input[name='currentdebt']").val(),
      'taxno':$("input[name='taxno']").val(),
      'creditlimit':$("input[name='creditlimit']").val(),
      'isstrictlimit':isstrictlimit,
      'bankname':$("input[name='bankname']").val(),
      'bankaccountno':$("input[name='bankaccountno']").val(),
      'accountowner':$("input[name='accountowner']").val(),
      'salesareaid':$("input[name='salesareaid']").val(),
      'pricecategoryid':$("input[name='pricecategoryid']").val(),
      'overdue':$("input[name='overdue']").val(),
      'invoicedate':$("input[name='invoicedate']").val(),
      'logo':$("input[name='logo']").val(),
      'url':$("input[name='url']").val(),
      'recordstatus':recordstatus,
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
		jQuery.ajax({'url': 'addressbook/delete',
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
		jQuery.ajax({'url': 'addressbook/purge', 'data': {'id': $id},
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
			'addressbookid': $id,
			'fullname': $("input[name='dlg_search_fullname']").val(),
			'taxno': $("input[name='dlg_search_taxno']").val(),
			'bankname': $("input[name='dlg_search_bankname']").val(),
			'bankaccountno': $("input[name='dlg_search_bankaccountno']").val(),
			'accountowner': $("input[name='dlg_search_accountowner']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'addressbookid=' + $id
		+ '&fullname=' + $("input[name='dlg_search_fullname']").val();
	window.open('addressbook/downpdf?' + array);
}