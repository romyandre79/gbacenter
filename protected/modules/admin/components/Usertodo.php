<?php
class Usertodo extends Portlet {
	public $sqldata	 = 'select a.usertodoid,a.username,a.tododate,a.menuname,a.docno,a.description 
					from usertodo a ';
	public $sqlcount	 = 'select count(1) 
		from usertodo a ';
	protected function renderContent() {
		$this->sqldata = $this->sqldata." where username = '".Yii::app()->user->id."'";
		$count				 = Yii::app()->db->createCommand($this->sqlcount." where username = '".Yii::app()->user->id."'")->queryScalar();
		$dataProvider	 = new CSqlDataProvider($this->sqldata,
			array(
			'totalItemCount' => $count,
			'keyField' => 'usertodoid',
			'pagination' => array(
				'pageSize' => 10,
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'usertodoid', 'tododate', 'docno', 'menuname'
				),
				'defaultOrder' => 'tododate desc',
			),
		));
		$this->render('usertodo', array('dataProvider' => $dataProvider));
	}
}