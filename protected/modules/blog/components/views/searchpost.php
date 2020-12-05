<form action="<?php echo Yii::app()->createUrl('blog/search') ?>" class="navbar-form navbar-right" role="search" method="post">
	<div class="form-group">
		<input type="text" name="term" class="form-control" placeholder="Search">
	</div>
	<button type="submit" class="btn btn-default">Submit</button>
</form>