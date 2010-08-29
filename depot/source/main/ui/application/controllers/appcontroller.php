<?php


class AppController extends Controller 
{



	function view()
	{
	  	$this->logger->debug( "Inside app controller view action");
		$this->set('title', 'appvamp');

//		$appModel = new AppModel();
//		$appModel->getAllApps();
		$this->_template->render();

	}






};



