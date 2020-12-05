<script src="<?php echo Yii::app()->baseUrl; ?>/js/gbacenter/team.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('team') ?></h3>
	</div>
	<div class="card-body">
    <?php $this->widget('Button',	array('menuname'=>'team')); ?>
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
							'visible' => booltostr(CheckAccess('team', 'iswrite')),
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
							'visible' => booltostr(CheckAccess('team', 'ispurge')),
							'url' => '"#"',
							'click' => "function() {
							purgedata($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
						'pdf' => array
							(
							'label' => getCatalog('downpdf'),
							'imageUrl' => Yii::app()->baseUrl.'/images/pdf.png',
							'visible' => booltostr(CheckAccess('team',
									'isdownload')),
							'url' => '"#"',
							'click' => "function() {
							downpdf($(this).parent().parent().children(':nth-child(3)').text());
						}",
						),
					),
				),
				array(
					'header' => getCatalog('teamid'),
					'name' => 'teamid',
					'value' => '$data["teamid"]'
				),
				array(
					'header' => getCatalog('kodeteam'),
					'name' => 'kodeteam',
					'value' => '$data["kodeteam"]'
				),
				array(
					'header' => getCatalog('namateam'),
					'name' => 'namateam',
					'value' => '$data["namateam"]'
				),
				array(
					'header' => getCatalog('koordinator'),
					'name' => 'koordinator',
					'value' => '$data["koordinator"]'
                ),
                array(
					'header' => getCatalog('wakilkoordinator'),
					'name' => 'wakilkoordinator',
					'value' => '$data["wakilkoordinator"]'
                ),
				array(
					'header' => getCatalog('keterangan'),
					'name' => 'keterangan',
					'value' => '$data["keterangan"]'
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
    <?php $this->widget('Button',	array('menuname'=>'team')); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'kodeteam'),
  array('searchtype'=>'text','searchname'=>'namateam'),
  array('searchtype'=>'text','searchname'=>'koordinator'),
  array('searchtype'=>'text','searchname'=>'wakilkoordinator')
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/BelvIaMxKag')); ?>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('team') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="actiontype">
				<input type="hidden" class="form-control" name="teamid">
        <div class="row">
					<div class="col-md-4">
						<label for="kodeteam">Kode Team</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="kodeteam">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="namateam">Nama Team</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="namateam">
					</div>
				</div>
        <div class="row">
					<div class="col-md-4">
						<label for="koordinator">Koordinator</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="koordinator">
					</div>
				</div>
		<div class="row">
					<div class="col-md-4">
						<label for="wakilkoordinator">Wakil Koordinator</label>
					</div>
					<div class="col-md-8">
						<input type="text" class="form-control" name="wakilkoordinator">
					</div>
				</div>
		<div class="row">
					<div class="col-md-4">
						<label for="keterangan"><?php echo getCatalog('keterangan') ?></label>
					</div>
					<div class="col-md-8">
					<input type="text" class="form-control" name="keterangan">
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