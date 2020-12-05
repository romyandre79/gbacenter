<?php
Yii::import('zii.widgets.CPortlet');
class RecentPost extends Portlet {
	public function getLimitPost() {
		$dependency	 = new CDbCacheDependency('SELECT MAX(postid) FROM posts');
		$posts			 = Yii::app()->getDb()->cache(1000, $dependency)->createCommand(
				'select title,slug from post a order by postupdate limit '.getparameter('maxrecentpost'))->queryAll();
		return $posts;
	}
	protected function renderContent() {
		$this->render('recentpost');
	}
}