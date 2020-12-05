<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/currency.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('currency') ?></h3>
	</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'currency')); ?>
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
					'template' => '{edit} {delete} {purge} {pdf}',
					'htmlOptions' => array('style' => 'width:160px'),
					'buttons' => array
						(
						'edit' => array
							(
							'label' => getCatalog('edit'),
							'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
							'visible' => booltostr(CheckAccess('currency', 'iswrite')),
							'url' => '"#"',
							'click' => "function() { 
							updatedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'delete' => array
							(
							'label' => getCatalog('delete'),
							'imageUrl' => Yii::app()->baseUrl.'/images/active.png',
							'visible' => booltostr(CheckAccess('currency', 'isreject')),
							'url' => '"#"',
							'click' => "function() { 
							deletedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('currency', 'ispurge')),
							'url' => '"#"',
							'click' => "function() { 
							purgedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('currency', 'isdownload')),
							'url' => '"#"',
							'click' => "function() { 
							downpdf($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
					),
				),
				array(
					'header' => getCatalog('currencyid'),
					'name' => 'currencyid',
					'value' => '$data["currencyid"]'
				),
				array(
					'header' => getCatalog('countryname'),
					'name' => 'countryid',
					'value' => '$data["countryname"]'
				),
				array(
					'header' => getCatalog('currencyname'),
					'name' => 'currencyname',
					'value' => '$data["currencyname"]'
				),
				array(
					'header' => getCatalog('symbol'),
					'name' => 'symbol',
					'value' => '$data["symbol"]'
				),
				array(
					'header' => getCatalog('i18n'),
					'name' => 'i18n',
					'value' => '$data["i18n"]'
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
    <?php $this->widget('Button',	array('menuname'=>'currency')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'countryname'),
  array('searchtype'=>'text','searchname'=>'currencyname'),
  array('searchtype'=>'text','searchname'=>'symbol'),
  array('searchtype'=>'text','searchname'=>'i18n')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/HG7_qM33B80')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo getCatalog('currency') ?></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="currencyid">
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'countryid', 'ColField' => 'countryname',
					'IDDialog' => 'countryid_dialog', 'titledialog' => getCatalog('countryname'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.CountryPopUp', 'PopGrid' => 'countryidgrid'));
				?>
				<div class="row">
					<div class="col-md-4">
						<label for="currencyname"><?php echo getCatalog('currencyname') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="currencyname">
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="symbol"><?php echo getCatalog('symbol') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="symbol">
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="i18n"><?php echo getCatalog('i18n') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="i18n">
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