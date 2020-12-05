if ("undefined" === typeof jQuery)
	throw new Error("User Dashboard's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'useraccess/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='useraccessid']").val(data.useraccessid);
				$("input[name='actiontype']").val(0);
				$("input[name='username']").val('');
				$("input[name='realname']").val('');
				$("input[name='password']").val('');
				$("input[name='email']").val('');
				$("input[name='phoneno']").val('');
				$("input[name='userphoto']").val('');
				$("input[name='languageid']").val('');
				$("input[name='recordstatus']").prop('checked', true);
				$("input[name='languagename']").val('');
				$.fn.yiiGridView.update('usergroupList', {data: {'useraccessid': data.useraccessid}});
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function newdatausergroup() {
	jQuery.ajax({'url': 'useraccess/createusergroup', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='usergroupid']").val('');
				$("input[name='groupaccessid']").val('');
				$("input[name='groupname']").val('');
				$('#InputDialogusergroup').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}

function updatedata($id) {
	jQuery.ajax({'url': 'useraccess/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='useraccessid']").val(data.useraccessid);
				$("input[name='actiontype']").val(1);
				$("input[name='username']").val(data.username);
				$("input[name='realname']").val(data.realname);
				$("input[name='password']").val(data.password);
				$("input[name='email']").val(data.email);
				$("input[name='userphoto']").val(data.userphoto);
				$("input[name='phoneno']").val(data.phoneno);
				$("input[name='languageid']").val(data.languageid);
				if (data.recordstatus === "1") {
					$("input[name='recordstatus']").prop('checked', true);
				} else {
					$("input[name='recordstatus']").prop('checked', false);
				}
				$("input[name='languagename']").val(data.languagename);
				$.fn.yiiGridView.update('usergroupList', {data: {'useraccessid': data.useraccessid}});
				$('#InputDialog').modal();
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
	return false;
}
function updatedatausergroup($id) {
	jQuery.ajax({'url': 'useraccess/updateusergroup', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='usergroupid']").val(data.usergroupid);
				$("input[name='groupaccessid']").val(data.groupaccessid);
				$("input[name='groupname']").val(data.groupname);
				$('#InputDialogusergroup').modal();
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
	jQuery.ajax({'url': 'useraccess/save',
		'data': {
			'actiontype': $("input[name='actiontype']").val(),
			'useraccessid': $("input[name='useraccessid']").val(),
			'username': $("input[name='username']").val(),
			'realname': $("input[name='realname']").val(),
			'password': $("input[name='password']").val(),
			'email': $("input[name='email']").val(),
			'userphoto': $("input[name='userphoto']").val(),
			'phoneno': $("input[name='phoneno']").val(),
			'languageid': $("input[name='languageid']").val(),
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
function savedatausergroup() {
	jQuery.ajax({'url': 'useraccess/saveusergroup',
		'data': {
			'useraccessid': $("input[name='useraccessid']").val(),
			'usergroupid': $("input[name='usergroupid']").val(),
			'groupaccessid': $("input[name='groupaccessid']").val(),
		},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$('#InputDialogusergroup').modal('hide');
				toastr.info(data.msg);
				$.fn.yiiGridView.update("usergroupList");
			} else {
				toastr.error(data.msg);
			}
		},
		'cache': false});
}
function deletedata($id) {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'useraccess/delete',
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
		jQuery.ajax({'url': 'useraccess/purge', 'data': {'id': $id},
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
function purgedatausergroup() {
	if (confirm('Apakah anda yakin ?')) {
		jQuery.ajax({'url': 'useraccess/purgeusergroup', 'data': {'id': $.fn.yiiGridView.getSelection("usergroupList")},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status === "success") {
					toastr.info(data.msg);
					$.fn.yiiGridView.update("usergroupList");
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
			'useraccessid': $id,
			'username': $("input[name='dlg_search_username']").val(),
			'realname': $("input[name='dlg_search_realname']").val(),
			'password': $("input[name='dlg_search_password']").val(),
			'email': $("input[name='dlg_search_email']").val(),
			'phoneno': $("input[name='dlg_search_phoneno']").val(),
			'languagename': $("input[name='dlg_search_languagename']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'useraccessid=' + $id
		+ '&username=' + $("input[name='dlg_search_username']").val()
		+ '&realname=' + $("input[name='dlg_search_realname']").val()
		+ '&password=' + $("input[name='dlg_search_password']").val()
		+ '&email=' + $("input[name='dlg_search_email']").val()
		+ '&phoneno=' + $("input[name='dlg_search_phoneno']").val()
		+ '&languagename=' + $("input[name='dlg_search_languagename']").val();
	window.open('useraccess/downpdf?' + array);
}
function GetDetail($id) {
	$('#ShowDetailDialog').modal('show');
	var array = 'useraccessid=' + $id;
	$.fn.yiiGridView.update("DetailusergroupList", {data: array});
}