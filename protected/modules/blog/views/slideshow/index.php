<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	function newdata() {
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/slideshow/create') ?>', 'data': {},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status == "success") {
					$("input[name='slideshowid']").val(data.slideshowid);
					$("input[name='slidepic']").val('');
					$("input[name='slidetitle']").val('');
					$("textarea[name='slidedesc']").val('');
          $("input[name='slideurl']").val('');
          for (x in data.allcategory) {
            $('#' + data.allcategory[x].categoryid).prop('checked', false);
          }
					$('#InputDialog').modal();
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
		return false;
	}

	function updatedata() {
    jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/slideshow/update') ?>', 
      'data': {'id': $.fn.yiiGridView.getSelection("GridList")},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status == "success") {
					$("input[name='slideshowid']").val(data.slideshowid);
					$("input[name='slidepic']").val(data.slidepic);
					$("input[name='slidetitle']").val(data.slidetitle);
					$("textarea[name='slidedesc']").val(data.slidedesc);
          $("input[name='slideurl']").val(data.slideurl);
          for (x in data.allcategory) {
            $('#' + data.allcategory[x].categoryid).prop('checked', false);
          }
          for (x in data.category) {
            $('#' + data.category[x].categoryid).prop('checked', true);
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
    var array = $("input[name='category']:checked").map(function () {
			return this.id;
		}).get();
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/slideshow/save') ?>',
			'data': {
				'slideshowid': $("input[name='slideshowid']").val(),
				'slidepic': $("input[name='slidepic']").val(),
				'slidetitle': $("input[name='slidetitle']").val(),
				'slidedesc': $("textarea[name='slidedesc']").val(),
        'slideurl': $("input[name='slideurl']").val(),
        'category': array
			},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status == "success") {
					$('#InputDialog').modal('hide');
					toastr.info(data.msg);
					$.fn.yiiGridView.update("GridList");
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
	}

	function approvedata() {
		if (confirm('<?php echo getCatalog('areyousure') ?>')) {
			jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/slideshow/approve') ?>',
				'data': {'id': $.fn.yiiGridView.getSelection("GridList")},
				'type': 'post', 'dataType': 'json',
				'success': function (data) {
					if (data.status == "success") {
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
	function deletedata() {
		if (confirm('<?php echo getCatalog('areyousure') ?>')) {
			jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/slideshow/delete') ?>',
				'data': {'id': $.fn.yiiGridView.getSelection("GridList")},
				'type': 'post', 'dataType': 'json',
				'success': function (data) {
					if (data.status == "success") {
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

	function purgedata() {
		if (confirm('<?php echo getCatalog('areyousure') ?>')) {
			jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('blog/slideshow/purge') ?>', 'data': {'id': $.fn.yiiGridView.getSelection("GridList")},
				'type': 'post', 'dataType': 'json',
				'success': function (data) {
					if (data.status == "success") {
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

	function searchdata() {
		$('#SearchDialog').modal('hide');
		var array = 'slidepic=' + $("input[name='search_slidepic']").val()
			+ '&slidetitle=' + $("input[name='search_slidetitle']").val();
		$.fn.yiiGridView.update("GridList", {data: array});
		return false;
	}

	function downpdf() {
		var array = 'slidepic=' + $("input[name='search_slidepic']").val()
			+ '&slidetitle=' + $("input[name='search_slidetitle']").val();
		window.open('<?php echo Yii::app()->createUrl('Blog/slideshow/downpdf') ?>?' + array);
	}

	function GetDetail($id) {
		$('#ShowDetailDialog').modal('show');
		var array = 'slideshowid=' + $id
	}
</script>
<div class="card card-purple">
	<div class="card-header with-border">
    <h3 class="card-title"><?php echo getCatalog('slideshow') ?></h3>
	</div>
	<div class="card-body">
  <?php if (CheckAccess('slideshow', 'iswrite')) { ?>
			<button name="CreateButton" type="button" class="btn btn-primary" onclick="newdata()"><?php echo getCatalog('new') ?></button>
		<?php } ?>
		<?php if (CheckAccess('slideshow', 'iswrite')) { ?>
			<button name="UpdateButton" type="button" class="btn btn-warning" onclick="updatedata()"><?php echo getCatalog('edit') ?></button>
		<?php } ?>
		<?php if (CheckAccess('slideshow', 'ispurge')) { ?>
			<button name="PurgeButton" type="button" class="btn btn-danger" onclick="purgedata()"><?php echo getCatalog('purge') ?></button>
		<?php } ?>
		<button name="SearchButton" type="button" class="btn btn-success" data-toggle="modal" data-target="#SearchDialog"><?php echo getCatalog('search') ?></button>
		<button name="HelpButton" type="button" class="btn btn-warning" data-toggle="modal" data-target="#HelpDialog"><?php echo getCatalog('help') ?></button>
  <?php
		$this->widget('zii.widgets.grid.CGridView',
			array(
			'dataProvider' => $dataProvider,
			'id' => 'GridList',
			'selectableRows' => 1,
			'ajaxUpdate' => true,
			'filter' => null,
			'enableSorting' => true,
			'columns' => array(
				array(
					'class' => 'CCheckBoxColumn',
					'id' => 'ids',
        ),
        array
					(
					'class' => 'CButtonColumn',
					'template' => '{edit} {delete} {purge} {pdf}',
					'htmlOptions' => array('style' => 'width:160px'),
					'buttons' => array
						(
						'edit' => array
							(
							'label' => getCatalog('edit'),
							'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
							'visible' => booltostr(CheckAccess('slideshow', 'iswrite')),
							'url' => '"#"',
							'click' => "function() {
							updatedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'delete' => array
							(
							'label' => getCatalog('delete'),
							'imageUrl' => Yii::app()->baseUrl.'/images/active.png',
							'visible' => 'false',
							'url' => '"#"',
							'click' => "function() {
							deletedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('slideshow', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
							purgedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('slideshow',
									'isdownload')),
							'url' => '"#"',
							'click' => "function() {
							downpdf($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
					),
				),
				array(
					'header' => getCatalog('slideshowid'),
					'name' => 'slideshowid',
					'value' => '$data["slideshowid"]'
				),
				array(
					'header' => getCatalog('slidepic'),
					'name' => 'slidepic',
					'value' => '$data["slidepic"]'
				),
				array(
					'header' => getCatalog('slidetitle'),
					'name' => 'slidetitle',
					'value' => '$data["slidetitle"]'
				),
				array(
					'header' => getCatalog('slidedesc'),
					'name' => 'slidedesc',
					'value' => '$data["slidedesc"]'
				),
				array(
					'header' => getCatalog('slideurl'),
					'name' => 'slideurl',
					'value' => '$data["slideurl"]'
				),
			)
		));
    ?>
    <?php if (CheckAccess('slideshow', 'iswrite')) { ?>
			<button name="CreateButton" type="button" class="btn btn-primary" onclick="newdata()"><?php echo getCatalog('new') ?></button>
		<?php } ?>
		<?php if (CheckAccess('slideshow', 'iswrite')) { ?>
			<button name="UpdateButton" type="button" class="btn btn-warning" onclick="updatedata()"><?php echo getCatalog('edit') ?></button>
		<?php } ?>
		<?php if (CheckAccess('slideshow', 'ispurge')) { ?>
			<button name="PurgeButton" type="button" class="btn btn-danger" onclick="purgedata()"><?php echo getCatalog('purge') ?></button>
		<?php } ?>
		<button name="SearchButton" type="button" class="btn btn-success" data-toggle="modal" data-target="#SearchDialog"><?php echo getCatalog('search') ?></button>
		<button name="HelpButton" type="button" class="btn btn-warning" data-toggle="modal" data-target="#HelpDialog"><?php echo getCatalog('help') ?></button>
    </div>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'slidepic'),
  array('searchtype'=>'text','searchname'=>'slidetitle'),
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/aFytqcJAvHw')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('slideshow') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="slideshowid">
        <div class="row">
					<div class="col-md-4">
						<label for="slidetitle"><?php echo getCatalog('slidetitle') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="slidetitle">
					</div>
				</div>

        <div class="row">
					<div class="col-md-4">
						<label for="slidedesc"><?php echo getCatalog('slidedesc') ?></label>
					</div>
					<div class="col-md-8">
						<textarea type="text" class="form-control" rows="5" name="slidedesc"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="slidepic"><?php echo getCatalog('slidepic') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" readonly class="form-control" name="slidepic">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-8">
						<script>
							function successUp(param, param2, param3) {
								$('input[name="slidepic"]').val(param2);
							}
						</script>
						<?php
						$events = array(
							'success' => 'successUp(param,param2,param3)',
						);
						$this->widget('ext.dropzone.EDropzone',
							array(
							'name' => 'upload',
							'url' => Yii::app()->createUrl('blog/slideshow/upload'),
							'mimeTypes' => array('.jpg', '.png', '.jpeg'),
							'events' => $events,
							'options' => CMap::mergeArray($this->options, $this->dict),
							'htmlOptions' => array('style' => 'height:95%; overflow: hidden;'),
						));
						?>
					</div>
        </div>
        <div class="row">
					<div class="col-md-4">
						<label for="slideurl"><?php echo getCatalog('slideurl') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="slideurl">
					</div>
        </div>	
        <div class="row">
          <div class="col-md-4">
            <label for="category"><?php echo getCatalog('category') ?></label>
          </div>
          <div class="col-md-8">
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
      </div>
      <div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedata()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">