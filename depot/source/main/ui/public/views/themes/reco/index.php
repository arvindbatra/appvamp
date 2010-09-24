
<div id="body-container">


<div class="error">
	<?php if(isset($errorMsg)) { 
	  	echo $errorMsg;
	}?>
</div>

<div class="info">
	<?php if(isset($infoMsg)) { 
	  	echo $infoMsg;
	}?>
</div>

<div align="center">

<h1> Similar App  Finder </h1>

<form name="app_search" action="/reco/app_search" method="GET">
Enter app name: <INPUT NAME="app_name" SIZE="100" value="<?php if(isset($app_name)) echo $app_name;?>"/ > <BR/>
<br/>
<input type="submit" value="Search Apps" />
</form>
<div class="nav" >
	<ul>
	<li>
		 Demo Apps: 
	</li>
	<li>
		<a href="/reco/show/24851" >FunScience</a>
	</li> <li>
		<a href="/reco/show/45998" >Pandora radio</a>
	</li><li>
		<a href="/reco/show/11828" >Rock Paper Scissors - Fight!</a>
	</li>
	</ul>

</div>

<div class="spacer" ></div>

<?php if(isset($fetchAppInfoArr)) { ?>
	<?php foreach ($fetchAppInfoArr as $ai) {	?> 
		<a href="/reco/show/<?php echo $ai->id; ?>" ><?php echo $ai->appName ?></a>
		<br/>
	<?php }?>
<?php }?>



</div>
<?php if(isset($appInfo)) { ?>
	<div class="curved-box clearfix">
		<div class="floater">
			<img src="<?php echo $appInfo->imageUrl?>" alt="<?php echo $appInfo->appName; ?>" />
		</div>
		<div class="floater">
			<h2> <?php echo $appInfo->appName; ?> </h2>
			<h4> <?php echo $appInfo->appSeller ?></h4>
			<h4> Genre: <?php echo $appInfo->genre ?></h4>
			<a href="<?php echo $appInfo->originalLink ?>" >iTunes link </a>
		</div>
	</div>
<?php }?>

<?php if(isset($appRecoArr)) { $i=0; ?>
	<h2> <b>Similar Apps: </b></h2>
	<?php if(count($appRecoArr) == 0) { ?>
	<h4> No recommendations found. We are in the process of building our app vocabulary. Sorry for the inconvenience. Please try some other app. </h4> 
	<?php } else { ?>
		<?php foreach($appRecoArr as $reco) { $i++; ?>
			<div class="similar-apps">
				<img src="<?php echo $reco->imageUrl?>" alt="<?php echo $reco->appName; ?>" > 
				<div>
					<a href="/reco/show/<?php echo $reco->id; ?>" >
						<?php echo $reco->appName; ?> <br/>
						<?php echo $reco->appSeller; ?> 
					</a>
				</div>
				</img>
			</div>
		<?php } ?>
	<?php } ?>
<?php } ?>


</div>
