<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-resizable.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/admin/menugenerator.js"></script>
<div class="card card-info">
                <div class="card-header with-border">
                  <h3 class="card-title">Menu Generator</h3>
                </div><!-- /.box-header -->
                <div class="card-body no-padding">
<form class="form-horizontal" role="form" id="konfigform">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item"><a class="nav-link active" href="#tabkonfigurasi" data-toggle="tab">Konfigurasi</a></li>
    <li class="nav-item"><a class="nav-link" href="#tabview" data-toggle="tab">View</a></li>
    <li class="nav-item"><a class="nav-link" href="#tabinstalasi" data-toggle="tab">Instalasi</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane fade show active" id="tabkonfigurasi" role="tabpanel" aria-labelledby="tabkonfigurasi">
      <div class="form-group">
				<label class="control-label col-sm-2" for="menuname"><?php echo getCatalog('menuname')?></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="menuname" placeholder="Menu Name">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="tablename"><?php echo getCatalog('tablename')?></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="tablename" placeholder="Table Name">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="controller"><?php echo getCatalog('controllername')?></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="controller" placeholder="Controller Name">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="module"><?php echo getCatalog('modulename')?></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="module" placeholder="Module Name">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="appwf"><?php echo getCatalog('approvewf')?></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="appwf" placeholder="Approval Workflow">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="rejwf"><?php echo getCatalog('rejectwf')?></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="rejwf" placeholder="Reject Workflow">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="inswf"><?php echo getCatalog('insertwf')?></label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="inswf" placeholder="Insert Workflow">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="addonwhere"><?php echo getCatalog('addonwhere')?></label>
				<div class="col-sm-8">
					<textarea type="text" class="form-control" rows="5" name="addonwhere" placeholder="Add On Where"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="iswrite"><?php echo getCatalog('iswrite')?></label>
				<div class="col-sm-8">
					<input type="checkbox" name="iswrite">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="ispurge"><?php echo getCatalog('ispurge')?></label>
				<div class="col-sm-8">
					<input type="checkbox" name="ispurge">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="isreject"><?php echo getCatalog('isreject')?></label>
				<div class="col-sm-8">
					<input type="checkbox" name="isreject">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="ispost"><?php echo getCatalog('ispost')?></label>
				<div class="col-sm-8">
					<input type="checkbox" name="ispost">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="isdownload"><?php echo getCatalog('isdownload')?></label>
				<div class="col-sm-8">
					<input type="checkbox" name="isdownload">
				</div>
			</div>    
    </div>
    <div class="tab-pane fade" id="tabview" role="tabpanel" aria-labelledby="tabview">
    <button name="GenerateButton" type="button" class="btn btn-primary" onclick="readfield()"><?php echo getCatalog('gettable')?></button>
<?php
	$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
		'id'=>'GridList',
		'selectableRows'=>1,
    'ajaxUpdate'=>true,
		'filter'=>null,
		'enableSorting'=>true,
		'rowCssClassExpression'=>'
			($data["tablerelation"] !== "")?"warning":"success"
		',
		'columns'=>array(
			array
			(
				'class'=>'CButtonColumn',
				'template'=>'{edit} {purge}',
				'htmlOptions' => array('style'=>'width:80px'),
				'buttons'=>array
				(
					'edit' => array
					(
							'label'=>getCatalog('edit'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/edit.png',
							'visible'=>'true',
							'url'=>'"#"',
							'click'=>"function() { 
								updatedata($(this).parent().parent().children(':nth-child(2)').text());
							}",
					),
					'purge' => array
					(
							'label'=>getCatalog('purge'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/trash.png',
							'visible'=>'true',
							'url'=>'"#"',
							'click'=>"function() { 
								purgedata($(this).parent().parent().children(':nth-child(2)').text());
							}",
					),
				),
			),
			array(
				'header'=>getCatalog('menugenid'),
				'name'=>'menugenid',
				'value'=>'$data["menugenid"]'
			),
			array(
				'header'=>getCatalog('tablename'),
				'name'=>'tablename',
				'value'=>'$data["tablename"]'
			),
			array(
				'header'=>getCatalog('namafield'),
				'name'=>'namafield',
				'value'=>'$data["namafield"]'
			),
			array(
				'header'=>getCatalog('defaultvalue'),
				'name'=>'defaultvalue',
				'value'=>'$data["defaultvalue"]'
			),
			array(
				'header'=>getCatalog('tipefield'),
				'name'=>'tipefield',
				'value'=>'$data["tipefield"]'
			),
			array(
				'class'=>'CCheckBoxColumn',
				'name'=>'isview',
				'header'=>getCatalog('isview'),
				'selectableRows'=>'0',
				'checked'=>'$data["isview"]',
			),
			array(
				'class'=>'CCheckBoxColumn',
				'name'=>'issearch',
				'header'=>getCatalog('issearch'),
				'selectableRows'=>'0',
				'checked'=>'$data["issearch"]',
			),
			array(
				'class'=>'CCheckBoxColumn',
				'name'=>'isinput',
				'header'=>getCatalog('isinput'),
				'selectableRows'=>'0',
				'checked'=>'$data["isinput"]',
			),
			array(
				'class'=>'CCheckBoxColumn',
				'name'=>'isvalidate',
				'header'=>getCatalog('isvalidate'),
				'selectableRows'=>'0',
				'checked'=>'$data["isvalidate"]',
			),
			array(
				'class'=>'CCheckBoxColumn',
				'name'=>'isprint',
				'header'=>getCatalog('isprint'),
				'selectableRows'=>'0',
				'checked'=>'$data["isprint"]',
			),
			array(
				'header'=>getCatalog('widgetrelation'),
				'name'=>'widgetrelation',
				'value'=>'$data["widgetrelation"]'
			),
			array(
				'header'=>getCatalog('tablerelation'),
				'name'=>'tablerelation',
				'value'=>'$data["tablerelation"]'
			),
			array(
				'header'=>getCatalog('tablefkname'),
				'name'=>'tablefkname',
				'value'=>'$data["tablefkname"]'
			),
			array(
				'header'=>getCatalog('relationname'),
				'name'=>'relationname',
				'value'=>'$data["relationname"]'
			),
		)
));
?>
    </div>
    <div class="tab-pane fade" id="tabinstalasi" role="tabpanel" aria-labelledby="tabinstalasi">
    <div class="jumbotron">
					<h1>Hampir Selesai .... </h1> 
					<p>Klik tombol Generate untuk melakukan generator code</p> 
				</div>
				<a class="btn btn-info" href="#" onclick="install()">Generate</a>
    </div>
  </div>
</form>
			                </div><!-- /.box-body -->
              </div><!-- /.box -->
			
<div id="InputDialog" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo getCatalog('menugenerator') ?></h4>
      </div>
      <div class="modal-body">
				<input type="hidden" class="form-control" name="menugenid">
				<div class="row">
					<div class="col-md-2">
						<label for="namafield"><?php echo getCatalog('namafield')?></label>
					</div>
					<div class="col-md-10">
						<input type="text" class="form-control" name="namafield">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="defaultvalue"><?php echo getCatalog('defaultvalue')?></label>
					</div>
					<div class="col-md-10">
						<input type="text" class="form-control" name="defaultvalue">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="tipefield"><?php echo getCatalog('tipefield')?></label>
					</div>
					<div class="col-md-10">
						<input type="text" class="form-control" name="tipefield">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="isview"><?php echo getCatalog('isview')?></label>
					</div>
					<div class="col-md-10">
						<input type="checkbox" name="isview">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="issearch"><?php echo getCatalog('issearch')?></label>
					</div>
					<div class="col-md-10">
						<input type="checkbox" name="issearch">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="isinput"><?php echo getCatalog('isinput')?></label>
					</div>
					<div class="col-md-10">
						<input type="checkbox" name="isinput">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="isvalidate"><?php echo getCatalog('isvalidate')?></label>
					</div>
					<div class="col-md-10">
						<input type="checkbox" name="isvalidate">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="isprint"><?php echo getCatalog('isprint')?></label>
					</div>
					<div class="col-md-10">
						<input type="checkbox" name="isprint">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="widgetrelation"><?php echo getCatalog('widgetrelation')?></label>
					</div>
					<div class="col-md-10">
						<select class="form-control" name="widgetrelation">
						<option value=""></option>
            <?php 
            $glob = glob(Yii::getPathOfAlias('webroot').'/protected/modules/*', GLOB_BRACE);
            if (is_array($glob) || is_object($glob)) {
						foreach ($glob as $moduleDirectory) {
              $globdir = glob($moduleDirectory.'/components/views/*'); 
              if (is_array($globdir) || is_object($globdir)) {
							foreach ($globdir as $popfile) {
								if (strpos($popfile,'PopUp') > 0)
								{
									$s = str_replace(Yii::getPathOfAlias('webroot').'/protected/modules/','',$popfile);
									$s = str_replace('.php','',$s);
									$s = str_replace('/','.',$s);
									$s = str_replace(' ','.',$s);
						echo "<option value=\"".$s."\">".$s."</option>";
								}
              }
            }
    }
  }?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="tablerelation"><?php echo getCatalog('tablerelation')?></label>
					</div>
					<div class="col-md-10">
						<input type="text" class="form-control" name="tablerelation">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="tablefkname"><?php echo getCatalog('tablefkname')?></label>
					</div>
					<div class="col-md-10">
						<input type="text" class="form-control" name="tablefkname">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="relationname"><?php echo getCatalog('relationname')?></label>
					</div>
					<div class="col-md-10">
						<input type="text" class="form-control" name="relationname">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="popupname"><?php echo getCatalog('popupname')?></label>
					</div>
					<div class="col-md-10">
						<input type="text" class="form-control" name="popupname">
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<label for="wirelsource"><?php echo getCatalog('wirelsource')?></label>
					</div>
					<div class="col-md-10">
						<input type="text" class="form-control" name="wirelsource">
					</div>
				</div>
      </div>
      <div class="modal-footer">
				<button type="submit" class="btn btn-success" onclick="savedata()"><?php echo getCatalog('save') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo getCatalog('close') ?></button>
      </div>
    </div>
  </div>
</div>
<div class="card card-primary">
                <div class="card-header with-border">
                  <h3 class="card-title">File Manager</h3>
                </div><!-- /.box-header -->
                <div class="card-body no-padding">
                  <?php
$this->widget('ext.elFinder.ElFinderWidget', array(
	'connectorRoute' => 'admin/menugenerator/connector',
	)
);
?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/adminlte.min.css">