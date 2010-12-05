
<?php if(isset($userInfo)) { ?>
<!--div class="body-box clearfix"-->
<input type="hidden" id="user_id" value="<?php echo $userInfo['id']?>">
<div class="clearfix">

<div class="user-post-body clearfix">
	<div class="user-sidebar">

		<div class="curved-box" style="background:white;">
			<div class="section" >
			<h2> <b>Welcome <?php echo $userInfo['name']?>! </b></h2>


			</div>
			<div class="section">
				<b>Your account </b>
			</div>
			<div>
			<ul class="user-meta">
				<li>Available moolah: <br/> <span id="sum_pending">$<?php if(isset($sumPending) ) echo $sumPending; else echo 0;?></span> </li>
				<li>Verified  moolah: <br/> $<?php if(isset($sumVerified) ) echo $sumVerified; else echo 0;?></span> </li>
				<li>Accepted Apps:<br/> <?php if(isset($numAccepted)) echo $numAccepted; else echo 0; ?> </li>
				<li>Cold hard cash you have saved:<br/> $<?php if(isset($sumAccepted)) echo $sumAccepted; else echo 0; ?> </li>
			</ul>
			</div>
			
			<button id="refund-user" value="submit" class="submitBtn" ><span>Pay me, Sucka!</span></button>

			<div class="section"> &nbsp;
			</div>
			<div>
				<a id="update-paypal" href="#"> Update paypal account </a>
			</div>
		</div>
	</div> <!-- end sidebar-->

	<div class="user-content">
		<?php if(isset($userAppArr) && count($userAppArr) > 0) { ?>
			<?php foreach ($userAppArr as $userAppInfo) {	?> 
			<div class="user-app-info clearfix">
				<div class="user-app-image">
					<img src="<?php echo $userAppInfo['img_url']?>" alt="<?php echo $userAppInfo['app_name']; ?>" />
				</div>
				<div class="user-app-meta">
					 <div class="app-name"> <?php echo $userAppInfo['app_name'] ?>  </div>
					 <div>
						 <span> Deal from :<?php $vdate = $userAppInfo['on_date']; 	$phpvdate = strtotime( $vdate ); $formattedvDate = date('d M Y', $phpvdate);echo $formattedvDate; ?></span>
						 	<span> Valid till:<?php echo pretty_date($userAppInfo['till_date']) ?>  </span>
						 <span> Price: $<?php if($userAppInfo['app_price'] != 0 ) echo $userAppInfo['app_price']; else echo 'Free';?>  </span>
						 <span> Refund: $<?php echo $userAppInfo['refund_price'] ?>  </span>
					</div>
					<div>
						 <span> Status:<?php echo $userAppInfo['status'] ?>  </span>
						 <?php if(strcmp('pending',$userAppInfo['status']) == 0 ) {?>
						 	<span> Valid till:<?php echo pretty_date($userAppInfo['till_date']) ?>  </span>
						 <?php } ?>



					</div>
				</div>
			</div>
				
			<?php } ?>
		<?php }?>

	
	</div>
</div>	<!--end post body-->


</div>


<?php }?>

