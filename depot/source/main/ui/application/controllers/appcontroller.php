<?php


class AppController extends Controller 
{



	function view()
	{
	  	echo "Inside app controller view action<br>";
		$this->set('title', 'appvamp');

		$appModel = new AppModel();
		$appModel->getAllApps();
		$this->_template->render();

	}






};



