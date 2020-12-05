<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/company.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('company') ?></h3>
	</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'company')); ?>
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
							'visible' => booltostr(CheckAccess('company', 'iswrite')),
							'url' => '"#"',
							'click' => "function() { 
							updatedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'delete' => array
							(
							'label' => getCatalog('delete'),
							'imageUrl' => Yii::app()->baseUrl.'/images/active.png',
							'visible' => booltostr(CheckAccess('company', 'isreject')),
							'url' => '"#"',
							'click' => "function() { 
							deletedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('company', 'ispurge')),
							'url' => '"#"',
							'click' => "function() { 
							purgedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('company', 'isdownload')),
							'url' => '"#"',
							'click' => "function() { 
							downpdf($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
					),
				),
				array(
					'header' => getCatalog('companyid'),
					'name' => 'companyid',
					'value' => '$data["companyid"]'
				),
				array(
					'header' => getCatalog('companyname'),
					'name' => 'companyname',
					'value' => '$data["companyname"]'
				),
				array(
					'header' => getCatalog('companycode'),
					'name' => 'companycode',
					'value' => '$data["companycode"]'
				),
				array(
					'header' => getCatalog('address'),
					'name' => 'address',
					'value' => '$data["address"]'
				),
				array(
					'header' => getCatalog('cityname'),
					'name' => 'cityid',
					'value' => '$data["cityname"]'
				),
				array(
					'header' => getCatalog('zipcode'),
					'name' => 'zipcode',
					'value' => '$data["zipcode"]'
				),
				array(
					'class' => 'CCheckBoxColumn',
					'name' => 'isholding',
					'header' => getCatalog('isholding'),
					'selectableRows' => '0',
					'checked' => '$data["isholding"]',
				), array(
					'class' => 'CCheckBoxColumn',
					'name' => 'recordstatus',
					'header' => getCatalog('recordstatus'),
					'selectableRows' => '0',
					'checked' => '$data["recordstatus"]',
				),
			)
		));
		?>
    <?php $this->widget('Button',	array('menuname'=>'company')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'companyname'),
  array('searchtype'=>'text','searchname'=>'companycode'),
  array('searchtype'=>'text','searchname'=>'cityname'),
  array('searchtype'=>'text','searchname'=>'zipcode'),
  array('searchtype'=>'text','searchname'=>'taxno'),
  array('searchtype'=>'text','searchname'=>'currencyname'),
  array('searchtype'=>'text','searchname'=>'faxno'),
  array('searchtype'=>'text','searchname'=>'phoneno'),
  array('searchtype'=>'text','searchname'=>'webaddress'),
  array('searchtype'=>'text','searchname'=>'email')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/htBesRYHuSw')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('company') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="companyid">
        <div class="row">
					<div class="col-md-4">
						<label for="companyname"><?php echo getCatalog('companyname') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="companyname">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="companycode"><?php echo getCatalog('companycode') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="companycode">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="address"><?php echo getCatalog('address') ?></label>
					</div>
					<div class="col-md-8">
						<textarea type="text" class="form-control" rows="5" name="address"></textarea>
					</div>
				</div>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'cityid', 'ColField' => 'cityname',
					'IDDialog' => 'cityid_dialog', 'titledialog' => getCatalog('cityname'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.CityPopUp', 'PopGrid' => 'cityidgrid'));
				?>
        <div class="row">
					<div class="col-md-4">
						<label for="zipcode"><?php echo getCatalog('zipcode') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="zipcode">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="taxno"><?php echo getCatalog('taxno') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="taxno">
					</div>
				</div>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'currencyid', 'ColField' => 'currencyname',
					'IDDialog' => 'currencyid_dialog', 'titledialog' => getCatalog('currencyname'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.CurrencyPopUp', 'PopGrid' => 'currencyidgrid'));
				?>
        <div class="row">
					<div class="col-md-4">
						<label for="faxno"><?php echo getCatalog('faxno') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="faxno">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="phoneno"><?php echo getCatalog('phoneno') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="phoneno">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="webaddress"><?php echo getCatalog('webaddress') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="webaddress">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="email"><?php echo getCatalog('email') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="email">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="leftlogofile"><?php echo getCatalog('leftlogofile') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="leftlogofile">
					</div>
        </div>
        <div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-8">
						<script>
							function successUpLeft(param, param2, param3) {
								$('input[name="leftlogofile"]').val(param2);
							}
						</script>
						<?php
						$events = array(
							'success' => 'successUpLeft(param,param2,param3)',
						);
						$this->widget('ext.dropzone.EDropzone',
							array(
							'name' => 'upload',
							'url' => Yii::app()->createUrl('admin/company/upload'),
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
						<label for="rightlogofile"><?php echo getCatalog('rightlogofile') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="rightlogofile">
					</div>
        </div>
        <div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-8">
						<script>
							function successUpRight(param, param2, param3) {
								$('input[name="rightlogofile"]').val(param2);
							}
						</script>
						<?php
						$events = array(
							'success' => 'successUpRight(param,param2,param3)',
						);
						$this->widget('ext.dropzone.EDropzone',
							array(
							'name' => 'upload',
							'url' => Yii::app()->createUrl('admin/company/upload'),
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
						<label for="isholding"><?php echo getCatalog('isholding') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="isholding">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="billto"><?php echo getCatalog('billto') ?></label>
					</div>
					<div class="col-md-8">
						<textarea type="text" class="form-control" rows="5" name="billto"></textarea>
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="lat"><?php echo getCatalog('lat') ?></label>
					</div>
					<div class="col-md-8">
						<input type="number" class="form-control" name="lat">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="lng"><?php echo getCatalog('lng') ?></label>
					</div>
					<div class="col-md-8">
						<input type="number" class="form-control" name="lng">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="filelayout"><?php echo getCatalog('filelayout') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="filelayout">
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