if ("undefined" === typeof jQuery)
	throw new Error("Capella's JavaScript requires jQuery");
$(document).ready(function () {
	$(document).keyup(function (e) {
		switch (e.which) {
			case 113: //F2 - Baru
				newdata();
				break;
			case 115: //F4 - Aktif / Not Aktif atau Reject
				deletedata($.fn.yiiGridView.getSelection('GridList'));
				break;
			case 118: //F7 - Purge / Hapus
				purgedata($.fn.yiiGridView.getSelection('GridList'));
				break;
			case 119: //F8 - Search
				$('#SearchDialog').modal("show");
				break;
			case 121: //F10 - PDF
				downpdf($.fn.yiiGridView.getSelection('GridList'));
				break;
		}
		e.preventDefault();
	});
	$(":input[name*='dlg_search_']").keyup(function (e) {
		if (e.keyCode === 13) {
			searchdata();
		}
	});
	$('#SearchDialog').on('shown.bs.modal', function () {
		$(":input[name*='dlg_search_']:visible:first").focus();
	});
	$('#InputDialog').on('shown.bs.modal', function () {
		$(":input:not(input[type=button],input[type=submit],button):visible:first").focus();
	});
	toastr.options = {
		positionClass: 'toast-bottom-right'
	};
});