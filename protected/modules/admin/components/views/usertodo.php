<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.yiigridview.min.js"></script>  
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.yiilivegridview.min.js"></script>  
<div class="card card-primary">
	<div class="card-header">
		<h3 class="card-title">User TO DO</h3>
	</div><!-- /.card-header -->
	<div class="card-body no-padding">	
		<div class="col-md-12">
			<?php
			$this->widget('application.extensions.LiveGridView.RefreshGridView',
				array(
				'id' => 'usertodo-grid',
				'updatingTime' => 60000, // 60 sec
				'dataProvider' => $dataProvider,
				'id' => 'TodoList',
				'selectableRows' => 2,
				'ajaxUpdate' => true,
				'filter' => null,
				'enableSorting' => true,
				'columns' => array(
					array(
						'header' => getCatalog('tododate'),
						'name' => 'tododate',
						'value' => 'Yii::app()->format->formatDateTime($data["tododate"])'
					),
					array(
						'header' => getCatalog('docno'),
						'name' => 'docno',
						'value' => '$data["docno"]'
					),
					array(
						'header' => getCatalog('menuname'),
						'type' => 'raw',
						'name' => 'menuname',
						'value' => 'CHtml::link(getCatalog($data["menuname"]),
					Yii::app()->createUrl(getMenuModule($data["menuname"])."index",
					array("name" => $data["usertodoid"])))'
					),
					array(
						'header' => getCatalog('description'),
						'name' => 'description',
						'value' => '$data["description"]'
					),
				)
			));
			?>
		</div>
  </div>
</div>
