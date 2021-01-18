<script src="<?php echo Yii::app()->baseUrl; ?>/js/gbacenter/divisi.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('divisi') ?></h3>
			</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'divisi')); ?>
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
							'visible' => booltostr(CheckAccess('divisi', 'iswrite')),
							'url' => '"#"',
							'click' => "function() {
								updatedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('divisi', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
								purgedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('divisi',
									'isdownload')),
							'url' => '"#"',
							'click' => "function() {
								downpdf($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
					),
				),
				array(
					'header' => getCatalog('divisiid'),
					'name' => 'divisiid',
					'value' => '$data["divisiid"]'
				),
				array(
					'header' => getCatalog('kodedivisi'),
					'name' => 'kodedivisi',
					'value' => '$data["kodedivisi"]'
				),
				array(
					'header' => getCatalog('namadivisi'),
					'name' => 'namadivisi',
					'value' => '$data["namadivisi"]'
				),
				array(
					'header' => getCatalog('parentid'),
					'name' => 'parentid',
					'value' => '$data["parentdivisi"]'
				),
				array(
					'header' => getCatalog('namateam'),
					'name' => 'namateam',
					'value' => '$data["namateam"]'
				),
				array(
					'header' => getCatalog('notes'),
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
     <?php $this->widget('Button',	array('menuname'=>'divisi')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
   array('searchtype'=>'text','searchname'=>'kodedivisi'),
   array('searchtype'=>'text','searchname'=>'namadivisi'),
   array('searchtype'=>'text','searchname'=>'namateam')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/e2pfyuqR0RU')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo getCatalog('divisi') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="divisiid">
        <div class="row">
					<div class="col-md-4">
						<label for="kodedivisi"><?php echo getCatalog('kodedivisi')?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="kodedivisi" maxlength="15">
					</div>
				</div><br>
        <div class="row">
					<div class="col-md-4">
						<label for="namadivisi"><?php echo getCatalog('namadivisi')?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="namadivisi">
					</div>
				</div><br>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'parentid', 'ColField' => 'parentdivisi',
					'IDDialog' => 'parentid_dialog', 'titledialog' => getCatalog('parentdivisi'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.DivisiPopUp', 'PopGrid' => 'parentid'));
				?><br>	
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'teamid', 'ColField' => 'namateam',
					'IDDialog' => 'teamid_dialog', 'titledialog' => getCatalog('namateam'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.TeamPopUp', 'PopGrid' => 'teamid'));
				?><br>		
		<div class="row">
					<div class="col-md-4">
						<label for="notes"><?php echo getCatalog('notes')?></label>
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
					<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#peserta"><?php echo getCatalog("pengurus")." dan ".getCatalog("jabatan") ?></a></li>
				</ul>
				<br>
				<div class="tab-content">
					<div id="peserta" class="tab-pane">
						<?php if (CheckAccess('peserta', 'iswrite')) { ?>
							<button name="CreateButtongroupmenu" type="button" class="btn btn-primary" onclick="newdatapeserta()"><?php echo getCatalog('new') ?></button>
						<?php } ?>
						<?php if (CheckAccess('peserta', 'ispurge')) { ?>
							<button name="PurgeButtongroupmenu" type="button" class="btn btn-danger" onclick="purgedatagroupmenu()"><?php echo getCatalog('purge') ?></button>
						<?php } ?>
						<?php
						echo "<br>";
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
											'visible' => booltostr(CheckAccess('peserta',
													'iswrite')),
											'url' => '"#"',
											'click' => "function() { 
                        updatedatagroupmenu($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
										'purge' => array (
											'label' => getCatalog('purge'),
											'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
											'visible' => booltostr(CheckAccess('peserta',
													'ispurge')),
											'url' => '"#"',
											'click' => "function() { 
                        purgedatagroupmenu($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
									),
								),
								array(
									'header' => 'ID',
									'name' => 'divisidetailid',
									'value' => '$data["divisidetailid"]'
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
									'header' => getCatalog('namajabatan'),
									'name' => 'namajabatan',
									'value' => '$data["namajabatan"]'
								),
								array(
									'header' => getCatalog('jobdesk'),
									'name' => 'jobdesk',
									'value' => '$data["jobdesk"]'
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
						<h3 class="card-title"><?php echo getCatalog('peserta') ?></h3>
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
									'header' => getCatalog('pesertaid'),
									'name' => 'pesertaid',
									'value' => '$data["pesertaid"]'
								),
								array(
									'header' => getCatalog('nohp'),
									'name' => 'nohp',
									'value' => '$data["nohp"]'
								),
								array(
									'header' => getCatalog('idtelegram'),
									'name' => 'idtelegram',
									'value' => '$data["idtelegram"]'
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
      <h4 class="modal-title"><?php echo getCatalog('peserta') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
			<div class="modal-body">
				<input type="hidden" class="form-control" name="divisidetailid">
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'pesertaid', 'ColField' => 'nama',
					'IDDialog' => 'pesertaid_dialog', 'titledialog' => getCatalog('peserta'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.PesertaPopUp', 'PopGrid' => 'pesertaidgrid'));
				?>
				<br>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'jabatanid', 'ColField' => 'namajabatan',
					'IDDialog' => 'jabatanid_dialog', 'titledialog' => getCatalog('jabatan'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.JabatanPopUp', 'PopGrid' => 'jabatanidgrid'));
				?>
				<br>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatapeserta()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">
