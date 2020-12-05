<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Categories</h3>
  </div>
  <div class="panel-body">
		<ul class="nav nav-list">
			<?php
			foreach ($this->getTopCategory() as $cat) {
				echo "<li>";
				echo "<a href='".$this->getController()->createUrl('/blog/category/read/'.$cat['slug'])."'>".$cat['title']."</a>";
				echo "</li>";
			}
			?>
		</ul>
  </div>
</div>