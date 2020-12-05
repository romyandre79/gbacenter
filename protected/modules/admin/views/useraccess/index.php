<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/useraccess.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('useraccess') ?></h3>
	</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'useraccess')); ?>
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
					'htmlOptions' => array('style' => 'width:100px'),
					'buttons' => array
						(
						'select' => array
							(
							'label' => getCatalog('detail'),
							'imageUrl' => Yii::app()->baseUrl.'/images/detail.png',
							'url' => '"#"',
							'click' => "function() {
								GetDetail($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'edit' => array
							(
							'label' => getCatalog('edit'),
							'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
							'visible' => booltostr(CheckAccess('useraccess', 'iswrite')),
							'url' => '"#"',
							'click' => "function() {
								updatedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('useraccess', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
								purgedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('useraccess', 'isdownload')),
							'url' => '"#"',
							'click' => "function() {
								downpdf($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
					),
				),
				array(
					'header' => getCatalog('useraccessid'),
					'name' => 'useraccessid',
					'value' => '$data["useraccessid"]'
				),
				array(
					'header' => getCatalog('username'),
					'name' => 'username',
					'value' => '$data["username"]'
				),
				array(
					'header' => getCatalog('realname'),
					'name' => 'realname',
					'value' => '$data["realname"]'
				),
				array(
					'header' => getCatalog('password'),
					'name' => 'password',
					'value' => '$data["password"]'
				),
				array(
					'header' => getCatalog('email'),
					'name' => 'email',
					'value' => '$data["email"]'
				),
				array(
					'header' => getCatalog('phoneno'),
					'name' => 'phoneno',
					'value' => '$data["phoneno"]'
				),
				array(
					'header' => getCatalog('languagename'),
					'name' => 'languageid',
					'value' => '$data["languagename"]'
				),
				array(
					'header' => getCatalog('userphoto'),
					'name' => 'userphoto',
					'type' => 'raw',
					'value' => 'CHtml::image(Yii::app()->baseUrl."/images/useraccess/".$data["userphoto"],$data["username"],
					array("width"=>"100"))'
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
    <?php $this->widget('Button',	array('menuname'=>'useraccess')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'username'),
  array('searchtype'=>'text','searchname'=>'realname'),
  array('searchtype'=>'text','searchname'=>'email'),
  array('searchtype'=>'text','searchname'=>'phoneno'),
  array('searchtype'=>'text','searchname'=>'languagename')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/duVCDN5oZ0Q')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('useraccess') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="useraccessid">
        <div class="row">
					<div class="col-md-4">
						<label for="username"><?php echo getCatalog('username') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="username">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="realname"><?php echo getCatalog('realname') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="realname">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="password"><?php echo getCatalog('password') ?></label>
					</div>
					<div class="col-md-8">
						<input type="password" class="form-control" name="password">
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
						<label for="phoneno"><?php echo getCatalog('phoneno') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="phoneno">
					</div>
				</div>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'languageid', 'ColField' => 'languagename',
					'IDDialog' => 'languageid_dialog', 'titledialog' => getCatalog('languagename'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.LanguagePopUp', 'PopGrid' => 'languageidgrid'));
				?>
				<div class="row">
					<div class="col-md-4">
						<label for="userphoto"><?php echo getCatalog('userphoto') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" readonly name="userphoto">
					</div>
				</div>
				<script>
					function successUp(param, param2, param3) {
						$('input[name="userphoto"]').val(param2);
						$('div.dz-success').remove();
					}
					function addedfile(param, param2, param3) {
						$('div.dz-success').remove();
					}
				</script>
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-8">
						<?php
						$events = array(
							'success' => 'successUp(param,param2,param3)',
							'addedfile' => 'addedfile(param,param2,param3)'
						);
						$this->widget('ext.dropzone.EDropzone',
							array(
							'name' => 'upload',
							'url' => Yii::app()->createUrl('admin/useraccess/upload'),
							'mimeTypes' => array('.jpg', '.png', '.jpeg'),
							'events' => $events,
							'options' => CMap::mergeArray($this->options, $this->dict),
							'htmlOptions' => array('style' => 'height:95%; overflow: hidden;'),
						));
						?></div>
				</div>	
        <div class="row">
					<div class="col-md-4">
						<label for="recordstatus"><?php echo getCatalog('recordstatus') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="recordstatus">
					</div>
				</div>
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#usergroup"><?php echo getCatalog("usergroup") ?></a></li>
          </ul>
				<div class="tab-content">
					<div id="usergroup" class="tab-pane">
						<?php if (CheckAccess('useraccess', 'iswrite')) { ?>
							<button name="CreateButtonusergroup" type="button" class="btn btn-primary" onclick="newdatausergroup()"><?php echo getCatalog('new') ?></button>
						<?php } ?>
						<?php if (CheckAccess('useraccess', 'ispurge')) { ?>
							<button name="PurgeButtonusergroup" type="button" class="btn btn-danger" onclick="purgedatausergroup()"><?php echo getCatalog('purge') ?></button>
						<?php } ?>
						<?php
						$this->widget('zii.widgets.grid.CGridView',
							array(
							'dataProvider' => $dataProviderusergroup,
							'id' => 'usergroupList',
							'selectableRows' => 2,
							'ajaxUpdate' => true,
							'filter' => null,
							'enableSorting' => true,
							'columns' => array(
								array(
									'class' => 'CCheckBoxColumn',
									'id' => 'ids',
								),
								array
									(
									'class' => 'CButtonColumn',
									'template' => '{edit} {purge}',
									'htmlOptions' => array('style' => 'width:160px'),
									'buttons' => array
										(
										'edit' => array
											(
											'label' => getCatalog('edit'),
											'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
											'visible' => booltostr(CheckAccess('useraccess', 'iswrite')),
											'url' => '"#"',
											'click' => "function() { 
								updatedatausergroup($(this).parent().parent().children(':nth-child(3)').text());
							}",
										),
										'purge' => array
											(
											'label' => getCatalog('purge'),
											'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
											'visible' => booltostr(CheckAccess('useraccess', 'ispurge')),
											'url' => '"#"',
											'click' => "function() { 
								purgedatausergroup($(this).parent().parent().children(':nth-child(3)').text());
							}",
										),
									),
								),
								array(
									'header' => getCatalog('usergroupid'),
									'name' => 'usergroupid',
									'value' => '$data["usergroupid"]'
								),
								array(
									'header' => getCatalog('groupaccess'),
									'name' => 'groupaccessid',
									'value' => '$data["groupname"]'
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
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo getCatalog('usergroup') ?></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						</div>
					</div><!-- /.box-header -->		
					<div class="box-body">
<?php
$this->widget('zii.widgets.grid.CGridView',
	array(
	'dataProvider' => $dataProviderusergroup,
	'id' => 'DetailusergroupList',
	'ajaxUpdate' => true,
	'filter' => null,
	'enableSorting' => true,
	'columns' => array(
		array(
			'header' => getCatalog('usergroupid'),
			'name' => 'usergroupid',
			'value' => '$data["usergroupid"]'
		),
		array(
			'header' => getCatalog('groupaccess'),
			'name' => 'groupaccessid',
			'value' => '$data["groupname"]'
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
<div id="InputDialogusergroup" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo getCatalog('usergroup') ?></h4>
      </div>
			<div class="modal-body">
				<input type="hidden" class="form-control" name="usergroupid">
<?php
$this->widget('DataPopUp',
	array('id' => 'Widget', 'IDField' => 'groupaccessid', 'ColField' => 'groupname',
	'IDDialog' => 'groupaccessid_dialog', 'titledialog' => getCatalog('groupaccess'),
	'classtype' => 'col-md-4',
	'classtypebox' => 'col-md-8',
	'PopUpName' => 'admin.components.views.GroupaccessPopUp', 'PopGrid' => 'groupaccessidgrid'));
?>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatausergroup()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">