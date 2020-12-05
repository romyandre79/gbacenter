<h1>Category : <?php echo $title ?></h1>
<p><?php echo $description ?></p>
<p>
	<?php
	foreach ($posts as $post) {
		echo "<h2>".$post['title']."</h2><br/>";
		echo "<p><img src='".Yii::app()->request->baseUrl.'/images/calendar.png'."'></img>   ".
		date(getparameter('dateformat'), strtotime($post['postupdate']))." by ".$post['username']."  <img src='".
		Yii::app()->request->baseUrl.'/images/post.png'."'>  Posted in ".gettagcategory($post['categoryid'])."</p><br/>";
		echo "<p>".truncateword($post['description'], 300, ' ')."</p>";
		echo "<p><a href='".$this->createUrl('post/read/'.$post['slug'])."'>".getCatalog('readmore')."</a></p>";
	}
	?>