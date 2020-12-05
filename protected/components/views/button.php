<?php 
if ($this->iswrite == true) {
  if (CheckAccess($this->menuname,'iswrite')) { ?>
  <button name="CreateButton" type="button" class="btn btn-primary" onclick="newdata()"><?php echo getCatalog('new')?></button>
<?php } } ?>
<?php
if ($this->ispost == true) {
  if (CheckAccess('stockopname', 'ispost')) { ?>
    <button name="ApproveButton" type="button" class="btn btn-success" onclick="approvedata($.fn.yiiGridView.getSelection('GridList'))"><?php echo getCatalog('approve') ?></button>
<?php } }?>
<?php 
if (($this->isreject == true) && ($this->ispost == false)) {
  if (CheckAccess($this->menuname,'isreject')) { ?>
<button name="DeleteButton" type="button" class="btn btn-warning" onclick="deletedata($.fn.yiiGridView.getSelection('GridList'))"><?php echo getCatalog('delete')?></button>
  <?php } } else 
if (($this->isreject == true) && ($this->ispost == true)) {
  if (CheckAccess($this->menuname,'isreject')) { ?>
<button name="DeleteButton" type="button" class="btn btn-warning" onclick="deletedata($.fn.yiiGridView.getSelection('GridList'))"><?php echo getCatalog('reject')?></button>
  <?php } } ?>
<?php if ($this->ispurge == true) { 
if (CheckAccess($this->menuname,'ispurge')) { ?>
<button name="PurgeButton" type="button" class="btn btn-danger" onclick="purgedata($.fn.yiiGridView.getSelection('GridList'))"><?php echo getCatalog('purge')?></button>
<?php } } ?>
<button name="SearchButton" type="button" class="btn btn-success btn-group" data-toggle="modal" data-target="#SearchDialog"><?php echo getCatalog('search') ?></button>
<?php if ($this->isdownload == true) { if (CheckAccess($this->menuname,'isdownload')) { ?>
  <div class="btn-group">
    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown">
    <?php echo getCatalog('download')?> <span class="caret"></span></button>
    <div class="dropdown-menu" role="menu">
      <a class="dropdown-item" onclick="downpdf($.fn.yiiGridView.getSelection('GridList'))"><?php echo getCatalog('downpdf')?></a>
</div>
  </div>
<?php } } ?>
<button name="HelpButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#HelpDialog"><?php echo getCatalog('help') ?></button>