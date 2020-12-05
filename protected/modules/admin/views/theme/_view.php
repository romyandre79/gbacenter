<div class="col-sm-6">
<div class="card bg-black">
  <div class="card-header">
    <h1 class="card-title"><?php echo CHtml::encode($data['themename']); ?></h1>
    <div class="card-tools">
      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#Modal<?php echo $data['themeid'] ?>">Uninstall</button>
    </div>
  </div>
  <div class="card-body" >
    <div class="row">
      <div class="col-md-4"><?php echo getCatalog('description') ?></div>
      <div class="col-md-8"><?php echo $data['description']; ?></div>
    </div>
    <div class="row">
      <div class="col-md-4"><?php echo getCatalog('isadmintheme') ?></div>
      <div class="col-md-8"><?php
			if ($data['isadmin']) {
				echo "<label class='label label-info'>Yes</label>";
			} else {
				echo "<label class='label label-info'>No</label>";
			}
			?></div>
    </div>
    <div class="row">
      <div class="col-md-4"><?php echo getCatalog('createdby') ?></div>
      <div class="col-md-8"><?php echo CHtml::encode($data['createdby']); ?></div>
    </div>
    <div class="row">
      <div class="col-md-4"><?php echo getCatalog('version') ?></div>
      <div class="col-md-8"><?php echo CHtml::encode($data['themeversion']); ?></div>
    </div>
    <div class="row">
      <div class="col-md-4"><?php echo getCatalog('installdate') ?></div>
      <div class="col-md-8"><?php echo CHtml::encode(Yii::app()->format->formatdatetime($data['installdate'])); ?></div>
    </div>
    <div class="row">
      <div class="col-md-12">    <form action="<?php echo Yii::app()->createUrl('admin/theme/activate') ?>" 
					method="post">
			<input type="hidden" class="btn btn-info" name="themeid" value="<?php echo $data['themeid'] ?>">
			<?php if ($data['recordstatus'] == 0) { ?>
				<input type="submit" class="btn btn-info" name="status" value="Active">
			<?php } ?>
			<?php if ($data['recordstatus'] == 1) { ?>
				<input type="submit" class="btn btn-info" name="status" value="Not Active">
			<?php } ?>
		</form></div>
    </div>
  </div>
</div>
</div>
<!-- Modal -->
<div id="Modal<?php echo $data['themeid'] ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Theme Editor</h4>
      </div>
			<form action="<?php echo Yii::app()->createUrl('admin/theme/uninstall') ?>" method="post">
				<div class="modal-body">
					<input type="hidden" name="theme" value="<?php echo $data['themeid'] ?>">				
					<p>Are You Sure to Uninstall this theme ?</p>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary"></input>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
    </div>
  </div>
</div>
