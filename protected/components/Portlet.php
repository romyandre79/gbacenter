<?php
Yii::import('zii.widgets.CPortlet');
class Portlet extends CPortlet {
  	public $options				 = array(
		'addRemoveLinks' => true,
	);
	public $dict					 = array(
		'dictDefaultMessage' => 'Drop files here or click to Upload',
		'dictFallbackMessage' => 'Your Browser doesn\'t support',
		'dictInvalidFileType' => 'File Type not allowed (only zip)',
		'dictFileTooBig' => 'Your File Too Big',
		'dictResponseError' => 'Oops! something wrong',
		'dictCancelUpload' => 'Cancelled',
		'dictCancelUploadConfirmation' => 'Are you sure to cancel this upload ?',
		'dictRemoveFile' => 'Delete',
		'dictMaxFilesExceeded' => 'Maximum file exceeded',
	);
	public function WidgetRegistration($widgetname, $widgettitle, $widgetby,
																		$widgetversion, $description, $widgeturl, $modulename) {
		$sql				 = "select ifnull(count(1),0) as jumlah ".
			" from widget a ".
			" where lower(widgetname) = lower('".$widgetname."')";
		$dependency	 = new CDbCacheDependency('SELECT MAX(widgetid) FROM widget');
		$menu				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
		if ($menu['jumlah'] == 0) {
			$sql				 = "select ifnull(moduleid,0) as moduleid ".
				" from modules a ".
				" where lower(modulename) = lower('".$modulename."')";
			$dependency	 = new CDbCacheDependency('SELECT MAX(moduleid) FROM modules');
			$moduleid		 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
			if ($moduleid > 0) {
				$sql = "insert into widget (widgetname,widgettitle,widgetby,widgetversion,description,widgeturl,moduleid ".
					" values ('".$widgetname."','".$widgettitle."','".$widgetby."','".$widgetversion."','".$description."','".$widgeturl."',".$moduleid.")";
				Yii::app()->db->execute();
			}
		}
	}
}