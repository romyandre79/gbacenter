<?php
class SearchController extends Controller {
	public function actionSearch() {
		getTheme(false, $this->module);
		$this->layout		 = '//layouts/column1';
		$this->pageTitle = 'Search';
		$connection			 = Yii::app()->db;
		$sql						 = "SELECT postid,title,description,metatag,slug,postupdate,b.username ".
			" FROM post a ".
			" inner join useraccess b on b.useraccessid = a.useraccessid ".
			" where lower(description) like :description";
		$command				 = $connection->createCommand($sql);
		$command->bindvalue(':description', '%'.$_REQUEST['term'].'%', PDO::PARAM_STR);
		$posts					 = $command->queryAll();
		$this->render('search', array('term' => $_REQUEST['term'], 'posts' => $posts));
	}
	public function actionSearchTag() {
		getTheme(false, $this->module);
		$this->layout		 = '//layouts/column1';
		$this->pageTitle = 'Search';
		$connection			 = Yii::app()->db;
		$sql						 = "SELECT postid,title,description,metatag,slug,postupdate,b.username ".
			" FROM post a ".
			" inner join useraccess b on b.useraccessid = a.useraccessid ".
			" where lower(metatag) like :metatag";
		$command->bindvalue(':metatag', '%'.$_REQUEST['term'].'%', PDO::PARAM_STR);
		$posts					 = $command->queryAll();
		$this->render('search', array('term' => $_REQUEST['term'], 'posts' => $posts));
	}
	public function actionIndex() {
		$this->actionSearch();
	}
}