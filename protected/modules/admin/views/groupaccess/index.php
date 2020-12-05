<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/groupaccess.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('groupaccess') ?></h3>
			</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'groupaccess')); ?>
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
							'visible' => booltostr(CheckAccess('groupaccess', 'iswrite')),
							'url' => '"#"',
							'click' => "function() {
								updatedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'purge' => array
							(
							'label' => getCatalog('purge'),
							'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
							'visible' => booltostr(CheckAccess('groupaccess', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
								purgedata($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('groupaccess',
									'isdownload')),
							'url' => '"#"',
							'click' => "function() {
								downpdf($(this).parent().parent().children(':nth-child(3)').text());
							}",
						),
					),
				),
				array(
					'header' => getCatalog('groupaccessid'),
					'name' => 'groupaccessid',
					'value' => '$data["groupaccessid"]'
				),
				array(
					'header' => getCatalog('groupname'),
					'name' => 'groupname',
					'value' => '$data["groupname"]'
				),
				array(
					'header' => getCatalog('description'),
					'name' => 'description',
					'value' => '$data["description"]'
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
     <?php $this->widget('Button',	array('menuname'=>'groupaccess')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'groupname'),
  array('searchtype'=>'text','searchname'=>'description')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/e2pfyuqR0RU')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo getCatalog('groupaccess') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="groupaccessid">
        <div class="row">
					<div class="col-md-4">
						<label for="groupname"><?php echo getCatalog('groupname') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="groupname">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="description"><?php echo getCatalog('description') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="description">
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
					<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#groupmenu"><?php echo getCatalog("groupmenu") ?></a></li>
					<li class="nav-item"><a data-toggle="tab" class="nav-link" href="#userdash"><?php echo getCatalog("userdash") ?></a></li>
				</ul>
				<div class="tab-content">
					<div id="groupmenu" class="tab-pane">
						<?php if (CheckAccess('groupaccess', 'iswrite')) { ?>
							<button name="CreateButtongroupmenu" type="button" class="btn btn-primary" onclick="newdatagroupmenu()"><?php echo getCatalog('new') ?></button>
						<?php } ?>
						<?php if (CheckAccess('groupaccess', 'ispurge')) { ?>
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
											'visible' => booltostr(CheckAccess('groupaccess',
													'iswrite')),
											'url' => '"#"',
											'click' => "function() { 
                        updatedatagroupmenu($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
										'purge' => array (
											'label' => getCatalog('purge'),
											'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
											'visible' => booltostr(CheckAccess('groupaccess',
													'ispurge')),
											'url' => '"#"',
											'click' => "function() { 
                        purgedatagroupmenu($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
									),
								),
								array(
									'header' => getCatalog('groupmenuid'),
									'name' => 'groupmenuid',
									'value' => '$data["groupmenuid"]'
								),
								array(
									'header' => getCatalog('menuaccess'),
									'name' => 'menuaccessid',
									'value' => '$data["menuname"]'
								),
								array(
									'class' => 'CCheckBoxColumn',
									'name' => 'isread',
									'header' => getCatalog('isread'),
									'selectableRows' => '0',
									'checked' => '$data["isread"]',
								), 
                array(
									'class' => 'CCheckBoxColumn',
									'name' => 'iswrite',
									'header' => getCatalog('iswrite'),
									'selectableRows' => '0',
									'checked' => '$data["iswrite"]',
								), 
                array(
									'class' => 'CCheckBoxColumn',
									'name' => 'ispost',
									'header' => getCatalog('ispost'),
									'selectableRows' => '0',
									'checked' => '$data["ispost"]',
								), 
                array(
									'class' => 'CCheckBoxColumn',
									'name' => 'isreject',
									'header' => getCatalog('isreject'),
									'selectableRows' => '0',
									'checked' => '$data["isreject"]',
								), 
                array(
									'class' => 'CCheckBoxColumn',
									'name' => 'ispurge',
									'header' => getCatalog('ispurge'),
									'selectableRows' => '0',
									'checked' => '$data["ispurge"]',
								), 
                array(
									'class' => 'CCheckBoxColumn',
									'name' => 'isupload',
									'header' => getCatalog('isupload'),
									'selectableRows' => '0',
									'checked' => '$data["isupload"]',
								), 
                array(
									'class' => 'CCheckBoxColumn',
									'name' => 'isdownload',
									'header' => getCatalog('isdownload'),
									'selectableRows' => '0',
									'checked' => '$data["isdownload"]',
								),
							)
						));
						?>
					</div>
					<div id="userdash" class="tab-pane">
						<?php if (CheckAccess('groupaccess', 'iswrite')) { ?>
							<button name="CreateButtonuserdash" type="button" class="btn btn-primary" onclick="newdatauserdash()"><?php echo getCatalog('new') ?></button>
						<?php } ?>
						<?php if (CheckAccess('groupaccess', 'ispurge')) { ?>
							<button name="PurgeButtonuserdash" type="button" class="btn btn-danger" onclick="purgedatauserdash()"><?php echo getCatalog('purge') ?></button>
						<?php } ?>
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
											'visible' => booltostr(CheckAccess('groupaccess',
													'iswrite')),
											'url' => '"#"',
											'click' => "function() { 
                        updatedatauserdash($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
										'purge' => array (
											'label' => getCatalog('purge'),
											'imageUrl' => Yii::app()->baseUrl.'/images/trash.png',
											'visible' => booltostr(CheckAccess('groupaccess',
													'ispurge')),
											'url' => '"#"',
											'click' => "function() { 
                        purgedatauserdash($(this).parent().parent().children(':nth-child(3)').text());
                      }",
										),
									),
								),
								array(
									'header' => getCatalog('userdashid'),
									'name' => 'userdashid',
									'value' => '$data["userdashid"]'
								),
								array(
									'header' => getCatalog('widget'),
									'name' => 'widgetid',
									'value' => '$data["widgetname"]'
								),
								array(
									'header' => getCatalog('menuaccess'),
									'name' => 'menuaccessid',
									'value' => '$data["menuname"]'
								),
								array(
									'header' => getCatalog('position'),
									'name' => 'position',
									'value' => '$data["position"]'
								),
								array(
									'header' => getCatalog('webformat'),
									'name' => 'webformat',
									'value' => '$data["webformat"]'
								),
								array(
									'header' => getCatalog('dashgroup'),
									'name' => 'dashgroup',
									'value' => '$data["dashgroup"]'
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
						<h3 class="card-title"><?php echo getCatalog('groupmenu') ?></h3>
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
									'header' => getCatalog('groupmenuid'),
									'name' => 'groupmenuid',
									'value' => '$data["groupmenuid"]'
								),
								array(
									'header' => getCatalog('menuaccess'),
									'name' => 'menuaccessid',
									'value' => '$data["menuname"]'
								),
								array(
									'class' => 'CCheckBoxColumn',
									'name' => 'isread',
									'header' => getCatalog('isread'),
									'selectableRows' => '0',
									'checked' => '$data["isread"]',
								), array(
									'class' => 'CCheckBoxColumn',
									'name' => 'iswrite',
									'header' => getCatalog('iswrite'),
									'selectableRows' => '0',
									'checked' => '$data["iswrite"]',
								), array(
									'class' => 'CCheckBoxColumn',
									'name' => 'ispost',
									'header' => getCatalog('ispost'),
									'selectableRows' => '0',
									'checked' => '$data["ispost"]',
								), array(
									'class' => 'CCheckBoxColumn',
									'name' => 'isreject',
									'header' => getCatalog('isreject'),
									'selectableRows' => '0',
									'checked' => '$data["isreject"]',
								), array(
									'class' => 'CCheckBoxColumn',
									'name' => 'ispurge',
									'header' => getCatalog('ispurge'),
									'selectableRows' => '0',
									'checked' => '$data["ispurge"]',
								), array(
									'class' => 'CCheckBoxColumn',
									'name' => 'isupload',
									'header' => getCatalog('isupload'),
									'selectableRows' => '0',
									'checked' => '$data["isupload"]',
								), array(
									'class' => 'CCheckBoxColumn',
									'name' => 'isdownload',
									'header' => getCatalog('isdownload'),
									'selectableRows' => '0',
									'checked' => '$data["isdownload"]',
								),
							)
						));
						?>
					</div>		
				</div>		
				<div class="card card-primary">
					<div class="card-header with-border">
						<h3 class="card-title"><?php echo getCatalog('userdash') ?></h3>
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
									'header' => getCatalog('userdashid'),
									'name' => 'userdashid',
									'value' => '$data["userdashid"]'
								),
								array(
									'header' => getCatalog('widget'),
									'name' => 'widgetid',
									'value' => '$data["widgetname"]'
								),
								array(
									'header' => getCatalog('menuaccess'),
									'name' => 'menuaccessid',
									'value' => '$data["menuname"]'
								),
								array(
									'header' => getCatalog('position'),
									'name' => 'position',
									'value' => '$data["position"]'
								),
								array(
									'header' => getCatalog('webformat'),
									'name' => 'webformat',
									'value' => '$data["webformat"]'
								),
								array(
									'header' => getCatalog('dashgroup'),
									'name' => 'dashgroup',
									'value' => '$data["dashgroup"]'
								)
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
      <h4 class="modal-title"><?php echo getCatalog('groupmenu') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
			<div class="modal-body">
				<input type="hidden" class="form-control" name="groupmenuid">
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'menuaccessid', 'ColField' => 'menuname',
					'IDDialog' => 'menuaccessid_dialog', 'titledialog' => getCatalog('menuaccess'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.MenuaccessPopUp', 'PopGrid' => 'menuaccessidgrid'));
				?>
        <div class="row">
					<div class="col-md-4">
						<label for="isread"><?php echo getCatalog('isread') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="isread">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="iswrite"><?php echo getCatalog('iswrite') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="iswrite">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="ispost"><?php echo getCatalog('ispost') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="ispost">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="isreject"><?php echo getCatalog('isreject') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="isreject">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="ispurge"><?php echo getCatalog('ispurge') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="ispurge">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="isupload"><?php echo getCatalog('isupload') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="isupload">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="isdownload"><?php echo getCatalog('isdownload') ?></label>
					</div>
					<div class="col-md-8">
						<input type="checkbox" name="isdownload">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatagroupmenu()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
		</div>
	</div>
</div>
<div id="InputDialoguserdash" class="modal fade" role="dialog">
	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('userdash') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
			<div class="modal-body">
				<input type="hidden" class="form-control" name="userdashid">
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'widgetid', 'ColField' => 'widgetname',
					'IDDialog' => 'widgetid_dialog', 'titledialog' => getCatalog('widget'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.WidgetPopUp', 'PopGrid' => 'widgetidgrid'));
				?>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'widgetmenuaccessid', 'ColField' => 'widgetmenuname',
					'IDDialog' => 'menuaccesswidgetid_dialog', 'titledialog' => getCatalog('menuaccess'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.MenuaccessPopUp', 'PopGrid' => 'menuaccesswidgetidgrid'));
				?>
        <div class="row">
					<div class="col-md-4">
						<label for="position"><?php echo getCatalog('position') ?></label>
					</div>
					<div class="col-md-8">
						<input type="number" name="position">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="webformat"><?php echo getCatalog('webformat') ?></label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="webformat">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="dashgroup"><?php echo getCatalog('dashgroup') ?></label>
					</div>
					<div class="col-md-8">
						<input type="number" name="dashgroup">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedatauserdash()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">