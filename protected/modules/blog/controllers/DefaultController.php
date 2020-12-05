<?php
class DefaultController extends AdminController {
	private $CreatedBy	 = 'Prisma Data Abadi';
	private $Version		 = '0.1';
	private $Description = 'Blog Module';
	protected $menuname	 = 'blog';
	public $module			 = 'blog';
	protected $pageTitle = 'Blog';
	public function actionIndex() {
		parent::actionIndex();
		$this->render('index');
	}
}