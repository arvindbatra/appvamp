<?php


class AboutController extends Controller
{
	function __construct($qpacket)
	{
		parent::__construct($qpacket);
		if(array_key_exists('attribute_1', $qpacket))
		{
			$type = $qpacket['attribute_1'];
			$this->set('showAboutType', $type);
			$this->set('viewMode',get_seo_string($type));
		}
	}


	function view()
	{
	  	$this->logger->debug( "Inside about  controller view action");
		$this->set('title', 'appvamp');
		
	}





};
