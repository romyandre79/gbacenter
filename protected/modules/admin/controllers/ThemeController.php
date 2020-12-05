<?php
class ThemeController extends AdminController {
	protected $menuname	 = 'theme';
  public $module			 = 'admin';
  public $sqldata			 = "select a.themeid,a.themename,a.description,a.isadmin,a.recordstatus,a.createdby,a.themeversion,a.installdate 
  from theme a 
";
public $sqlcount		 = "select count(1) 
  from theme a 
";
public function getSQL() {
  $this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
  $where			 = "";
  $themename	 = filterinput(2, 'themename', FILTER_SANITIZE_STRING);
  $description = filterinput(2, 'description', FILTER_SANITIZE_STRING);
  $themeversion = filterinput(2, 'themeversion', FILTER_SANITIZE_STRING);
  $where			 .= " where coalesce(a.themename,'') like '%".$themename."%'
    and coalesce(a.description,'') like '%".$description."%'
    and coalesce(a.themeversion,'') like '%".$themeversion."%'"
    ;
  $this->sqldata = $this->sqldata.$where;
  $this->count	 = Yii::app()->db->createCommand($this->sqlcount.$where)->queryScalar();
}
	public function actionIndex() {
		parent::actionIndex();
    $this->pageTitle = "Theme";
    $this->getSQL();
		$dataProvider		 = new CSqlDataProvider($this->sqldata,
			array(
			'totalItemCount' => $this->count,
			'keyField' => 'themeid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'moduleid', 'themename', 'description',
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
	}
	public function actionUninstall() {
		$sql				 = "select themename 
			from theme a 
			where a.themeid = ".filterinput(1,'theme',FILTER_SANITIZE_NUMBER_INT);
		$dependency	 = new CDbCacheDependency('SELECT count(1) FROM theme');
		$datas			 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
		$a					 = $datas['themename'];
		$sql				 = file_get_contents(dirname('__FILES__').'/themes/'.$a.'/uninstall.sql');
		try {
			Yii::app()->db->createCommand($sql)->execute();
			rrmdir(dirname('__FILES__').'/themes/'.$a);
			getMessage('success', 'alreadyuninstall');
		} catch (CException $e) {
			getMessage('error', $e->getMessage());
		}
		$this->redirect(Yii::app()->request->urlReferrer);
	}
	public function actionInstall() {
		if (!empty($_FILES)) {
			$storeFolder = dirname('__FILES__').'/uploads/';
			$tempFile		 = $_FILES['upload']['tmp_name'];
			$targetFile	 = $storeFolder.$_FILES['upload']['name'];
			move_uploaded_file($tempFile, $targetFile);
			$zip				 = new ZipArchive;
			if ($zip->open($storeFolder.$_FILES['upload']['name']) === TRUE) {
				$zip->extractTo(dirname('__FILES__').'/themes/');
				$zip->close();
				unlink($storeFolder.$_FILES['upload']['name']);
				$s	 = basename($_FILES['upload']['name'], ".zip");
				$sql = file_get_contents(dirname('__FILES__').'/themes/'.$s.'/install.sql');
				Yii::app()->db->createCommand($sql)->execute();
				getMessage('success', 'alreadyinstalled');
			} else {
				getMessage('error', 'themeserror');
			}
		}
	}
	public function actionActivate() {
		try {
      $status = filterinput(1, 'status');
      $theme = filterinput(1, 'themeid');
			if (strtolower($status) === 'active') {
				$sql = "update theme set recordstatus = 0 where themeid = ".$theme;
				Yii::app()->db->createCommand($sql)->execute();
			} else {
				$sql				 = "select isadmin 
					from theme a 
					where a.themeid = ".$theme;
				$s					 = Yii::app()->db->createCommand($sql)->queryRow();
				$sql				 = "update theme set recordstatus = 0 where isadmin = ".$s['isadmin'];
				Yii::app()->db->createCommand($sql)->execute();
				$sql				 = "update theme set recordstatus = 1 where isadmin = ".$s['isadmin']." and themeid = ".$theme;
				Yii::app()->db->createCommand($sql)->execute();
			}
			Yii::app()->user->setFlash('info', 'Your data already saved');
			$this->redirect(Yii::app()->request->urlReferrer);
		} catch (CExcpetion $e) {
			Yii::app()->user->setFlash('error', $e->getMessage());
		}
	}
}