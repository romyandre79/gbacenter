<div class="col-sm-6">
  <div class="card bg-black">
    <div class="card-header">
      <h1 class="card-title"><?php echo CHtml::encode($data['description']); ?></h1>
      <div class="card-tools">
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#Modal<?php echo $data['moduleid'] ?>">Uninstall</button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4"><?php echo getCatalog('createdby') ?></div>
        <div class="col-md-8"><?php echo $data['createdby']; ?></div>
      </div>
      <div class="row">
        <div class="col-md-4"><?php echo getCatalog('moduleversion') ?></div>
        <div class="col-md-8"><?php echo $data['moduleversion']; ?></div>
      </div>
      <div class="row">
        <div class="col-md-4"><?php echo getCatalog('installdate') ?></div>
        <div class="col-md-8"><?php echo Yii::app()->format->formatDateTime($data['installdate']); ?></div>
      </div>
      <div class="row">
        <div class="col-md-4"><?php echo getCatalog('dependmodule') ?></div>
        <div class="col-md-8"><?php echo $this->getModuleRelation($data['moduleid']); ?></div>
      </div>  
    </div>
  </div>
</div>
<div id="Modal<?php echo $data['moduleid'] ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Uninstall</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
				<input type="hidden" name="module" value="<?php echo $data['moduleid'] ?>">				
        <p><?php echo getcatalog('areyousureuninstall')?></p>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" onclick="Uninstall(<?php echo $data['moduleid'] ?>)"></input>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>