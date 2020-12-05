<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Recent Comment</h3>
  </div>
  <div class="panel-body">
		<?php
		foreach ($this->getComments() as $post) {
			echo "<li>";
			echo "<p>".truncateword($post['comment'], 50)." (".date(getparameter('dateformat'),
				strtotime($post['commentdate'])).") in <a href='".$this->getController()->createUrl('post/'.$post['slug'])."'>".$post['title']."</a></p>";
			echo "</li>";
		}
		?>
		</ul>
  </div>
</div>
