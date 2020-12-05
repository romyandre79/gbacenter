<?php
class SlideShow extends Portlet {
	protected function renderContent() {
		$sql			 = "select count(1) from slideshow";
		$datacount = Yii::app()->db->createCommand($sql)->queryScalar();
		$sql			 = "select * from slideshow";
		$datas		 = Yii::app()->db->createCommand($sql)->queryAll();
		$this->render('slideshow', array('datacount' => $datacount, 'datas' => $datas));
	}
}