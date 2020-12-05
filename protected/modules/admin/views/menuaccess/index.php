<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/menuaccess.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('menuaccess') ?></h3>
	</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'menuaccess')); ?>
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
					'htmlOptions' => array('style' => 'width:auto'),
					'buttons' => array
						(
						'edit' => array
							(
							'label' => getCatalog('edit'),
							'imageUrl' => Yii::app()->baseUrl.'/images/edit.png',
							'visible' => booltostr(CheckAccess('menuaccess', 'iswrite')),
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
							'visible' => booltostr(CheckAccess('menuaccess', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
							purgedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('menuaccess',
									'isdownload')),
							'url' => '"#"',
							'click' => "function() {
							downpdf($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
					),
				),
				array(
					'header' => getCatalog('menuaccessid'),
					'name' => 'menuaccessid',
					'value' => '$data["menuaccessid"]'
				),
				array(
					'header' => getCatalog('menuname'),
					'name' => 'menuname',
					'value' => '$data["menuname"]'
				),
				array(
					'header' => getCatalog('menutitle'),
					'name' => 'menutitle',
					'value' => '$data["menutitle"]'
				),
				array(
					'header' => getCatalog('description'),
					'name' => 'description',
					'value' => '$data["description"]'
				),
				array(
					'header' => getCatalog('modulename'),
					'name' => 'moduleid',
					'value' => '$data["modulename"]'
				),
				array(
					'header' => getCatalog('parentid'),
					'name' => 'parentid',
					'value' => '$data["parentname"]'
				),
				array(
					'header' => getCatalog('menuurl'),
					'name' => 'menuurl',
					'value' => '$data["menuurl"]'
				),
				array(
					'header' => getCatalog('sortorder'),
					'name' => 'sortorder',
					'value' => '$data["sortorder"]'
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
    <?php $this->widget('Button',	array('menuname'=>'menuaccess')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'menuname'),
  array('searchtype'=>'text','searchname'=>'menutitle'),
  array('searchtype'=>'text','searchname'=>'modulename'),
  array('searchtype'=>'text','searchname'=>'menuurl'),
  array('searchtype'=>'text','searchname'=>'parentname'),
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/6Np0O2wnf-E')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('menuaccess') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" class="form-control" name="actiontype">
        <input type="hidden" class="form-control" name="menuaccessid">
        <div class="row">
          <div class="col-md-4">
            <label for="menuname"><?php echo getCatalog('menuname') ?></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="menuname">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="menutitle"><?php echo getCatalog('menutitle') ?></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="menutitle">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="description"><?php echo getCatalog('description') ?></label>
          </div>
          <div class="col-md-8">
            <textarea type="text" class="form-control" rows="5" name="description"></textarea>
          </div>
        </div>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'moduleid', 'ColField' => 'modulename',
					'IDDialog' => 'moduleid_dialog', 'titledialog' => getCatalog('modulename'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.ModulesPopUp', 'PopGrid' => 'moduleidgrid'));
				?>
				<?php
				$this->widget('DataPopUp',
					array('id' => 'Widget', 'IDField' => 'parentid', 'ColField' => 'parentname',
					'IDDialog' => 'parentid_dialog', 'titledialog' => getCatalog('parentmenu'),
					'classtype' => 'col-md-4',
					'classtypebox' => 'col-md-8',
					'PopUpName' => 'admin.components.views.ParentmenuPopUp', 'PopGrid' => 'parentidgrid'));
				?>
        <div class="row">
          <div class="col-md-4">
            <label for="menuurl"><?php echo getCatalog('menuurl') ?></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" name="menuurl">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="sortorder"><?php echo getCatalog('sortorder') ?></label>
          </div>
          <div class="col-md-8">
            <input type="number" class="form-control" name="sortorder">
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