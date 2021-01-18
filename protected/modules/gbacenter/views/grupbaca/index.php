<script src="<?php echo Yii::app()->baseUrl; ?>/js/gbacenter/grupbaca.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('grupbaca') ?></h3>
			</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'grupbaca')); ?><br>
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
							'visible' => booltostr(CheckAccess('grupbaca', 'iswrite')),
							'url' => '"#"',
							'click' => "function() {
								updatedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('grupbaca', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
								purgedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('grupbaca',
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
        <h4 class="modal-title"><?php echo getCatalog('grupbaca') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="grupbacaid">
        <div class="row">
					<div class="col-md-4">
						<label for="kodegrup"><?php echo getCatalog('kodegrup') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="kodegrup">
					</div>
				</div><br>
        <div class="row">
					<div class="col-md-4">
						<label for="namagrup"><?php echo getCatalog('namagrup') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="namagrup">
					</div>
				</div><br>
		<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'divisiid', 'ColField' => 'namadivisi',
					'IDDialog' => 'divisiid_dialog', 'titledialog' => 'Divisi',
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.DivisiPopUp', 'PopGrid' => 'divisiid'));
				?><br>
		<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'bukubacaanid', 'ColField' => 'namabuku',
					'IDDialog' => 'bukubacaanid_dialog', 'titledialog' => 'Buku Bacaan',
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'gbacenter.components.views.BacaanPopUp', 'PopGrid' => 'bukubacaanid'));
				?><br>
		<div class="row">
					<div class="col-md-4">
						<label for="startdate"><?php echo getCatalog('startdate') ?></label>
					</div>
					<div class="col-md-8">
                    <input type="date" name="startdate" class="form-control">
					</div>
				</div><br>					
        <div class="row">
					<div class="col-md-4">
						<label for="notes"><?php echo getCatalog('notes') ?></label>
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
				<button name="CreateButtonProsesGrupBaca" type="button" class="btn btn-primary" onclick="prosesgrupbaca()">Proses</button><br>		
				<br>
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#groupmenu"><?php echo getCatalog("menuharian") ?></a></li>
					<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#userdash"><?php echo getCatalog("Memberbaca") ?></a></li>
				</ul>
				<div class="tab-content">
				<br>
					<div id="groupmenu" class="tab-pane">
						<?php if (CheckAccess('grupbaca', 'iswrite')) { ?>
							<button name="CreateButtongroupmenu" type="button" class="btn btn-primary" onclick="newdatagrupbaca()"><?php echo getCatalog('new') ?></button>	
						<?php } ?>
						<?php if (CheckAccess('grupbaca', 'ispurge')) { ?>
							<button name="PurgeButtongroupmenu" type="button" class="btn btn-danger" onclick="purgedatagroupmenu()"><?php echo getCatalog('purge') ?></button><br>
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
								array(
									'header' => 'ID Detail',
									'name' => 'grupbacadetailid',
									'value' => '$data["grupbacadetailid"]'
								),
								array(
									'header' => 'ID',
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
									'class' => 'CCheckBoxColumn',
									'name' => 'recordstatus',
									'header' => getCatalog('recordstatus'),
									'selectableRows' => '0',
									'checked' => '((date("Y-m-d") > $data["menudate"]) ? 0 : 1)',
								),
							)
						));
						?>
					</div>
					<div id="userdash" class="tab-pane">
						<?php if (CheckAccess('grupbaca', 'iswrite')) { ?>
							<button name="CreateButtonuserdash" type="button" class="btn btn-primary" onclick="newdatauserdash()"><?php echo getCatalog('new') ?></button>
						<?php } ?>
						<?php if (CheckAccess('grupbaca', 'ispurge')) { ?>
							<button name="PurgeButtonuserdash" type="button" class="btn btn-danger" onclick="purgedatauserdash()"><?php echo getCatalog('purge') ?></button>
						<?php } ?>
            <script>
							function successUp(param, param2, param3) {
								toastr.info(param2);
								$.fn.yiiGridView.update("userdashList");
							}
						</script>
						<?php
							$this->widget('ext.dropzone.EDropzone',
								array(
								'name' => 'upload',
								'url' => Yii::app()->createUrl('gbacenter/grupbaca/uploadmember'),
								'mimeTypes' => array('.xlsx'),
								'options' => CMap::mergeArray($this->options, $this->dict),
								'events' => array(
                  'success' => 'successUp(param,param2,param3)',
                  'sending' => "param3.append('grupbacaid',$('input[name=grupbacaid]').val())"
								),
								'htmlOptions' => array('style' => 'height:95%; overflow: hidden;'),
							));
						?>
						<?php
						$this->widget('zii.widgets.grid.CGridView',
							array(
							'dataProvider' => $dataProvideruserdash,
							'id' => 'userdashList',
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
											'visible' => booltostr(CheckAccess('grupbaca',
													'iswrite')),
											'url' => '"#"',
											'click' => "function() { 
                        updatedatauserdash($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
										'purge' => array (
											'label' => getCatalog('purge'),
											'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
											'visible' => booltostr(CheckAccess('grupbaca',
													'ispurge')),
											'url' => '"#"',
											'click' => "function() { 
                        purgedatauserdash($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
									),
								),
								array(
									'header' => getCatalog("membergrupbacaid"),
									'name' => 'membergrupbacaid',
									'value' => '$data["membergrupbacaid"]',
									'visible' => 'false'
								),
								array(
									'header' => getCatalog("nama"),
									'name' => 'nama',
									'value' => '$data["nama"]'
								),
								array(
									'header' => getCatalog("aliasid"),
									'name' => 'aliasid',
									'value' => '$data["aliasid"]'
								),
								array(
									'header' => getCatalog("nohp"),
									'name' => 'nohp',
									'value' => '$data["nohp"]'
								),
								array(
									'header' => getCatalog("idtelegram"),
									'name' => 'idtelegram',
									'value' => '$data["idtelegram"]'
								),
								array(
									'class' => 'CCheckBoxColumn',
									'name' => 'statusbaca',
									'header' => getCatalog('statusbaca'),
									'selectableRows' => '0',
									'checked' => '$data["statusbaca"]',
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
						<h3 class="card-title"><?php echo getCatalog('menuharian') ?></h3>
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
									'header' => 'ID Detail',
									'name' => 'grupbacadetailid',
									'value' => '$data["grupbacadetailid"]'
								),
								array(
									'header' => getCatalog('grupbacaid'),
									'name' => 'grupbacaid',
									'value' => '$data["grupbacaid"]'
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
									'header' => getCatalog('menudate'),
									'name' => 'menudate',
									'value' => '$data["menudate"]'
								),
							)
						));
						?>
					</div>		
				</div>		
				<div class="card card-primary">
					<div class="card-header with-border">
						<h3 class="card-title"><?php echo getCatalog('membergrupbaca') ?></h3>
						<div class="card-tools pull-right">
							<button class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div><!-- /.card-header -->		
					<div class="card-body">
						<?php
						$this->widget('zii.widgets.grid.CGridView',
							array(
							'dataProvider' => $dataProvideruserdash,
							'id' => 'DetailuserdashList',
							'ajaxUpdate' => true,
							'filter' => null,
							'enableSorting' => true,
							'columns' => array(
								array(
									'header' => getCatalog("membergrupbacaid"),
									'name' => 'membergrupbacaid',
									'value' => '$data["membergrupbacaid"]',
									'visible' => 'false'
								),
								array(
									'header' => getCatalog("nama"),
									'name' => 'nama',
									'value' => '$data["nama"]'
								),
								array(
									'header' => getCatalog("aliasid"),
									'name' => 'aliasid',
									'value' => '$data["aliasid"]'
								),
								array(
									'header' => getCatalog("nohp"),
									'name' => 'nohp',
									'value' => '$data["nohp"]'
								),
								array(
									'header' => getCatalog("idtelegram"),
									'name' => 'idtelegram',
									'value' => '$data["idtelegram"]'
								),
								array(
									'class' => 'CCheckBoxColumn',
									'name' => 'statusbaca',
									'header' => getCatalog('statusbaca'),
									'selectableRows' => '0',
									'checked' => '$data["statusbaca"]',
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
      <h4 class="modal-title"><?php echo getCatalog('menuharian') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
			<div class="modal-body">
					<input type="hidden" class="form-control" name="grupbacadetailid">
					
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
							<label for="menudate">Menu Date</label>
						</div>
						<div class="col-md-8">
						<input type="date" name="menudate" class="form-control">
						</div>
					</div><br>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatamenuharian()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
		</div>
	</div>
</div>
<div id="InputDialoguserdash" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('memberbaca') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       </div>
				<div class="modal-body">
						<input type="hidden" class="form-control" name="membergrupbacaid">
						<?php
						$this->widget('DataPopUp',
							array('id' => 'Widget', 'IDField' => 'pesertaid', 'ColField' => 'aliasid',
							'IDDialog' => 'pesertaid_dialog', 'titledialog' => 'Alias',
							'classtype' => 'col-md-4',
							'classtypebox' => 'col-md-8',
							'PopUpName' => 'gbacenter.components.views.PesertaaliasPopUp', 'PopGrid' => 'pesertaid'));
						?><br>
						<div class="row">
							<div class="col-md-4">
								<label for="recordstatus2"><?php echo getCatalog('recordstatus') ?></label>
							</div>
							<div class="col-md-8">
								<input type="checkbox" name="recordstatus2">
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
				</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatauserdash()"><?php echo getCatalog('save') ?></button>
        		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      		</div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">