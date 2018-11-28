<?php
class BreadCrumb extends CWidget{
	public $crumbs = array();
	public function run(){
		$this->render('breadCrumb');
	}
	}
?>