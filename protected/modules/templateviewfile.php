<script type="text/javascript">
	TJSNewData
	TJSUpdateData
	TJSSaveData
	TJSApproveData
	TJSDeleteData
	TJSPurgeData
	TJSSearchData
	TJSDownPDF
	TJSGetDetail
</script>
<h3><?php echo getCatalog('TMenuName') ?></h3>
TIsNewData
TIsApproveData
TIsDeleteData
TIsPurgeData
<button name="SearchButton" type="button" class="btn btn-success" data-toggle="modal" data-target="#SearchDialog"><?php echo getCatalog('search') ?></button>
TIsDownload

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
		),
		TGridColumns
	)
));
?>
<div id="SearchDialog" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo getCatalog('search') ?></h4>
      </div>
			<div class="modal-body">
				TSearchForm
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="searchdata()"><?php echo getCatalog('search') ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
			</div>
		</div>
	</div>
</div>
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo getCatalog('TMenuName') ?></h4>
      </div>
      <div class="modal-body">
				TInputMasterForm
				TGridDetailMasterForm
      </div>
      <div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedata()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
    </div>
  </div>
</div>

TShowDetail
TInputDetailForm