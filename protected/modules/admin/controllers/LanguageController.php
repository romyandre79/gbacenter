<?php
class LanguageController extends AdminController {
	protected $menuname	 = 'language';
	public $module			 = 'Admin';
	protected $pageTitle = 'Bahasa';
	public $wfname			 = '';
	public $sqldata			 = "select a0.languageid,a0.languagename,a0.recordstatus
    from language a0 
  ";
	public $sqlcount		 = "select count(1)
    from language a0 
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$languageid		 = filterinput(2, 'languageid');
		$languagename	 = filterinput(2, 'languagename');
		$where				 .= " where coalesce(a0.languagename,'') like '%".$languagename."%'";
		if (($languageid !== '0') && ($languageid !== '')) {
			$where .= " and a0.languageid in (".$languageid.")";
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
			'keyField' => 'languageid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'languageid', 'languagename', 'recordstatus'
				),
				'defaultOrder' => array(
					'languageid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where languageid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'languageid' => $model['languageid'],
				'languagename' => $model['languagename'],
				'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('languagename', 'string', 'emptylanguagename'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'languageid',
				array(
				array(':languageid', 'languageid', PDO::PARAM_STR),
				array(':languagename', 'languagename', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into language (languagename,recordstatus)
			      values (:languagename,:recordstatus)',
				'update language
			      set languagename = :languagename,recordstatus = :recordstatus
			      where languageid = :languageid');
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
        $sql		 = "select recordstatus from language where languageid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update language set recordstatus = 0 where languageid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update language set recordstatus = 1 where languageid = ".$id;
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
        $sql = "delete from language where languageid = ".$id[$i];
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
		$this->pdf->title					 = getCatalog('language');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('languageid'), getCatalog('languagename'),
			getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10, 170, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'c');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['languageid'], $row1['languagename'],
				(($row1['recordstatus'] == 1) ? 'Active' : 'NotActive')));
		}
		$this->pdf->Output();
	}
}