if("undefined"==typeof jQuery)throw new Error("Menu Generator's JavaScript requires jQuery");
function readfield()
{
	var iswrite = 0;
	if ($("input[name='iswrite']").prop('checked'))
	{
		iswrite = 1;
	}
	else
	{
		iswrite = 0;
	}
	var isreject = 0;
	if ($("input[name='isreject']").prop('checked'))
	{
		isreject = 1;
	}
	else
	{
		isreject = 0;
	}
	var ispost = 0;
	if ($("input[name='ispost']").prop('checked'))
	{
		ispost = 1;
	}
	else
	{
		ispost = 0;
	}
	var isdownload = 0;
	if ($("input[name='isdownload']").prop('checked'))
	{
		isdownload = 1;
	}
	else
	{
		isdownload = 0;
	}
	var ispurge = 0;
	if ($("input[name='ispurge']").prop('checked'))
	{
		ispurge = 1;
	}
	else
	{
		ispurge = 0;
	}
	jQuery.ajax({'url':'menugenerator/readfield',
		'data':{
			'tablename':$('input[name="tablename"]').val(),
			'menuname':$('input[name="menuname"]').val(),
			'module':$('input[name="module"]').val(),
			'controller':$('input[name="controller"]').val(),
			'appwf':$('input[name="appwf"]').val(),
			'rejwf':$('input[name="appwf"]').val(),
			'inswf':$('input[name="inswf"]').val(),
			'addonwhere':$('textarea[name="addonwhere"]').val(),
			'iswrite':iswrite,
			'isreject':isreject,
			'ispurge':ispurge,
			'isdownload':isdownload,
			'ispost':ispost,
		},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == "success")
			{
				toastr.info(data.div);
			}
			else
			{
				toastr.error(data.div);
			}
			$.fn.yiiGridView.update("GridList");
		},
	'cache':false});
	return false;
}
function updatedata($id)
{
	jQuery.ajax({'url':'menugenerator/update',
		'data':{'id':$id},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == "success")
			{
				$("input[name='namafield']").val(data.namafield);
				$("input[name='menugenid']").val(data.menugenid);
				$("input[name='tipefield']").val(data.tipefield);
				if (data.isview == 1)
				{
					$("input[name='isview']").prop('checked',true);
				}
				else
				{
					$("input[name='isview']").prop('checked',false)
				}
				if (data.issearch == 1)
				{
					$("input[name='issearch']").prop('checked',true);
				}
				else
				{
					$("input[name='issearch']").prop('checked',false)
				}
				if (data.isvalidate == 1)
				{
					$("input[name='isvalidate']").prop('checked',true);
				}
				else
				{
					$("input[name='isvalidate']").prop('checked',false)
				}
				if (data.isinput == 1)
				{
					$("input[name='isinput']").prop('checked',true);
				}
				else
				{
					$("input[name='isinput']").prop('checked',false)
				}
				if (data.isprint == 1)
				{
					$("input[name='isprint']").prop('checked',true);
				}
				else
				{
					$("input[name='isprint']").prop('checked',false)
				}
				$("select[name='widgetrelation']").val(data.widgetrelation);
				$("input[name='wirelsource']").val(data.wirelsource);
				$("input[name='relationname']").val(data.relationname);
				$("input[name='defaultvalue']").val(data.defaultvalue);
				$("input[name='tablefkname']").val(data.tablefkname);
				$("input[name='popupname']").val(data.popupname);
				$("input[name='tablerelation']").val(data.tablerelation);
				$('#InputDialog').modal();
			}
		},
		'cache':false});
	return false;
}
function savedata()
{
	var isview = 0;
	if ($("input[name='isview']").prop('checked'))
	{
		isview = 1;
	}
	else
	{
		isview = 0;
	}
	var issearch = 0;
	if ($("input[name='issearch']").prop('checked'))
	{
		issearch = 1;
	}
	else
	{
		issearch = 0;
	}
	var isvalidate = 0;
	if ($("input[name='isvalidate']").prop('checked'))
	{
		isvalidate = 1;
	}
	else
	{
		isvalidate = 0;
	}
	var isinput = 0;
	if ($("input[name='isinput']").prop('checked'))
	{
		isinput = 1;
	}
	else
	{
		isinput = 0;
	}
	var isprint = 0;
	if ($("input[name='isprint']").prop('checked'))
	{
		isprint = 1;
	}
	else
	{
		isprint = 0;
	}
	jQuery.ajax({'url':'menugenerator/save',
		'data':{
			'namafield':$('input[name="namafield"]').val(),
			'menugenid':$('input[name="menugenid"]').val(),
			'tipefield':$('input[name="tipefield"]').val(),
			'widgetrelation':$('select[name="widgetrelation"]').val(),
			'wirelsource':$('input[name="wirelsource"]').val(),
			'relationname':$('input[name="relationname"]').val(),
			'tablerelation':$('input[name="tablerelation"]').val(),
			'tablefkname':$('input[name="tablefkname"]').val(),
			'popupname':$('input[name="popupname"]').val(),
			'issearch':issearch,
			'isview':isview,
			'isvalidate':isvalidate,
			'isinput':isinput,
			'isprint':isprint,
			'tablename':$('input[name="tablename"]').val(),
			'menuname':$('input[name="menuname"]').val(),
			'module':$('input[name="module"]').val(),
			'controller':$('input[name="controller"]').val(),
			'defaultvalue':$('input[name="defaultvalue"]').val(),
			'appwf':$('input[name="appwf"]').val(),
			'rejwf':$('input[name="appwf"]').val(),
			'inswf':$('input[name="inswf"]').val(),
			'addonwhere':$('textarea[name="addonwhere"]').val(),
		},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == "success")
			{
				$('#InputDialog').modal('hide');
				toastr.info(data.div);
				$.fn.yiiGridView.update("GridList");
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	return false;
}
function purgedata($id)
{
	jQuery.ajax({'url':'menugenerator/purge',
		'data':{
			'id':$id
		},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == "success")
			{
				$('#InputDialog').modal('hide');
				toastr.info(data.div);
				$.fn.yiiGridView.update("GridList");
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	return false;
}
function install()
{
	var iswrite = 0;
	if ($("input[name='iswrite']").prop('checked'))
	{
		iswrite = 1;
	}
	else
	{
		iswrite = 0;
	}
	var isreject = 0;
	if ($("input[name='isreject']").prop('checked'))
	{
		isreject = 1;
	}
	else
	{
		isreject = 0;
	}
	var ispost = 0;
	if ($("input[name='ispost']").prop('checked'))
	{
		ispost = 1;
	}
	else
	{
		ispost = 0;
	}
	var isdownload = 0;
	if ($("input[name='isdownload']").prop('checked'))
	{
		isdownload = 1;
	}
	else
	{
		isdownload = 0;
	}
	var ispurge = 0;
	if ($("input[name='ispurge']").prop('checked'))
	{
		ispurge = 1;
	}
	else
	{
		ispurge = 0;
	}
	jQuery.ajax({'url':'menugenerator/generate',
	'data':{
		'tablename':$('input[name="tablename"]').val(),
		'menuname':$('input[name="menuname"]').val(),
		'module':$('input[name="module"]').val(),
		'controller':$('input[name="controller"]').val(),
		'appwf':$('input[name="appwf"]').val(),
		'rejwf':$('input[name="appwf"]').val(),
		'inswf':$('input[name="inswf"]').val(),
		'addonwhere':$('textarea[name="addonwhere"]').val(),
		'iswrite':iswrite,
		'isreject':isreject,
		'ispurge':ispurge,
		'isdownload':isdownload,
		'ispost':ispost,
	},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == "success")
			{
				toastr.info(data.div);
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	return false;
}
$(function() {
    $('#rootwizard').bootstrapWizard({onTabShow: function(tab, navigation, index) {
			var $total = navigation.find('li').length;
		var $current = index+1;
		var $percent = ($current/$total) * 100;
		$('#wizprogress').css({width:$percent+'%'});
		$('#wizprogress').attr({'aria-valuenow':$percent});
		$('#wizprogress').html(Math.round($percent)+'%');
		
		// If it's the last tab then hide the last button and show the finish instead
		if($current >= $total) {
			$('#rootwizard').find('.pager .next').hide();
			$('#rootwizard').find('.pager .finish').show();
			$('#rootwizard').find('.pager .finish').removeClass('disabled');
		} else {
			$('#rootwizard').find('.pager .next').show();
			$('#rootwizard').find('.pager .finish').hide();
		}
	},
	/*onTabClick: function(tab, navigation, index) {
		alert('<?php echo $this->getCatalog("notallowtabclick")?>');
		return false;
	},*/
	onNext: function(tab, navigation, index) {
			if(index==2) {
				// Make sure we entered the name
				if(!$('input[name="tablename"]').val()) {
					toastr.error('<?php echo $this->getCatalog("emptytablename")?>');
					$('input[name="tablename"]').focus();
					return false;
				}
				else
				if(!$('input[name="controller"]').val()) {
					toastr.error('<?php echo $this->getCatalog("emptycontroller")?>');
					$('input[name="controller"]').focus();
					return false;
				}
				else
				if(!$('input[name="module"]').val()) {
					toastr.error('<?php echo $this->getCatalog("emptymodule")?>');
					$('input[name="module"]').focus();
					return false;
				}
			}			
		}}
	);
	$('#rootwizard .finish').click(function() {
		location.reload();
	});
});
/*<![CDATA[*/
jQuery(function($) {

$('input[name="tablename"]').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('input[name="controller"]').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('input[name="menuname"]').bind('keyup change', function(){
	var tablename=$('input[name="tablename"]');
	var controller=$('input[name="controller"]');
	if(!tablename.data('changed')) {
		var id=new String($(this).val().match(/\w*$/));
		if(id.length>0)
			id=id.substring(0,1).toLowerCase()+id.substring(1);
		tablename.val(id);
	}
	if(!controller.data('changed')) {
		var id=new String($(this).val().match(/\w*$/));
		if(id.length>0)
			id=id.substring(0,1).toUpperCase()+id.substring(1);
		controller.val(id);
	}
});

$('input[name="tablefkname"]').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('input[name="relationname"]').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('input[name="popupname"]').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('input[name="tablerelation"]').bind('keyup change', function(){
	var tablename=$('input[name="tablefkname"]');
	var controller=$('input[name="relationname"]');
	var popup=$('input[name="popupname"]');
	if(!tablename.data('changed')) {
		var id=new String($(this).val().match(/\w*$/));
		if(id.length>0)
			id=id.substring(0)+'id';
		tablename.val(id);
	}
	if(!controller.data('changed')) {
		var id=new String($(this).val().match(/\w*$/));
		if(id.length>0)
			id=id.substring(0)+'name';
		controller.val(id);
	}
	if(!popup.data('changed')) {
		var id=new String($(this).val().match(/\w*$/));
		if(id.length>0)
			id=id.substring(0)+'name';
		popup.val(id);
	}
});

});
/*]]>*/