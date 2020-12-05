<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Tags</h3>
  </div>
  <div class="panel-body">
		<div class="tags">
			<?php
			$newtag = $this->getTags();
			foreach ($newtag as $tag) {
				echo '<a href="'.Yii::app()->createUrl('blog/post/searchtag',
					array('term' => $tag)).'">'.$tag.'</a>';
			}
			?>	
		</div>
  </div>
</div>