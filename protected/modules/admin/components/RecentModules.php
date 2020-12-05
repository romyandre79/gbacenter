<?php
class RecentModules extends Portlet {
	public function getComments() {
		$posts = array();
		try {
			$dependency	 = new CDbCacheDependency('SELECT MAX(postcommentid) FROM postcomment');
			$posts			 = Yii::app()->getDb()->cache(1000, $dependency)->createCommand(
					'select a.modulename, a.description 
					from modules a ')->queryAll();
			return $posts;
		} catch (CDbException $ex) {
			return $posts;
		}
	}
	protected function renderContent() {
		$this->render('recentmodules');
	}
}