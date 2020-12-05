<?php
$usercount = Yii::app()->db->createCommand('select ifnull(count(1),0)
from useraccess a')->queryScalar();
?>

<div class="info-box">
	<span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
	<div class="info-box-content">
		<span class="info-box-text"><?php echo getCatalog('useraccess') ?></span>
		<span class="info-box-number"><?php echo $usercount ?></span>
	</div><!-- /.info-box-content -->
</div><!-- /.info-box -->