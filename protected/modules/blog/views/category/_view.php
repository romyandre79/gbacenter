<div class="col-sm-6">
  <div class="card bg-black">
    <div class="card-header">
      <h1 class="card-title"><?php echo CHtml::encode($data['title']); ?></h1>
      <div class="card-tools">
        <?php if (CheckAccess($this->menuname, 'iswrite')) { ?>
          <button name="<?php echo $data['categoryid'] ?>" type="button" class="btn btn-primary" onclick="updatedata(this)"><?php echo getCatalog('edit') ?></button>
        <?php } ?>
        <?php if (CheckAccess($this->menuname, 'isreject')) { ?>
          <button name="<?php echo $data['categoryid'] ?>" type="button" class="btn btn-warning" onclick="deletedata(this)"><?php echo getCatalog('delete') ?></button>
        <?php } ?>
        <?php if (CheckAccess($this->menuname, 'ispurge')) { ?>
          <button name="<?php echo $data['categoryid'] ?>" type="button" class="btn btn-danger" onclick="purgedata(this)"><?php echo getCatalog('purge') ?></button>
        <?php } ?>
      </div>
    </div>
    <div class="card-body" >
      <div class="row">
        <div class="col-md-4"><?php echo getCatalog('description') ?></div>
        <div class="col-md-8"><?php echo $data['description']; ?></div>
      </div>
      <div class="row">
        <div class="col-md-4"><?php echo getCatalog('parent') ?></div>
        <div class="col-md-8"><?php echo $data['parenttitle']; ?></div>
      </div>
      <div class="row">
        <div class="col-md-4"><?php echo getCatalog('slug') ?></div>
        <div class="col-md-8"><?php echo $data['slug']; ?></div>
      </div>
      <div class="row">
        <div class="col-md-4"><?php echo getCatalog('status') ?></div>
        <div class="col-md-8"><?php echo inttostr($data['recordstatus']); ?></div>
      </div>  
    </div>
  </div>
</div>