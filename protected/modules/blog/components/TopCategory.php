<?php
class TopCategory extends Portlet {
	public function getTopCategory() {
		$dependency	 = new CDbCacheDependency('SELECT MAX(categoryid) FROM category');
		$category		 = Yii::app()->getDb()->cache(1000, $dependency)->createCommand('select categoryid,slug,title,description from category where parentid is null limit 5')->queryAll();
		return $category;
	}
	protected function renderContent() {
		$this->render('topcategory');
	}
}