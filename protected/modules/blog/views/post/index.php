<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	function newdata() {
		var x;
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/post/create') ?>', 'data': {},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				$("input[name='postid']").val('');
				$("input[name='title']").val('');
				$("input[name='metatag']").val('');
				$("#description").summernote('code','');
				$("input[name='slug']").val('');
				$("input[name='postpic']").val('');
				for (x in data.allcategory) {
					$('#' + data.allcategory[x].categoryid).prop('checked', false);
				}
				$('#InputDialog').modal();
			},
			'cache': false});
		return false;
	}
	function updatedata(elmnt) {
		var x;
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/post/update') ?>', 'data': {'id': elmnt.name},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				$("input[name='postid']").val(data.data.postid);
				$("input[name='title']").val(data.data.title);
				$("select[name='parentid']").val(data.data.parentid);
				$("#description").summernote('code',data.data.description);
				$("input[name='slug']").val(data.data.slug);
				$("input[name='metatag']").val(data.data.metatag);
				$("input[name='postpic']").val(data.data.postpic);
				for (x in data.allcategory) {
					$('#' + data.allcategory[x].categoryid).prop('checked', false);
				}
				for (x in data.category) {
					$('#' + data.category[x].categoryid).prop('checked', true);
				}
				$('#InputDialog').modal();
			},
			'cache': false});
		return false;
	}
	function savedata() {
		var array = $("input[name='category']:checked").map(function () {
			return this.id;
		}).get();
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/post/save') ?>',
			'data': {
				'postid': $("input[name='postid']").val(),
				'title': $("input[name='title']").val(),
				'metatag': $("input[name='metatag']").val(),
				'description': $("#description").summernote('code'),
				'slug': $("input[name='slug']").val(),
				'postpic': $("input[name='postpic']").val(),
				'category': array
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
		return false;
	}
	function deletedata(elmnt) {
		if (confirm('<?php echo getCatalog('areyousure') ?>')) {
			jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/post/delete') ?>',
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
		return false;
	}
	function purgedata() {
		if (confirm('<?php echo getCatalog('areyousure') ?>')) {
			jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/post/purge') ?>', 'data': {'id': elmnt.name},
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
		return false;
	}
	function searchdata() {
		$('#SearchDialog').modal('hide');
    $.fn.yiiListView.update("GridList", {data: {
			'title': $("input[name='dlg_search_title']").val(),
			'description': $("input[name='dlg_search_description']").val()
		}});
		return false;
	}
	function downpdf() {
		var array = 'title=' + $("input[name='dlg_search_title']").val() +
			'&description=' + $("input[name='dlg_search_description']").val();
		window.open('<?php echo Yii::app()->createUrl('blog/post/downpdf') ?>?' + array);
	}
	$(document).ready(function () {
		$('#description').summernote({
			height: 300,
		});
	});
</script>
<div class="card card-purple">
	<div class="card-header with-border">
    <h3 class="card-title"><?php echo getCatalog('post') ?></h3>
    <div class="card-tools">
    <?php if (CheckAccess('post', 'iswrite')) { ?>
			<button name="CreateButton" type="button" class="btn btn-primary" onclick="newdata()"><?php echo getCatalog('new') ?></button>
		<?php } ?>
		<button name="SearchButton" type="button" class="btn btn-success" data-toggle="modal" data-target="#SearchDialog"><?php echo getCatalog('search') ?></button>
		<?php if (CheckAccess('post', 'isdownload')) { ?>
			<div class="btn-group">
        <button type="button" class="btn btn-info"><?php echo getCatalog('download') ?></button>
        <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="true">
					<span class="caret"></span></button>
				<div class="dropdown-menu" role="menu">
					<a class="dropdown-item" href="#" onclick="downpdf()">PDF</a>
    </div>
    </button>
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
	</div>
</div>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/TE8JIEMwSkY')); ?>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'title'),
  array('searchtype'=>'text','searchname'=>'description'),
))); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('category') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="postid">
				<div class="form-group">
					<label for="title"><?php echo getCatalog('title') ?></label>
					<input type="text" class="form-control" name="title">
				</div>
				<div class="form-group">
					<label for="description"><?php echo getCatalog('content') ?></label>
					<div id="description"></div>
				</div>
				<div class="form-group">
					<label for="metatag"><?php echo getCatalog('metatag') ?></label>
					<input type="text" class="form-control" name="metatag">
				</div>
				<div class="form-group">
					<label for="slug"><?php echo getCatalog('slug') ?></label>
					<input type="text" class="form-control" name="slug">
        </div>
        <div class="row">
					<div class="col-md-4">
						<label for="postpic"><?php echo getCatalog('postpic') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" readonly class="form-control" name="postpic">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-8">
						<script>
							function successUp(param, param2, param3) {
								$('input[name="postpic"]').val(param2);
							}
						</script>
						<?php
						$events = array(
							'success' => 'successUp(param,param2,param3)',
						);
						$this->widget('ext.dropzone.EDropzone',
							array(
							'name' => 'upload',
							'url' => Yii::app()->createUrl('blog/post/upload'),
							'mimeTypes' => array('.jpg', '.png', '.jpeg'),
							'events' => $events,
							'options' => CMap::mergeArray($this->options, $this->dict),
							'htmlOptions' => array('style' => 'height:95%; overflow: hidden;'),
						));
						?>
          </div>
          </div>
				<div class="form-group" style="height:150px;overflow:auto;width:auto">
					<label for="category"><?php echo getCatalog('category') ?></label>
					<div class="table-responsive">
						<table class="table">
							<?php
							foreach (getAllCategory() as $category) {
								echo '<div class="checkbox">';
								echo '<label><input name="category" id="'.$category['categoryid'].'" type="checkbox">'.$category['title'].'</label>';
								echo '</div>';
							}
							?>
						</table>
					</div>
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