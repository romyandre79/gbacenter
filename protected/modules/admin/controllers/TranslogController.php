<?php
class TranslogController extends AdminController {
	protected $menuname	 = 'translog';
	public $module			 = 'Admin';
	protected $pageTitle = 'Catatan Transaksi';
	public $wfname			 = '';
	public $sqldata			 = "select a0.translogid,a0.username,a0.createddate,a0.useraction,a0.newdata,a0.menuname,a0.tableid 
    from translog a0 
  ";
	public $sqlcount		 = "select count(1) 
    from translog a0 
  ";
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		$translogid	 = filterinput(2, 'translogid', FILTER_SANITIZE_STRING);
		$username		 = filterinput(2, 'username', FILTER_SANITIZE_STRING);
		$useraction	 = filterinput(2, 'useraction', FILTER_SANITIZE_STRING);
		$menuname		 = filterinput(2, 'menuname', FILTER_SANITIZE_STRING);
		$where			 .= " where coalesce(a0.username,'') like '%".$username."%'
			and coalesce(a0.useraction,'') like '%".$useraction."%'
			and coalesce(a0.menuname,'') like '%".$menuname."%'";
		if (($translogid !== '0') && ($translogid !== '')) {
			$where .= " and a0.translogid in (".$translogid.")";
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
			'keyField' => 'translogid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'translogid', 'username', 'createddate', 'useraction', 'newdata', 'menuname',
					'tableid'
				),
				'defaultOrder' => array(
					'translogid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
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
        $sql = "delete from translog where translogid = ".$id;
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
		$this->pdf->title					 = getCatalog('translog');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('translogid'), getCatalog('username'),
			getCatalog('createddate'), getCatalog('useraction'), getCatalog('newdata'),
			getCatalog('menuname'), getCatalog('table'));
		$this->pdf->setwidths(array(10, 20, 20, 20, 60, 30, 20));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['translogid'], $row1['username'],
				Yii::app()->format->formatDateTime($row1['createddate']), $row1['useraction'],
				$row1['newdata'], $row1['menuname'], $row1['tableid']));
		}
		$this->pdf->Output();
	}
}