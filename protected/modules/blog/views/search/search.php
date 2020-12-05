<h1>Search Term : <?php echo $term ?></h1>
<?php
foreach ($posts as $post) {
	echo "<h2>".$post['title']."</h2><br/>";
	echo "<p><img src='".Yii::app()->theme->baseUrl.'/img/icons/calendar.png'."'>   ".
	date(getparameter('dateformat'), strtotime($post['postupdate']))." by ".$post['username']."</p><br/>";
	echo "<p>".truncateword($post['description'], 500)."</p>";
	echo "<p><a href='".$this->createUrl('post/read/'.$post['slug'])."'>".getCatalog('readmore')."</a></p>";
}
?>