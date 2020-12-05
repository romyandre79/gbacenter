<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/modules.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('modules') ?></h3>
	</div>
	<div class="card-body">
  <?php $this->widget('Button',	array('menuname'=>'widget','iswrite'=>false,'isreject'=>false,'ispurge'=>false,'isdownload'=>false)); ?>
		<?php
		$this->widget('ext.dropzone.EDropzone',
			array(
			'name' => 'upload',
			'url' => Yii::app()->createUrl('admin/modules/install'),
			'mimeTypes' => array('.zip'),
			'options' => CMap::mergeArray($this->options, $this->dict),
			'events' => array(
				'success' => 'js:running(this,param2)'
			),
			'htmlOptions' => array('style' => 'height:95%; overflow: hidden;'),
		));
    ?>
		<?php
		$this->widget('zii.widgets.CListView',
			array(
			'dataProvider' => $dataProvider,
			'id' => 'GridList',
			'template' => '{sorter}{pager}{summary}{items}{pager}',
			'itemView' => '_view',
		));
    ?>
  <?php $this->widget('Button',	array('menuname'=>'widget','iswrite'=>false,'isreject'=>false,'ispurge'=>false,'isdownload'=>false)); ?>
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'createdby'),
  array('searchtype'=>'text','searchname'=>'moduleversion'),
  array('searchtype'=>'text','searchname'=>'modulename'),
  array('searchtype'=>'text','searchname'=>'description'),
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/duyCHXpQEQ0')); ?>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">