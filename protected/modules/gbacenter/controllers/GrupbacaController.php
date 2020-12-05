<?php
class GrupbacaController extends AdminController {
	protected $menuname				 = 'bacaan';
	public $module						 = 'gbacenter';
	protected $pageTitle			 = 'Bacaan';
	public $wfname						 = '';
	public $sqldata						 = "select a0.grupbacaid,a0.kodegrup,a0.namagrup,a0.startdate, a0.notes,a0.recordstatus,
	b0.grupbacadetailid  idgrupbacadetail, b0.hari, b0.menuharian, b0.menudate,
	c0.divisiid, c0.namadivisi, d0.bukubacaanid, d0.namabuku
	from grupbaca a0 
	join grupbacadetail b0 on b0.grupbacaid = a0.grupbacaid
	left join divisi c0 on c0.divisiid = a0.divisiid
	left join bukubacaan d0 on d0.bukubacaanid = a0.bukubacaanid
  ";
  
    //peserta
	public $sqldatagroupmenu	 = "select a0.grupbacaid,a0.kodegrup,a0.namagrup,a0.startdate, a0.notes,a0.recordstatus,
	b0.grupbacadetailid  idgrupbacadetail, b0.hari, b0.menuharian, b0.menudate,
	c0.divisiid, c0.namadivisi, d0.bukubacaanid, d0.namabuku
	from grupbaca a0 
	join grupbacadetail b0 on b0.grupbacaid = a0.grupbacaid
	left join divisi c0 on c0.divisiid = a0.divisiid
	left join bukubacaan d0 on d0.bukubacaanid = a0.bukubacaanid
  ";

	public $sqlcount					 = "select count(1) 
	from grupbaca a0 
	join grupbacadetail b0 on b0.grupbacaid = a0.grupbacaid
	left join divisi c0 on c0.divisiid = a0.divisiid
	left join bukubacaan d0 on d0.bukubacaanid = a0.bukubacaanid
  ";
  
    //count Menu Harian
	public $sqlcountgroupmenu	 = "select count(1) 
	from grupbaca a0 
	join grupbacadetail b0 on b0.grupbacaid = a0.grupbacaid
	left join divisi c0 on c0.divisiid = a0.divisiid
	left join bukubacaan d0 on d0.bukubacaanid = a0.bukubacaanid
  ";

	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$grupbacaid = filterinput(2, 'grupbacaid');
		$kodegrup		 = filterinput(2, 'kodegrup');
		$namagrup	 = filterinput(2, 'namagrup');
		$where				 .= " where a0.kodegrup like '%".$kodegrup."%' 
			and a0.namagrup like '%".$namagrup."%'";
		if (($grupbacaid !== '0') && ($grupbacaid !== '')) {
			$where .= " and a0.grupbacaid in (".$grupbacaid.")";
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
			'keyField' => 'grupbacaid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'grupbacaid', 'kodegrup', 'namagrup', 'namadivisi', 'namabuku', 'startdate','recordstatus'
				),
				'defaultOrder' => array(
					'grupbacaid' => CSort::SORT_DESC
				),
			),
		));
	$grupbacaid = filterinput(1, 'grupbacaid',FILTER_SANITIZE_NUMBER_INT);
	
    if ($grupbacaid > 0) {
      $this->sqlcountgroupmenu .= ' where a0.grupbacaid = '.$grupbacaid;
      $this->sqldatagroupmenu	 .= ' where a0.grupbacaid = '.$grupbacaid;
    }
		$countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
		$dataProvidergroupmenu = new CSqlDataProvider($this->sqldatagroupmenu,
			array(
			'totalItemCount' => $countgroupmenu,
			'keyField' => 'grupbacaid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
        'attributes' => array(
					'grupbacaid'
				),
				'defaultOrder' => array(
					'grupbacaid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index',
		array('dataProvider' => $dataProvider, 'dataProvidergroupmenu' => $dataProvidergroupmenu));
		
	}
	public function actionCreate() {
		parent::actionCreate();
		$grupbacaid = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'status' => 'success',
			'grupbacaid' => $grupbacaid,
		));
	}
	public function actionCreategrupbaca() {
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.grupbacaid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'grupbacaid' => $model['grupbacaid'],
				'kodegrup' => $model['kodegrup'],
				'namagrup' => $model['namagrup'],
				'divisiid' => $model['divisiid'],
				'startdate' => $model['startdate'],
				'bukubacaanid' => $model['bukubacaanid'],
				'notes' => $model['notes'],
				'namadivisi' => $model['namadivisi'],
				'namabuku' => $model['namabuku'],
				'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
	public function actionUpdategroupmenu() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where a0.grupbacaid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'grupbacaid' => $model['grupbacaid'],
				'kodegrup' => $model['kodegrup'],
				'namagrup' => $model['namagrup'],
				'divisiid' => $model['divisiid'],
				'startdate' => $model['startdate'],
				'bukubacaanid' => $model['bukubacaanid'],
				'notes' => $model['notes'],
				'hari' => $model['hari'],
				'menuharian' => $model['menuharian'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('kodegrup', 'string', 'emptykodegrup'),
			array('namagrup', 'string', 'emptynamagrup'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'grupbacaid',
				array(
				array(':grupbacaid', 'grupbacaid', PDO::PARAM_STR),
				array(':kodegrup', 'kodegrup', PDO::PARAM_STR),
				array(':namagrup', 'namagrup', PDO::PARAM_STR),
				array(':divisiid', 'divisiid', PDO::PARAM_STR),
				array(':startdate', 'startdate', PDO::PARAM_STR),
				array(':bukubacaanid', 'bukubacaanid', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into grupbaca (grupbacaid,kodegrup,namagrup,divisiid,startdate,bukubacaanid,notes,recordstatus)
				values (:grupbacaid,:kodegrup,:namagrup,:divisiid,:startdate,:bukubacaanid,:notes,:recordstatus)'
			  ,
			  'update grupbaca
                  set kodegrup = :kodegrup,namagrup = :namagrup,divisiid = :divisiid,startdate = :startdate
                  ,bukubacaanid = :bukubacaanid,notes = :notes ,recordstatus = :recordstatus
			      where grupbacaid = :grupbacaid'
			  );
		}
	}
	public function actionSavegrupbacaharian() {
		parent::actionSave();
		$error = ValidateData(array(
			array('grupbacaid', 'string', 'emptygrupbacaid'),
			array('kodegrup', 'string', 'emptykodegrup'),
			array('namagrup', 'string', 'emptynamagrup'),
		));
		if ($error == false) {
			
			ModifyCommand(1, $this->menuname, 'grupbacaid',
				array(
				array(':grupbacaid', 'grupbacaid', PDO::PARAM_STR),
				array(':hari', 'hari', PDO::PARAM_STR),
				array(':menuharian', 'menuharian', PDO::PARAM_STR),
				array(':menudate', 'menudate', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into grupbacadetail (grupbacaid,hari,menuharian,menudate,recordstatus)
			      values (:grupbacaid,:hari,:menuharian,:menudate,recordstatus)',
				'insert into grupbacadetail (grupbacaid,hari,menuharian,menudate,recordstatus)
			      values (:grupbacaid,:hari,:menuharian,:menudate,:recordstatus)');
				
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
        $sql		 = "select recordstatus from grupbaca where grupbacaid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update grupbaca set recordstatus = 0 where grupbacaid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update grupbaca set recordstatus = 1 where grupbacaid = ".$id;
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
        $sql = "delete from grupbacadetail where grupbacaid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
	  }
	  
	  foreach ($ids as $id) {
        $sql = "delete from grupbaca where grupbacaid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
      }

      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionPurgegroupmenu() {
		parent::actionPurge();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$ids = filterinput(4,'id');
      if ($ids == '') {
        GetMessage('error', 'chooseone');
      }
      foreach ($ids as $id) {
        $sql = "delete from grupabacadetail where grupbacadetailid = ".$id;
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
		$this->pdf->title					 = getCatalog('grupbaca');
		$this->pdf->AddPage('L');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('grupbacaid'), getCatalog('kodegrup'),
			getCatalog('namagrup'), getCatalog('namadivisi'), getCatalog('startdate'), getCatalog('namabuku'), getCatalog('notes'),getCatalog('recordstatus'));
		$this->pdf->setwidths(array(20, 20, 30, 30, 15, 20, 25, 20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['grupbacaid'], $row1['kodegrup'], $row1['namagrup'],$row1['namadivisi'],
			$row1['startdate'],$row1['namabuku'],$row1['notes'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
}