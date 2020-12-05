<?php
class CurrencyController extends AdminController {
	protected $menuname	 = 'currency';
	public $module			 = 'Admin';
	protected $pageTitle = 'Mata Uang';
	public $wfname			 = '';
	public $sqldata			 = "select a0.currencyid,a0.countryid,a0.currencyname,a0.symbol,a0.i18n,a0.recordstatus,a1.countryname as countryname 
    from currency a0 
    left join country a1 on a1.countryid = a0.countryid
  ";
	public $sqlcount		 = "select count(1) 
    from currency a0 
    left join country a1 on a1.countryid = a0.countryid
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$currencyid		 = filterinput(2, 'currencyid');
		$currencyname	 = filterinput(2, 'currencyname');
		$symbol				 = filterinput(2, 'symbol');
		$i18n					 = filterinput(2, 'i18n');
		$countryname	 = filterinput(2, 'countryname');
		$where				 .= " where coalesce(a0.currencyname,'') like '%".$currencyname."%' 
      and coalesce(a0.symbol,'') like '%".$symbol."%' 
      and coalesce(a0.i18n,'') like '%".$i18n."%' 
      and coalesce(a1.countryname,'') like '%".$countryname."%'";
		if (($currencyid !== '0') && ($currencyid !== '')) {
			$where .= " and a0.currencyid in (".$currencyid.")";
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
			'keyField' => 'currencyid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'currencyid', 'countryid', 'currencyname', 'symbol', 'i18n', 'recordstatus'
				),
				'defaultOrder' => array(
					'currencyid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
	}
	public function actionCreate() {
		parent::actionCreate();
		echo CJSON::encode(array(
			'status' => 'success',
			"currencyid" => getparameter("basecurrencyid"),
			"currencyname" => getparameter("basecurrency")
		));
	}
	public function actionUpdate() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldata.' where currencyid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'currencyid' => $model['currencyid'],
				'countryid' => $model['countryid'],
				'currencyname' => $model['currencyname'],
				'symbol' => $model['symbol'],
				'i18n' => $model['i18n'],
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
			array('currencyname', 'string', 'emptycurrencyname'),
			array('symbol', 'string', 'emptysymbol'),
			array('i18n', 'string', 'emptyi18n'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'currencyid',
				array(
				array(':currencyid', 'currencyid', PDO::PARAM_STR),
				array(':countryid', 'countryid', PDO::PARAM_STR),
				array(':currencyname', 'currencyname', PDO::PARAM_STR),
				array(':symbol', 'symbol', PDO::PARAM_STR),
				array(':i18n', 'i18n', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into currency (countryid,currencyname,symbol,i18n,recordstatus)
			      values (:countryid,:currencyname,:symbol,:i18n,:recordstatus)',
				'update currency
			      set countryid = :countryid,currencyname = :currencyname,symbol = :symbol,i18n = :i18n,recordstatus = :recordstatus
			      where currencyid = :currencyid');
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
        $sql		 = "select recordstatus from currency where currencyid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update currency set recordstatus = 0 where currencyid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update currency set recordstatus = 1 where currencyid = ".$id;
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
        $sql = "delete from currency where currencyid = ".$id;
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
		$this->pdf->title					 = getCatalog('currency');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('currencyid'), getCatalog('country'),
			getCatalog('currencyname'), getCatalog('symbol'), getCatalog('i18n'),
			getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10, 40, 40, 40, 40, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['currencyid'], $row1['countryname'], $row1['currencyname'],
				$row1['symbol'],
				$row1['i18n'], (($row1['recordstatus'] == 1) ? 'Active' : 'NotActive')));
		}
		$this->pdf->Output();
	}
}