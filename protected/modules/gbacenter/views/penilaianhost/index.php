<script src="<?php echo Yii::app()->baseUrl; ?>/js/gbacenter/penilaianhost.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
	<h3 class="card-title"><?php echo getCatalog('penilaian') ?></h3>
			</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'penilaianhost')); ?>
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
					'name' => 'penilaianid',
					'value' => '$data["penilaianid"]'
				),
				array(
					'header' => 'Grup',
					'name' => 'namagrup',
					'value' => '$data["namagrup"]'
				),
				array(
					'header' => 'Start Date',
					'name' => 'startdate',
					'value' => '$data["startdate"]'
				),
				array(
					'header' => 'End Date',
					'name' => 'enddate',
					'value' => '$data["enddate"]'
				),
				array(
					'header' => 'Nama Peserta',
					'name' => 'nama',
					'value' => '$data["nama"]'
                ),
                array(
					'header' => 'Rating',
					'name' => 'rating',
					'value' => '$data["rating"]'
                ),
                array(
					'header' => 'Notes',
					'name' => 'notes',
					'value' => '$data["notes"]'
				),
			)
		));
		?>
     <?php $this->widget('Button',	array('menuname'=>'penilaianhost')); ?>
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
        <h4 class="modal-title">Penilaian</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
	  <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="penilaianid">
                <?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'grupbacaid', 'ColField' => 'namagrup',
					'IDDialog' => 'grupbacaid_dialog', 'titledialog' => 'Grup',
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.GrupbacaPopUp', 'PopGrid' => 'grupbacaid'));
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
						<label for="enddate">End Date</label>
					</div>
					<div class="col-md-8">
                    <input type="date" name="enddate" class="form-control">
					</div>
				</div>	        			
        <div class="row">
					<div class="col-md-4">
						<label for="notes">Notes</label>
					</div>
					<div class="col-md-8">
                        <textarea type="text" class="form-control" rows="5" name="notes"></textarea>
					</div>
				</div>	
				<ul class="nav nav-pills nav-fill">
					<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#penilaianhost">Menu Penilaian</a></li>
					<!--<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#jabatan"><?php echo getCatalog("penilaian") ?></a></li>-->
				</ul>
				<div class="tab-content">
					<div id="penilaianhost" class="tab-pane">
						<?php if (CheckAccess('penilaianhost', 'iswrite')) { ?>
							<button name="CreateButtongroupmenu" type="button" class="btn btn-primary" onclick="newdatagruppenilaian()"><?php echo getCatalog('new') ?></button>
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
									'header' => getCatalog('penilaianid'),
									'name' => 'penilaianid',
									'value' => '$data["penilaianid"]'
								),
								array(
									'header' => 'Peserta',
									'name' => 'nama',
									'value' => '$data["nama"]'
								),
								array(
									'header' => 'Rating',
									'name' => 'rating',
									'value' => '$data["rating"]'
								),
								array(
									'header' => 'Notes',
									'name' => 'notesdetail',
									'value' => '$data["notesdetail"]'
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
						<h3 class="card-title"><?php echo getCatalog('penilaian') ?></h3>
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
									'header' => getCatalog('penilaianid'),
									'name' => 'penilaianid',
									'value' => '$data["penilaianid"]'
								),
								array(
									'header' => getCatalog('namagrup'),
									'name' => 'namagrup',
									'value' => '$data["namagrup"]'
								),
								array(
									'header' => getCatalog('startdate'),
									'name' => 'startdate',
									'value' => '$data["startdate"]'
								),
								array(
									'header' => getCatalog('enddate'),
									'name' => 'enddate',
									'value' => '$data["enddate"]'
								),
								array(
									'header' => getCatalog('notes'),
									'name' => 'notes',
									'value' => '$data["notes"]'
                                ),
                                array(
									'header' => getCatalog('nama'),
									'name' => 'nama',
									'value' => '$data["nama"]'
                                ),
                                array(
									'header' => getCatalog('rating'),
									'name' => 'rating',
									'value' => '$data["rating"]'
								),
								array(
									'header' => getCatalog('notesdetail'),
									'name' => 'notesdetail',
									'value' => '$data["notesdetail"]'
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
			<input type="hidden" class="form-control" name="penilaianid">
			<?php
			$this->widget('DataPopUp',
				array('id' => 'Widget', 'IDField' => 'pesertaid', 'ColField' => 'nama',
				'IDDialog' => 'pesertaid_dialog', 'titledialog' => 'Peserta',
				'classtype' => 'col-md-4',
				'classtypebox' => 'col-md-8',
				'PopUpName' => 'gbacenter.components.views.PesertaPopUp', 'PopGrid' => 'pesertaid'));
			?>
			<div class="row">
				<div class="col-md-4">
					<label for="rating">Rating</label>
				</div>
				<div class="col-md-8">
					<input type="text" class="form-control" name="rating">
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<label for="notesdetail">Notes</label>
				</div>
				<div class="col-md-8">
				<textarea type="text" class="form-control" rows="5" name="notesdetail"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatamenupenilaian()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">