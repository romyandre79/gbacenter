<?php
Yii::import('zii.widgets.grid.CGridView');
class RefreshGridView extends CGridView {
	public $updatingTime = 6000;
	public function registerClientScript() {
		$updateParameters											 = array();
		if (isset($this->ajaxUpdateError))
				$updateParameters['ajaxUpdateError']	 = (strpos($this->ajaxUpdateError,
					'js:') !== 0 ? 'js:' : '').$this->ajaxUpdateError;
		if (isset($this->afterAjaxUpdate))
				$updateParameters['afterAjaxUpdate']	 = (strpos($this->afterAjaxUpdate,
					'js:') !== 0 ? 'js:' : '').$this->afterAjaxUpdate;
		if (isset($this->beforeAjaxUpdate))
				$updateParameters['beforeAjaxUpdate']	 = (strpos($this->beforeAjaxUpdate,
					'js:') !== 0 ? 'js:' : '').$this->beforeAjaxUpdate;
		parent::registerClientScript();
		$id																		 = $this->getId();
		$cs																		 = Yii::app()->getClientScript();
		$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.yiilivegridview.min.js');
		$cs->registerScript(__CLASS__.'# '.$id, "jQuery('#$id').yiiLiveGridView();");
		$cs->registerScript(__CLASS__.'# '.$id.'-live',
			"setInterval(function(){;$.fn.yiiLiveGridView.update( '$id', ".CJavaScript::encode($updateParameters).");}, {$this->updatingTime});"
		);
	}
}