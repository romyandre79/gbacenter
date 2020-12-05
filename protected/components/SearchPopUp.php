<?php
class SearchPopUp extends Portlet {
  public $searchitems;
  protected function renderContent() {
		$this->render('searchpopup');
	}
}
