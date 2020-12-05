<?php
class CityController extends AdminController {
	protected $menuname	 = 'city';
	public $module			 = 'Admin';
	protected $pageTitle = 'Kota';
	public $wfname			 = '';
	public $sqldata			 = "select a0.cityid,a0.provinceid,a0.citycode,a0.cityname,a0.recordstatus,a1.provincename as provincename 
    from city a0 
    left join province a1 on a1.provinceid = a0.provinceid
  ";
	public $sqlcount		 = "select count(1) 
    from city a0 
    left join province a1 on a1.provinceid = a0.provinceid
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$cityid				 = filterinput(2, 'cityid');
		$citycode			 = filterinput(2, 'citycode');
		$cityname			 = filterinput(2, 'cityname');
		$provincename	 = filterinput(2, 'provincename');
		$where				 .= " where coalesce(a0.citycode,'') like '%".$citycode."%'
			and coalesce(a0.cityname,'') like '%".$cityname."%'
			and coalesce(a1.provincename,'') like '%".$provincename."%'";
		if (($cityid !== '0') && ($cityid !== '')) {
			$where .= " and a0.cityid in (".$cityid.")";
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
			'keyField' => 'cityid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'cityid', 'provinceid', 'citycode', 'cityname', 'recordstatus'
				),
				'defaultOrder' => array(
					'cityid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where cityid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'cityid' => $model['cityid'],
				'provinceid' => $model['provinceid'],
				'citycode' => $model['citycode'],
				'cityname' => $model['cityname'],
				'recordstatus' => $model['recordstatus'],
				'provincename' => $model['provincename'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('provinceid', 'string', 'emptyprovinceid'),
			array('citycode', 'string', 'emptycitycode'),
			array('cityname', 'string', 'emptycityname'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'cityid',
				array(
				array(':cityid', 'cityid', PDO::PARAM_STR),
				array(':provinceid', 'provinceid', PDO::PARAM_STR),
				array(':citycode', 'citycode', PDO::PARAM_STR),
				array(':cityname', 'cityname', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into city (provinceid,citycode,cityname,recordstatus)
				values (:provinceid,:citycode,:cityname,:recordstatus)',
				'update city
				set provinceid = :provinceid,citycode = :citycode,cityname = :cityname,recordstatus = :recordstatus
				where cityid = :cityid');
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
        $sql		 = "select recordstatus from city where cityid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update city set recordstatus = 0 where cityid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update city set recordstatus = 1 where cityid = ".$id;
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
        $sql = "delete from city where cityid = ".$id;
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
		$this->pdf->title					 = getCatalog('city');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('cityid'), getCatalog('province'),
			getCatalog('citycode'), getCatalog('cityname'), getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10, 60, 20, 60, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['cityid'], $row1['provincename'], $row1['citycode'],
				$row1['cityname'],
				(($row1['recordstatus'] == 1) ? 'Active' : 'NotActive')));
		}
		$this->pdf->Output();
	}
}