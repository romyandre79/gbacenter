<?php

class MenugeneratorController extends AdminController {
	protected $menuname = 'menugenerator';	
	public $module = 'admin';	
	protected $pageTitle = 'Menu Generator';
	protected $sqldata = 'select a.* 
		from menugen a ';
	protected $sqlcount = 'select count(1) 
		from menugen a ';
		
  public function actions() {
    return [
      'connector' => [
        'class' => 'ext.elFinder.ElFinderConnectorAction',
        'settings' => [
          'roots' => [
            [
              'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
              'path'          => Yii::getPathOfAlias('webroot'),                 // path to files (REQUIRED)
              'URL'           => Yii::app()->baseUrl, // URL to files (REQUIRED)
              'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
              'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
              'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
              'uploadAllow'   => 'internal', // Mimetype `image` and `text/plain` allowed to upload
              'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
              'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
            ]
          ]
        ]
      ]
    ];
  }
	
	public function actionIndex() {
		parent::actionIndex();
		$this->count=Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$dataProvider=new CSqlDataProvider($this->sqldata,array(
			'totalItemCount'=>$this->count,
			'keyField'=>'menugenid',
			'pagination'=>array(
				'pageSize'=>getParameter('DefaultPageSize'),
				'pageVar'=>'page',
			),
			'sort'=>array(
				'attributes'=>array(
             'menugenid','tablename','sortorder'
        ),
        'defaultOrder' => array( 
					'menugenid' => CSort::SORT_ASC,
				),
			),
		));
		$this->render('index',array('dataProvider'=>$dataProvider));
	}
	
	public function actionReadfield() {
		$db = Yii::app()->db->createCommand('select database()')->queryScalar();
		$issearch = 0;
		$isvalidate = 0;
		$isinput = 0;
		$defaultvalue = "";
		Yii::app()->db->createCommand("truncate table menugen")->execute();
    if ($_POST['tablename'] !== '')
    {
      if ($db !== '')
      {
        try
        {
          $tables = explode(',',$_POST['tablename']);
          foreach ($tables as $table)
          {
            $koloms = Yii::app()->db->createCommand('SHOW COLUMNS FROM '.$table)->queryAll();
            $i=0;
            foreach ($koloms as $kolom)
            {
              $fktable = Yii::app()->db->createCommand("select TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME from information_schema.KEY_COLUMN_USAGE
                where TABLE_SCHEMA = '".$db."' and TABLE_NAME = '".$table."' and  COLUMN_NAME = '".$kolom['Field']."' 
                and referenced_column_name is not NULL;")->queryRow();
              if (strpos($kolom['Key'],'PRI') === 0)
              {
                $issearch = 0;
                $isvalidate = 0;
              }
              else
              if (strpos($kolom['Type'],'varchar') === 0)
              {
                $issearch = 1;
                $isvalidate = 1;
              }
              else
                if ($fktable['REFERENCED_TABLE_NAME'] !== null)
              {
                $issearch = 1;
                $isvalidate = 1;
              }
              else
              if ($kolom['Null'] == 'NO')
              {
                $issearch = 0;
                $isvalidate = 1;
              }
              else
              {
                $issearch = 0;
                $isvalidate = 0;
              }
              if ($_POST['iswrite'] == 1)
              {
                $isinput = 1;
              }
              else
              {
                $isinput = 0;
                $isvalidate = 0;
              }
              if (strpos($kolom['Field'],'currencyid') === 0)
              {
                $defaultvalue = "\"currencyid\" => \$this->GetParameter(\"basecurrencyid\"),
                  \"currencyname\" => \$this->GetParameter(\"basecurrency\")";
              }
              else
                if (strpos($kolom['Field'],'companyid') === 0)
              {
                $defaultvalue = "\"companyid\" => \$company[\"companyid\"],
                  \"companyname\" => \$company[\"companyname\"]";
              }
              else
                if (strpos($kolom['Type'],'datetime') === 0)
              {
                $defaultvalue = "\"".$kolom['Field']."\" =>date(\"Y-m-d H:i:s\")";
              }
              else
              if (strpos($kolom['Type'],'date') === 0)
              {
                $defaultvalue = "\"".$kolom['Field']."\" =>date(\"Y-m-d\")";
              }
              else
              if (strpos($kolom['Type'],'decimal') === 0)
              {
                $defaultvalue = "\"".$kolom['Field']."\" =>0";
              }
              else
              if ((strpos($kolom['Field'],'recordstatus') === 0) && ($_POST['ispost'] == 1))
              {
                $defaultvalue = "\"".$kolom['Field']."\" =>\$this->findstatusbyuser(\"".$_POST['inswf']."\")";
              }
              else
              {
                $defaultvalue = "";
              }
              
              if ($fktable['REFERENCED_TABLE_NAME'] !== null)
              {
                Yii::app()->db->createCommand("replace into menugen(namafield,tipefield,isview,issearch,isvalidate,tablename,tablerelation,tablefkname,
                    widgetrelation,sortorder,isinput,defaultvalue) 
                  values ('".$kolom['Field']."','".$kolom['Type']."',1,".$issearch.",".$isvalidate.",'".$table."','".$fktable['REFERENCED_TABLE_NAME']."','".$fktable['REFERENCED_COLUMN_NAME']."',
                    '[fillmodule].components.views.[fillpopup]',".$i.",".$isinput.",'".$defaultvalue."')")->execute();
              }
              else
              {
                Yii::app()->db->createCommand("replace into menugen(namafield,tipefield,isview,issearch,isvalidate,tablename,tablerelation,tablefkname,sortorder,isinput,
                  defaultvalue) 
                  values ('".$kolom['Field']."','".$kolom['Type']."',1,".$issearch.",".$isvalidate.",'".$table."','".$fktable['REFERENCED_TABLE_NAME']."','".$fktable['REFERENCED_COLUMN_NAME']."',".$i.",".$isinput.",
                  '".$defaultvalue."')")->execute();
              }
              $i++;
            }
          }
          getmessage('success','alreadysaved');
        }
        catch (CDbException $e)
        {
          getmessage('error',$e->getMessage());
        }
      }
    }
    else
    {
      getmessage('error','tablenamemustentry');
    }
	}
	
	public function actionUpdate()
	{
		parent::actionUpdate();
		if (isset($_POST['id']))
		{
			$id= $_POST['id'];
			$model = Yii::app()->db->createCommand($this->sqldata." where a.menugenid = '".((is_array($id))?$id[0]:$id)."'")->queryRow();
			if ($model !== null)
			{
				echo CJSON::encode(array(
					'status'=>'success',
					'namafield'=>$model['namafield'],
					'menugenid'=>$model['menugenid'],
					'tipefield'=>$model['tipefield'],
					'isview'=>$model['isview'],
					'issearch'=>$model['issearch'],
					'isvalidate'=>$model['isvalidate'],
					'isinput'=>$model['isinput'],
					'isprint'=>$model['isprint'],
					'widgetrelation'=>$model['widgetrelation'],
					'relationname'=>$model['relationname'],
					'wirelsource'=>$model['wirelsource'],
					'tablerelation'=>$model['tablerelation'],
					'tablefkname'=>$model['tablefkname'],
					'popupname'=>$model['popupname'],
					'defaultvalue'=>$model['defaultvalue'],
					));
				Yii::app()->end();
			}
		}
	}
	
	public function actionPurge()
	{
		parent::actionUpdate();
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			if (isset($_POST['id']))
			{
				$id = $_POST['id']; if (!is_array($id)) { $ids[] = $id; $id = $ids; }
				for ($i = 0; $i < count($_POST['id']);$i++)
				{
				$sql = "delete from menugen where menugenid = ".$id[$i];
				Yii::app()->db->createCommand($sql)->execute();
				}
				$transaction->commit();
				getmessage('success','alreadysaved');
			}
		}
		catch (CDbException $e)
		{
			$transaction->rollback();
			getmessage('error',$e->getMessage());
		}
	}
	
	public function actionSave()
	{
		parent::actionSave();
		$error = $this->ValidateData(array(
			array('namafield','string','emptynamafield'),
			array('tipefield','string','emptytipefield'),
		));
		if ($error == false)
		{
			$id = $_POST['menugenid'];
			if ($id !== '')
			{
				$sql = "update menugen set isview = ".$_POST['isview']."
					, widgetrelation = ".(($_POST['widgetrelation']!=='')?("'".$_POST['widgetrelation']."'"):"''")."
					, isinput = '".$_POST['isinput']."'
					, issearch = '".$_POST['issearch']."'
					, isvalidate = '".$_POST['isvalidate']."'
					, isprint = '".$_POST['isprint']."'
					, wirelsource = '".$_POST['wirelsource']."'
					, relationname = '".$_POST['relationname']."'
					, defaultvalue = '".$_POST['defaultvalue']."'
					, tablerelation = '".$_POST['tablerelation']."'
					, tablefkname = '".$_POST['tablefkname']."'
					, popupname = '".$_POST['popupname']."'
					, tipefield = '".$_POST['tipefield']."'
					, namafield = '".$_POST['namafield']."'
						where menugenid = '".$id."'";
			}
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$connection->createCommand($sql)->execute();
				$transaction->commit();
				getmessage('success','alreadysaved');
			}
			catch (CDbException $e)
			{
				$transaction->rollBack();
				getmessage('error',$e->getMessage());
			}
		}
	}
	
	protected function replace_in_file($FilePath, $OldText, $NewText)
	{
		$Result = array('status' => 'error', 'message' => '');
		if(file_exists($FilePath)===TRUE)
		{
			if(is_writeable($FilePath))
			{
				try
				{
					$FileContent = file_get_contents($FilePath);
					$FileContent = str_replace($OldText, $NewText, $FileContent);
					if(file_put_contents($FilePath, $FileContent) > 0)
					{
							$Result["status"] = 'success';    
					}
					else
					{
						 $Result["message"] = 'Error while writing file'; 
					}
				}
				catch(Exception $e)
				{
						$Result["message"] = 'Error : '.$e; 
				}
			}
			else
			{
					$Result["message"] = 'File '.$FilePath.' is not writable !';       
			}
		}
		else
		{
				$Result["message"] = 'File '.$FilePath.' does not exist !';
		}
		return $Result;
	}
	
	public function actionGenerate()
	{
		try
		{
		//definisi nama file control,template,template view,module tujuan,view tujuan
		$controlfile = Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/controllers/".$_POST['controller']."Controller.php";
		$templatefile = Yii::getPathOfAlias('webroot')."/protected/modules/templatefile.php";
		$templatereportfile = Yii::getPathOfAlias('webroot')."/protected/modules/templatereportfile.php";
		$templateviewfile = Yii::getPathOfAlias('webroot')."/protected/modules/templateviewfile.php";
		$templateviewreportfile = Yii::getPathOfAlias('webroot')."/protected/modules/templateviewreportfile.php";
		$modulefile = Yii::getPathOfAlias('webroot')."/protected/modules/".$_POST['module']."/".$_POST['module']."Module.php";
		$defaultfile = Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/controllers/DefaultController.php";
		$indexdefault = Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/views/default/DefaultController.php";
		$viewfile = Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/views/".$_POST['menuname']."/index.php";
		//end 
		//cek folder, create jika tidak ada
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])))
		{
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module']));
			$fh = fopen($modulefile, "w");
			$isi = "<?php
class ".$_POST['module']."Module extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		\$this->setImport(array(
			'".strtolower($_POST['module']).".models.*',
			'".strtolower($_POST['module']).".components.*',
		));
	}

	public function beforeControllerAction(\$controller, \$action)
	{
		if(parent::beforeControllerAction(\$controller, \$action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
";
			fwrite($fh,$isi);
			fclose($fh);
		}
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/controllers/"))
		{
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/controllers/");
			$fh = fopen($defaultfile, "w");
			$isi = "<?php

class DefaultController extends AdminController
{
	protected \$menuname = '".$_POST['menuname']."';
	public \$module = '".strtolower($_POST['module'])."';
	
	public function actionIndex()
	{
		parent::actionIndex();
		\$this->redirect(Yii::app()->createUrl('admin'));
	}
}";
			fwrite($fh,$isi);
			fclose($fh);
		}
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/models/"))
		{
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/models/");
		}
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/messages/"))
		{
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/messages/");
		}
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/components/"))
		{
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/components/");
		}
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/components/views"))
		{
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/components/views");
		}
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/views/"))
		{
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/views/");
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/views/default/");
			$fh = fopen($indexdefault, "w");
			$isi="<?php 
\$position=0;\$group=0;\$i=0;
foreach(\$datas as \$data)
{
	if (\$i == 0)
	{
		echo \"<div class='row'>\";
		\$group = \$data['dashgroup'];
	}
	echo \"<div class='\".\$data['webformat'].\"'>\";
	\$this->widget(\$data['widgeturl']);	
	echo \"</div>\";	
	\$i++;
	if (\$i == \$data['dashcount'])
	{
		echo \"</div>\";
		\$i=0;
	}	
}
?>";
fwrite($fh,$isi);
			fclose($fh);
		}
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/views/".$_POST['menuname']))
		{
			mkdir(Yii::getPathOfAlias('webroot')."/protected/modules/".strtolower($_POST['module'])."/views/".$_POST['menuname']);
		}
		//end
		//duplikasi file template ke controlfile 
		//duplikasi file template view ke viewfile
		//if ($_POST['isreport'] == 1)
		//{
		//copy($templateviewreportfile,$viewfile);
		//copy($templatereportfile,$controlfile);
		//}
		//else
		//{
		copy($templateviewfile,$viewfile);
		copy($templatefile,$controlfile);
		//}
		
		if (($_POST['tablename'] !== '') && ($_POST['module'] !== '') && ($_POST['menuname'] !== ''))
		{
		$tables = explode(',',$_POST['tablename']);
		$RPktable = "";
		$TPkTable = "";
		$TSqlData = "";
		$TSqlCount = "";
		$TRequest = "";
		$TWhere = "";
		$TSort = "";
		$dataProvider = "";
		$TActionIndex = "
	public function actionIndex()
	{
		parent::actionIndex();
		\$this->getSQL();\n";
		$TActionCreate = "";
		$TActionUpdate = "";
		$TActionSave = "";
		$TActionDelete = "";
		$TActionPurge = "";
		$TActionDownPDF = "";
		$TActionDownXLS = "";
		$TActionApprove = "";
		$TAddOnWhere = "\$where = \"".$_POST['addonwhere']."\";";
		if ($_POST['addonwhere'] !== '')
		{
			$TWhere = " \"";
		}
		$TJSNewData = "";
		$TJSUpdateData = "";
		$TJSSaveData = "";
		$TJSApproveData = "";
		$TJSDeleteData = "";
		$TJSPurgeData = "";
		$TJSSearchData = "";
		$TJSDownPDF = "";
		$TJSDownXLS = "";
		$TGridColumns = "";
		$TSearchForm = "";
		$TInputMasterForm = "";
		$TIsNewData = "";
		$TIsUpdateData = "";
		$TIsApproveData	= "";
		$TIsDeleteData = "";
		$TIsPurgeData = "";
		$TIsDownload = "";
		$TIsUpload = "";
		$TInputShowDetail = "";
		$TInputDetailForm = "";
		$ExtProviders = "";
		$paramscript = "";
		$DetailGrids = "";
		$TDetailGrids = "";
		$TJSGetDetail = "";
		$jsgetdetail = "";
		$TShowDetail = "";
		$showdetail = "";
		$TGridDetailMasterForm = "";
		$TDetailMasterForm = "";
		$tabs = "";
		$tabelke = 0;
		
		if (count($tables) == 1)
		{
			$TGridColumns = "array
			(
				'class'=>'CButtonColumn',
				'template'=>'{edit} {delete} {purge} {pdf} {xls}',
				'htmlOptions' => array('style'=>'width:160px'),
				'buttons'=>array
				(
					'edit' => array
					(
							'label'=>\$this->getCatalog('edit'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/edit.png',
							".(($_POST['iswrite'] == 0)?"'visible'=>'false',":"'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','iswrite')),")."							
							'url'=>'\"#\"',
							'click'=>\"function() { 
								updatedata($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
					'delete' => array
					(
							'label'=>\$this->getCatalog('delete'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/active.png',
							".(($_POST['isreject'] == 0)?"'visible'=>'false',":"'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','isreject')),")."							
							'url'=>'\"#\"',
							'click'=>\"function() { 
								deletedata($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
					'purge' => array
					(
							'label'=>\$this->getCatalog('purge'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/trash.png',
							".(($_POST['ispurge'] == 0)?"'visible'=>'false',":"'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','ispurge')),")."							
							'url'=>'\"#\"',
							'click'=>\"function() { 
								purgedata($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
					'pdf' => array
					(
							'label'=>\$this->getCatalog('downpdf'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/pdf.png',
							'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','isdownload')),
							'url'=>'\"#\"',
							'click'=>\"function() { 
								downpdf($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
					'xls' => array
					(
							'label'=>\$this->getCatalog('downxls'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/xls.png',
							'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','isdownload')),
							'url'=>'\"#\"',
							'click'=>\"function() { 
								downxls($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
				),
			),\n";
		}
		else
		if (count($tables) > 1)
		{
			$TGridColumns = "array
			(
				'class'=>'CButtonColumn',
				'template'=>'{select} {edit} {purge} {pdf}',
				'htmlOptions' => array('style'=>'width:100px'),
				'buttons'=>array
				(
					'select' => array
					(
							'label'=>\$this->getCatalog('detail'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/detail.png',
							'url'=>'\"#\"',
							'click'=>\"function() { 
								GetDetail($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
					'edit' => array
					(
							'label'=>\$this->getCatalog('edit'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/edit.png',
							".(($_POST['iswrite'] == 0)?"'visible'=>'false',":"'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','iswrite')),")."							
							'url'=>'\"#\"',
							'click'=>\"function() { 
								updatedata($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
					'purge' => array
					(
							'label'=>\$this->getCatalog('purge'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/trash.png',
							".(($_POST['ispurge'] == 0)?"'visible'=>'false',":"'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','ispurge')),")."							
							'url'=>'\"#\"',
							'click'=>\"function() { 
								purgedata($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
					'pdf' => array
					(
							'label'=>\$this->getCatalog('downpdf'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/pdf.png',
							'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','isdownload')),
							'url'=>'\"#\"',
							'click'=>\"function() { 
								downpdf($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
				),
			),\n";
		}
		
		foreach ($tables as $table)
		{
			$select = "select";
			$selectcount = "select count(1)";
			$from = "from";
			$pktable = "";
			$update = "";
			$updatescript = "";
			$fieldinsert = "";
			$fieldupdate = "";
			$validate = "";
			$bindinsert = "";
			$bindscript = "";
			$deletescript = "";
			$jsnewdata = "";
			$jsupdatedata = "";
			$jssavedata = "";
			$jstinyint = "";
			$jssearch = "";
			$fielddownpdf = "";
			$fielddownxls = "";
			$colheader = "";
			$setwidths = "";
			$colalign = "";
			$coldetailalign = "";
			$colheaderxls = "";
			$defaultvalue = "";
			$griddetails = "";
			$inputdetailform = "";
			$IsRecordstatus = false;
			$i = 0;
			if ($TSqlData == "")
			{
				$TSqlData = "protected \$sqldata";
				$TSqlCount = "protected \$sqlcount";
			}
			else
			{
				$TSqlData .= "protected \$sqldata".$table;
				$TSqlCount .= "protected \$sqlcount".$table;
			}
			$k = 0;
			$fields = Yii::app()->db->createCommand("select * from menugen where tablename = '".$table."' order by sortorder asc")->queryAll();
			foreach ($fields as $field)
			{
				if ($field['namafield'] == 'recordstatus')
				{
					$IsRecordstatus = true;
				}
				if ($TPkTable == "")
				{
					$TPkTable = $field['namafield'];
				}
				if ($jssearch == "")
				{
					$jssearch = "var array = '".$TPkTable."='+\$id";
				}
				$RPktable = "
					if (isset(\$_REQUEST['".$TPkTable."']))
					{
						if ((\$_REQUEST['".$TPkTable."'] !== '0') && (\$_REQUEST['".$TPkTable."'] !== ''))
						{
							\$where .= \" and a0.".$TPkTable." in (\$_REQUEST['".$TPkTable."'];
						}
					}";
				if (($tabelke == 0) && ($field['isinput'] == 1))
				{
						$paramscript .= "\n,:".$field['namafield'];
				}
				if ($pktable == "")
				{
					$pktable = $field['namafield'];
				}
					if ($select == "select")
					{
						$select .= " a".$i.".".$field['namafield'];
					}
					else
					{
						$select .= ",a".$i.".".$field['namafield'];
					}
				if (($from == "from") && ($field['tablerelation'] == ''))
				{
					$from .= " ".$field['tablename']." a".$i." \n";
				}
				if ($TSort == "")
				{
					$TSort = " '".$field['namafield']."'";
				}
				else
				{
					$TSort .= ",'".$field['namafield']."'";
				}
				if ($field['issearch'] == 1) 
				{
					if ($tabelke == 0)
					{
						if (($TRequest == "") && ($field['tablerelation'] == ''))
						{
							$TRequest = "(isset(\$_REQUEST['".$field['namafield']."']))";
						}
						else
							if ($field['tablerelation'] == '')
						{
							$TRequest .= " && (isset(\$_REQUEST['".$field['namafield']."']))";
						}
						
						if (($TWhere == "") && ($field['tablerelation'] == ''))
						{
							$TWhere = "\" where a0.".$field['namafield']." like '%\". \$_REQUEST['".$field['namafield']."'].\"%'";
						}
						else
						if ($field['tablerelation'] == '')
						{
							$TWhere .= " \nand a0.".$field['namafield']." like '%\". \$_REQUEST['".$field['namafield']."'].\"%'";
						}
					}
					if ($jssearch == "")
					{
						$jssearch = "var array = '".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."='+$(\"input[name='dlg_search_".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."']\").val()";
					}
					else
					{
						$jssearch .= "\n+ '&".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."='+$(\"input[name='dlg_search_".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."']\").val()";
					}
					
					if ($TSearchForm == "")
					{
						$TSearchForm = "<div class=\"form-group\">
						<label for=\"dlg_search_".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"><?php echo \$this->getCatalog('".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."')?></label>
						<input type=\"text\" class=\"form-control\" name=\"dlg_search_".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\">
					</div>";
					}
					else
					{
						$TSearchForm .= "\n          <div class=\"form-group\">
						<label for=\"dlg_search_".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"><?php echo \$this->getCatalog('".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."')?></label>
						<input type=\"text\" class=\"form-control\" name=\"dlg_search_".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\">
					</div>";
					}
				}
				if ($field['isinput'] == 1)
				{
					if ($update == "")
					{
						$update = "'".$field['namafield']."'=>\$model['".$field['namafield']."'],\n";
					}
					else
					{
						$update .= "          '".$field['namafield']."'=>\$model['".$field['namafield']."'],\n";
					}
				}
				if (($validate == "") && ($field['isvalidate'] == 1))
				{
					$validate = "array('".$field['namafield']."','string','empty".$field['namafield']."'),\n";
				}
				else
				if ($field['isvalidate'] == 1)
				{
					$validate .= "      array('".$field['namafield']."','string','empty".$field['namafield']."'),\n";
				}
				if ($field['isinput'] == 1)
				{
					if ($field['namafield'] !== $pktable)
					{
						if ($fieldinsert == "")
						{
							$fieldinsert = $field['namafield'];
						}
						else
						{
							$fieldinsert .= ",".$field['namafield'];
						}
						if ($bindinsert == "")
						{
							$bindinsert = ":".$field['namafield'];
						}
						else
						{
							$bindinsert .= ",:".$field['namafield'];
						}
						if ($bindscript == "")
						{
							$bindscript = "\$command->bindvalue(':".$field['namafield']."',((\$_POST['".$field['namafield']."']!=='')?\$_POST['".$field['namafield']."']:null),PDO::PARAM_STR);";
						}
						else
						{
							$bindscript .= "\n        \$command->bindvalue(':".$field['namafield']."',((\$_POST['".$field['namafield']."']!=='')?\$_POST['".$field['namafield']."']:null),PDO::PARAM_STR);";
						}
						if ($fieldupdate == "")
						{
							$fieldupdate = $field['namafield']." = :".$field['namafield'];
						}
						else
						{
							$fieldupdate .= ",".$field['namafield']." = :".$field['namafield'];
						}
					}
				}
				if (($field['isinput'] == 1) && ($field['namafield'] !== $TPkTable))
				{
					if (strpos($field['tipefield'],'text') === 0)
					{
						if ($jsnewdata == "")
						{
							$jsnewdata = "\$(\"textarea[name='".$field['namafield']."']\").val('');";
						}
						else
						{
							$jsnewdata .= "\n      \$(\"textarea[name='".$field['namafield']."']\").val('');";
						}
						if ($jsupdatedata == "")
						{
							$jsupdatedata = "\$(\"textarea[name='".$field['namafield']."']\").val(data.".$field['namafield'].");";
						}
						else
						{
							$jsupdatedata .= "\n      \$(\"textarea[name='".$field['namafield']."']\").val(data.".$field['namafield'].");";
						}
						if ($jssavedata == "")
						{
							$jssavedata = "'".$field['namafield']."':$(\"textarea[name='".$field['namafield']."']\").val(),";
						}
						else
						{
							$jssavedata .= "\n      '".$field['namafield']."':$(\"textarea[name='".$field['namafield']."']\").val(),";
						}
					}
					else 
					if (strpos($field['tipefield'],'tinyint') === false)
					{
						if ($field['defaultvalue'] == '')
						{
							if ($jsnewdata == "")
							{
								$jsnewdata = "\$(\"input[name='".$field['namafield']."']\").val('');";
							}
							else
							{
								$jsnewdata .= "\n      \$(\"input[name='".$field['namafield']."']\").val('');";
							}
						}
						else
						{
							if ($jsnewdata == "")
							{
								$jsnewdata = "\$(\"input[name='".$field['namafield']."']\").val(data.".$field['namafield'].");";
							}
							else
							{
								$jsnewdata .= "\n      \$(\"input[name='".$field['namafield']."']\").val(data.".$field['namafield'].");";
							}
						}
						if ($jsupdatedata == "")
						{
							$jsupdatedata = "\$(\"input[name='".$field['namafield']."']\").val(data.".$field['namafield'].");";
						}
						else
						{
							$jsupdatedata .= "\n      \$(\"input[name='".$field['namafield']."']\").val(data.".$field['namafield'].");";
						}
						if ($jssavedata == "")
						{
							$jssavedata = "'".$field['namafield']."':$(\"input[name='".$field['namafield']."']\").val(),";
						}
						else
						{
							$jssavedata .= "\n      '".$field['namafield']."':$(\"input[name='".$field['namafield']."']\").val(),";
						}
					}
					else
					if ((strpos($field['tipefield'],'tinyint') === 0) && ($_POST['ispost'] == 1) && (strpos($field['namafield'],'recordstatus') === 0))
					{
						if ($jsnewdata == "")
						{
							$jsnewdata = "\$(\"input[name='recordstatus']\").val(data.recordstatus);";
						}
						else
						{
							$jsnewdata .= "\n      \$(\"input[name='recordstatus']\").val(data.recordstatus);";
						}
						if ($jsupdatedata == "")
						{
							$jsupdatedata = "\$(\"input[name='recordstatus']\").val(data.recordstatus);";
						}
						else
						{
							$jsupdatedata .= "\n      \$(\"input[name='recordstatus']\").val(data.recordstatus);";
						}
						if ($jssavedata == "")
						{
							$jssavedata = "'recordstatus':$(\"input[name='recordstatus']\").val(),";
						}
						else
						{
							$jssavedata .= "\n      'recordstatus':$(\"input[name='recordstatus']\").val(),";
						}
					}
					else
					{
						$jsnewdata .= "\n      \$(\"input[name='".$field['namafield']."']\").prop('checked',true);";
						$jsupdatedata .= "\n      if (data.".$field['namafield']." == 1)
			{
				\$(\"input[name='".$field['namafield']."']\").prop('checked',true);
			}
			else
			{
				\$(\"input[name='".$field['namafield']."']\").prop('checked',false)
			}";
						if ($jstinyint == "")
						{
							$jstinyint = "var ".$field['namafield']." = 0;
	if ($(\"input[name='".$field['namafield']."']\").prop('checked'))
	{
		".$field['namafield']." = 1;
	}
	else
	{
		".$field['namafield']." = 0;
	}";
						}
						else
						{
						$jstinyint .= "\nvar ".$field['namafield']." = 0;
	if ($(\"input[name='".$field['namafield']."']\").prop('checked'))
	{
		".$field['namafield']." = 1;
	}
	else
	{
		".$field['namafield']." = 0;
	}";
						}
						$jssavedata .= "\n      '".$field['namafield']."':".$field['namafield'].",";

					}
				}
				
				if (($field['isview'] == 1) && ($tabelke == 0))
				{
					$s = strpos($field['namafield'],'currency');
					if ((strpos($field['tipefield'],'decimal') === 0) && ($s > 0))
					{
							$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatCurrency(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"])'
				),
							";
					} else
						if ((strpos($field['tipefield'],'decimal') === 0))
					{
							$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatNumber(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"])'
				),
							";
					} else
					if ((strpos($field['tipefield'],'datetime') === 0))
					{
							$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatDateTime(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"])'
				),
							";
					} else
					if ((strpos($field['tipefield'],'timestamp') === 0))
					{
							$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatDateTime(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"])'
				),
							";
					}
				else
						if ((strpos($field['tipefield'],'time') === 0))
					{
							$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatTime(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"])'
				),
							";
					} else
					if ((strpos($field['tipefield'],'date') === 0))
					{
							$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatDate(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"])'
				),
							";
					} else
					if ((strpos($field['tipefield'],'varchar') === 0))
					{
							$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"]'
				),
							";
					}
					else
						if ((strpos($field['tipefield'],'int') === 0) && (strpos($field['tipefield'],'tinyint') === false))
					{
						$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['popupname'] == '')?(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2)):$field['popupname'])."'),
					'name'=>'".$field['namafield']."',
					'value'=>'\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"]'
				),
							";
					}
					else
					if ((strpos($field['tipefield'],'tinyint') === 0) && (strpos($field['namafield'],'recordstatus') === 0) && ($_POST['ispost'] == 1))
					{
						$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".$field['namafield']."'),
					'name'=>'statusname',
					'value'=>'\$data[\"statusname\"]'
				),
							";
					} else 
						if ((strpos($field['tipefield'],'tinyint') === 0))
					{
						$TGridColumns .= "array(
					'class'=>'CCheckBoxColumn',
					'name'=>'".$field['namafield']."',
					'header'=>\$this->getCatalog('".$field['namafield']."'),
					'selectableRows'=>'0',
					'checked'=>'\$data[\"".$field['namafield']."\"]',
				),";
					} else {
						$TGridColumns .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['relationname'])."\"]'
				),
							";
					}
				}
				
				if (($field['isinput'] == 1) && ($tabelke == 0))
				{
					if ($TInputMasterForm == "")
					{
						$TInputMasterForm .= "<input type=\"hidden\" class=\"form-control\" name=\"actiontype\">";
						$TInputMasterForm .= "\n<input type=\"hidden\" class=\"form-control\" name=\"".$TPkTable."\">";
					}
					else
					{
						if (strpos($field['tipefield'],"varchar") === 0)
						{
							$TInputMasterForm .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"text\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
							if ((strpos($field['tipefield'],'tinyint') === 0) && ($_POST['ispost'] == 1) && ($field['namafield'] == 'recordstatus'))
						{
							$TInputMasterForm .= "<input type=\"hidden\" class=\"form-control\" name=\"recordstatus\">";
						} 
						else
						if (strpos($field['tipefield'],'tinyint') === 0)
						{
							$TInputMasterForm .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"checkbox\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if (strpos($field['tipefield'],'float') === 0)
						{
							$TInputMasterForm .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"number\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if (strpos($field['tipefield'],'decimal') === 0)
						{
							$TInputMasterForm .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"number\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if ((strpos($field['tipefield'],'tinyint') === false) && (strpos($field['tipefield'],"int") === 0) && ($field['tablerelation'] !== ''))
						{
							$TInputMasterForm .= "\n        <?php \$this->widget('DataPopUp',
						array('id'=>'Widget','IDField'=>'".$field['namafield']."','ColField'=>'".$field['popupname']."',
							'IDDialog'=>'".$field['namafield']."_dialog','titledialog'=>\$this->getCatalog('".$field['popupname']."'),'classtype'=>'col-md-4',
							'classtypebox'=>'col-md-8',".(($field['wirelsource']!=='')?"'RelationID'=>'".$field['wirelsource']."',":'')."
							'PopUpName'=>'".$field['widgetrelation']."','PopGrid'=>'".$field['namafield']."grid')); 
					?>
							";
						} else
						if ((strpos($field['tipefield'],'tinyint') === false) && (strpos($field['tipefield'],"int") === 0) && ($field['tablerelation'] == ''))
						{
							$TInputMasterForm .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"number\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						}   else
						if (strpos($field['tipefield'],'datetime') === 0)
						{
							$TInputMasterForm .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"datetime-local\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if (strpos($field['tipefield'],'date') === 0)
						{
							$TInputMasterForm .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"date\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if (strpos($field['tipefield'],'text') === 0)
						{
							$TInputMasterForm .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<textarea type=\"text\" class=\"form-control\" rows=\"5\" name=\"".$field['namafield']."\"></textarea>
						</div>
					</div>
							";
						} 
					}
				}
				
				if (($field['isinput'] == 1) && ($tabelke > 0))
				{
					if ($inputdetailform == "")
					{
						$inputdetailform = "<input type=\"hidden\" class=\"form-control\" name=\"".$field['namafield']."\">";
					}
					else
					if ($field['namafield'] !== $TPkTable)
					{
						if (strpos($field['tipefield'],"varchar") === 0)
						{
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"text\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if (strpos($field['tipefield'],'tinyint') === 0)
						{
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"checkbox\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if (strpos($field['tipefield'],'float') === 0)
						{
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"number\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if (strpos($field['tipefield'],'decimal') === 0)
						{
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"number\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if ((strpos($field['tipefield'],'tinyint') === false) && (strpos($field['tipefield'],"int") === 0) && ($field['tablerelation'] == ''))
						{
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"number\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if ((strpos($field['tipefield'],'tinyint') === false) && (strpos($field['tipefield'],"int") === 0) && ($field['tablerelation'] !== ''))
						{
							$inputdetailform .= "\n        <?php \$this->widget('DataPopUp',
						array('id'=>'Widget','IDField'=>'".$field['namafield']."','ColField'=>'".$field['popupname']."',
							'IDDialog'=>'".$field['namafield']."_dialog','titledialog'=>\$this->getCatalog('".substr($field['namafield'],0,strlen($field['namafield'])-2)."'),'classtype'=>'col-md-4',
							'classtypebox'=>'col-md-8',".(($field['wirelsource']!=='')?"'RelationID'=>'".$field['wirelsource']."',":'')."
							'PopUpName'=>'".$field['widgetrelation']."','PopGrid'=>'".$field['namafield']."grid')); 
					?>
							";
						} else
						if (strpos($field['tipefield'],'datetime') === 0)
						{
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"datetime-local\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						} else
						if (strpos($field['tipefield'],'date') === 0)
						{
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"date\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						}  else
						if (strpos($field['tipefield'],'text') === 0)
						{
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<textarea type=\"text\" class=\"form-control\" rows=\"5\" name=\"".$field['namafield']."\"></textarea>
						</div>
					</div>
							";
						} else {
							$inputdetailform .= "\n        <div class=\"row\">
						<div class=\"col-md-4\">
							<label for=\"".$field['namafield']."\"><?php echo \$this->getCatalog('".$field['namafield']."')?></label>
						</div>
						<div class=\"col-md-8\">
							<input type=\"text\" class=\"form-control\" name=\"".$field['namafield']."\">
						</div>
					</div>
							";
						}
					}
				}
				if ($fielddownpdf == "")
				{
					$fielddownpdf = "\$row1['".(($field['tablerelation'] == '')?$field['namafield']:$field['relationname'])."']";
					$colheader = "\$this->getCatalog('".$field['namafield']."')";
					$setwidths = "10";
					$colalign = "'C'";
					$coldetailalign = "'L'";
				}
				else
				{
					$fielddownpdf .= ",\$row1['".(($field['tablerelation'] == '')?$field['namafield']:$field['relationname'])."']";
					$colheader .= ",\$this->getCatalog('".((strpos($field['namafield'],'id')===false)?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."')";
					$setwidths .= ((strpos($field['tipefield'],'tinyint')===false)?",40":",15");
					$colalign .= ",'C'";
					$coldetailalign .= ",'L'";
				}
				if ($field['isprint'] == 1)
				{
					if ($fielddownxls == "")
					{
						$fielddownxls = "->setCellValueByColumnAndRow(".$k.", \$i+1, \$row1['".(($field['tablerelation'] == '')?$field['namafield']:$field['relationname'])."'])";
						$colheaderxls = "->setCellValueByColumnAndRow(".$k.",4,\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:$field['relationname'])."'))";
					}
					else
					{
						$fielddownxls .= "\n->setCellValueByColumnAndRow(".$k.", \$i+1, \$row1['".(($field['tablerelation'] == '')?$field['namafield']:$field['relationname'])."'])";
						$colheaderxls .= "\n->setCellValueByColumnAndRow(".$k.",4,\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:$field['relationname'])."'))";
					}
				}
				$k++;
				if (($field['defaultvalue'] == null))
				{
					$defaultvalue .= "";
				}
				else
				{
					if ($defaultvalue == "") 
					{
						$defaultvalue = $field['defaultvalue'];
					}
					else
					{
						$defaultvalue .= ",\n      ".$field['defaultvalue'];
					}
				}
				if (($tabelke > 0) && ($field['isview'] == 1) && ($field['namafield'] !== $TPkTable))
			{
				if ((strpos($field['tipefield'],'decimal') === 0) && (strpos($field['namafield'],'currency') === 0))
					{
							$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatCurrency(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"])'
				),
							";
					} else
						if ((strpos($field['tipefield'],'decimal') === 0))
					{
							$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatNumber(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"])'
				),
							";
					} else
					if ((strpos($field['tipefield'],'datetime') === 0))
					{
							$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatDateTime(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"])'
				),
							";
					} else
					if ((strpos($field['tipefield'],'timestamp') === 0))
					{
							$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatDateTime(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"])'
				),
							";
					}
				else
						if ((strpos($field['tipefield'],'time') === 0))
					{
							$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatTime(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"])'
				),
							";
					} else
					if ((strpos($field['tipefield'],'date') === 0))
					{
							$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'Yii::app()->format->formatDate(\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"])'
				),
							";
					} else
					if ((strpos($field['tipefield'],'varchar') === 0))
					{
							$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"]'
				),
							";
					}
					else
						if ((strpos($field['tipefield'],'int') === 0) && (strpos($field['tipefield'],'tinyint') === false))
					{
						$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"]'
				),
							";
					}
					else
						if ((strpos($field['tipefield'],'tinyint') === 0))
					{
						$griddetails .= "array(
					'class'=>'CCheckBoxColumn',
					'name'=>'".$field['namafield']."',
					'header'=>\$this->getCatalog('".$field['namafield']."'),
					'selectableRows'=>'0',
					'checked'=>'\$data[\"".$field['namafield']."\"]',
				),";
					} else {
						$griddetails .= "array(
					'header'=>\$this->getCatalog('".(($field['tablerelation'] == '')?$field['namafield']:substr($field['namafield'],0,strlen($field['namafield'])-2))."'),
					'name'=>'".$field['namafield']."',
					'value'=>'\$data[\"".(($field['tablerelation']=='')?$field['namafield']:$field['popupname'])."\"]'
				),
							";
					}
				}
			}
			if ($tabelke > 0)
			{
				$DetailGrids .= "$.fn.yiiGridView.update('".$table."List',{data:{'".$TPkTable."':data.".$TPkTable."}});\n";
			}
			
			//baca tabel menugen,filter yang ada tablerelasi
			$fields = Yii::app()->db->createCommand("select * from menugen where tablename = '".$table."' and tablerelation <> '' order by sortorder asc")->queryAll();
			$i = 1;
			foreach ($fields as $field)
			{
				$select .= ",a".$i.".".$field['relationname']." as ".$field['popupname'];
				$from .= "    left join ".$field['tablerelation']." a".$i." on a".$i.".".$field['tablefkname']." = a0.".$field['namafield']."\n";
				if ($field['issearch'] == 1)
				{
					if ($tabelke == 0)
					{
						if ($TRequest == "")
						{
							$TRequest = "(isset(\$_REQUEST['".$field['popupname']."']))";
						}
						else
						{
							$TRequest .= " && (isset(\$_REQUEST['".$field['popupname']."']))";
						}
						if ($TWhere == "")
						{
							$TWhere = "\" where a".$i.".".$field['popupname']." like '%\". \$_REQUEST['".$field['popupname']."'].\"%'";
						}
						else
						{
							$TWhere .= " \nand a".$i.".".$field['popupname']." like '%\". \$_REQUEST['".$field['popupname']."'].\"%'";
						}
					}
					if ($update == "")
					{
						$update = "'".$field['popupname']."'=>\$model['".$field['popupname']."'],\n";
					}
					else
					{
						$update .= "          '".$field['popupname']."'=>\$model['".$field['popupname']."'],\n";
					}
					if ($field['defaultvalue'] === '')
					{
						if ($jsnewdata == "")
						{
							$jsnewdata = "\$(\"input[name='".$field['popupname']."']\").val('');";
						}
						else
						{
							$jsnewdata .= "\n      \$(\"input[name='".$field['popupname']."']\").val('');";
						}
					}
					else
					{
						if ($jsnewdata == "")
						{
							$jsnewdata = "\$(\"input[name='".$field['popupname']."']\").val(data.".$field['popupname'].");";
						}
						else
						{
							$jsnewdata .= "\n      \$(\"input[name='".$field['popupname']."']\").val(data.".$field['popupname'].");";
						}
					}
					if ($jsupdatedata === '')
					{
						$jsupdatedata = "\$(\"input[name='".$field['popupname']."']\").val(data.".$field['popupname'].");";
					}
					else
					{
						$jsupdatedata .= "\n      \$(\"input[name='".$field['popupname']."']\").val(data.".$field['popupname'].");";
					}
				}
				else
				{
					if ($update == "")
					{
						$update = "'".$field['popupname']."'=>\$model['".$field['popupname']."'],\n";
					}
					else
					{
						$update .= "          '".$field['popupname']."'=>\$model['".$field['popupname']."'],\n";
					}
					if ($jsnewdata == "")
					{
						$jsnewdata = "\$(\"input[name='".$field['popupname']."']\").val('');";
					}
					else
					{
						$jsnewdata .= "\n      \$(\"input[name='".$field['popupname']."']\").val('');";
					}
					if ($jsupdatedata === '')
					{
						$jsupdatedata = "\$(\"input[name='".$field['popupname']."']\").val(data.".$field['popupname'].");";
					}
					else
					{
						$jsupdatedata .= "\n      \$(\"input[name='".$field['popupname']."']\").val(data.".$field['popupname'].");";
					}
				}
				$i++;
			}

			$insertscript = "'insert into TTable (TColumns) 
			      values (TBindValue)'";
			$insertscript = str_replace('TTable',$table,$insertscript);
			$insertscript = str_replace('TColumns',$fieldinsert,$insertscript);
			$insertscript = str_replace('TBindValue',$bindinsert,$insertscript);
			$updatescript = "'update TTable 
			      set TColumns 
			      where ".$pktable." = :".$pktable."'";
			$updatescript = str_replace('TTable',$table,$updatescript);
			$updatescript = str_replace('TColumns',$fieldupdate,$updatescript);

			if (($TActionSave == "") && ($_POST['iswrite'] == 1))
			{
				if (count($tables) == 1)
			{
				$TActionSave = "public function actionSave()
	{
		parent::actionSave();
		\$error = \$this->ValidateData(array(
			".$validate."    ));
		if (\$error == false)
		{
			\$id = \$_POST['".$TPkTable."'];
			\$connection=Yii::app()->db;
			\$transaction=\$connection->beginTransaction();
			try
			{
				if (\$id !== '')
				{
					\$sql = ".$updatescript.";
				}
				else
				{
					\$sql = ".$insertscript.";
				}
				\$command = \$connection->createCommand(\$sql);
				if (\$id !== '')
				{
					\$command->bindvalue(':".$TPkTable."',\$_POST['".$TPkTable."'],PDO::PARAM_STR);
				}
				".$bindscript."
				\$command->execute();
				\$transaction->commit();
				\InsertTranslog(\$command,\$id);
				\getMessage('success','alreadysaved');
			}
			catch (CDbException \$e)
			{
				\$transaction->rollBack();
				\getmessage('error',\$e->getMessage());
			}
		}
	}
				";
			}
			else
				if ((count($tables) > 1) && ($_POST['iswrite'] == 1))
			{
				$TActionSave = "\npublic function actionSave()
	{
		parent::actionSave();
		\$error = \$this->ValidateData(array(
			".$validate."    ));
		if (\$error == false)
		{
			\$id = \$_POST['".$TPkTable."'];
			\$connection=Yii::app()->db;
			\$transaction=\$connection->beginTransaction();
			try
			{
				\$sql = 'call Insert".$_POST['menuname']." (:actiontype".$paramscript.",:vcreatedby)';
				\$command = \$connection->createCommand(\$sql);
				\$command->bindvalue(':actiontype',\$_POST['actiontype'],PDO::PARAM_STR);
				\$command->bindvalue(':".$TPkTable."',\$_POST['".$TPkTable."'],PDO::PARAM_STR);
				".$bindscript."
				\$command->bindvalue(':vcreatedby',Yii::app()->user->id,PDO::PARAM_STR);
				\$command->execute();
				\$transaction->commit();
				\getmessage('success','alreadysaved');
			}
			catch (CDbException \$e)
			{
				\$transaction->rollBack();
				\getmessage('error',\$e->getMessage());
			}
		}
	}";
			}
				}
				else
					if ($_POST['iswrite'] == 1)
				{
					$TActionSave .= "\npublic function actionSave".$table."()
	{
		parent::actionSave();
		\$error = \$this->ValidateData(array(
			".$validate."    ));
		if (\$error == false)
		{
			\$id = \$_POST['".$pktable."'];
			\$connection=Yii::app()->db;
			\$transaction=\$connection->beginTransaction();
			try
			{
				if (\$id !== '')
				{
					\$sql = ".$updatescript.";
				}
				else
				{
					\$sql = ".$insertscript.";
				}
				\$command = \$connection->createCommand(\$sql);
				if (\$id !== '')
				{
					\$command->bindvalue(':".$pktable."',\$_POST['".$pktable."'],PDO::PARAM_STR);
				}
				".$bindscript."
				\$command->execute();
				\$transaction->commit();
				\InsertTranslog(\$command,\$id);
				\getmessage('success','alreadysaved');
			}
			catch (CDbException \$e)
			{
				\$transaction->rollBack();
				\getmessage('error',\$e->getMessage());
			}
		}
	}
				";
				}
				
			if (($_POST['ispost'] == 1) && ($tabelke == 0) && ($_POST['iswrite'] == 1))
			{
				$select .= ",getwfstatusbywfname('".$_POST['appwf']."',a0.recordstatus) as statusname ";
			}
			$TSqlData .= " = \"".$select." \n    ".$from."  \";\n";
			$TSqlCount .= " = \"".$selectcount." \n    ".$from."  \";\n";
			if ($dataProvider == "")
			{
				$dataProvider = "    \$dataProvider=new CSqlDataProvider(\$this->sqldata,array(
			'totalItemCount'=>\$this->count,
			'keyField'=>'".$TPkTable."',
			'pagination'=>array(
				'pageSize'=>\$this->getParameter('DefaultPageSize'),
				'pageVar'=>'page',
			),
			'sort'=>array(
				'attributes'=>array(
					".$TSort."
				),
				'defaultOrder' => array( 
					'".$TPkTable."' => CSort::SORT_DESC
				),
			),
		));";
			}
			else
			{
				$ExtProviders .= ",'dataProvider".$table."'=>\$dataProvider".$table;
				$dataProvider .= "\n		if (isset(\$_REQUEST['".$TPkTable."']))
		{
			\$this->sqlcount".$table." .= ' where a0.".$TPkTable." = '.\$_REQUEST['".$TPkTable."'];
			\$this->sqldata".$table." .= ' where a0.".$TPkTable." = '.\$_REQUEST['".$TPkTable."'];
		}
		\$count".$table." = Yii::app()->db->createCommand(\$this->sqlcount".$table.")->queryScalar();
\$dataProvider".$table."=new CSqlDataProvider(\$this->sqldata".$table.",array(
					'totalItemCount'=>\$count".$table.",
					'keyField'=>'".$pktable."',
					'pagination'=>array(
						'pageSize'=>\$this->getParameter('DefaultPageSize'),
						'pageVar'=>'page',
					),
					'sort'=>array(
						'defaultOrder' => array( 
							'".$pktable."' => CSort::SORT_DESC
						),
					),
					));";
			}
			
			if (($TActionCreate == "") && ($_POST['iswrite'] == 1))
			{
				$TActionCreate = "public function actionCreate()
	{
		parent::actionCreate();
		\$company = \$this->getCompany();
		".((count($tables)>1)?"\$".$TPkTable." = rand(-1,-1000000000);":"")."
		echo CJSON::encode(array(
			'status'=>'success',
			".((count($tables)>1)?"'".$TPkTable."'=>$".$TPkTable.",":"")."
			".$defaultvalue."
		));
	}";
			}
			else
				if ($_POST['iswrite'] == 1)
			{
				$TActionCreate .= "\n  public function actionCreate".$table."()
	{
		parent::actionCreate();
		echo CJSON::encode(array(
			'status'=>'success',
			".$defaultvalue."
		));
	}";
			}
			
			
			if (($TActionUpdate == "") && ($_POST['iswrite'] == 1))
			{
				if ($_POST['ispost'] == 0)
				{
				$TActionUpdate = "public function actionUpdate()
	{
		parent::actionUpdate();
		if (isset(\$_POST['id']))
		{
			\$id = \$_POST['id'];
			\$model = Yii::app()->db->createCommand(\$this->sqldata.' where a0.".$TPkTable." = '.\$id)->queryRow();
			if (\$model !== null)
			{
				echo CJSON::encode(array(
					'status'=>'success',
					".$update."
				));
				Yii::app()->end();
			}
		}
	}\n";
			}
			else
				if ($_POST['iswrite'] == 1)
			{
				$TActionUpdate = "public function actionUpdate()
	{
		parent::actionUpdate();
		if (isset(\$_POST['id']))
		{
			\$id = \$_POST['id'];
			\$model = Yii::app()->db->createCommand(\$this->sqldata.' where a0.".$TPkTable." = '.\$id)->queryRow();
			if (\$this->CheckDoc(\$model['recordstatus']) == '')
			{
				if (\$model !== null)
				{
					echo CJSON::encode(array(
						'status'=>'success',
						".$update."
					));
					Yii::app()->end();
				}
			}
			else
			{
				\getmessage('error',\$this->getCatalog(\"docreachmaxstatus\"));
			}
		}
	}\n";
			}
			}
			else
				if ($_POST['iswrite'] == 1)
			{
				$TActionUpdate .= "\n  public function actionUpdate".$table."()
	{
		parent::actionUpdate();
		if (isset(\$_POST['id']))
		{
			\$id = \$_POST['id'];
			\$model = Yii::app()->db->createCommand(\$this->sqldata".$table.".' where ".$pktable." = '.\$id)->queryRow();
			if (\$model !== null)
			{
				echo CJSON::encode(array(
					'status'=>'success',
					".$update."
				));
				Yii::app()->end();
			}
		}
	}";
			}
			
			if (($TActionDelete == "") && ($IsRecordstatus == true) && ($_POST['ispost'] == 0))
			{
				$TActionDelete = "public function actionDelete()
	{
		parent::actionDelete();
		\$connection=Yii::app()->db;
		\$transaction=\$connection->beginTransaction();
		try
		{
			if (isset(\$_POST['id']))
			{
				\$id = \$_POST['id']; if (!is_array(\$id)) { \$ids[] = \$id; \$id = \$ids; }
				for (\$i = 0; \$i < count(\$_POST['id']);\$i++)
				{
					\$sql = \"select recordstatus from ".$table." where ".$pktable." = \".\$id[\$i];
					\$status = Yii::app()->db->createCommand(\$sql)->queryRow();
					if (\$status['recordstatus'] == 1)
					{			
						\$sql = \"update ".$table." set recordstatus = 0 where ".$pktable." = \".\$id[\$i];
					}
					else
					if (\$status['recordstatus'] == 0)
					{
						\$sql = \"update ".$table." set recordstatus = 1 where ".$pktable." = \".\$id[\$i];
					}
					\$connection->createCommand(\$sql)->execute();
				}
				\$transaction->commit();
				\getmessage('success','alreadysaved');
			}
			else
			{
				\getmessage('success','chooseone');
			}
		}
		catch (CDbException \$e)
		{
			\$transaction->rollback();
			\getmessage('error',\$e->getMessage());
		}
	}";
			} 
			else
				if (($TActionDelete == "") && ($IsRecordstatus == true) && ($_POST['ispost'] == 1))
			{
				$TActionDelete = "\npublic function actionDelete()
	{
		parent::actionDelete();
		if (isset(\$_POST['id']))
		{
			\$id=\$_POST['id']; if (!is_array(\$id)) { \$ids[] = \$id; \$id = \$ids; }
			\$connection=Yii::app()->db;
			\$transaction=\$connection->beginTransaction();
			try
			{
				\$sql = 'call Delete".$_POST['menuname']."(:vid,:vcreatedby)';
				\$command=\$connection->createCommand(\$sql);
				foreach(\$id as \$ids)
				{
					\$command->bindvalue(':vid',\$ids,PDO::PARAM_STR);
					\$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					\$command->execute();
				}
				\$transaction->commit();
				\getmessage('success','alreadysaved',1);
			}
			catch (Exception \$e)
			{
				\$transaction->rollback();
				\getmessage('error',\$e->getMessage(),1);
			}
		}
		else
		{
		\getmessage('error','chooseone',1);
		}
	}";
			}
			
			if (($TActionApprove == "") && ($IsRecordstatus == true) && ($_POST['ispost'] == 1))
			{
				$TActionApprove = "\npublic function actionApprove()
	{
		parent::actionPost();
		if (isset(\$_POST['id']))
		{
			\$id=\$_POST['id']; if (!is_array(\$id)) { \$ids[] = \$id; \$id = \$ids; }
			\$connection=Yii::app()->db;
			\$transaction=\$connection->beginTransaction();
			try
			{
				\$sql = 'call Approve".$_POST['menuname']."(:vid,:vcreatedby)';
				\$command=\$connection->createCommand(\$sql);
				foreach(\$id as \$ids)
				{
					\$command->bindvalue(':vid',\$ids,PDO::PARAM_STR);
					\$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					\$command->execute();
				}
				\$transaction->commit();
				\getmessage('success','alreadysaved',1);
			}
			catch (Exception \$e)
			{
				\$transaction->rollback();
				\getmessage('error',\$e->getMessage(),1);
			}
		}
		else
		{
		\getmessage('error','chooseone',1);
		}
	}";
			}
			
			if (($TActionPurge == "") && ($_POST['ispurge'] == 1))
			{
				$TActionPurge = "public function actionPurge()
	{
		parent::actionPurge();
		\$connection=Yii::app()->db;
		\$transaction=\$connection->beginTransaction();
		try
		{
			if (isset(\$_POST['id']))
			{
				\$id = \$_POST['id']; if (!is_array(\$id)) { \$ids[] = \$id; \$id = \$ids; }
				for (\$i = 0; \$i < count(\$_POST['id']);\$i++)
				{
				\$sql = \"delete from ".$table." where ".$pktable." = \".\$id[\$i];
				Yii::app()->db->createCommand(\$sql)->execute();
				}
				\$transaction->commit();
				\getmessage('success','alreadysaved');
			}
			else
			{
				\getmessage('success','chooseone');
			}
		}
		catch (CDbException \$e)
		{
			\$transaction->rollback();
			\getmessage('error',\$e->getMessage());
		}
	}";
			}
			else
				if ($_POST['ispurge'] == 1)
			{
				$TActionPurge .= "public function actionPurge".$table."()
	{
		parent::actionPurge();
		\$connection=Yii::app()->db;
		\$transaction=\$connection->beginTransaction();
		try
		{
			if (isset(\$_POST['id']))
			{
				\$id = \$_POST['id']; if (!is_array(\$id)) { \$ids[] = \$id; \$id = \$ids; }
				for (\$i = 0; \$i < count(\$_POST['id']);\$i++)
				{
				\$sql = \"delete from ".$table." where ".$pktable." = \".\$id[\$i];
				Yii::app()->db->createCommand(\$sql)->execute();
				}
				\$transaction->commit();
				\getmessage('success','alreadysaved');
			}
		}
		catch (CDbException \$e)
		{
			\$transaction->rollback();
			\getmessage('error',\$e->getMessage());
		}
	}";
			}
			
			if (($TActionDownPDF == "") && ($_POST['isdownload'] == 1))
			{
		$TActionDownPDF = "public function actionDownPDF()
	{
		parent::actionDownPDF();
		\$this->getSQL();
		\$dataReader=Yii::app()->db->createCommand(\$this->sqldata)->queryAll();

		//masukkan judul
		\$this->pdf->title=\$this->getCatalog('".$_POST['menuname']."');
		\$this->pdf->AddPage('P');
		\$this->pdf->colalign = array(".$colalign.");
		\$this->pdf->colheader = array(".$colheader.");
		\$this->pdf->setwidths(array(".$setwidths."));
		\$this->pdf->Rowheader();
		\$this->pdf->coldetailalign = array(".$coldetailalign.");
		
		foreach(\$dataReader as \$row1)
		{
			//masukkan baris untuk cetak
		  \$this->pdf->row(array(".$fielddownpdf."));
		}
		// me-render ke browser
		\$this->pdf->Output();
	}";
			}
			
			if (($TActionDownXLS == "") && ($_POST['isdownload'] == 1))
			{
				$TActionDownXLS = "public function actionDownXLS()
	{
		parent::actionDownXLS();
		\$this->getSQL();
		\$dataReader=Yii::app()->db->createCommand(\$this->sqldata)->queryAll();
		\$i=4;
		\$this->phpExcel->setActiveSheetIndex(0)
					".$colheaderxls.";
		foreach(\$dataReader as \$row1)
		{
			  \$this->phpExcel->setActiveSheetIndex(0)
					".$fielddownxls.";
		\$i+=1;
		}
		\$this->getFooterXLS(\$this->phpExcel);
	}";
			}

			if (($TJSNewData == "") && ($_POST['iswrite'] == 1))
			{
				$TJSNewData = "function newdata()
{
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/create')?>','data':{},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
			\$(\"input[name='".$TPkTable."']\").val(data.".$TPkTable.");
			".((count($tables)>1)?("\$(\"input[name='actiontype']\").val(0);"):"")."
			".$jsnewdata."
			TDetailGrids
			$('#InputDialog').modal();
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	return false;
}\n";
			}
			else
				if ($_POST['iswrite'] == 1)
			{
				$TJSNewData .= "function newdata".$table."()
{
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/create".$table."')?>','data':{},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
			".$jsnewdata."
			$('#InputDialog".$table."').modal();
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	return false;
}\n";
			}
			
			if (($TJSUpdateData == "") && ($_POST['iswrite'] == 1))
			{
				$TJSUpdateData = "function updatedata(\$id)
{
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/update')?>','data':{'id':\$id},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
				\$(\"input[name='".$TPkTable."']\").val(data.".$TPkTable.");
				".((count($tables)>1)?("\$(\"input[name='actiontype']\").val(1);"):"")."
				".$jsupdatedata."
				TDetailGrids
				$('#InputDialog').modal();
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	return false;
}\n";
			}
			else
				if ($_POST['iswrite'] == 1)
			{
				$TJSUpdateData .= "function updatedata".$table."(\$id)
{
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/update".$table."')?>','data':{'id':\$id},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
			".$jsupdatedata."
			$('#InputDialog".$table."').modal();
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	return false;
}\n";
			}
			
			if (($TJSSaveData == "") && ($_POST['iswrite'] == 1))
			{
				$TJSSaveData = "function savedata()
{
".$jstinyint."
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/save')?>',
		'data':{
			".((count($tables)>1)?"'actiontype':$(\"input[name='actiontype']\").val(),":"")."
			'".$TPkTable."':$(\"input[name='".$TPkTable."']\").val(),
			".$jssavedata."
		},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
				$('#InputDialog').modal('hide');
				toastr.info(data.div);
				$.fn.yiiGridView.update(\"GridList\");
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
}\n";
			}
			else
				if ($_POST['iswrite'] == 1)
			{
			$TJSSaveData .= "\nfunction savedata".$table."()
{
".$jstinyint."
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/save".$table."')?>',
		'data':{
			'".$TPkTable."':$(\"input[name='".$TPkTable."']\").val(),
			".$jssavedata."
		},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
				$('#InputDialog".$table."').modal('hide');
				toastr.info(data.div);
				$.fn.yiiGridView.update(\"".$table."List\");
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
}\n";
			}
			
			if (($TJSApproveData == "") && ($_POST['ispost'] == 1))
			{
				$TJSApproveData = "function approvedata(\$id)
{
	\$.msg.confirmation('Confirm','<?php echo \$this->getCatalog('areyousure')?>',function(){	
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/approve')?>',
		'data':{'id':\$id},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
				toastr.info(data.div);
				$.fn.yiiGridView.update(\"GridList\");
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});	});
	return false;
}";
			}
			
			if (($TJSDeleteData == "") && ($_POST['isreject'] == 1))
			{
				$TJSDeleteData = "function deletedata(\$id)
{
	$.msg.confirmation('Confirm','<?php echo \$this->getCatalog('areyousure')?>',function(){	
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/delete')?>',
		'data':{'id':\$id},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
				toastr.info(data.div);
				$.fn.yiiGridView.update(\"GridList\");
			}
			else
			{
				toastr.error(data.div);
			}
		},
	'cache':false});});
	return false;
}\n";			
}
			
			if (($TJSPurgeData == "") && ($_POST['ispurge'] == 1))
			{
			$TJSPurgeData = "function purgedata(\$id)
{
	\$.msg.confirmation('Confirm','<?php echo \$this->getCatalog('areyousure')?>',function(){
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/purge')?>','data':{'id':\$id},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
				toastr.info(data.div);
				$.fn.yiiGridView.update(\"GridList\");
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	});
	return false;
}\n";
			}
			else
				if ($_POST['ispurge'] == 1)
			{
			$TJSPurgeData .= "function purgedata".$table."()
{
	\$.msg.confirmation('Confirm','<?php echo \$this->getCatalog('areyousure')?>',function(){
	jQuery.ajax({'url':'<?php echo Yii::app()->createUrl('".strtolower($_POST['module'])."/".$_POST['menuname']."/purge".$table."')?>','data':{'id':$.fn.yiiGridView.getSelection(\"".$table."List\")},
		'type':'post','dataType':'json',
		'success':function(data) {
			if (data.status == \"success\")
			{
				toastr.info(data.div);
				$.fn.yiiGridView.update(\"".$table."List\");
			}
			else
			{
				toastr.error(data.div);
			}
		},
		'cache':false});
	});
	return false;
}\n";
			}
			
			if ($TJSSearchData == "")
			{
				$TJSSearchData = "function searchdata(\$id=0)
{
	\$('#SearchDialog').modal('hide');
	".$jssearch.";
	$.fn.yiiGridView.update(\"GridList\",{data:array});
	return false;
}\n";
			}
			
			if ($TJSDownPDF == "")
			{
			$TJSDownPDF = "function downpdf(\$id=0) {
	".$jssearch.";
	window.open('<?php echo Yii::app()->createUrl('".$_POST['module']."/".$_POST['menuname']."/downpdf')?>?'+array);
}\n";
			}
			
			if ($TJSDownXLS == "")
			{
			$TJSDownXLS = "function downxls(\$id=0) {
	".$jssearch.";
	window.open('<?php echo Yii::app()->createUrl('".$_POST['module']."/".$_POST['menuname']."/downxls')?>?'+array);
}\n";
			}
			
			if ((count($tables) > 1) && ($tabelke > 0))
			{
				$jsgetdetail .= "\n\$.fn.yiiGridView.update(\"Detail".$table."List\",{data:array});";
			}

			if (($tabelke > 0))
			{
				$showdetail .= "<div class=\"box box-primary\">
	<div class=\"box-header with-border\">
		<h3 class=\"box-title\"><?php echo \$this->getCatalog('".$table."')?></h3>
		<div class=\"box-tools pull-right\">
			<button class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i></button>
		</div>
	</div><!-- /.box-header -->		
<div class=\"box-body\">
				<?php
	\$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>\$dataProvider".$table.",
		'id'=>'Detail".$table."List',
    'ajaxUpdate'=>true,
		'filter'=>null,
		'enableSorting'=>true,
		'columns'=>array(
			".$griddetails."
		)
));?>
		</div>		
		</div>		
				";
			}
			
			if ((count($tabelke) > 0) && ($tabelke >0))
			{
				$TGridDetailColumns = "array
			(
				'class'=>'CButtonColumn',
				'template'=>'{edit} {purge}',
				'htmlOptions' => array('style'=>'width:160px'),
				'buttons'=>array
				(
					'edit' => array
					(
							'label'=>\$this->getCatalog('edit'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/edit.png',
							".(($_POST['iswrite'] == 0)?"'visible'=>'false',":"'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','iswrite')),")."							
							'url'=>'\"#\"',
							'click'=>\"function() { 
								updatedata".$table."($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
					'purge' => array
					(
							'label'=>\$this->getCatalog('purge'),
							'imageUrl'=>Yii::app()->baseUrl.'/images/trash.png',
							".(($_POST['ispurge'] == 0)?"'visible'=>'false',":"'visible'=>\$this->booltostr(\$this->checkAccess('".$_POST['menuname']."','ispurge')),")."							
							'url'=>'\"#\"',
							'click'=>\"function() { 
								purgedata".$table."($(this).parent().parent().children(':nth-child(3)').text());
							}\",
					),
				),
			),\n";
				$TDetailMasterForm .= "\n<div id=\"".$table."\" class=\"tab-pane\">
	<?php if (\$this->checkAccess('".$_POST['menuname']."','iswrite')) { ?>
<button name=\"CreateButton".$table."\" type=\"button\" class=\"btn btn-primary\" onclick=\"newdata".$table."()\"><?php echo \$this->getCatalog('new')?></button>
<?php } ?>
<?php if (\$this->checkAccess('".$_POST['menuname']."','ispurge')) { ?>
<button name=\"PurgeButton".$table."\" type=\"button\" class=\"btn btn-danger\" onclick=\"purgedata".$table."()\"><?php echo \$this->getCatalog('purge')?></button>
<?php } ?>
    <?php
	\$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>\$dataProvider".$table.",
		'id'=>'".$table."List',
		'selectableRows'=>2,
    'ajaxUpdate'=>true,
		'filter'=>null,
		'enableSorting'=>true,
		'columns'=>array(
			array(
				'class'=>'CCheckBoxColumn',
				'id'=>'ids',
			),
			".$TGridDetailColumns."
			".$griddetails."
		)
));
?>
  </div>";
			}
			
			if ((count($tables) > 1) && ($tabelke > 0))
			{
				$tabs .= "<li><a data-toggle=\"tab\" href=\"#".$table."\"><?php echo \$this->getCatalog(\"".$table."\")?></a></li>\n";
			}
			
			if ((count($tables) > 1) && ($tabelke > 0))
			{
				$TInputDetailForm .= "<div id=\"InputDialog".$table."\" class=\"modal fade\" role=\"dialog\">
	<div class=\"modal-dialog\">

    <!-- Modal content-->
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
        <h4 class=\"modal-title\"><?php echo \$this->getCatalog('".$table."') ?></h4>
      </div>
			<div class=\"modal-body\">
			".$inputdetailform."
			</div>
			      <div class=\"modal-footer\">
				<button type=\"submit\" class=\"btn btn-success\" onclick=\"savedata".$table."()\"><?php echo \$this->getCatalog('save') ?></button>
        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\"><?php echo \$this->getCatalog('close') ?></button>
      </div>
			</div>
			</div>
			</div>
			";	
			}
			
			$tabelke++;
		}
		
		
		$TActionIndex .= $dataProvider."
		\$this->render('index',array('dataProvider'=>\$dataProvider".$ExtProviders."));
	}\n";

			$TJSGetDetail = "function GetDetail(\$id)
{
	$('#ShowDetailDialog').modal('show');
	var array = '".$TPkTable."='+\$id".$jsgetdetail."
} ";

	
		if ($_POST['iswrite'] == 1)
		{
			$TIsNewData = "<?php if (\$this->checkAccess('".$_POST['menuname']."','iswrite')) { ?>
<button name=\"CreateButton\" type=\"button\" class=\"btn btn-primary\" onclick=\"newdata()\"><?php echo \$this->getCatalog('new')?></button>
<?php } ?>";
		}
		
		if ($_POST['ispurge'] == 1)
		{
			$TIsPurgeData = "<?php if (\$this->checkAccess('".$_POST['menuname']."','ispurge')) { ?>
<button name=\"PurgeButton\" type=\"button\" class=\"btn btn-danger\" onclick=\"purgedata($.fn.yiiGridView.getSelection('GridList'))\"><?php echo \$this->getCatalog('purge')?></button>
<?php } ?>";
		}
		
		if ($_POST['ispost'] == 1)
		{
			$TIsApproveData = "<?php if (\$this->checkAccess('".$_POST['menuname']."','ispost')) { ?>
<button name=\"ApproveButton\" type=\"button\" class=\"btn btn-success\" onclick=\"approvedata($.fn.yiiGridView.getSelection('GridList'))\"><?php echo \$this->getCatalog('approve')?></button>
<?php } ?>";
		}
		
		if (($_POST['isreject'] == 1) && ($_POST['ispost'] == 0))
		{
			$TIsDeleteData = "<?php if (\$this->checkAccess('".$_POST['menuname']."','isreject')) { ?>
<button name=\"DeleteButton\" type=\"button\" class=\"btn btn-warning\" onclick=\"deletedata($.fn.yiiGridView.getSelection('GridList'))\"><?php echo \$this->getCatalog('delete')?></button>
<?php } ?>";
		} else 
		if (($_POST['isreject'] == 1) && ($_POST['ispost'] == 1))
				{
			$TIsDeleteData = "<?php if (\$this->checkAccess('".$_POST['menuname']."','isreject')) { ?>
<button name=\"DeleteButton\" type=\"button\" class=\"btn btn-warning\" onclick=\"deletedata($.fn.yiiGridView.getSelection('GridList'))\"><?php echo \$this->getCatalog('reject')?></button>
<?php } ?>";
		}
		if ($_POST['isdownload'] == 1)
		{
			$TIsDownload = "<?php if (\$this->checkAccess('".$_POST['menuname']."','isdownload')) { ?>
  <div class=\"btn-group\">
    <button type=\"button\" class=\"btn btn-primary dropdown-toggle\" data-toggle=\"dropdown\">
    <?php echo \$this->getCatalog('download')?> <span class=\"caret\"></span></button>
    <ul class=\"dropdown-menu\" role=\"menu\">
      <li><a onclick=\"downpdf(\$.fn.yiiGridView.getSelection('GridList'))\"><?php echo \$this->getCatalog('downpdf')?></a></li>
      <li><a onclick=\"downxls(\$.fn.yiiGridView.getSelection('GridList'))\"><?php echo \$this->getCatalog('downxls')?></a></li>
    </ul>
  </div>
<?php } ?>";
		}
		
		if (count($tables) > 1)
		{
			$TShowDetail = "<div id=\"ShowDetailDialog\" class=\"modal fade\" role=\"dialog\">
	<div class=\"modal-dialog modal-lg\">
		<div class=\"modal-content\">
			<div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
      </div>
				<div class=\"modal-body\">
			".$showdetail."
			</div>
			</div>
			</div>
			</div>
			";
		}
		
		if (count($tables)>1)
		{
			$TGridDetailMasterForm .= "<ul class=\"nav nav-tabs\">
				".$tabs."
				</ul>
				<div class=\"tab-content\">
				".$TDetailMasterForm."
				</div>";
		}
		
		$TJSNewData = str_replace("TDetailGrids",$DetailGrids,$TJSNewData);
		$TJSUpdateData = str_replace("TDetailGrids",$DetailGrids,$TJSUpdateData);
		$this->replace_in_file($controlfile,'TController',$_POST['controller'].'Controller');
		$this->replace_in_file($controlfile,'TMenuName',"'".$_POST['menuname']."'");
		$this->replace_in_file($controlfile,'TModule',"'".$_POST['module']."'");
		$this->replace_in_file($controlfile,'TPageTitle',"'".$this->getCatalog($_POST['menuname'])."'");
		$this->replace_in_file($controlfile,'TPkTable',"'".$TPkTable."'");
		$this->replace_in_file($controlfile,'TApproveWorkflow',"public \$wfname = '".$_POST['appwf']."';");
		$this->replace_in_file($controlfile,'TSqlData',$TSqlData);
		$this->replace_in_file($controlfile,'TSqlCount',$TSqlCount);
		$this->replace_in_file($controlfile,'TWhere',$TWhere."\"");
		$this->replace_in_file($controlfile,'TAddOnWhere',$TAddOnWhere);
		$this->replace_in_file($controlfile,'TRequest',$TRequest);
		$this->replace_in_file($controlfile,'TActionIndex',$TActionIndex);
		$this->replace_in_file($controlfile,'TActionCreate',$TActionCreate);
		$this->replace_in_file($controlfile,'TActionUpdate',$TActionUpdate);
		$this->replace_in_file($controlfile,'TActionSave',$TActionSave);
		$this->replace_in_file($controlfile,'TActionApprove',$TActionApprove);
		$this->replace_in_file($controlfile,'TActionDelete',$TActionDelete);
		$this->replace_in_file($controlfile,'TActionPurge',$TActionPurge);
		$this->replace_in_file($controlfile,'TActionDownPDF',$TActionDownPDF);
		$this->replace_in_file($controlfile,'TActionDownXLS',$TActionDownXLS);
		$this->replace_in_file($controlfile,'TRPkTable',"if (isset(\$_REQUEST['".$TPkTable."']))
			{
				if ((\$_REQUEST['".$TPkTable."'] !== '0') && (\$_REQUEST['".$TPkTable."'] !== ''))
				{
					if (\$where == \"\")
					{
						\$where .= \" where a0.".$TPkTable." in (\".\$_REQUEST['".$TPkTable."'].\")\";
					}
					else
					{
						\$where .= \" and a0.".$TPkTable." in (\".\$_REQUEST['".$TPkTable."'].\")\";
					}
				}
			}");
		$this->replace_in_file($viewfile,'TMenuName',$_POST['menuname']);
		$this->replace_in_file($viewfile,'TModule',$_POST['module']);
		$this->replace_in_file($viewfile,'TJSNewData',$TJSNewData);
		$this->replace_in_file($viewfile,'TDetailGrids',$DetailGrids);
		$this->replace_in_file($viewfile,'TJSUpdateData',$TJSUpdateData);
		$this->replace_in_file($viewfile,'TJSSaveData',$TJSSaveData);
		$this->replace_in_file($viewfile,'TJSApproveData',$TJSApproveData);
		$this->replace_in_file($viewfile,'TJSDeleteData',$TJSDeleteData);
		$this->replace_in_file($viewfile,'TJSPurgeData',$TJSPurgeData);
		$this->replace_in_file($viewfile,'TJSSearchData',$TJSSearchData);
		$this->replace_in_file($viewfile,'TJSDownPDF',$TJSDownPDF);
		$this->replace_in_file($viewfile,'TJSDownXLS',$TJSDownXLS);
		$this->replace_in_file($viewfile,'TJSGetDetail',$TJSGetDetail);
		$this->replace_in_file($viewfile,'TGridColumns',$TGridColumns);
		$this->replace_in_file($viewfile,'TSearchForm',$TSearchForm);
		$this->replace_in_file($viewfile,'TInputMasterForm',$TInputMasterForm);
		$this->replace_in_file($viewfile,'TGridDetailMasterForm',$TGridDetailMasterForm);
		$this->replace_in_file($viewfile,'TIsNewData',$TIsNewData);
		$this->replace_in_file($viewfile,'TIsApproveData',$TIsApproveData);
		$this->replace_in_file($viewfile,'TIsDeleteData',$TIsDeleteData);
		$this->replace_in_file($viewfile,'TIsPurgeData',$TIsPurgeData);
		$this->replace_in_file($viewfile,'TIsDownload',$TIsDownload);
		$this->replace_in_file($viewfile,'TShowDetail',$TShowDetail);
		$this->replace_in_file($viewfile,'TInputDetailForm',$TInputDetailForm);
		
		//todo ambil moduleid dan menuparentid
		$connection = Yii::app()->db;
		$sql = "select moduleid from modules where lower(modulename) = lower('".$_POST['module']."')";
		$module = $connection->createCommand($sql)->queryScalar();
		if ($module == 0)
		{			
			$sql = "insert into modules (modulename,description,createdby,moduleversion,installdate,themeid,recordstatus) 
					values (lower('".$_POST['module']."'),'".$_POST['module']." Module','Prisma Data Abadi','0.1',now(),2,1)";
			$connection->createCommand($sql)->execute();
			$sql = "select last_insert_id();";
			$module = $connection->createCommand($sql)->queryScalar();
		}
		
		// todo find menu parent
		$sql = "select menuaccessid from menuaccess where lower(menuname) = lower('".$_POST['module']."')";
		$menuparent = $connection->createCommand($sql)->queryScalar();
		if ($menuparent == 0)
		{		
			$sql = "select max(sortorder) from menuaccess where parentid is null";
			$sortorder = $connection->createCommand($sql)->queryScalar();
			$sql = "insert INTO `menuaccess` (`menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) 
				VALUES ('".strtolower($_POST['module'])."','".$_POST['module']."','".$_POST['module']."',".$module.",null,null,".$sortorder.",1)";
			$connection->createCommand($sql)->execute();
			$sql = "select last_insert_id();";
			$menuparent = $connection->createCommand($sql)->queryScalar();
		}
		
		$sql = "
			replace into groupmenu (groupaccessid,menuaccessid,isread,iswrite,ispost,isreject,ispurge,isupload,isdownload)
			select 1,".$menuparent.",1,1,1,1,1,1,1
			from menuaccess 
			where moduleid = ".$module."
			";
		$connection->createCommand($sql)->execute();	
		
		//todo cek menu jika tidak ada buat menu
		$sql = "select menuaccessid from menuaccess where lower(menuname) = lower('".$_POST['menuname']."')";
		$menu = $connection->createCommand($sql)->queryScalar();
		if ($menu == 0)
		{			
			$sql = "INSERT INTO `menuaccess` (`menuname`, `menutitle`, `description`, `moduleid`, `parentid`, `menuurl`, `sortorder`, `recordstatus`) 
				VALUES ('".strtolower($_POST['menuname'])."','".$_POST['menuname']."','".$_POST['menuname']."',".$module.",".$menuparent.",'".$_POST['menuname']."',1,1)";
			$connection->createCommand($sql)->execute();
			$sql = "select last_insert_id();";
			$menu = $connection->createCommand($sql)->queryScalar();
		}
		
		//todo authorize menu
		$sql = "
			replace into groupmenu (groupaccessid,menuaccessid,isread,iswrite,ispost,isreject,ispurge,isupload,isdownload)
			select 1,".$menu.",1,1,1,1,1,1,1
			from menuaccess 
			where moduleid = ".$module."
			";
		$connection->createCommand($sql)->execute();	
		getmessage('success','alreadysaved');
	}
	else
	{
		getmessage('error','You must entry first question');
	}
		}
	catch (CException $e)
		{
			getmessage('error',$e->getMessage());
		}
	}
}