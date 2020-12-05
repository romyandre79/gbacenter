<?php
class ProvinceController extends AdminController {
	protected $menuname	 = 'province';
	public $module			 = 'Admin';
	protected $pageTitle = 'Provinsi';
	public $wfname			 = '';
	public $sqldata			 = "select a0.provinceid,a0.countryid,a0.provincecode,a0.provincename,a0.recordstatus,a1.countryname as countryname 
    from province a0 
    left join country a1 on a1.countryid = a0.countryid
  ";
	public $sqlcount		 = "select count(1) 
    from province a0 
    left join country a1 on a1.countryid = a0.countryid
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$provinceid		 = filterinput(2, 'provinceid', FILTER_SANITIZE_STRING);
		$provincename	 = filterinput(2, 'provincename', FILTER_SANITIZE_STRING);
		$provincecode	 = filterinput(2, 'provincecode', FILTER_SANITIZE_STRING);
		$countryname	 = filterinput(2, 'countryname', FILTER_SANITIZE_STRING);
		$where				 .= " where coalesce(a0.provincecode,'') like '%".$provincecode."%'
			and coalesce(a0.provincename,'') like '%".$provincename."%'
			and coalesce(a1.countryname,'') like '%".$countryname."%'";
		if (($provinceid !== '0') && ($provinceid !== '')) {
			$where .= " and a0.provinceid in (".$provinceid.")";
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
			'keyField' => 'provinceid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'provinceid', 'countryid', 'provincecode', 'provincename', 'recordstatus'
				),
				'defaultOrder' => array(
					'provinceid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where provinceid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'provinceid' => $model['provinceid'],
				'countryid' => $model['countryid'],
				'provincecode' => $model['provincecode'],
				'provincename' => $model['provincename'],
				'recordstatus' => $model['recordstatus'],
				'countryname' => $model['countryname'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('countryid', 'string', 'emptycountryid'),
			array('provincecode', 'string', 'emptyprovincecode'),
			array('provincename', 'string', 'emptyprovincename'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'provinceid',
				array(
				array(':provinceid', 'provinceid', PDO::PARAM_STR),
				array(':countryid', 'countryid', PDO::PARAM_STR),
				array(':provincecode', 'provincecode', PDO::PARAM_STR),
				array(':provincename', 'provincename', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				), 
				'insert into province (countryid,provincecode,provincename,recordstatus)
			      values (:countryid,:provincecode,:provincename,:recordstatus)',
				'update province
			      set countryid = :countryid,provincecode = :provincecode,provincename = :provincename,recordstatus = :recordstatus
			      where provinceid = :provinceid');
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
        $sql		 = "select recordstatus from province where provinceid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update province set recordstatus = 0 where provinceid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update province set recordstatus = 1 where provinceid = ".$id;
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
        $sql = "delete from province where provinceid = ".$id;
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
		$this->pdf->title					 = getCatalog('province');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('provinceid'), getCatalog('country'),
			getCatalog('provincecode'), getCatalog('provincename'), getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10, 50, 20, 70, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['provinceid'], $row1['countryname'], $row1['provincecode'],
				$row1['provincename'],
				(($row1['recordstatus'] == 1) ? 'Active' : 'NotActive')));
		}
		$this->pdf->Output();
	}
}