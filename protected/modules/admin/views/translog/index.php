<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/translog.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('translog') ?></h3>
	</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'translog','iswrite'=>false,'isreject'=>false)); ?>
		<?php
		$this->widget('zii.widgets.grid.CGridView',
			array(
			'dataProvider' => $dataProvider,
			'id' => 'GridList',
			'selectableRows' => 2,
			'ajaxUpdate' => true,
			'filter' => null,
			'enableSorting' => true,
			'columns' => array(
				array(
					'class' => 'CCheckBoxColumn',
					'id' => 'ids',
					'htmlOptions' => array('style' => 'width:10px'),
				),
				array
					(
					'class' => 'CButtonColumn',
					'template' => '{purge} {pdf}',
					'htmlOptions' => array('style' => 'width:160px'),
					'buttons' => array
						(
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('translog', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
							purgedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('translog', 'isdownload')),
							'url' => '"#"',
							'click' => "function() {
							downpdf($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
					),
				),
				array(
					'header' => getCatalog('translogid'),
					'name' => 'translogid',
					'value' => '$data["translogid"]'
				),
				array(
					'header' => getCatalog('username'),
					'name' => 'username',
					'value' => '$data["username"]'
				),
				array(
					'header' => getCatalog('createddate'),
					'name' => 'createddate',
					'value' => 'Yii::app()->format->formatDateTime($data["createddate"])'
				),
				array(
					'header' => getCatalog('useraction'),
					'name' => 'useraction',
					'value' => '$data["useraction"]'
				),
				array(
					'header' => getCatalog('newdata'),
					'name' => 'newdata',
					'value' => '$data["newdata"]'
				),
				array(
					'header' => getCatalog('menuname'),
					'name' => 'menuname',
					'value' => '$data["menuname"]'
				),
				array(
					'header' => getCatalog('tableid'),
					'name' => 'tableid',
					'value' => '$data["tableid"]'
				),
			)
		));
		?>
    <?php $this->widget('Button',	array('menuname'=>'translog','iswrite'=>false, 'isreject'=>false)); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'username'),
  array('searchtype'=>'text','searchname'=>'useraction'),
  array('searchtype'=>'text','searchname'=>'menuname')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/yDg-qwmPAU0')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo getCatalog('translog') ?></h4>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="translogid">
        <div class="row">
					<div class="col-md-4">
						<label for="username"><?php echo getCatalog('username') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="username">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="useraction"><?php echo getCatalog('useraction') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="useraction">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="newdata"><?php echo getCatalog('newdata') ?></label>
					</div>
					<div class="col-md-8">
						<textarea type="text" class="form-control" rows="5" name="newdata"></textarea>
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="menuname"><?php echo getCatalog('menuname') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="menuname">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="tableid"><?php echo getCatalog('tableid') ?></label>
					</div>
					<div class="col-md-8">
						<input type="number" class="form-control" name="tableid">
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