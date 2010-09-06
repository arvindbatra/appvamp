<?php

class AppReview
{
	public $id;
	public $appId;
	public $appName;
	public $title;
	public $review;
	public $reviewer;
	public $createdAt;


	public function toString()
	{
	  	$string = $this->id . "\t" . $this->appId . "\t" . $this->appName;
		return $string;
	}


}
