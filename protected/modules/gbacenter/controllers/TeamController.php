<?php
class TeamController extends AdminController {
	protected $menuname	 = 'team';
	public $module			 = 'Gbacenter';
	protected $pageTitle = 'Team';
	public $wfname			 = '';
    public $sqldata			 = "select a0.teamid,a0.kodeteam,a0.namateam,a0.koordinator,a0.wakilkoordinator
    ,a0.keterangan, a0.recordstatus
    from team a0 
  ";
	public $sqlcount		 = "select count(1) 
    from team a0 
  ";
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		$teamid		 = filterinput(2, 'teamid', FILTER_SANITIZE_STRING);
		$kodeteam	 = filterinput(2, 'kodeteam', FILTER_SANITIZE_STRING);
		$namateam = filterinput(2, 'namateam', FILTER_SANITIZE_STRING);
        $kordinator	 = filterinput(2, 'koordinator', FILTER_SANITIZE_STRING);
        $wakilkordinator	 = filterinput(2, 'wakilkordinator', FILTER_SANITIZE_STRING);
		$where			 .= " where a0.kodeteam like '%".$kodeteam."%'
            and a0.namateam like '%".$namateam."%'
            and a0.koordinator like '%".$koordinator."%'
			and a0.wakilkoordinator like '%".$wakilkoordinator."%'";
		if (($teamid !== '0') && ($teamid !== '')) {
			$where .= " and a0.teamid in (".$teamid.")";
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
			'keyField' => 'teamid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'teamid', 'kodeteam', 'namateam', 'koordinator', 'wakilkoordinator', 'keterangan', 'recordstatus'
				),
				'defaultOrder' => array(
					'teamid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where teamid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'teamid' => $model['teamid'],
				'namateam' => $model['namateam'],
				'kodeteam' => $model['kodeteam'],
                'koordinator' => $model['koordinator'],
                'wakilkoordinator' => $model['wakilkoordinator'],
                'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('kodeteam', 'string', 'emptykodeteam'),
			array('namateam', 'string', 'emptynamateam'),
            array('koordinator', 'string', 'emptykoordinator'),
            array('wakilkoordinator', 'string', 'emptywakilkoordinator'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'teamid',
				array(
				array(':teamid', 'teamid', PDO::PARAM_STR),
				array(':kodeteam', 'kodeteam', PDO::PARAM_STR),
				array(':namateam', 'namateam', PDO::PARAM_STR),
                array(':koordinator', 'koordinator', PDO::PARAM_STR),
                array(':wakilkoordinator', 'wakilkoordinator', PDO::PARAM_STR),
                array(':keterangan', 'keterangan', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into team (kodeteam,namateam,koordinator,wakilkoordinator,keterangan, recordstatus)
			      values (:kodeteam,:namateam,:koordinator,:wakilkoordinator,:keterangan,:recordstatus)',
				'update team
                  set kodeteam = :kodeteam,namateam = :namateam,koordinator = :koordinator,wakilkoordinator = :wakilkoordinator
                  ,keterangan = :keterangan ,recordstatus = :recordstatus
			      where teamid = :teamid');
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
        $sql = "delete from team where teamid = ".$id;
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
		$this->pdf->title					 = getCatalog('team');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('teamid'), getCatalog('kodeteam'),
            getCatalog('namateam'), getCatalog('koordinator'), getCatalog('wakilkoordinator')
            , getCatalog('keterangan'));
		$this->pdf->setwidths(array(10, 60, 60, 60));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L','L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['teamid'], $row1['kodeteam'], $row1['namateam'],
				$row1['koordinator'], $row1['wakilkoordinator'], $row1['keterangan']));
		}
		$this->pdf->Output();
	}
}