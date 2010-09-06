<?php



class AppPost
{
	public $id;
	public $appInfo;
	public $appReview;
	public $appName; 
	public $onDate;
	public $createdAt;
	public $updatedAt;


	public function toString()
	{
		$string = $this->id . "\t" . $this->appName . "\t";
		if(isset($this->appInfo))
		{
		  	$string .= $this->appInfo->toString() . "\t";
		}
		if(isset($this->appReview))
		{
		  	$string .= $this->appReview->toString() . "\t";
		}
		return $string;
	}

}
