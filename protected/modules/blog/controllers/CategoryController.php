<?php
class CategoryController extends AdminController {
	protected $menuname	 = "category";
	protected $pageTitle = "Category";
	public $module			 = 'blog';
	public $sqldata	 = 'select a.categoryid,a.title,a.parentid,b.title as parenttitle, a.description,a.slug,a.recordstatus 
		from category a
		left join category b on b.categoryid = a.parentid ';
	public $sqlcount	 = 'select count(1) 
		from category a
		left join category b on b.categoryid = a.parentid ';
	public function actionRead() {
		//getTheme(false, $this->module);
		$this->pageTitle = 'Category';
		$sql						 = "select categoryid,title,description ".
			" from category a ".
			" where lower(slug) = lower('".$_GET['name']."')";
		$dependency			 = new CDbCacheDependency('SELECT MAX(categoryid) FROM category');
		$menu						 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
		$post;
		if ($menu['categoryid'] > 0) {
			$sql1 = "select distinct a.title,a.description,a.slug,a.postupdate,a.metatag,b.username,c.categoryid ".
				" from post a ".
				" inner join useraccess b on b.useraccessid = a.useraccessid ".
				" inner join postcategory c on c.postid = a.postid ".
				" where c.categoryid in (".$menu['categoryid'].")";
		} else {
			$sql1 = "select distinct a.title,a.description,a.slug,a.postupdate,a.metatag,b.username,c.categoryid ".
				" from post a ".
				" inner join useraccess b on b.useraccessid = a.useraccessid ".
				" inner join postcategory c on c.postid = a.postid ";
		}
		$dependency1 = new CDbCacheDependency('SELECT MAX(postid) FROM post');
		$post				 = Yii::app()->db->cache(1000, $dependency1)->createCommand($sql1)->queryAll();
		$this->render('read',
			array('title' => $menu['title'], 'description' => $menu['description'], 'posts' => $post));
	}
	public function actionIndex() {
		parent::actionIndex();
		$count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		if (isset($_REQUEST['title']) && isset($_REQUEST['description'])) {
			$where				 = " where a.title like '%".$_REQUEST['title']."%' 
				and a.description like '%".$_REQUEST['title']."%'";
			$this->sqldata = $this->sqldata.$where;
			$count				 = Yii::app()->db->createCommand($this->sqlcount.$where)->queryScalar();
		}
		$dataProvider = new CSqlDataProvider($this->sqldata,
			array(
			'totalItemCount' => $count,
			'keyField' => 'categoryid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'categoryid', 'title', 'description'
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
  }
	public function actionCreate() {
		parent::actionCreate();
		echo CJSON::encode(array(
			'status' => 'success',
			'allcategory' => getAllCategory()
		));
	}
	public function actionUpdate() {
		parent::actionUpdate();
		$data = Yii::app()->db->createCommand($this->sqldata.
				' where a.categoryid = '.$_POST['id'])->queryRow();
		echo CJSON::encode(array(
			'status' => 'success',
			'data' => $data,
			'allcategory' => getAllCategory()
		));
	}
	public function actionSave() {
		parent::actionSave();
		if (!isset($_POST['categoryid'])) {
			getMessage('error', 'emptycategoryid');
		} else
		if ($_POST['title'] == '') {
			getMessage('error', 'emptytitle');
		} else
		if ($_POST['description'] == '') {
			getMessage('error', 'emptydescription');
		} else
		if ($_POST['slug'] == '') {
			getMessage('error', 'emptyslug');
		} else {
			$id = $_POST['categoryid'];
			if ($id !== '') {
				$sql = "update category set title = '".$_POST['title'].
					"', parentid = ".(($_POST['parentid'] == '') ? 'null' : $_POST['parentid']).
					", description = '".$_POST['description'].
					"', slug = '".$_POST['slug'].
					"' where categoryid = ".$id;
			} else {
				$sql = "insert into category (title,parentid,description,slug,recordstatus) 
					values ('".$_POST['title']."',".(($_POST['parentid'] == '') ? 'null' : $_POST['parentid']).",'".$_POST['description']."',
					'".$_POST['slug']."',1)";
			}
			$connection	 = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				$command = $connection->createCommand($sql);
				$command->execute();
				Inserttranslog($command, $id, $this->menuname);
				$transaction->commit();
				getMessage('success', 'alreadysaved');
			} catch (Exception $e) {
				$transaction->rollBack();
				getMessage('error', $e->getMessage());
			}
		}
	}
	public function actionDelete() {
		parent::actionDelete();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			if (isset($_POST['id'])) {
				$sql		 = "select recordstatus from category where categoryid = ".(int) $_POST['id'];
				$status	 = Yii::app()->db->createCommand($sql)->queryRow();
				if ($status['recordstatus'] == 1) {
					$sql = "update category set recordstatus = 0 where categoryid = ".(int) $_POST['id'];
				} else
				if ($status['recordstatus'] == 0) {
					$sql = "update category set recordstatus = 1 where categoryid = ".(int) $_POST['id'];
				}
			}
			$connection->createCommand($sql)->execute();
			$transaction->commit();
			getMessage('success', 'alreadysaved');
		} catch (Exception $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$sql = "delete from category where categoryid = ".$_POST['id'];
			Yii::app()->db->createCommand($sql)->execute();
			$transaction->commit();
			getMessage('success', 'alreadysaved');
		} catch (Exception $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionUpload() {
		if (!empty($_FILES)) {
			$storeFolder = dirname('__FILES__').'/uploads/';
			$tempFile		 = $_FILES['upload']['tmp_name'];
			$targetFile	 = $storeFolder.$_FILES['upload']['name'];
			move_uploaded_file($tempFile, $targetFile);
			if (($handle			 = fopen($storeFolder.$_FILES['upload']['name'], "r")) !== FALSE) {
				$s					 = getparameter('csvformat');
				$row				 = 1;
				$connection	 = Yii::app()->db;
				$transaction = $connection->beginTransaction();
				try {
					while (($data = fgetcsv($handle, 2000, $s)) !== FALSE) {
						if ($row > 1) {
							$sql = "replace into category (title,parentid,description,slug,recordstatus) 
								values ('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."',".$data[4].")";
							$connection->createCommand($sql)->execute();
						}
						$row++;
					}
					$transaction->commit();
				} catch (Exception $e) {
					$transaction->rollBack();
					getMessage('error', $e->getMessage());
				}
			}
		}
	}
	public function actionDownPDF() {
		parent::actionDownPDF();
		//masukkan perintah download
		if (isset($_REQUEST['title']) && isset($_REQUEST['description']) && isset($_REQUEST['slug'])) {
			$where				 = " where a.title like '%".$_REQUEST['title']."%' 
				and a.description like '%".$_REQUEST['description']."%' 
				and a.slug like '%".$_REQUEST['slug']."%'";
			$this->sqldata = $this->sqldata.$where;
			$count				 = Yii::app()->db->createCommand($this->sqlcount.$where)->queryScalar();
		}
		$dataReader = Yii::app()->db->createCommand($this->sqldata)->queryAll();

		//masukkan judul
		$this->pdf->title					 = getCatalog('category');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('title'), getCatalog('description'),
			getCatalog('slug'));
		$this->pdf->setwidths(array(60, 60, 60));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L');

		foreach ($dataReader as $row1) {
			//masukkan baris untuk cetak
			$this->pdf->row(array($row1['title'], $row1['description'], $row1['slug']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
}