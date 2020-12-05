<script>
	function saveprofile() {
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('admin/useraccess/saveprofile') ?>',
			'data': {
				'useraccessid': $("input[name='useraccessid']").val(),
				'username': $("input[name='username']").val(),
				'realname': $("input[name='realname']").val(),
				'password': $("input[name='password']").val(),
				'email': $("input[name='email']").val(),
				'phoneno': $("input[name='phoneno']").val(),
				'userphoto': $("input[name='userphoto']").val()
			},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status === "success") {
					toastr.info(data.msg);
					location.reload();
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
		return false;
	}
</script>
<div class="card card-success">
	<div class="card-header with-border">
		<h3 class="card-title">User Profile</h3>
	</div><!-- /.card-header -->
	<div class="card-body">	
		<input type="hidden" class="form-control" name="useraccessid" value="<?php echo $useraccessid ?>">
		<div class="row">
			<div class="col-md-4">
				<label for="username"><?php echo getCatalog('username') ?></label>
			</div>
			<div class="col-md-7">
				<input type="text" class="form-control" name="username" id="username" value ="<?php echo $username ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<label for="password"><?php echo getCatalog('password') ?></label>
			</div>
			<div class="col-md-7">
				<input type="text" class="form-control" name="password" id="password" value ="<?php echo $password ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<label for="realname"><?php echo getCatalog('realname') ?></label>
			</div>
			<div class="col-md-7">
				<input type="text" class="form-control" name="realname" id="realname" value ="<?php echo $realname ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<label for="email"><?php echo getCatalog('email') ?></label>
			</div>
			<div class="col-md-7">
				<input type="text" class="form-control" name="email" id="email" value ="<?php echo $email ?>">
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<label for="phoneno"><?php echo getCatalog('phoneno') ?></label>
			</div>
			<div class="col-md-7">
				<input type="text" class="form-control" name="phoneno" id="phoneno" value ="<?php echo $phoneno ?>">
			</div>
		</div>
    <div class="row">
					<div class="col-md-4">
						<label for="userphoto"><?php echo getCatalog('userphoto') ?></label>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control" readonly value="<?php echo $userphoto ?>" name="userphoto" id="userphoto">
					</div>
				</div>
				<script>
					function successUp(param, param2, param3) {
						$('input[name="userphoto"]').val(param2);
						$('div.dz-success').remove();
					}
					function addedfile(param, param2, param3) {
						$('div.dz-success').remove();
					}
				</script>
        <div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-7">
						<?php
						$events = array(
							'success' => 'successUp(param,param2,param3)',
							'addedfile' => 'addedfile(param,param2,param3)'
						);
						$this->widget('ext.dropzone.EDropzone',
							array(
							'name' => 'upload',
							'url' => Yii::app()->createUrl('admin/useraccess/upload'),
							'mimeTypes' => array('.jpg', '.png', '.jpeg'),
							'events' => $events,
							'options' => CMap::mergeArray($this->options, $this->dict),
							'htmlOptions' => array('style' => 'height:95%; overflow: hidden;'),
						));
						?></div>
				</div>	
		<div class="row">
			<div class="col-md-4">
				<button type="submit" class="btn bg-black" onclick="saveprofile()"><?php echo getCatalog('save') ?></button>
			</div>
		</div>
	</div>
</div>