<?php
class DataPopUp extends Portlet {
	public $ColField; //Textbox untuk menerima return teks 
	public $IDField; //ID untuk menerima return integer
	public $IDDialog;
	public $RelationID;
	public $RelationID2;
	public $titledialog;
	public $PopUpName;
	public $PopGrid;
	public $classtype		 = 'col-md-3';
	public $classtypebox = 'col-md-9';
	public $onclicksign;
	public $onaftersign;
	public $readonly = false;
	protected function renderContent() {
		$this->render($this->PopUpName);
	}
}