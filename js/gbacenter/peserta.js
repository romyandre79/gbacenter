if ("undefined" === typeof jQuery)
	throw new Error("Parameter's JavaScript requires jQuery");
function newdata() {
	jQuery.ajax({'url': 'peserta/create', 'data': {},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='pesertaid']").val(data.pesertaid);
				$("input[name='idtelegram']").val('');
				$("input[name='nohp']").val('');
				$("input[name='nama']").val('');
				$("input[name='aliasid']").val('');
				$("input[name='alamat']").val('');
				$("input[name='kota']").val('');
				$("input[name='provinsi']").val('');
				$("input[name='negara']").val('');
				$("input[name='asalgereja']").val('');
				$("input[name='jabatangereja']").val('');
				$("input[name='tgllahir']").val('');
				$("input[name='sexid']:checked").val();
				$("input[name='statusbaca']").prop('checked', true);
				$("input[name='foto']").val('');	
				$("input[name='kontak']").val('');
				$("input[name='note']").val('');
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
	jQuery.ajax({'url': 'peserta/update', 'data': {'id': $id},
		'type': 'post', 'dataType': 'json',
		'success': function (data) {
			if (data.status === "success") {
				$("input[name='pesertaid']").val(data.pesertaid);
				$("input[name='idtelegram']").val(data.idtelegram);
				$("input[name='nohp']").val(data.nohp);
				$("input[name='nama']").val(data.nama);
				$("input[name='aliasid']").val(data.aliasid);
				$("input[name='alamat']").val(data.alamat);
				$("input[name='kota']").val(data.kota);
				$("input[name='provinsi']").val(data.provinsi);
				$("input[name='negara']").val(data.negara);
				$("input[name='asalgereja']").val(data.asalgereja);
				$("input[name='jabatangereja']").val(data.jabatangereja);
				$("input[name='tgllahir']").val(data.tgllahir);
				if (data.sexid === "1") {
					$("input[name='laki']").is('checked', true);
				} else {
					$("input[name='perempuan']").is('checked', false)
				}
				if (data.statusbaca === "1") {
					$("input[name='statusbaca']").prop('checked', true);
				} else {
					$("input[name='statusbaca']").prop('checked', false)
				}
				$("input[name='foto']").val(data.foto);
				$("input[name='kontak']").val(data.kontak);
				$("input[name='note']").val(data.note);
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
var statusbaca = 0;
	if ($("input[name='statusbaca']").prop('checked')) {
		statusbaca = 1;
	} else {
		statusbaca = 0;
	}
	jQuery.ajax({'url': 'peserta/save',
		'data': {
			'pesertaid': $("input[name='pesertaid']").val(),
			'idtelegram': $("input[name='idtelegram']").val(),
			'nohp': $("input[name='nohp']").val(),
			'nama': $("input[name='nama']").val(),
			'aliasid': $("input[name='aliasid']").val(),
			'alamat': $("input[name='alamat']").val(),
			'kota': $("input[name='kota']").val(),
			'provinsi': $("input[name='provinsi']").val(),
			'negara': $("input[name='negara']").val(),
			'asalgereja': $("input[name='asalgereja']").val(),
			'jabatangereja': $("input[name='jabatangereja']").val(),
			'tgllahir': $("input[name='tgllahir']").val(),
			'sexid': $("input[name='sexid']").val(),
			'statusbaca': statusbaca,
			'foto': $("input[name='foto']").val(),
			'kontak': $("input[name='kontak']").val(),
			'note': $("input[name='note']").val(),
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
		jQuery.ajax({'url': 'peserta/delete',
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
		jQuery.ajax({'url': 'peserta/purge', 'data': {'id': $id},
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
			'pesertaid': $id,
			'nama': $("input[name='dlg_search_nama']").val(),
			'idtelegram': $("input[name='dlg_search_idtelegram']").val(),
			'aliasid': $("input[name='dlg_search_aliasid']").val()
		}});
	return false;
}
function downpdf($id = 0) {
	var array = 'pesertaid=' + $id
		+ '&nama=' + $("input[name='dlg_search_nama']").val()
		+ '&aliasid=' + $("input[name='dlg_search_aliasid']").val()
		+ '&idtelegram=' + $("input[name='dlg_search_idtelegram']").val()
		+ '&nohp=' + $("input[name='dlg_search_nohp']").val()
		+ '&alamat=' + $("input[name='dlg_search_alamat']").val()
		+ '&asalgereja=' + $("input[name='dlg_search_asalgereja']").val()
		+ '&jabatangereja=' + $("input[name='dlg_search_jabatangereja']").val()
		+ '&kontak=' + $("input[name='dlg_search_kontak']").val()
		+ '&note=' + $("input[name='dlg_search_note']").val();
	window.open('peserta/downpdf?' + array);
}