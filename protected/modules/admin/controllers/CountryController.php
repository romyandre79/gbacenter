<?php
class CountryController extends AdminController {
	protected $menuname	 = 'country';
	public $module			 = 'Admin';
	protected $pageTitle = 'Negara';
	public $wfname			 = '';
	public $sqldata			 = "select a0.countryid,a0.countrycode,a0.countryname,a0.recordstatus 
    from country a0 
  ";
	public $sqlcount		 = "select count(1) 
    from country a0 
  ";
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		$countryid	 = filterinput(2, 'countryid');
		$countrycode = filterinput(2, 'countrycode');
		$countryname = filterinput(2, 'countryname');
		$where			 .= " where coalesce(a0.countrycode,'') like '%".$countrycode."%'
			and coalesce(a0.countryname,'') like '%".$countryname."%'";
		if (($countryid !== '0') && ($countryid !== '')) {
			$where .= " and a0.countryid in (".$countryid.")";
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
			'keyField' => 'countryid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'countryid', 'countrycode', 'countryname', 'recordstatus'
				),
				'defaultOrder' => array(
					'countryid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.countryid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'countryid' => $model['countryid'],
				'countrycode' => $model['countrycode'],
				'countryname' => $model['countryname'],
				'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('countrycode', 'string', 'emptycountrycode'),
			array('countryname', 'string', 'emptycountryname'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'countryid',
				array(
				array(':countryid', 'countryid', PDO::PARAM_STR),
				array(':countrycode', 'countrycode', PDO::PARAM_STR),
				array(':countryname', 'countryname', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into country (countrycode,countryname,recordstatus)
			      values (:countrycode,:countryname,:recordstatus)',
				'update country
			      set countrycode = :countrycode,countryname = :countryname,recordstatus = :recordstatus
			      where countryid = :countryid');
		}
	}
	public function actionDelete() {
		parent::actionDelete();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$ids = filterinput(4,'id');
      if ($ids == '') {
        GetMessage('error', 'chooseone');
      }
      foreach ($ids as $id) {
        $sql		 = "select recordstatus from country where countryid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update country set recordstatus = 0 where countryid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update country set recordstatus = 1 where countryid = ".$id;
        }
        $connection->createCommand($sql)->execute();
      }
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
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
        $sql = "delete from country where countryid = ".$id;
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
		$this->pdf->title					 = getCatalog('country');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('countryid'), getCatalog('countrycode'),
			getCatalog('countryname'), getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10, 40, 120, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['countryid'], $row1['countrycode'], $row1['countryname'],
				(($row1['recordstatus'] == 1) ? 'Active' : 'NotActive')));
		}
		$this->pdf->Output();
	}
}