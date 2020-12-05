<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/css/alt/adminlte.core.min.css">
<script type="text/javascript">
	function profileuser() {
		var x;
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('site/getprofile') ?>',
			'data': {'username': '', 'password': ''},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status == "success") {
					$("input[name='useraccessid']").val(data.useraccessid),
						$("input[name='username']").val(data.username),
						$("input[name='realname']").val(data.realname),
						$("input[name='email']").val(data.email),
						$("input[name='phoneno']").val(data.phoneno),
						$("input[name='birthdate']").val(data.birthdate),
						$("textarea[name='useraddress']").val(data.useraddress)
					$('#RegisterDialog').modal();
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
		return false;
	}
	function registeruser() {
		var x;
		$('#RegisterDialog').modal();
		return false;
	}
	function submituser() {
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('site/login') ?>',
			'data': {
				'pptt': $("input[name='pptt']").val(),
				'sstt': $("input[name='sstt']").val(),
			},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status == "success") {
					location.href = "<?php echo Yii::app()->createUrl('admin') ?>";
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
	}
	function savedata() {
		jQuery.ajax({'url': '<?php echo Yii::app()->createUrl('site/saveuser') ?>',
			'data': {
				'useraccessid': $("input[name='useraccessid']").val(),
				'username': $("input[name='username']").val(),
				'password': $("input[name='password']").val(),
				'realname': $("input[name='realname']").val(),
				'email': $("input[name='email']").val(),
				'phoneno': $("input[name='phoneno']").val(),
				'birthdate': $("input[name='birthdate']").val(),
				'useraddress': $("textarea[name='useraddress']").val()
			},
			'type': 'post', 'dataType': 'json',
			'success': function (data) {
				if (data.status == "success") {
					$('#RegisterDialog').modal('hide');
					toastr.info(data.msg);
				} else {
					toastr.error(data.msg);
				}
			},
			'cache': false});
	}
	$(function () {
		$(":input[name*='tt']").keyup(function (e) {
			if (e.keyCode == 13) {
				submituser();
			}
		});
	});
</script>
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Login</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a style="color:#4674a7" href="#">Home</a></li>
          <li class="breadcrumb-item active" style="color:#000">Login</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
  <section class="content">
    <div class="container-fluid">
    <?php
		if (Yii::app()->user->id == null) {
			?>
			<form method="post" action="<?php echo Yii::app()->createUrl('/site/login') ?>" role="form">
				<div class="form-group">
          <label for="pptt"><?php echo getCatalog('username') ?></label>
          <input name="pptt" id="pptt" type="text" class="form-control">
				</div>
				<div class="form-group">
          <label for="sstt"><?php echo getCatalog('password') ?></label>
          <input name="sstt" id="sstt" type="password" class="form-control">
				</div>
				<div class="checkbox">
          <label for="rrmm"><?php echo getCatalog('rememberme') ?></label>
          <input name="rrmm" id="rrmm" type="checkbox">
				</div>
				<button name="submit" type="button" class="btn bg-black" onclick="submituser()"><?php echo getCatalog('Login') ?></button>
			</form>
			<?php
		} else {
			echo "Welcome ".Yii::app()->user->id." (<a href='".Yii::app()->createUrl('site/logout')."'>logout</a>)<br>";
			echo '<button name="profile" onclick="profileuser()">Profile</button>';
		}
		?>
    </div>
  </section>
</div>