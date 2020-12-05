<script src="<?php echo Yii::app()->baseUrl; ?>/js/gbacenter/grupbaca.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('grupbaca') ?></h3>
			</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'grupbaca')); ?>
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
					'name' => 'grupbacaid',
					'value' => '$data["grupbacaid"]'
				),
				array(
					'header' => 'Kode Grup',
					'name' => 'kodegrup',
					'value' => '$data["kodegrup"]'
				),
				array(
					'header' => 'Nama Grup',
					'name' => 'namagrup',
					'value' => '$data["namagrup"]'
				),
				array(
					'header' => 'Divisi',
					'name' => 'namadivisi',
					'value' => '$data["namadivisi"]'
				),
				array(
					'header' => 'Start Date',
					'name' => 'startdate',
					'value' => '$data["startdate"]'
				),
				array(
					'header' => 'Buku',
					'name' => 'namabuku',
					'value' => '$data["namabuku"]'
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
     <?php $this->widget('Button',	array('menuname'=>'grupbaca')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'kodegrup'),
  array('searchtype'=>'text','searchname'=>'namagrup')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/e2pfyuqR0RU')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Grup Baca</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="grupbacaid">
        <div class="row">
					<div class="col-md-4">
						<label for="kodegrup">Kode Grup</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="kodegrup">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="namagrup">Nama Grup</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="namagrup">
					</div>
				</div>
                <?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'divisiid', 'ColField' => 'namadivisi',
					'IDDialog' => 'divisiid_dialog', 'titledialog' => 'Divisi',
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.DivisiPopUp', 'PopGrid' => 'divisiid'));
				?>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'bukubacaanid', 'ColField' => 'namabuku',
					'IDDialog' => 'bukubacaanid_dialog', 'titledialog' => 'Buku Bacaan',
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.BacaanPopUp', 'PopGrid' => 'bukubacaanid'));
				?>
		<div class="row">
					<div class="col-md-4">
						<label for="startdate">Start Date</label>
					</div>
					<div class="col-md-8">
                    <input type="date" name="startdate" class="form-control">
					</div>
				</div>					
        <div class="row">
					<div class="col-md-4">
						<label for="notes">Notes</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="notes">
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
				<ul class="nav nav-pills nav-fill">
					<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#grupbaca">Menu Harian</a></li>
					<!--<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#jabatan"><?php echo getCatalog("grupbaca") ?></a></li>-->
				</ul>
				<div class="tab-content">
					<div id="grupbaca" class="tab-pane">
						<?php if (CheckAccess('grupbaca', 'iswrite')) { ?>
							<button name="CreateButtongroupmenu" type="button" class="btn btn-primary" onclick="newdatagrupbaca()"><?php echo getCatalog('new') ?></button>
						<?php } ?>
						<?php if (CheckAccess('peserta', 'ispurge')) { ?>
							<button name="PurgeButtongroupmenu" type="button" class="btn btn-danger" onclick="purgedatagroupmenu()"><?php echo getCatalog('purge') ?></button>
						<?php } ?>
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
									'header' => getCatalog('grupbacaid'),
									'name' => 'grupbacaid',
									'value' => '$data["grupbacaid"]'
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
									'header' => 'Menu Date',
									'name' => 'menudate',
									'value' => '$data["menudate"]'
                                ),
                                array(
									'header' => 'Status',
									'name' => 'recordstatus',
									'value' => '$data["recordstatus"]'
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
						<h3 class="card-title"><?php echo getCatalog('grupbaca') ?></h3>
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
									'header' => getCatalog('bukubacaanid'),
									'name' => 'bukubacaanid',
									'value' => '$data["bukubacaanid"]'
								),
								array(
									'header' => getCatalog('kodebuku'),
									'name' => 'kodebuku',
									'value' => '$data["kodebuku"]'
								),
								array(
									'header' => getCatalog('namabuku'),
									'name' => 'namabuku',
									'value' => '$data["namabuku"]'
								),
								array(
									'header' => getCatalog('jumlah'),
									'name' => 'jumlah',
									'value' => '$data["jumlah"]'
								),
								array(
									'header' => getCatalog('total'),
									'name' => 'total',
									'value' => '$data["total"]'
								),
								array(
									'header' => getCatalog('notes'),
									'name' => 'notes',
									'value' => '$data["notes"]'
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
				<input type="hidden" class="form-control" name="bukubacaanid">
			
				<div class="row">
					<div class="col-md-4">
						<label for="hari">Hari</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="hari">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="menuharian">Menu Harian</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="menuharian">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="menudate">Menu Date</label>
					</div>
					<div class="col-md-8">
                    <input type="date" name="menudate" class="form-control">
					</div>
				</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatamenuharian()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">