<?php
class MediaMgrController extends AdminController {
	protected $menuname = 'mediamgr';
	public $module = 'media';
	protected $pageTitle = 'Media Manager';
	
	public function actionIndex() {
		parent::actionIndex();
		$this->render('index');
	}
	
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
}