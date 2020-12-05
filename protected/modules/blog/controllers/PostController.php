<?php
class PostController extends AdminController {
	protected $menuname		 = "post";
	public $module				 = 'blog';
	protected $pageTitle	 = "Post";
	public $sqldata		 = 'select a.postid,a.useraccessid,a.title,a.description,a.metatag,a.slug,a.postupdate,a.postpic,
			a.created,a.recordstatus,b.username
		from post a 
		inner join useraccess b on b.useraccessid = a.useraccessid ';
	public $sqlcount		 = 'select count(1)
		from post a 
		inner join useraccess b on b.useraccessid = a.useraccessid ';
	public $sqlcategory = 'select a.categoryid,a.title,a.parentid,b.title as parenttitle, a.description,a.slug,a.recordstatus 
		from category a
		left join category b on b.categoryid = a.parentid ';
	public function actionRead() {
		//getTheme(false, $this->module);
		$this->pageTitle	 = 'Post';
		$sql1							 = "select distinct a.postid,title,description,metatag,slug,postupdate,b.username,c.categoryid ".
			" from post a ".
			" inner join useraccess b on b.useraccessid = a.useraccessid ".
			" inner join postcategory c on c.postid = a.postid ".
			" where lower(slug) = lower('".$_GET['name']."')";
		$dependency1			 = new CDbCacheDependency('SELECT MAX(postid) FROM post');
		$post							 = Yii::app()->db->cache(1000, $dependency1)->createCommand($sql1)->queryRow();
		$this->metatag		 = explode(',', trim($post['metatag']));
		$this->description = truncateword($post['description'], 1000);
		if (count($post) > 0) {
			$sql				 = "select comment,commentdate,b.username ".
				" from postcomment a ".
				" inner join useraccess b on b.useraccessid = a.useraccessid ".
				" inner join post c on c.postid = a.postid ".
				" where lower(slug) = lower('".$_GET['name']."')".
				" order by commentdate desc ";
			$dependency	 = new CDbCacheDependency('SELECT MAX(postcommentid) FROM postcomment');
			$postcomment = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
			$this->render('read',
				array('title' => $post['title'], 'description' => $post['description'], 'metatag' => $post['metatag'],
				'username' => $post['username'], 'categoryid' => $post['categoryid'], 'postupdate' => $post['postupdate'],
				'postcomment' => $postcomment));
		}
  }
  public function actionUpload() {
		if (!file_exists(Yii::getPathOfAlias('webroot').'/images/blog/')) {
			mkdir(Yii::getPathOfAlias('webroot').'/images/blog/');
		}
		$this->storeFolder = dirname('__FILES__').'/images/blog/';
		parent::actionUpload();
		echo $_FILES['upload']['name'];
	}
	public function actionSearchTag() {
		$this->layout		 = '//layouts/column2';
		$this->pageTitle = 'Search';
		$sql						 = "SELECT postid,title,description,metatag,slug,postupdate,b.username ".
			" FROM post a ".
			" inner join useraccess b on b.useraccessid = a.useraccessid ".
			" where lower(metatag) like '%".$_REQUEST['term']."%'";
		$dependency			 = new CDbCacheDependency('SELECT MAX(postid) FROM post');
		$post						 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
		getTheme(false, $this->module);
		$this->render('search', array('term' => $_REQUEST['term'], 'posts' => $post));
	}
	public function getTag($categoryid) {
		$splittag	 = explode(',', $categoryid);
		$tagku		 = "";
		foreach ($splittag as $tag) {
			$sql				 = "select slug,title ".
				" from category a ".
				" where categoryid = ".$tag;
			$dependency	 = new CDbCacheDependency('SELECT MAX(categoryid) FROM category');
			$tagg				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
			if ($tagku == "") {
				$tagku = "<a href=".$this->createUrl('category/'.$tagg['slug']).">".$tagg['title']."</a>";
			} else {
				$tagku += ",".$tagg['title'];
			}
		}
		return $tagku;
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
			'keyField' => 'postid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'postid', 'title', 'description'
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
		$data			 = Yii::app()->db->createCommand($this->sqldata.
				' where a.postid = '.$_POST['id'])->queryRow();
		$sql			 = "select a.categoryid 
			from postcategory a  
			where a.postid = ".$_POST['id'];
		$category	 = Yii::app()->db->createCommand($sql)->queryAll();
		echo CJSON::encode(array(
			'status' => 'success',
			'data' => $data,
			'category' => $category,
			'allcategory' => getAllCategory()
		));
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('title', 'string', 'emptytitle'),
			array('description', 'string', 'emptydescription'),
			array('metatag', 'string', 'emptymetatag'),
			array('slug', 'string', 'emptyslug'),
		));
		if ($error == false) {
			$userid	 = Yii::app()->db->createCommand("select useraccessid from useraccess where username = '".Yii::app()->user->id."'")->queryScalar();
			$postid	 = $_POST['postid'];
			if ($postid !== '') {
				$sql = "update post set title = :0, description = :1, metatag = :2, slug = :3, postpic = :4 where postid = ".$postid;
			} else {
				$sql = "insert into post (title,description,metatag,slug,postpic,recordstatus,useraccessid,created) 
					values (:0,:1,:2,:3,:4,1,".$userid.",now())";
			}
			$connection	 = Yii::app()->db;
			$transaction = $connection->beginTransaction();
			try {
				$command = $connection->createCommand($sql);
				$command->bindvalue(':0', $_POST['title'], PDO::PARAM_STR);
				$command->bindvalue(':1', $_POST['description'], PDO::PARAM_STR);
				$command->bindvalue(':2', $_POST['metatag'], PDO::PARAM_STR);
				$command->bindvalue(':3', $_POST['slug'], PDO::PARAM_STR);
				$command->bindvalue(':4', $_POST['postpic'], PDO::PARAM_STR);
				$command->execute();
				InsertTransLog($command, $postid, $this->menuname);
				if ($postid == '') {
					$sql		 = "select postid from post where title = '".$_POST['title']."'";
					$id			 = Yii::app()->db->createCommand($sql)->queryRow();
					$postid	 = $id['postid'];
				}
				$i = 0;
				if (count($_POST) > 2) {
					$sql = "delete from postcategory where postid = ".$postid;
					$connection->createCommand($sql)->execute();
					if (isset($_POST['category'])) {
						foreach ($_POST['category'] as $menu) {
							$sql = "insert into postcategory (postid,categoryid)
									values (".$postid.",".$menu.")";
							$connection->createCommand($sql)->execute();
							InsertTransLog($command, $postid);
						}
					}
				}
				$transaction->commit();
				getMessage('success', 'alreadysaved');
			} catch (CDbException $e) {
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
				$sql		 = "select recordstatus from post where postid = ".(int) $_POST['id'];
				$status	 = Yii::app()->db->createCommand($sql)->queryRow();
				if ($status['recordstatus'] == 1) {
					$sql = "update post set recordstatus = 0 where postid = ".(int) $_POST['id'];
				} else
				if ($status['recordstatus'] == 0) {
					$sql = "update post set recordstatus = 1 where postid = ".(int) $_POST['id'];
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
			$sql = "delete from postcategory where postid = ".$_POST['id'];
			Yii::app()->db->createCommand($sql)->execute();
			$sql = "delete from postcomment where postid = ".$_POST['id'];
			Yii::app()->db->createCommand($sql)->execute();
			$sql = "delete from post where postid = ".$_POST['id'];
			Yii::app()->db->createCommand($sql)->execute();
			$transaction->commit();
			getMessage('success', 'alreadysaved');
		} catch (Exception $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
}