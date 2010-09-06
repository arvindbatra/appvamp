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
	<td> <?php echo $post->appReview->id;?> </td>
	<?php
	
	  
}




?>
