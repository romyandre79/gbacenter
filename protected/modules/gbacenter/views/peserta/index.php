<script src="<?php echo Yii::app()->baseUrl; ?>/js/gbacenter/peserta.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('peserta') ?></h3>
	</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'peserta','uploadurl'=>'gbacenter/peserta/uploaddata')); ?>
	<br>
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
							'visible' => booltostr(CheckAccess('peserta', 'iswrite')),
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
							'visible' => booltostr(CheckAccess('peserta', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
							purgedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('peserta',
									'isdownload')),
							'url' => '"#"',
							'click' => "function() {
							downpdf($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						
					),
				),
				array(
					'header' => getCatalog('pesertaid'),
					'name' => 'pesertaid',
					'value' => '$data["pesertaid"]'
				),
				array(
					'header' => getCatalog('idtelegram'),
					'name' => 'idtelegram',
					'value' => '$data["idtelegram"]'
				),
				array(
					'header' => getCatalog('nohp'),
					'name' => 'nohp',
					'value' => '$data["nohp"]'
				),
				array(
					'header' => getCatalog('nama'),
					'name' => 'nama',
					'value' => '$data["nama"]'
				),
				array(
					'header' => getCatalog('aliasid'),
					'name' => 'aliasid',
					'value' => '$data["aliasid"]'
				),
				array(
					'header' => getCatalog('alamat'),
					'name' => 'alamat',
					'value' => '$data["alamat"]'
				),
				array(
					'header' => getCatalog('kota'),
					'name' => 'namakota',
					'value' => '$data["namakota"]'
				),
				array(
					'header' => getCatalog('provinsi'),
					'name' => 'namaprovinsi',
					'value' => '$data["namaprovinsi"]'
				),
				array(
					'header' => getCatalog('negara'),
					'name' => 'namanegara',
					'value' => '$data["namanegara"]'
				),
				array(
					'header' => getCatalog('asalgereja'),
					'name' => 'asalgereja',
					'value' => '$data["asalgereja"]'
				),
				array(
					'header' => getCatalog('jabatangereja'),
					'name' => 'jabatangereja',
					'value' => '$data["jabatangereja"]'
				),
				array(
					'header' => getCatalog('tgllahir'),
					'name' => 'tgllahir',
					'value' => '$data["tgllahir"]'
				),
				array(
					'header' => getCatalog('sexid'),
					'name' => 'sexid',
					'value' => '$data["sexid"]'
				),
				array(
					'header' => getCatalog('statusbaca'),
					'name' => 'statusbaca',
					'value' => '$data["statusbaca"]'
				),
				array(
					'header' => getCatalog('foto'),
					'name' => 'foto',
					'value' => '$data["foto"]'
				),
				array(
					'header' => getCatalog('kontak'),
					'name' => 'kontak',
					'value' => '$data["kontak"]'
				),
				array(
					'header' => getCatalog('note'),
					'name' => 'note',
					'value' => '$data["note"]'
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
		<br>
    <?php $this->widget('Button',	array('menuname'=>'peserta')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'nama'),
  array('searchtype'=>'text','searchname'=>'aliasid'),
  array('searchtype'=>'text','searchname'=>'nohp')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/BelvIaMxKag')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('peserta') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="pesertaid">
		
        <div class="row">
					<div class="col-md-4">
						<label for="idtelegram"><?php echo getCatalog('idtelegram'); ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="idtelegram">
					</div>
				</div><br>
        <div class="row">
					<div class="col-md-4">
						<label for="nohp"><?php echo getCatalog('nohp'); ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="nohp">
					</div>
		</div> <br>
		<div class="row">
					<div class="col-md-4">
						<label for="nama"><?php echo getCatalog('nama'); ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="nama">
					</div>
		</div><br>		
		<div class="row">
                <div class="col-md-4">
                    <label for="aliasid"><?php echo getCatalog('aliasid'); ?></label>
                </div>
                <div class="col-md-8">
				<input type="text" class="form-control" name="aliasid">
                </div>
        </div><br> 
		<div class="row">
                <div class="col-md-4">
                    <label for="alamat"><?php echo getCatalog('alamat'); ?></label>
                </div>
                <div class="col-md-8">
				<input type="text" class="form-control" name="alamat">
                </div>
        </div><br> 
		<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'kota', 'ColField' => 'namakota',
					'IDDialog' => 'kota_dialog', 'titledialog' => 'Kota',
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.CityPopUp', 'PopGrid' => 'kota'));
		?><br> 
		<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'provinsi', 'ColField' => 'namaprovinsi',
					'IDDialog' => 'provinsi_dialog', 'titledialog' => 'Provinsi',
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.ProvincePopUp', 'PopGrid' => 'provinsi'));
		?><br> 
		<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'negara', 'ColField' => 'namanegara',
					'IDDialog' => 'negara_dialog', 'titledialog' => 'Negara',
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.CountryPopUp', 'PopGrid' => 'negara'));
		?><br> 
		<div class="row">
                <div class="col-md-4">
                    <label for="asalgereja"><?php echo getCatalog('asalgereja'); ?></label>
                </div>
                <div class="col-md-8">
				<input type="text" class="form-control" name="asalgereja">
                </div>
        </div><br> 
		<div class="row">
                <div class="col-md-4">
                    <label for="jabatangereja"><?php echo getCatalog('jabatangereja'); ?></label>
                </div>
                <div class="col-md-8">
				<input type="text" class="form-control" name="jabatangereja">
                </div>
        </div><br> 
		<div class="row">
                <div class="col-md-4">
                    <label for="tgllahir"><?php echo getCatalog('tgllahir'); ?></label>
                </div>
                <div class="col-md-8">
				<input type="date" name="tgllahir" class="form-control">
                </div>
        </div><br> 
		<div class="row">
                <div class="col-md-4">
                    <label for="sexid"><?php echo getCatalog('sexid'); ?></label>
                </div>
                <div class="col-md-8">
					<input type="radio" id="sexidl" name="sexid" value="L"> Laki
					&nbsp;&nbsp;&nbsp;<input type="radio" id="sexidp" name="sexid" value="P"> Perempuan
                </div>
        </div><br> 
		<div class="row">
                <div class="col-md-4">
                    <label for="statusbaca"><?php echo getCatalog('statusbaca') ?></label>
                </div>
                <div class="col-md-8">
                    <input type="checkbox" name="statusbaca">
                </div>
        </div><br> 
		<div class="row">
					<div class="col-md-4">
						<label for="foto"><?php echo getCatalog('foto'); ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="foto">
					</div>
        </div>
		<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-8">
						<script>
							function successUpLeft(param, param2, param3) {
								$('input[name="foto"]').val(param2);
							}
						</script>
						<?php
						$events = array(
							'success' => 'successUpLeft(param,param2,param3)',
						);
						$this->widget('ext.dropzone.EDropzone',
							array(
							'name' => 'upload',
							'url' => Yii::app()->createUrl('gbacenter/peserta/upload'),
							'mimeTypes' => array('.jpg', '.png', '.jpeg'),
							'events' => $events,
							'options' => CMap::mergeArray($this->options, $this->dict),
							'htmlOptions' => array('style' => 'height:95%; overflow: hidden;'),
						));
						?>
          </div>
		</div><br>   
		<div class="row">
					<div class="col-md-4">
						<label for="kontak"><?php echo getCatalog('kontak'); ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="kontak">
					</div>
        </div><br> 
		<div class="row">
					<div class="col-md-4">
						<label for="note"><?php echo getCatalog('note'); ?></label>
					</div>
					<div class="col-md-8">
						<textarea type="text" class="form-control" rows="5" name="note"></textarea>
					</div>
        </div><br> 
        <div class="row">
                <div class="col-md-4">
                    <label for="recordstatus"><?php echo getCatalog('recordstatus') ?></label>
                </div>
                <div class="col-md-8">
                    <input type="checkbox" name="recordstatus">
                </div>
        </div><br>       
      </div>
    
        
      <div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedata()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">