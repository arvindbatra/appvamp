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
		<?php /*<div class="featured-verify">
			<br/>	
			<div class="curved-box download-app">
				<div id="verify-app" loggedin="<?php echo $loggedin?>"><input type="submit" value="Verify App Download" /></div>
				<form action="<?php echo $pageHost?>/account" id="submit-verify-app" method="post" >
					<input type="hidden" name="user_info" value="<?php echo urlencode(json_encode($me))?>">
					<input type="hidden" name="auth_type" value="facebook">
				</form>
			</div>
		</div> */?>

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

			<div class="shaded_box" >
				<h4><b> App Features </b> </h4>
				<table>
				<tr>
				<td>
					<h4> Price:<?php echo $featuredPost->appInfo->price ?></h4>
				</td> <td>
					<h4> Genre:<?php echo $featuredPost->appInfo->genre ?></h4>
				</td></tr>
				<tr><td>
				<h4> <?php echo $featuredPost->appInfo->releaseDate ?></h4>
				</td> <td>
				<h4> <?php echo $featuredPost->appInfo->language ?></h4>
				</td></tr>
				<tr><td>
				<h4> <?php echo $featuredPost->appInfo->requirements ?></h4>
				</td></tr>
				</table>

			<!--h4> <span style="height:100px" > Description: <?php $featuredPost->appInfo->description; ?>... </span></h4-->
				<div class="curved-box download-app ">
					<a href="<?php echo $featuredPost->appInfo->originalLink ?>" >Download Now </a>
				</div>
			</div>
		</div>
	</div>	<!--end post body-->
</div>	<!--end body box-->


<?php if(isset($previousPostsArr)) { ?>
<div class="body-box clearfix">
<p align="left" ><h3><b>	Previous Posts </b></h3>  </p>
	<?php foreach($previousPostsArr as $previousPost) { ?>
			<div class="previous-posts">
				<a href="/app/<?php $mdate = $previousPost->onDate; echo date('Y/m/d', strtotime($mdate)); ?>/<?php echo (get_seo_string($previousPost->appInfo->appName)); ?>">
					<img src="<?php echo $previousPost->appInfo->imageUrl?>" alt="<?php echo $previousPost->appInfo->appName; ?>" > 
					<div>
						<?php echo $previousPost->appInfo->appName; ?>
					</div>
					</img>
				</a>
			</div>
	<?php } ?>
</div>
<?php }?> <!-- end previousPostsArr check-->
<?php } ?> <!-- end featured post check-->

