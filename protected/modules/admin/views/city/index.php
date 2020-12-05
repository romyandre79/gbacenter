<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/city.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('city') ?></h3>
	</div>
	<div class="card-body">
     <?php $this->widget('Button',	array('menuname'=>'city')); ?>
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
				array	(
					'class' => 'CButtonColumn',
					'template' => '{edit} {delete} {purge} {pdf}',
					'htmlOptions' => array('style' => 'width:160px'),
					'buttons' => array (
						'edit' => array (
							'label' => getCatalog('edit'),
							'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
							'visible' => booltostr(CheckAccess('city', 'iswrite')),
							'url' => '"#"',
							'click' => "function() { 
							updatedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'delete' => array (
							'label' => getCatalog('delete'),
							'imageUrl' => Yii::app()->baseUrl.'/images/active.png',
							'visible' => booltostr(CheckAccess('city', 'isreject')),
							'url' => '"#"',
							'click' => "function() { 
                deletedata($(this).parent().parent().children(':nth-child(3)').text());
              }",
						),
						'purge' => array (
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('city', 'ispurge')),
							'url' => '"#"',
							'click' => "function() { 
                purgedata($(this).parent().parent().children(':nth-child(3)').text());
              }",
						),
						'pdf' => array (
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('city', 'isdownload')),
							'url' => '"#"',
							'click' => "function() { 
                downpdf($(this).parent().parent().children(':nth-child(3)').text());
              }",
						),
					),
				),
				array(
					'header' => getCatalog('cityid'),
					'name' => 'cityid',
					'value' => '$data["cityid"]'
				),
				array(
					'header' => getCatalog('provincename'),
					'name' => 'provinceid',
					'value' => '$data["provincename"]'
				),
				array(
					'header' => getCatalog('citycode'),
					'name' => 'citycode',
					'value' => '$data["citycode"]'
				),
				array(
					'header' => getCatalog('cityname'),
					'name' => 'cityname',
					'value' => '$data["cityname"]'
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
     <?php $this->widget('Button',	array('menuname'=>'city')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'provincename'),
  array('searchtype'=>'text','searchname'=>'citycode'),
  array('searchtype'=>'text','searchname'=>'cityname'),
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/7sxflX-zJcw')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
         <h4 class="modal-title"><?php echo getCatalog('city') ?></h4>
       <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="cityid">
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'provinceid', 'ColField' => 'provincename',
					'IDDialog' => 'provinceid_dialog', 'titledialog' => getCatalog('provincename'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.ProvincePopUp', 'PopGrid' => 'provinceidgrid'));
				?>
        <div class="row">
					<div class="col-md-4">
						<label for="citycode"><?php echo getCatalog('citycode') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="citycode">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="cityname"><?php echo getCatalog('cityname') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="cityname">
					</div>
				</div>
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