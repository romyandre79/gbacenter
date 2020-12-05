<?php
class RecentComment extends Portlet {
	public function getComments() {
		$dependency	 = new CDbCacheDependency('SELECT MAX(postcommentid) FROM postcomment');
		$posts			 = Yii::app()->getDb()->cache(1000, $dependency)->createCommand(
				'select a.postid,comment,commentdate,b.slug,b.title
				from postcomment a 
				inner join post b on b.postid = a.postid 
				order by commentdate desc limit '.getparameter('maxrecentcomment'))->queryAll();
		return $posts;
	}
	protected function renderContent() {
		$this->render('recentcomment');
	}
}