<?php
class RecentTag extends Portlet {
	public function getTags() {
		$tags			 = Yii::app()->getDb()->createCommand('select metatag from post a')->queryAll();
		$splittag	 = array();
		$newtag		 = array();
		foreach ($tags as $tag) {
			$splittag = explode(',', $tag['metatag']);
			foreach ($splittag as $sptag) {
				if (!in_array(trim($sptag), $newtag)) {
					array_push($newtag, $sptag);
				}
			}
		}
		return $newtag;
	}
	protected function renderContent() {
		$this->render('recenttag');
	}
}