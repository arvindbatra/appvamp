<?php



function print_app_info($appInfo)
{
  	if(!isset($appInfo))
	  	return;
	?> 
	App Id: <?php echo $appInfo->appId; ?> <br> 
	App Name: <?php echo $appInfo->appName; ?> <br> 
	App Seller: <?php echo $appInfo->appSeller; ?> <br> 
	App External Id: <?php echo $appInfo->appExternalId; ?> <br> 
	App Release Date: <?php echo $appInfo->releaseDate; ?> <br> 
	Price: <?php echo $appInfo->price; ?> <br> 
	Description: <?php echo $appInfo->description; ?> <br> 
	<?php 	
}




function print_post_info($post)
{
  	
  	if(!isset($post))
	  	return;
	?> 
	<td> <?php echo $post->appName; ?> </td>
	<td> <?php echo $post->onDate;?> </td>
	<td> <?php echo $post->appReview->id ;?> </td>
	<?php
	
	  
}

function print_review_info($rev)
{
  	
  	if(!isset($rev))
	  	return;
	?> 
	<td> <?php echo $rev->appName; ?> </td>
	<td> <?php echo $rev->id;?> </td>
	<td> <?php echo substr($rev->review, 0 , 200);?> </td>
	<?php
	
	  
}
function get_seo_string($string)
{
  	$string = trim($string);
	$newS = str_replace("-", "hyphen", $string);
	$newS = str_replace("+", "plus", $newS);
	$newS = str_replace(" ", "-", $newS);
	return $newS;

}

function parse_seo_string($string)
{
	$newS = str_replace("-", " ", $string);
	$newS = str_replace("hyphen", "-", $newS);
	$newS = str_replace("plus", "+", $newS);
	return $newS;

}


?>
