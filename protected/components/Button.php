<?php
class Button extends Portlet {
  public $menuname = '';
  public $iswrite = true;
  public $ispurge = true;
  public $ispost = false;
  public $isreject = true;
  public $isupload = true;
  public $isdownload = true;
  public $uploadurl = '';
  protected function renderContent() {
		$this->render('button');
	}
}

