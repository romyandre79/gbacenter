<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Recent Posts</h3>
  </div>
  <div class="panel-body">
		<ul class="nav nav-list">
			<?php
			foreach ($this->getLimitPost() as $post) {
				echo "<li>";
				echo "<a href='".Yii::app()->createUrl('blog/post/read/'.$post['slug'])."'>".$post['title']."</a>";
				echo "</li>";
			}
			?>
		</ul>
  </div>
</div>