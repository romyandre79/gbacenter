<?php
class SlideshowController extends AdminController {
	protected $menuname	 = 'slideshow';
	public $module			 = 'Blog';
	protected $pageTitle = 'slideshow';
	public $wfname			 = '';
	public $sqldata	 = "select a0.slideshowid,a0.slidepic,a0.slidetitle,a0.slidedesc,a0.slideurl 
    from slideshow a0 
  ";
	public $sqlcount	 = "select count(1) 
    from slideshow a0 
  ";
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		if ((isset($_REQUEST['slidepic'])) && (isset($_REQUEST['slidetitle']))) {
			$where .= " where coalesce(a0.slidepic,'') like '%".$_REQUEST['slidepic']."%' 
and coalesce(a0.slidetitle,'') like '%".$_REQUEST['slidetitle']."%'";
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
			'keyField' => 'slideshowid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'slideshowid', 'slidepic', 'slidetitle', 'slidedesc'
				),
				'defaultOrder' => array(
					'slideshowid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
	}
	public function actionUpload() {
		if (!file_exists(Yii::getPathOfAlias('webroot').'/images/slideshow')) {
			mkdir(Yii::getPathOfAlias('webroot').'/images/slideshow');
		}
		$this->storeFolder = dirname('__FILES__').'/images/slideshow/';
		parent::actionUpload();
		echo $_FILES['upload']['name'];
	}
	public function actionCreate() {
		parent::actionCreate();

		echo CJSON::encode(array(
			'status' => 'success',
		));
	}
	public function actionUpdate() {
		parent::actionUpdate();
		if (isset($_POST['id'])) {
      $id		 = $_POST['id'];
      $sql			 = "select a.categoryid 
        from slideshowcategory a  
        where a.slideshowid = ".$id[0];
      $category	 = Yii::app()->db->createCommand($sql)->queryAll();
			$model = Yii::app()->db->createCommand($this->sqldata.' where slideshowid = '.$id[0])->queryRow();
			if ($model !== null) {
				echo CJSON::encode(array(
					'status' => 'success',
					'slideshowid' => $model['slideshowid'],
					'slidepic' => $model['slidepic'],
					'slidetitle' => $model['slidetitle'],
					'slidedesc' => $model['slidedesc'],
          'slideurl' => $model['slideurl'],
          'category' => $category,
          'allcategory'=>getAllCategory()
				));
				Yii::app()->end();
			}
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('slidepic', 'string', 'emptyslidepic'),
			array('slidetitle', 'string', 'emptyslidetitle'),
		));
		if ($error == false) {
			$slideid					 = $_POST['slideshowid'];
			$connection	 = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				if ($slideid !== '') {
					$sql = 'update slideshow 
			      set slidepic = :slidepic,slidetitle = :slidetitle,slidedesc = :slidedesc,slideurl = :slideurl 
			      where slideshowid = :slideshowid';
				} else {
					$sql = 'insert into slideshow (slidepic,slidetitle,slidedesc,slideurl) 
			      values (:slidepic,:slidetitle,:slidedesc,:slideurl)';
				}
				$command = $connection->createCommand($sql);
				if ($slideid !== '') {
					$command->bindvalue(':slideshowid', $_POST['slideshowid'], PDO::PARAM_STR);
				}
				$command->bindvalue(':slidepic',
					(($_POST['slidepic'] !== '') ? $_POST['slidepic'] : null), PDO::PARAM_STR);
				$command->bindvalue(':slidetitle',
					(($_POST['slidetitle'] !== '') ? $_POST['slidetitle'] : null),
					PDO::PARAM_STR);
				$command->bindvalue(':slidedesc',
					(($_POST['slidedesc'] !== '') ? $_POST['slidedesc'] : null), PDO::PARAM_STR);
				$command->bindvalue(':slideurl',
					(($_POST['slideurl'] !== '') ? $_POST['slideurl'] : null), PDO::PARAM_STR);
				$command->execute();
				$transaction->commit();
        Inserttranslog($command, $slideid, $this->menuname);
        
        if ($slideid == '') {
					$sql		 = "select slideshowid from slideshow where slidetitle = '".$_POST['slidetitle']."'";
					$id			 = Yii::app()->db->createCommand($sql)->queryRow();
					$slideid	 = $id['slideshowid'];
				}
				$i = 0;
				if (count($_POST) > 2) {
					$sql = "delete from slideshowcategory where slideshowid = ".$slideid;
					$connection->createCommand($sql)->execute();
					if (isset($_POST['category'])) {
						foreach ($_POST['category'] as $menu) {
							$sql = "insert into slideshowcategory (slideshowid,categoryid)
									values (".$slideid.",".$menu.")";
							$connection->createCommand($sql)->execute();
							InsertTransLog($command, $slideid);
						}
					}
				}
				getMessage('success', 'alreadysaved');
			} catch (CDbException $e) {
				$transaction->rollBack();
				getMessage('error', $e->getMessage());
			}
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			if (isset($_POST['id'])) {
				$id	 = $_POST['id'];
				$sql = "delete from slideshow where slideshowid = ".$id[0];
				Yii::app()->db->createCommand($sql)->execute();
				$transaction->commit();
				getMessage('success', 'alreadysaved');
			} else {
				getMessage('success', 'chooseone');
			}
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionDownPDF() {
		parent::actionDownPDF();
		$this->getSQL();
		$dataReader = Yii::app()->db->createCommand($this->sqldata)->queryAll();

		//masukkan judul
		$this->pdf->title					 = getCatalog('slideshow');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('slideshowid'), getCatalog('slidepic'),
			getCatalog('slidetitle'), getCatalog('slidedesc'),getCatalog('slideurl'));
		$this->pdf->setwidths(array(10, 40, 40, 40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L');

		foreach ($dataReader as $row1) {
			//masukkan baris untuk cetak
			$this->pdf->row(array($row1['slideshowid'], $row1['slidepic'], $row1['slidetitle'],
				$row1['slidedesc'],$row1['slideurl']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
}