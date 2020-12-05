<div id="SearchDialog" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
      <h4 class="modal-title"><?php echo getCatalog('search') ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
			<div class="modal-body">
        <?php foreach ($this->searchitems as $searchitem) { ?>
				<div class="form-group">
						<label for="dlg_search_<?php echo $searchitem['searchname']?>"><?php echo getCatalog($searchitem['searchname'])?></label>
						<input type="<?php echo $searchitem['searchtype']?>" class="form-control" name="dlg_search_<?php echo $searchitem['searchname']?>">
					</div>          
        <?php } ?>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="searchdata()"><?php echo getCatalog('search') ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
			</div>
		</div>
	</div>
</div>