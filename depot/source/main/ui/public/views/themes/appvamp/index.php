<?php



if(isset($viewMode))
{
	require_once($themeDir . DS. $viewMode .'.php');
}
else
{
	require_once($themeDir . DS . 'app-data.php');
}

/*
<div class="dark-grey">
	<div id="body-container" >
		<?php if(isset($featuredPost)) { ?>
		<div class="body-box clearfix">
			<div class="featured clearfix">
				<div class="featured-image">
					<h4> <i><?php $date = $featuredPost->onDate;
								$phpdate = strtotime( $date );
								$formattedDate = date('d M Y', $phpdate);
								echo $formattedDate;
							?></i>
					</h4>
					<br>
					<img src="<?php echo $featuredPost->appInfo->imageUrl?>" alt="<?php echo $featuredPost->appInfo->appName; ?>" />
				</div>
				<div class="featured-meta">
					<h2> <?php echo $featuredPost->appName; ?> 
					</h2>
					<h4> <?php echo $featuredPost->appInfo->appSeller ?></h4>
					<h4> Price: <?php echo $featuredPost->appInfo->price ?></h4>
					<h4> Genre: <?php echo $featuredPost->appInfo->genre ?></h4>
					<div style="width:200px" class="curved-box download-app">
						<a href="<?php echo $featuredPost->appInfo->originalLink ?>" >View in iTunes </a>
					</div>
					<br>
				</div>
				<div class="featured-verify">
					<!--div class="fb-login"-->
					<br/>	
					<div class="curved-box download-app">
						<div id="verify-app" loggedin="<?php echo $loggedin?>"><input type="submit" value="Verify App Download" /></div>
						<form action="<?php echo $pageHost?>/account" id="submit-verify-app" method="post" >
							<input type="hidden" name="user_info" value="<?php echo urlencode(json_encode($me))?>">
							<input type="hidden" name="auth_type" value="facebook">
						</form>
					</div>
				</div>

			</div> <!-- end featured  -->
			<div class="post-body ">
				<div class="sidebar">
					<?php for($i=0; $i<count($featuredPost->appInfo->screenshotsArr) && $i<5; $i++) { ?>
						<img src="<?php echo $featuredPost->appInfo->screenshotsArr[$i]; ?>"  />
					<?php }?>
				</div> <!-- end sidebar-->
				
				
				<div class="content">

					<div >
						<p> <?php 	echo $featuredPost->appReview->review ?> </p>
					</div>
					<br>

					<div  >
					<h4><b> App Features </b> </h4>
					<h4> Price:<?php echo $featuredPost->appInfo->price ?></h4>
					<h4> Genre:<?php echo $featuredPost->appInfo->genre ?></h4>
					<h4> <?php echo $featuredPost->appInfo->requirements ?></h4>
					<h4> <?php echo $featuredPost->appInfo->releaseDate ?></h4>
					<h4> <?php echo $featuredPost->appInfo->language ?></h4>

					<!--h4> <span style="height:100px" > Description: <?php $featuredPost->appInfo->description; ?>... </span></h4-->
					</div>
					<br>
					<div class="curved-box download-app">
						<a href="<?php echo $featuredPost->appInfo->originalLink ?>" >Download Now </a>
					</div>
				</div>
			</div>	<!--end post body-->
		</div>	<!--end body box-->
		
		
		<?php if(isset($previousPostsArr)) { ?>
		<div class="body-box clearfix">
		<p align="left" ><h3><b>	Previous Posts </b></h3>  </p>
			<?php foreach($previousPostsArr as $previousPost) { ?>
			  		<div class="previous-posts">
						<img src="<?php echo $previousPost->appInfo->imageUrl?>" alt="<?php echo $previousPost->appInfo->appName; ?>" > 
						<div>
							<a href="/app/<?php $mdate = $previousPost->onDate; echo date('Y/m/d', strtotime($mdate)); ?>/<?php echo (get_seo_string($previousPost->appInfo->appName)); ?>">
								<?php echo $previousPost->appInfo->appName; ?>
							</a>
						</div>
						</img>
					</div>
			<?php } ?>
		</div>
		<?php }?> <!-- end previousPostsArr check-->
		<?php } ?> <!-- end featured post check-->
		<?php if(isset($showAboutType)) { ?>
			<div class="body-box clearfix">
				<?php include_once(get_seo_string($showAboutType) . '.php'); ?>
			</div> 
		<?php } ?>
	</div>	<!-- end body-container -->
</div>		<!-- end dark-grey-->

*/
