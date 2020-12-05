<?php
class PenilaianhostController extends AdminController {
	protected $menuname				 = 'penilaianhost';
	public $module						 = 'gbacenter';
	protected $pageTitle			 = 'Penilaianhost';
	public $wfname						 = '';
	public $sqldata						 = "select a0.penilaianid,a0.grupbacaid,a0.startdate,a0.enddate, a0.notes,
	b0.penilaiandetailid, b0.pesertaid, b0.rating, b0.notes,
	c0.namagrup, d0.nama
	from penilaian a0 
	join penilaiandetail b0 on b0.penilaianid = a0.penilaianid
	left join grupbaca c0 on c0.grupbacaid = a0.grupbacaid
	left join peserta d0 on d0.pesertaid = b0.pesertaid
  ";
  
  
	public $sqldatagroupmenu	 = "select a0.penilaianid,a0.grupbacaid,a0.startdate,a0.enddate, a0.notes,
	b0.penilaiandetailid, b0.pesertaid, b0.rating, b0.notes notesdetail,
	c0.namagrup, d0.nama
	from penilaian a0 
	join penilaiandetail b0 on b0.penilaianid = a0.penilaianid
	left join grupbaca c0 on c0.grupbacaid = a0.grupbacaid
	left join peserta d0 on d0.pesertaid = b0.pesertaid
  ";

	public $sqlcount					 = "select count(1) 
	from penilaian a0 
	join penilaiandetail b0 on b0.penilaianid = a0.penilaianid
	left join grupbaca c0 on c0.grupbacaid = a0.grupbacaid
	left join peserta d0 on d0.pesertaid = b0.pesertaid
  ";
  

	public $sqlcountgroupmenu	 = "select count(1) 
	from penilaian a0 
	join penilaiandetail b0 on b0.penilaianid = a0.penilaianid
	left join grupbaca c0 on c0.grupbacaid = a0.grupbacaid
	left join peserta d0 on d0.pesertaid = b0.pesertaid
  ";

	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$penilaianid = filterinput(2, 'penilaianid');
        $namagrup		 = filterinput(2, 'namagrup');
        $nama		 = filterinput(2, 'nama');
        $where				 .= " where c0.namagrup like '%".$kodegrup."%'
        and d0.nama like '%".$nama."%'";
		if (($penilaianid !== '0') && ($penilaianid !== '')) {
			$where .= " and a0.penilaianid in (".$penilaianid.")";
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
			'keyField' => 'penilaianid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'penilaianid', 'penilaiandetailid','grupbacaid', 'namagrup', 'nama', 'startdate', 'enddate', 'notes', 'notesdetail','rating'
				),
				'defaultOrder' => array(
					'penilaianid' => CSort::SORT_DESC
				),
			),
		));
	$penilaianid = filterinput(1, 'penilaianid',FILTER_SANITIZE_NUMBER_INT);
	
    if ($penilaianid > 0) {
      $this->sqlcountgroupmenu .= ' where a0.penilaianid = '.$penilaianid;
      $this->sqldatagroupmenu	 .= ' where a0.penilaianid = '.$penilaianid;
    }
		$countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
		$dataProvidergroupmenu = new CSqlDataProvider($this->sqldatagroupmenu,
			array(
			'totalItemCount' => $countgroupmenu,
			'keyField' => 'penilaianid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
        'attributes' => array(
					'penilaianid'
				),
				'defaultOrder' => array(
					'penilaianid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index',
		array('dataProvider' => $dataProvider, 'dataProvidergroupmenu' => $dataProvidergroupmenu));
	}
	public function actionCreate() {
		parent::actionCreate();
		$penilaianid = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'status' => 'success',
			'penilaianid' => $penilaianid,
		));
	}
	public function actionCreategruppenilaian() {
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.penilaianid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'penilaianid' => $model['penilaianid'],
				'grupbacaid' => $model['grupbacaid'],
				'startdate' => $model['startdate'],
				'enddate' => $model['enddate'],
				'notes' => $model['notes'],
				'namagrup' => $model['namagrup'],
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
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where a0.penilaianid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'penilaianid' => $model['penilaianid'],
				'pesertaid' => $model['pesertaid'],
				'rating' => $model['rating'],
				'notes' => $model['notes'],
				'nama' => $model['nama'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('grupbacaid', 'string', 'emptygrupbacaid'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'penilaianid',
				array(
				array(':penilaianid', 'penilaianid', PDO::PARAM_STR),
				array(':grupbacaid', 'grupbacaid', PDO::PARAM_STR),
				array(':startdate', 'startdate', PDO::PARAM_STR),
				array(':enddate', 'enddate', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				),
				'insert into penilaian (penilaianid,grupbacaid,startdate,enddate,notes)
				values (:penilaianid,:grupbacaid,:startdate,:enddate,:notes)'
			  ,
			   'insert into penilaian (penilaianid,grupbacaid,startdate,enddate,notes)
				values (:penilaianid,:grupbacaid,:startdate,:enddate,:notes)'
			  );
		}
	}
	public function actionSavegruppenilaian() {
		parent::actionSave();
		$error = ValidateData(array(
			array('penilaianid', 'string', 'emptypenilaianid'),
			array('pesertaid', 'string', 'emptypesertaid'),
            array('rating', 'string', 'emptyrating'),
            array('notes', 'string', 'emptynotes'),
		));
		if ($error == false) {
			
			ModifyCommand(1, $this->menuname, 'penilaianid',
				array(
                array(':penilaianid', 'penilaianid', PDO::PARAM_STR),
				array(':pesertaid', 'pesertaid', PDO::PARAM_STR),
				array(':rating', 'rating', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				),
				'insert into penilaiandetail (penilaianid,pesertaid,rating,notes)
			      values (:penilaianid,:pesertaid,:rating,:notes)',
                'update penilaiandetail
                  set pesertaid = :pesertaid,rating = :rating,notes = :notes
			      where penilaianid = :penilaianid');
				
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
        $sql		 = "select recordstatus from penilaian where penilaianid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update penilaian set recordstatus = 0 where penilaianid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update penilaian set recordstatus = 1 where penilaianid = ".$id;
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
        $sql = "delete from penilaiandetailid where penilaianid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
	  }
	  
	  foreach ($ids as $id) {
        $sql = "delete from penilaian where penilaianid = ".$id;
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
        $sql = "delete from penilaiandetail where penilaiandetailid = ".$id;
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
		$this->pdf->colheader			 = array(getCatalog('penilaianid'), getCatalog('namagrup'),
			getCatalog('startdate'), getCatalog('enddate'), getCatalog('notes'), getCatalog('nama'), getCatalog('rating'),getCatalog('notesdetail'));
		$this->pdf->setwidths(array(20, 20, 30, 30, 15, 20, 25, 20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['penilaianid'], $row1['namagrup'], $row1['startdate'],$row1['enddate'],
			$row1['notes'],$row1['nama'],$row1['rating'],$row1['notesdetail']));
		}
		$this->pdf->Output();
	}
}