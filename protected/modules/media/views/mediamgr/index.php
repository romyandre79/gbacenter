<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<?php
$this->widget('ext.elFinder.ElFinderWidget', array(
	'connectorRoute' => 'media/mediamgr/connector',
	)
);
?>
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">