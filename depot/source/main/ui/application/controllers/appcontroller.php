<?php


class AppController extends Controller 
{
	function __construct(&$qpacket)
	{
		parent::__construct($qpacket);
		$y = '';
		$m = '';
		$d = '';
		if(array_key_exists('attribute_1', $qpacket)) {
			$y = $qpacket['attribute_1'];
		}
		if(array_key_exists('attribute_2', $qpacket)) {
			$m = $qpacket['attribute_2'];
		}
		if(array_key_exists('attribute_3', $qpacket)) {
			$d = $qpacket['attribute_3'];
		}

		$date = $y.'-'.$m.'-'.$d;
		$this->logger->info("Setting appDate:". $date);
		$this->set('appDate', $date);


		
	
		if(array_key_exists('attribute_4', $qpacket))
		{
			$appName = $qpacket['attribute_4'];
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
		$appDate = $this->get('appDate', '');
		//$featuredPost = $appModel->getAppPost();
		if(empty($appName))
		{
			$this->logger->info("Getting app post of the day");
			$featuredPost = $appModel->getAppPostOfTheDay(null);
		}else
		{
			$this->logger->info("Getting app post" . $appName . ' with date' . $appDate);
			$featuredPost = $appModel->getAppPost($appName, $appDate);
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



