<?php
return array(
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => 'Capella CMS Indonesia',
	'theme' => 'ecourse',
	'preload' => array(
	),
	'import' => array(
		'application.models.*',
		'application.components.*',
		'ext.fpdf.*',
		'ext.yii-mail.YiiMailMessage',
	),
	'components' => array(
    'clientScript' => array('scriptMap' => array(
      'jquery.js'=> false,
      'jquery.min.js' => false,
      'jquery-ui.min.js'=>false,
      'jquery.yiiactiveform.js' => false )),
		'user' => array(
      'class'=>'application.components.WebUser',
			'allowAutoLogin' => true,
		),
		'mail' => array(
			'class' => 'ext.yii-mail.YiiMail',
			'transportType' => 'smtp',
			'transportOptions' => array(
				'host' => '',
				'username' => '',
				'password' => '',
				'port' => '',
			),
			'viewPath' => 'application.views.mail',
			'logging' => true,
			'dryRun' => false
		),
		'request' => array(
			'enableCookieValidation' => true,
		),
		'format' => array(
			'class' => 'application.components.Formatter',
		),
		'widgetFactory' => array(
			'widgets' => array(
        'CListView'=>array(
          'cssFile' => false,
          'enablePagination' => false,
          'summaryText' => false,
          'ajaxUpdate' => true,
          'itemsCssClass' => 'row',
        ),
				'CLinkPager' => array(
					'header' => '',
					'footer' => '',
					'nextPageLabel' => '>',
					'prevPageLabel' => '<',
					'lastPageLabel' => '>>',
					'firstPageLabel' => '<<',
					'cssFile' => false,
					'selectedPageCssClass' => 'active',
          'hiddenPageCssClass' => 'disabled',
          'firstPageCssClass' => 'paginate_button page-item page-link',
          'previousPageCssClass' => 'paginate_button page-item page-link',
          'nextPageCssClass' => 'paginate_button page-item page-link',
          'lastPageCssClass' => 'paginate_button page-item page-link',
          'internalPageCssClass' => 'page-link',
					'htmlOptions' => array(
						'class' => 'pagination',
					)
        ),
				'CGridView' => array(
          'ajaxUpdate' => true,
          'blankDisplay' => '',
					'htmlOptions' => array(
						'class' => 'table-responsive table-hover dataTable dtr-inline',
						'style' => 'cursor: pointer;',
					),
					'pagerCssClass' => 'dataTables_paginate',
					'itemsCssClass' => 'table table-striped table-hover',
					'cssFile' => false,
					'summaryCssClass' => 'dataTables_info',
					'summaryText' => 'Showing {start} to {end} of {count} entries',
					'template' => '{pager}{items}{summary}{pager}'
				),
				'RefreshGridView' => array(
					'htmlOptions' => array(
						'class' => 'table-responsive table-hover dataTable dtr-inline',
						'style' => 'cursor: pointer;',
					),
					'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
					'itemsCssClass' => 'table table-striped table-hover',
					'cssFile' => false,
					'summaryCssClass' => 'dataTables_info',
					'summaryText' => 'Showing {start} to {end} of {count} entries',
					'template' => '{pager}{items}{summary}{pager}',
				),
			)
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'caseSensitive' => false,
			'rules' => array(
				'' => 'site/index',
				'login' => 'site/login',
				'logout' => 'site/logout',
				'<module:[\w-]+>/<controller:[\w-]+>/index/' => '<module>/<controller>/index',
				'<module:[\w-]+>/<controller:[\w-]+>/index/url' => '<module>/<controller>/index',
				'<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<name:[\w-]+>' => '<module>/<controller>/<action>'
			)
		),
		'db' => array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=prismagr_gbacenter',
			'emulatePrepare' => true,
			'username' => 'prism_gbacenter',
			'password' => 'gb4c3nt3r',
			'charset' => 'utf8',
			'initSQLs' => array('set names utf8'),
			'schemaCachingDuration' => 3600,
		),
		'cache'=>array(
			'class'=>'CFileCache',
    ),
    'errorHandler'=>array(
      'errorAction'=>'site/error',
  ),
	),
	'params' => array(
		'install' => false,
    'googleApiKey' => 'AIzaSyCiH2X-10OQmmRbJkviFxMqAivrujTu8N4',
    'TelegramKey'=>'1424329745:AAFjn685z0S78AvXNxQC9N1S0NmLuF1kBp8',
    'TelegramUrl'=>'https://api.telegram.org/bot',
    'TelegramFileUrl'=>'https://api.telegram.org/file/bot',
    'ConnectTimeOut'=>60,
    'TimeOut'=>60,
    'ismaintain'=>false
	),
);