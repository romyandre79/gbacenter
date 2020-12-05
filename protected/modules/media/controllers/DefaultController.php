<?php

class DefaultController extends AdminController
{
	protected $menuname = 'media';
	public $module = 'media';
	
	public function actionIndex()
	{
		parent::actionIndex();
		$this->redirect(Yii::app()->createUrl('media/mediamgr'));
	}
}