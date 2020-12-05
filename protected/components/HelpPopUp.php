<?php
class HelpPopUp extends Portlet {
  public $helpurl;
  protected function renderContent() {
		$this->render('helppopup');
	}
}
