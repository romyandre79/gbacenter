<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Recent Modules</h3>
  </div>
  <div class="panel-body">
		<ul class="nav nav-list">
			<?php
			foreach ($this->getComments() as $post) {
				echo "<li>";
				echo "<a href='".Yii::app()->createUrl($post['modulename'])."'>".$post['description']."</a>";
				echo "</li>";
			}
			?>
		</ul>
  </div>
</div>
