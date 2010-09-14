<?php

class AppInfo
{
	public $appName;
	public $appSeller;
	public $id;
	public $appExternalId;
	public $releaseDate;
	public $price;
	public $originalLink;
	public $imageUrl;
	public $requirements;
	public $genre;
	public $appRating;
	public $screenshots;
	public $language;
	public $createdAt;
	public $updatedAt;
	public $description;

	public $screenshotsArr;

	public function __construct()
	{
		$this->screenshotsArr = array();

	}

	public function toString()
	{
		$string =  $this->appName . "\t" . $this->appSeller . "\t" . $this->id  . "\t" . $this->screenshots;

		return $string;
	}


	public function clean()
	{
		$logger = AppLogger::getInstance()->getLogger();
		$images = $this->screenshots;
		$arr = array("[", "]");
		$images = str_replace($arr ,"", $images);
		$imagesArr  = preg_split("/,/", $images);
		$scount = 0;
		for($i = 0; $i < count($imagesArr); $i++)
		{
			$image = $imagesArr[$i];
			$imageArr  = preg_split("/\t/", $image);
			if(count($imageArr) == 2)
			{
			  	//$logger->debug($imageArr[1]);
				$this->screenshotsArr[$scount] = $imageArr[1];
				$scount++;
			}
		}


	}


}
