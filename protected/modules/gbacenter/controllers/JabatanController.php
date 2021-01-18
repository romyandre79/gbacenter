<?php
class JabatanController extends AdminController {
	protected $menuname	 = 'jabatan';
	public $module			 = 'Gbacenter';
	protected $pageTitle = 'Jabatan';
	public $wfname			 = '';
	public $sqldata			 = "select a0.jabatanid,a0.kodejabatan,a0.namajabatan,a0.jobdesk,a0.istelegram,a0.recordstatus
    from jabatan a0 
  ";
	public $sqlcount		 = "select count(1) 
    from jabatan a0 
  ";
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		$jabatanid		 = filterinput(2, 'jabatanid', FILTER_SANITIZE_STRING);
		$kodejabatan	 = filterinput(2, 'kodejabatan', FILTER_SANITIZE_STRING);
		$namajabatan = filterinput(2, 'namajabatan', FILTER_SANITIZE_STRING);
		$jobdesk	 = filterinput(2, 'jobdesk', FILTER_SANITIZE_STRING);
		$where			 .= " where a0.kodejabatan like '%".$kodejabatan."%'
			and a0.namajabatan like '%".$namajabatan."%'
			and a0.jobdesk like '%".$jobdesk."%'";
		if (($jabatanid !== '0') && ($jabatanid !== '')) {
			$where .= " and a0.jabatanid in (".$jabatanid.")";
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
			'keyField' => 'jabatanid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'jabatanid', 'kodejabatan', 'namajabatan', 'jobdesk', 'istelegram', 'recordstatus'
				),
				'defaultOrder' => array(
					'jabatanid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where jabatanid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'jabatanid' => $model['jabatanid'],
				'namajabatan' => $model['namajabatan'],
				'kodejabatan' => $model['kodejabatan'],
                'jobdesk' => $model['jobdesk'],
                'istelegram' => $model['istelegram'],
                'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('kodejabatan', 'string', 'emptykodejabatan'),
			array('namajabatan', 'string', 'emptynamajabatan'),
            array('jobdesk', 'string', 'emptyjobdesk'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'jabatanid',
				array(
				array(':jabatanid', 'jabatanid', PDO::PARAM_STR),
				array(':kodejabatan', 'kodejabatan', PDO::PARAM_STR),
				array(':namajabatan', 'namajabatan', PDO::PARAM_STR),
                array(':jobdesk', 'jobdesk', PDO::PARAM_STR),
                array(':istelegram', 'istelegram', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into jabatan (kodejabatan,namajabatan,jobdesk,istelegram,recordstatus)
			      values (:kodejabatan,:namajabatan,:jobdesk,:istelegram,:recordstatus)',
				'update jabatan
                  set kodejabatan = :kodejabatan,namajabatan = :namajabatan,jobdesk = :jobdesk,istelegram = :istelegram
                  ,recordstatus = :recordstatus
			      where jabatanid = :jabatanid');
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
        $sql = "delete from jabatan where jabatanid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
      }
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
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
			$sql		 = "select recordstatus from jabatan where jabatanid = ".$id;
			$status	 = Yii::app()->db->createCommand($sql)->queryRow();
			if ($status['recordstatus'] == 1) {
			$sql = "update jabatan set recordstatus = 0 where jabatanid = ".$id;
			} else
			if ($status['recordstatus'] == 0) {
			$sql = "update jabatan set recordstatus = 1 where jabatanid = ".$id;
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

	public function actionDownPDF() {
		parent::actionDownPDF();
		$this->getSQL();
		$dataReader = Yii::app()->db->createCommand($this->sqldata)->queryAll();
		$this->pdf->title					 = getCatalog('jabatan');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('jabatanid'), getCatalog('kodejabatan'),
			getCatalog('namajabatan'), getCatalog('jobdesk'));
		$this->pdf->setwidths(array(10, 60, 60, 60));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['jabatanid'], $row1['kodejabatan'], $row1['namajabatan'],
				$row1['jobdesk']));
		}
		$this->pdf->Output();
	}
}