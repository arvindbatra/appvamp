<?php


class AppController extends Controller 
{



	function view()
	{
	  	$this->logger->debug( "Inside app controller view action");
		$this->set('title', 'appvamp');

		$appModel = new AppModel();
		$featuredPost = $appModel->getAppPost();
		if(isset($featuredPost)) {
			$this->set('featuredPost', $featuredPost);
		}

	}






};



