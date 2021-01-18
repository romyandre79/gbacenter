<script src="<?php echo Yii::app()->baseUrl; ?>/js/gbacenter/bacaan.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('bacaan') ?></h3>
			</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'bacaan')); ?><br>
		<?php
		$this->widget('zii.widgets.grid.CGridView',
			array(
			'dataProvider' => $dataProvider,
			'id' => 'GridList',
			'selectableRows' => 2,
			'ajaxUpdate' => true,
			'filter' => null,
			'enableSorting' => true,
			'rowCssClassExpression' => '(($data["jumsub"]==0)?"warning":"primary")',
			'columns' => array(
				array(
					'class' => 'CCheckBoxColumn',
					'id' => 'ids',
				),
				array
					(
					'class' => 'CButtonColumn',
					'template' => '{select} {edit} {purge} {pdf}',
					'htmlOptions' => array('style' => 'width:120px'),
					'buttons' => array
						(
						'select' => array
							(
							'label' => getCatalog('detail'),
              'imageUrl' => Yii::app()->baseUrl.'/images/detail.png',
							'url' => '"#"',
							'click' => "function() {
								getdetail($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'edit' => array
							(
							'label' => getCatalog('edit'),
							'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
							'visible' => booltostr(CheckAccess('bacaan', 'iswrite')),
							'url' => '"#"',
							'click' => "function() {
								updatedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('bacaan', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
								purgedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('bacaan',
									'isdownload')),
							'url' => '"#"',
							'click' => "function() {
								downpdf($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
					),
				),
				array(
					'header' => 'ID',
					'name' => 'bukubacaanid',
					'value' => '$data["bukubacaanid"]'
				),
				array(
					'header' => 'Kode Buku',
					'name' => 'kodebuku',
					'value' => '$data["kodebuku"]'
				),
				array(
					'header' => 'Nama Buku',
					'name' => 'namabuku',
					'value' => '$data["namabuku"]'
				),
				array(
					'header' => 'Jumlah',
					'name' => 'jumlah',
					'value' => '$data["jumlah"]'
				),
				array(
					'header' => 'Total',
					'name' => 'total',
					'value' => '$data["total"]'
				),
				array(
					'header' => 'Notes',
					'name' => 'notes',
					'value' => '$data["notes"]'
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
     <?php $this->widget('Button',	array('menuname'=>'bacaan')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'kodebuku'),
  array('searchtype'=>'text','searchname'=>'namabuku')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/e2pfyuqR0RU')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Buku Bacaan</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="bukubacaanid">
        <div class="row">
					<div class="col-md-4">
						<label for="kodebuku">Kode Buku</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="kodebuku">
					</div>
				</div><br>
        <div class="row">
					<div class="col-md-4">
						<label for="namabuku">Nama Buku</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="namabuku">
					</div>
				</div><br>
		<div class="row">
					<div class="col-md-4">
						<label for="jumlah">Jumlah Pasal</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="jumlah">
					</div>
				</div><br>
		<div class="row">
					<div class="col-md-4">
						<label for="total">Total Hari Bacaan</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="total">
					</div>
				</div><br>					
		<div class="row">
					<div class="col-md-4">
						<label for="notes">Notes</label>
					</div>
					<div class="col-md-8">
					<textarea type="text" class="form-control" rows="5" name="notes"></textarea>
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
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#bacaan">Menu Harian</a></li>
				</ul><br>
				<div class="tab-content">
					<div id="bacaan" class="tab-pane">
						<?php if (CheckAccess('bacaan', 'iswrite')) { ?>
							<!--<button name="CreateButtongroupmenu" type="button" class="btn btn-primary" onclick="newdatapeserta()"><?php echo getCatalog('new') ?></button>-->
						<?php } ?>
						<?php if (CheckAccess('bacaan', 'ispurge')) { ?>
							<button name="PurgeButtongroupmenu" type="button" class="btn btn-danger" onclick="purgedatagroupmenu()"><?php echo getCatalog('purge') ?></button>
						<?php } ?>
            <script>
							function successUp(param, param2, param3) {
								toastr.info(param2);
								$.fn.yiiGridView.update("groupmenuList");
							}
						</script>
						<?php
							$this->widget('ext.dropzone.EDropzone',
								array(
								'name' => 'upload',
								'url' => Yii::app()->createUrl('gbacenter/bacaan/uploaddetail'),
								'mimeTypes' => array('.xlsx'),
								'options' => CMap::mergeArray($this->options, $this->dict),
								'events' => array(
                  'success' => 'successUp(param,param2,param3)',
                  'sending' => "param3.append('grupbacaid',$('input[name=bukubacaanid]').val())"
								),
								'htmlOptions' => array('style' => 'height:95%; overflow: hidden;'),
							));
						?>
						<?php
						$this->widget('zii.widgets.grid.CGridView',
							array(
							'dataProvider' => $dataProvidergroupmenu,
							'id' => 'groupmenuList',
							'selectableRows' => 2,
							'ajaxUpdate' => true,
							'filter' => null,
							'enableSorting' => true,
							'columns' => array(
								array(
									'class' => 'CCheckBoxColumn',
									'id' => 'ids',
								),
								array (
									'class' => 'CButtonColumn',
									'template' => '{edit} {purge}',
									'htmlOptions' => array('style' => 'width:160px'),
									'buttons' => array (
										'edit' => array (
											'label' => getCatalog('edit'),
											'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
											'visible' => booltostr(CheckAccess('bacaan',
													'iswrite')),
											'url' => '"#"',
											'click' => "function() { 
                        updatedatagroupmenu($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
										'purge' => array (
											'label' => getCatalog('purge'),
											'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
											'visible' => booltostr(CheckAccess('bacaan',
													'ispurge')),
											'url' => '"#"',
											'click' => "function() { 
                        purgedatagroupmenu($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
									),
								),
								array(
									'header' => getCatalog('bukubacaandetailid'),
									'name' => 'bukubacaandetailid',
									'value' => '$data["bukubacaandetailid"]'
								),
								array(
									'header' => 'Hari',
									'name' => 'hari',
									'value' => '$data["hari"]'
								),
								array(
									'header' => 'Menu Harian',
									'name' => 'menuharian',
									'value' => '$data["menuharian"]'
								),
								array(
									'header' => 'Url',
									'name' => 'url',
									'value' => '$data["url"]'
								),
							)
						));
						?>
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
<div id="ShowDetailDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
			<div class="modal-body">
				<div class="card card-primary">
					<div class="card-header with-border">
						<h3 class="card-title"><?php echo getCatalog('bacaan') ?></h3>
						<div class="card-tools pull-right">
							<button class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div><!-- /.card-header -->		
					<div class="card-body">
						<?php
						$this->widget('zii.widgets.grid.CGridView',
							array(
							'dataProvider' => $dataProvidergroupmenu,
							'id' => 'DetailgroupmenuList',
							'ajaxUpdate' => true,
							'filter' => null,
							'enableSorting' => true,
							'columns' => array(
								array(
									'header' => getCatalog('bukubacaandetailid'),
									'name' => 'bukubacaandetailid',
									'value' => '$data["bukubacaandetailid"]'
								),
								array(
									'header' => getCatalog('hari'),
									'name' => 'hari',
									'value' => '$data["hari"]'
								),
								array(
									'header' => getCatalog('menuharian'),
									'name' => 'menuharian',
									'value' => '$data["menuharian"]'
								),
								array(
									'header' => getCatalog('url'),
									'name' => 'url',
									'value' => '$data["url"]'
								),
							)
						));
						?>
					</div>		
				</div>		
					
			</div>
		</div>
	</div>
</div>
<div id="InputDialoggroupmenu" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Menu Harian</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
			<div class="modal-body">
				<input type="hidden" class="form-control" name="bukubacaandetailid">
			
				<div class="row">
					<div class="col-md-4">
						<label for="hari">Hari</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="hari">
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-4">
						<label for="menuharian">Menu Harian</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="menuharian">
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-4">
						<label for="url">Url</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="url">
					</div>
				</div><br>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatamenuharian()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">