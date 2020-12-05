<?php
class ParameterController extends AdminController {
	protected $menuname	 = 'parameter';
	public $module			 = 'Admin';
	protected $pageTitle = 'Parameter';
	public $wfname			 = '';
	public $sqldata			 = "select a0.paramid,a0.paramname,a0.paramvalue,a0.description 
    from parameter a0 
  ";
	public $sqlcount		 = "select count(1) 
    from parameter a0 
  ";
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		$paramid		 = filterinput(2, 'paramid', FILTER_SANITIZE_STRING);
		$paramname	 = filterinput(2, 'paramname', FILTER_SANITIZE_STRING);
		$description = filterinput(2, 'description', FILTER_SANITIZE_STRING);
		$paramvalue	 = filterinput(2, 'paramvalue', FILTER_SANITIZE_STRING);
		$where			 .= " where a0.paramname like '%".$paramname."%'
			and a0.paramvalue like '%".$paramvalue."%'
			and a0.description like '%".$description."%'";
		if (($paramid !== '0') && ($paramid !== '')) {
			$where .= " and a0.paramid in (".$paramid.")";
		}
		$this->sqldata = $this->sqldata.$where;
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount.$where)->queryScalar();
	}
	public function actionIndex() {
		parent::actionIndex();
		$this->getSQL();
		$dataProvider = new CSqlDataProvider($this->sqldata,
			array(
			'totalItemCount' => $this->count,
			'keyField' => 'paramid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'paramid', 'paramname', 'paramvalue', 'description'
				),
				'defaultOrder' => array(
					'paramid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
	}
	public function actionCreate() {
		parent::actionCreate();
		echo CJSON::encode(array(
			'status' => 'success',
		));
	}
	public function actionUpdate() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldata.' where paramid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'paramid' => $model['paramid'],
				'paramname' => $model['paramname'],
				'paramvalue' => $model['paramvalue'],
				'description' => $model['description'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('paramname', 'string', 'emptyparamname'),
			array('paramvalue', 'string', 'emptyparamvalue'),
			array('description', 'string', 'emptydescription'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'paramid',
				array(
				array(':paramid', 'paramid', PDO::PARAM_STR),
				array(':paramname', 'paramname', PDO::PARAM_STR),
				array(':paramvalue', 'paramvalue', PDO::PARAM_STR),
				array(':description', 'description', PDO::PARAM_STR),
				),
				'insert into parameter (paramname,paramvalue,description)
			      values (:paramname,:paramvalue,:description)',
				'update parameter
			      set paramname = :paramname,paramvalue = :paramvalue,description = :description
			      where paramid = :paramid');
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
      $ids = filterinput(4,'id');
      if ($ids == '') {
        GetMessage('error', 'chooseone');
      }
      foreach ($ids as $id) {
        $sql = "delete from parameter where paramid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
      }
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionDownPDF() {
		parent::actionDownPDF();
		$this->getSQL();
		$dataReader = Yii::app()->db->createCommand($this->sqldata)->queryAll();
		$this->pdf->title					 = getCatalog('parameter');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('paramid'), getCatalog('paramname'),
			getCatalog('paramvalue'), getCatalog('description'));
		$this->pdf->setwidths(array(10, 60, 60, 60));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['paramid'], $row1['paramname'], $row1['paramvalue'],
				$row1['description']));
		}
		$this->pdf->Output();
	}
}