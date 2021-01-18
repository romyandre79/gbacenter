<?php
class PenilaianhostController extends AdminController {
	protected $menuname				 = 'penilaianhost';
	public $module						 = 'gbacenter';
	protected $pageTitle			 = 'Penilaianhost';
	public $wfname						 = '';
	public $sqldata						 = "select a0.penilaianid,a0.grupbacaid,a0.startdate,a0.enddate, a0.notes,
	b0.kodegrup, b0.namagrup
	from penilaian a0 
	left join grupbaca b0 on b0.grupbacaid = a0.grupbacaid
  ";
  
  
	public $sqldatagroupmenu	 = "select
	a0.penilaiandetailid, a0.penilaianid, a0.pesertaid, a0.rating, a0.notes,
	b0.nama
	from penilaiandetail a0 
	left join peserta b0 on b0.pesertaid = a0.pesertaid
  ";

	public $sqlcount					 = "select count(1) 
	from penilaian a0 
	left join grupbaca b0 on b0.grupbacaid = a0.grupbacaid
  ";
  

	public $sqlcountgroupmenu	 = "select count(1) 
	from penilaiandetail a0 
	left join peserta b0 on b0.pesertaid = a0.pesertaid
  ";

	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$penilaianid = filterinput(2, 'penilaianid');
        $kodegrup		 = filterinput(2, 'kodegrup');
        $namagrup		 = filterinput(2, 'namagrup');
        $where				 .= " where b0.kodegrup like '%".$kodegrup."%'
        and b0.namagrup like '%".$namagrup."%'";
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
					'penilaianid', 'penilaiandetailid','grupbacaid', 'kodegrup', 'namagrup', 'startdate', 'enddate', 'notes'
				),
				'defaultOrder' => array(
					'penilaianid' => CSort::SORT_DESC
				),
			),
		));


	if (isset($_REQUEST['penilaianid'])) {
		$penilaianid = $_REQUEST['penilaianid'];
		$this->sqlcountgroupmenu .= ' where a0.penilaianid = '.$penilaianid;
		$this->sqldatagroupmenu	 .= ' where a0.penilaianid = '.$penilaianid;
		$countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
    }
		
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
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where a0.penilaiandetailid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'penilaiandetailid' => $model['penilaiandetailid'],
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
				array(':actiontype', 'actiontype', PDO::PARAM_STR),
				array(':grupbacaid', 'grupbacaid', PDO::PARAM_STR),
				array(':startdate', 'startdate', PDO::PARAM_STR),
				array(':enddate', 'enddate', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				array(':vcreatedby', 'vcreatedby', PDO::PARAM_STR),
				),
				'call InsertPenilaian (:actiontype
					,:penilaianid
					,:grupbacaid
					,:startdate
					,:enddate
					,:notes
					,:vcreatedby)',
			    'call InsertPenilaian (:actiontype
					,:penilaianid
					,:grupbacaid
					,:startdate
					,:enddate
					,:notes
					,:vcreatedby)');
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
				array(':penilaiandetailid', 'penilaiandetailid', PDO::PARAM_STR),
				array(':actiontype2', 'actiontype2', PDO::PARAM_STR),
                array(':penilaianid', 'penilaianid', PDO::PARAM_STR),
				array(':pesertaid', 'pesertaid', PDO::PARAM_STR),
				array(':rating', 'rating', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				array(':vcreatedby', 'vcreatedby', PDO::PARAM_STR),
				),
				'call InsertPenilaianDetail (:actiontype2
					,:penilaiandetailid
					,:penilaianid
					,:pesertaid
					,:rating
					,:notes
					,:vcreatedby)',
					'call InsertPenilaianDetail (:actiontype2
					,:penilaiandetailid
					,:penilaianid
					,:pesertaid
					,:rating
					,:notes
					,:vcreatedby)');
				
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
        $sql = "delete from penilaiandetail where penilaianid = ".$id;
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

		foreach ($dataReader as $row1) {
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C');
			$this->pdf->colheader			 = array(getCatalog('penilaianid'), getCatalog('namagrup'),
				getCatalog('startdate'), getCatalog('enddate'), getCatalog('notes'));
			$this->pdf->setwidths(array(20, 60, 30, 30, 80));
			$this->pdf->Rowheader();
			$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L');
			$this->pdf->row(array($row1['penilaianid'], $row1['namagrup'], $row1['startdate'],$row1['enddate'],
			$row1['notes']));

				$sql2 = "select a0.penilaiandetailid,a0.penilaianid,a0.rating,a0.notes,
				b0.aliasid, b0.nama
				from penilaiandetail a0 
				left join peserta b0 on b0.pesertaid = a0.pesertaid
				where penilaianid = ".$row1['penilaianid'];
				$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
				$this->pdf->sety($this->pdf->gety() + 7);
				$this->pdf->colalign			 = array('C', 'C', 'C', 'C');
				$this->pdf->colheader			 = array(getCatalog('aliasid'), getCatalog('nama'), getCatalog('rating')
				, getCatalog('notes'));
				$this->pdf->setwidths(array(30, 50, 30, 80));
				$this->pdf->Rowheader();
				$this->pdf->coldetailalign = array('L', 'L', 'L', 'L');
				foreach ($dataReader2 as $row2) {
					$this->pdf->row(array($row2['aliasid'],$row2['nama'],$row2['rating'],$row2['notes']));
				}	
				
		}


		$this->pdf->Output();
	}

	

}