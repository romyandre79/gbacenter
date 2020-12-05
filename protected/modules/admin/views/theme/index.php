<script src="<?php echo Yii::app()->baseUrl; ?>/js/admin/theme.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="card card-purple">
	<div class="card-header with-border">
		<h3 class="card-title"><?php echo getCatalog('themes') ?></h3>
	</div>
	<div class="card-body">
  <?php $this->widget('Button',	array('menuname'=>'menuaccess','iswrite'=>false,'ispurge'=>false,'isreject'=>false)); ?>
  <?php
		$this->widget('ext.dropzone.EDropzone',
			array(
			'name' => 'upload',
			'url' => Yii::app()->createUrl('admin/theme/install'),
			'mimeTypes' => array('.zip'),
			'options' => CMap::mergeArray($this->options, $this->dict),
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
	</div>
</div>
<?php $this->widget('SearchPopUp',array('searchitems'=>array(
  array('searchtype'=>'text','searchname'=>'themename'),
  array('searchtype'=>'text','searchname'=>'description'),
  array('searchtype'=>'text','searchname'=>'themeversion'),
))); ?>
<?php $this->widget('HelpPopUp',array('helpurl'=>'https://www.youtube.com/embed/avteTlPe-ho')); ?>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">
