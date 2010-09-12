<?php


class AppController extends Controller 
{
	function __construct(&$qpacket)
	{
		parent::__construct($qpacket);
	
		if(array_key_exists('attribute_1', $qpacket))
		{
			$appName = $qpacket['attribute_1'];
			$this->logger->info("Setting appName:". $appName);
			$this->set('appName', $appName);
		}
	}

	function view()
	{
	  	$this->logger->debug( "Inside app controller view action");
		$this->set('title', 'appvamp');

		$appModel = new AppModel();
		$appName = $this->get('appName','');
		//$featuredPost = $appModel->getAppPost();
		if(empty($appName))
		{
			$this->logger->info("Getting app post of the day");
			$featuredPost = $appModel->getAppPostOfTheDay(null);
		}else
		{
			$this->logger->info("Getting app post" . $appName);
			$featuredPost = $appModel->getAppPost($appName);
		}

		if(isset($featuredPost)) {
			$this->set('featuredPost', $featuredPost);
			$date = $featuredPost->onDate;
			$previousPostsArr = $appModel->getPreviousPostsFromDate($date, 6);
			if(isset($previousPostsArr))
			{
				$this->logger->debug('Found previous posts size:' . count($previousPostsArr));
				$this->set('previousPostsArr', $previousPostsArr);
			}
		}
	}

	




};



