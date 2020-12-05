<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	function newdata() {
		var x;
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/category/create') ?>', 'data': {},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				$("input[name='categoryid']").val('');
				$("input[name='title']").val('');
				$("select[name='parentid']").val('');
				$("#description").summernote('code');
				$("input[name='slug']").val('');
				$('#InputDialog').modal();
			},
			'cache': false});
		return false;
	}
	function updatedata(elmnt) {
		var x;
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/category/update') ?>', 'data': {'id': elmnt.name},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				$("input[name='categoryid']").val(data.data.categoryid);
				$("input[name='title']").val(data.data.title);
				$("select[name='parentid']").val(data.data.parentid);
				$("#description").summernote('code',data.data.description);
				$("input[name='slug']").val(data.data.slug);
				$('#InputDialog').modal();
			},
			'cache': false});
		return false;
	}
	function savedata() {
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/category/save') ?>',
			'data': {
				'categoryid': $("input[name='categoryid']").val(),
				'title': $("input[name='title']").val(),
				'parentid': $("select[name='parentid']").val(),
				'description': $("#description").summernote('code'),
				'slug': $("input[name='slug']").val()
			},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status == "success") {
					$('#InputDialog').modal('hide');
					toastr.info(data.msg);
					$.fn.yiiListView.update("GridList");
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
	}
	function deletedata(elmnt) {
    if (confirm('<?php echo getCatalog('areyousure') ?>')) {
			jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/category/delete') ?>',
				'data': {'id': elmnt.name},
				'type': 'post', 'dataType': 'json',
				'success': function (data) {
					if (data.status == "success") {
						toastr.info(data.msg);
						$.fn.yiiListView.update("GridList");
					} else {
						toastr.error(data.msg);
					}
				},
				'cache': false});
    };
	}
	function purgedata() {
		if (confirm('<?php echo getCatalog('areyousure') ?>')) {
			jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/category/purge') ?>', 'data': {'id': elmnt.name},
				'type': 'post', 'dataType': 'json',
				'success': function (data) {
					if (data.status == "success") {
						toastr.info(data.msg);
						$.fn.yiiListView.update("GridList");
					} else {
						toastr.error(data.msg);
					}
				},
				'cache': false});
		};
	}
	function searchdata() {
		$('#SearchDialog').modal('hide');
		var array = 'title=' + $("input[name='dlg_search_title']").val() +
			'&description=' + $("input[name='dlg_search_description']").val();
		$.fn.yiiListView.update("GridList", {data: array});
		return false;
	}
	function downpdf() {
		var array = 'title=' + $("input[name='dlg_search_title']").val() +
			'&description=' + $("input[name='dlg_search_description']").val();
		window.open('<?php echo Yii::app()->createUrl('blog/category/downpdf') ?>?' + array);
	}
	$(document).ready(function () {
		$('#description').summernote({
			height: 300,
		});
	});
</script>
<div class="card card-purple">
	<div class="card-header with-border">
    <h2 class="card-title"><?php echo getCatalog('category') ?></h2>
    <div class="card-tools">
    <?php if (CheckAccess('category', 'iswrite')) { ?>
	<button name="CreateButton" type="button" class="btn btn-primary" onclick="newdata()"><?php echo getCatalog('new') ?></button>
<?php } ?>
<button name="SearchButton" type="button" class="btn btn-success" data-toggle="modal" data-target="#SearchDialog"><?php echo getCatalog('search') ?></button>
<?php if (CheckAccess('category', 'isdownload')) { ?>
	<div class="btn-group">
		<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
			<?php echo getCatalog('download') ?> <span class="caret"></span></button>
		<ul class="dropdown-menu" role="menu">
			<li class="dropdown-item"><a onclick="downpdf()">PDF</a></li>
		</ul>
	</div>
<?php } ?>
<button name="HelpButton" type="button" class="btn btn-warning" data-toggle="modal" data-target="#HelpDialog"><?php echo getCatalog('help') ?></button>
</div>
	</div>
	<div class="card-body">
<?php
$this->widget('zii.widgets.CListView',
	array(
	'dataProvider' => $dataProvider,
	'id' => 'GridList',
	'template' => '{sorter}{pager}{summary}{items}{pager}',
	'itemView' => '_view',
));
?>
<?php if (CheckAccess('category', 'iswrite')) { ?>
	<button name="CreateButton" type="button" class="btn btn-primary" onclick="newdata()"><?php echo getCatalog('new') ?></button>
<?php } ?>
<button name="SearchButton" type="button" class="btn btn-success" data-toggle="modal" data-target="#SearchDialog"><?php echo getCatalog('search') ?></button>
<?php if (CheckAccess('category', 'isdownload')) { ?>
	<div class="btn-group">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
			<?php echo getCatalog('download') ?> <span class="caret"></span></button>
		<ul class="dropdown-menu" role="menu">
			<li><a onclick="downpdf()">PDF</a></li>
		</ul>
	</div>
<?php } ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'title'),
  array('searchtype'=>'text','searchname'=>'description'),
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/v3C353O1ph4')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('category') ?></h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="categoryid">
				<div class="form-group">
					<label for="title"><?php echo getCatalog('title') ?></label>
					<input type="text" class="form-control" name="title">
				</div>
				<div class="form-group">
					<label for="description"><?php echo getCatalog('description') ?></label>
					<div name="description" id="description"></div>
				</div>
				<div class="form-group">
					<label for="parentid"><?php echo getCatalog('parent') ?></label>
					<select class="form-control" name="parentid">
						<?php
						foreach (getAllCategory() as $category) {
							echo "<option value='".$category['categoryid']."'>".$category['title']."</option>";
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="slug"><?php echo getCatalog('slug') ?></label>
					<input type="text" class="form-control" name="slug">
				</div>
      </div>
      <div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedata()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/summernote/summernote-bs4.css">
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/summernote/summernote-bs4.min.js"></script>  
