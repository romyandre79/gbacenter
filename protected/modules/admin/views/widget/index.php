<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin/widget.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
    <h3 class="card-title"><?php echo getCatalog('widget') ?></h3>
	</div>
	<div class="card-body">
  <?php $this->widget('Button',	array('menuname'=>'widget')); ?>
		<?php
		$this->widget('zii.widgets.grid.CGridView',
			array(
			'dataProvider' => $dataProvider,
			'id' => 'GridList',
			'selectableRows' => 2,
			'ajaxUpdate' => true,
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
					'template' => '{edit} {delete} {purge} {pdf}',
					'htmlOptions' => array('style' => 'width:auto'),
					'buttons' => array
						(
						'edit' => array
							(
							'label' => getCatalog('edit'),
							'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
							'visible' => booltostr(CheckAccess('widget', 'iswrite')),
							'url' => '"#"',
							'click' => "function() {
							updatedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'delete' => array
							(
							'label' => getCatalog('delete'),
							'imageUrl' => Yii::app()->baseUrl.'/images/active.png',
							'visible' => booltostr(CheckAccess('widget', 'isreject')),
							'url' => '"#"',
							'click' => "function() {
							deletedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('widget', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
							purgedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('widget', 'isdownload')),
							'url' => '"#"',
							'click' => "function() {
							downpdf($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
					),
				),
				array(
					'header' => getCatalog('widgetid'),
					'name' => 'widgetid',
					'value' => '$data["widgetid"]'
				),
				array(
					'header' => getCatalog('widgetname'),
					'name' => 'widgetname',
					'value' => '$data["widgetname"]'
				),
				array(
					'header' => getCatalog('widgettitle'),
					'name' => 'widgettitle',
					'value' => '$data["widgettitle"]'
				),
				array(
					'header' => getCatalog('widgetversion'),
					'name' => 'widgetversion',
					'value' => '$data["widgetversion"]'
				),
				array(
					'header' => getCatalog('widgetby'),
					'name' => 'widgetby',
					'value' => '$data["widgetby"]'
				),
				array(
					'header' => getCatalog('description'),
					'name' => 'description',
					'value' => '$data["description"]'
				),
				array(
					'header' => getCatalog('widgeturl'),
					'name' => 'widgeturl',
					'value' => '$data["widgeturl"]'
				),
				array(
					'header' => getCatalog('modulename'),
					'name' => 'moduleid',
					'value' => '$data["modulename"]'
				),
				array(
					'header' => getCatalog('installdate'),
					'name' => 'installdate',
					'value' => 'Yii::app()->format->formatDateTime($data["installdate"])'
				),
				array(
					'class' => 'CCheckBoxColumn',
					'name' => 'recordstatus',
					'header' => getCatalog('recordstatus'),
					'selectableRows' => '0',
					'checked' => '$data["recordstatus"]',
				),
			)
		));
		?>
    <?php $this->widget('Button',	array('menuname'=>'widget')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'widgetname'),
  array('searchtype'=>'text','searchname'=>'widgettitle'),
  array('searchtype'=>'text','searchname'=>'widgetversion'),
  array('searchtype'=>'text','searchname'=>'widgetby'),
  array('searchtype'=>'text','searchname'=>'widgeturl'),
  array('searchtype'=>'text','searchname'=>'modulename'),
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/duyCHXpQEQ0')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo getCatalog('widget') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="widgetid">
        <div class="row">
					<div class="col-md-4">
						<label for="widgetname"><?php echo getCatalog('widgetname') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="widgetname">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="widgettitle"><?php echo getCatalog('widgettitle') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="widgettitle">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="widgetversion"><?php echo getCatalog('widgetversion') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="widgetversion">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="widgetby"><?php echo getCatalog('widgetby') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="widgetby">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="description"><?php echo getCatalog('description') ?></label>
					</div>
					<div class="col-md-8">
						<textarea type="text" class="form-control" rows="5" name="description"></textarea>
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="widgeturl"><?php echo getCatalog('widgeturl') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="widgeturl">
					</div>
				</div>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'moduleid', 'ColField' => 'modulename',
					'IDDialog' => 'moduleid_dialog', 'titledialog' => getCatalog('modulename'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.ModulesPopUp', 'PopGrid' => 'moduleidgrid'));
				?>
        <div class="row">
					<div class="col-md-4">
						<label for="recordstatus"><?php echo getCatalog('recordstatus') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="recordstatus">
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