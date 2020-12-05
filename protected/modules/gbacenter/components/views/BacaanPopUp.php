<?php
$sqldata = "select a.bukubacaanid,a.kodebuku,a.namabuku,a.notes 
        from bukubacaan a
		where a.recordstatus = 1 
		and a.kodebuku like '%".(isset($_REQUEST['kodebuku']) ? $_REQUEST['kodebuku']
        : '')."%'
		and a.namabuku like '%".(isset($_REQUEST['namabuku']) ? $_REQUEST['namabuku']
        : '')."%'";       
$count	 = count(Yii::app()->db->createCommand($sqldata)->queryAll());
$product = new CSqlDataProvider($sqldata,
	array(
	'totalItemCount' => $count,
	'keyField' => 'bukubacaanid',
	'pagination' => array(
		'pageSize' => ('DefaultPageSize'),
		'pageVar' => 'page',
	),
	'sort' => array(
		'attributes' => array(
			'kodebuku', 'namabuku', 'notes'
		),
		'defaultOrder' => array(
			'bukubacaanid' => CSort::SORT_DESC
		),
	),
	));
?>
<script>
	function <?php echo $this->IDField ?>searchdata() {
		$.fn.yiiGridView.update("<?php echo $this->PopGrid ?>", {data: {
				'kodebuku': $("input[name='<?php echo $this->IDField; ?>_search_kodebuku']").val(),
				'namabuku': $("input[name='<?php echo $this->IDField; ?>_search_namabuku']").val()
			}});
		return false;
	}
	function <?php echo $this->IDField ?>ShowButtonClick() {
		$('#<?php echo $this->IDDialog ?>').modal();
<?php echo $this->IDField ?>searchdata();
	}
	function <?php echo $this->IDField ?>ClearButtonClick() {
		$("input[name='<?php echo $this->ColField ?>']").val('');
		$("input[name='<?php echo $this->IDField ?>']").val('');
	}
	function <?php echo $this->PopGrid ?>onSelectionChange() {
		$("#<?php echo $this->PopGrid ?> > table > tbody > tr").each(function (i) {
			if ($(this).hasClass("selected")) {
				$("input[name='<?php echo $this->ColField ?>']").val($(this).find("td:nth-child(3)").text());
				$("input[name='<?php echo $this->IDField ?>']").val($(this).find('td:first-child').text());<?php echo $this->onaftersign ?>
			}
		});
		$('#<?php echo $this->IDDialog ?>').modal('hide');
	}
	$(document).ready(function () {
		$("input[name='<?php echo $this->ColField; ?>']").keyup(function (e) {
			if (e.keyCode == 13) {
<?php echo $this->IDField ?>ShowButtonClick();
			}
		});
		$(":input[name*='<?php echo $this->IDField; ?>_search_']").keyup(function (e) {
			if (e.keyCode == 13) {
<?php echo $this->IDField ?>searchdata()
			}
		});
	});
</script>
<div class="row">
	<div class="<?php echo $this->classtype ?>">
		<label class="control-label" for="<?php echo $this->ColField; ?>"><?php echo $this->titledialog ?></label>
	</div>
	<div class="<?php echo $this->classtypebox ?>">
		<input name="<?php echo $this->IDField ?>" type="hidden" value="">
		<div class="input-group">
			<input type="text" name="<?php echo $this->ColField ?>" readonly class="form-control">
			<span class="input-group-btn">
				<button name="<?php echo $this->IDField ?>ShowButton" type="button" class="btn btn-primary" onclick="<?php echo $this->IDField ?>ShowButtonClick()"><span class="fa fa-search"></span></button>
				<button name="<?php echo $this->IDField ?>ClearButton" type="button" class="btn btn-danger" onclick="<?php echo $this->IDField ?>ClearButtonClick()"><span class="fa fa-ban"></span></button>
			</span>
		</div>
	</div>
</div>

<div id="<?php echo $this->IDDialog ?>" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">	
		<div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title"><?php echo $this->titledialog ?></h4>
				<button type="button" class="close" data-dismiss="modal" href="#<?php echo $this->IDDialog ?>">&times;</button>
      </div>
      <div class="modal-body">
				<div class="row">
					<div class="col-md-4">
						<label class="control-label" for="<?php echo $this->IDField; ?>_search_kodebuku">Kode Buku</label>
					</div>
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" name="<?php echo $this->IDField; ?>_search_kodebuku" class="form-control">
							<span class="input-group-btn">
								<button name="<?php echo $this->IDField ?>SearchButton" type="button" class="btn btn-primary" onclick="<?php echo $this->IDField ?>searchdata()"><span class="fa fa-search"></span></button>
							</span>
						</div>
					</div>
				</div>			
				<div class="row">
					<div class="col-md-4">
						<label class="control-label" for="<?php echo $this->IDField; ?>_search_namabuku">Nama Buku</label>
					</div>
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" name="<?php echo $this->IDField; ?>_search_namabuku" class="form-control">
						</div>
					</div>
				</div>
				<?php
				$this->widget('zii.widgets.grid.CGridView',
					array(
					'id' => $this->PopGrid,
					'selectableRows' => 1,
					'dataProvider' => $product,
					'selectionChanged' => 'function(id){'.$this->PopGrid.'onSelectionChange()}',
					'columns' => array(
						array(
							'header' => 'ID',
							'name' => 'bukubacaanid',
							'value' => '$data["bukubacaanid"]',
							'htmlOptions' => array('width' => '1%')
                        ),
                        array(
							'header' => 'Kode Buku',
							'name' => 'kodebuku',
							'value' => '$data["kodebuku"]',
						),
                        array(
							'header' => 'Nama Buku',
							'name' => 'namabuku',
							'value' => '$data["namabuku"]',
                        ),
                        array(
							'header' => 'Notes',
							'name' => 'notes',
							'value' => '$data["notes"]',
						),
					),
				));
				?>
			</div>
		</div>
	</div>
</div>