<form name="add_review" action="add_review" method="GET">
<input type="submit" value="Add Review" />
</form>

<form name="view_schedule" action="view_schedule" method="GET">
<input type="submit" value="View Schedule" />
</form>
<form name="reports" action="reports" method="GET">
<input type="submit" value="Reports" />
</form>
<form name="sitemap" action="sitemap" method="GET">
<input type="submit" value="View Sitemap" />
</form>
	
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

<?php 

if((strcmp($action, "add_review") == 0) || (strcmp($action, "fetch_app") == 0) || (strcmp($action, "submit_review") == 0)) { ?>

<form name="fetch_app" action="fetch_app" method="GET">
Enter app url: <INPUT NAME="app_url" SIZE="100" value="<?php if(isset($app_url)) echo $app_url;?>"/ ><BR>
Enter app name: <INPUT NAME="app_name" SIZE="100" value="<?php if(isset($app_name)) echo $app_name;?>"/ > <BR>
<input type="submit" value="Fetch App" />
</form>


		<?php if(isset($fetchAppInfo)) { ?> 
			<div class="curved-box">
				<?php	print_app_info($fetchAppInfo); ?>
			</div>
			<form name="submit_review" action="submit_review" method="POST" >
				AppName: <INPUT NAME="app_name" SIZE="100" value="<?php echo $fetchAppInfo->appName;?>" /><BR>
				AppId: <INPUT NAME="app_id" SIZE="100" value="<?php echo $fetchAppInfo->id;?>" /><BR>
				Reviewer: <INPUT NAME="app_reviewer" SIZE="100" value="<?php if(isset($app_reviewer)) echo $app_reviewer;?>" /><BR>
				Review Title: <INPUT NAME="app_review_title" SIZE="200" value="<?php if(isset($app_review_title)) echo $app_review_title;?>"/><BR>
				App Review: <br><TextArea NAME="app_review" cols="200" rows="20" ><?php if(isset($app_review)) echo $app_review;?></textarea>  <BR>
				<input type="submit" value="Submit Review" />
			</form>
		<?php }?>



<?php } ?>



<?php

if((strcmp($action, "view_schedule") == 0)|| (strcmp($action,"fetch_review") == 0) || (strcmp($action,"fetch_schedule") == 0) || (strcmp($action,"add_schedule") == 0) || (strcmp($action,"update_schedule") == 0))  { ?>


	<form name="fetch_schedule" action="fetch_schedule" method="GET">
		Start date (YYYY-MM-DD): <INPUT NAME="sched_start_date" SIZE="100" value="<?php if(isset($sched_start_date)) echo $sched_start_date;?>" /><BR>
		End date (YYYY-MM-DD):   <INPUT NAME="sched_end_date"   SIZE="100" value="<?php if(isset($sched_end_date)) echo $sched_end_date;?>" /> <BR>
		<input type="submit" value="Fetch Schedule" />
	</form>

	
	<?php if(isset($scheduledPosts)) { ?>
	<div class="curved-box">
	  	<table>
			 <tr> 
			 	<td> App Name</td>
			 	<td> On Date </td>
			 	<td> Till Date </td>
			 	<td> App Price </td>
			 	<td> Refund Price </td>
				<td> App Review Id </td>
			  </tr>
			<?php foreach ($scheduledPosts as $post) {	?> 
				<tr>
				<?php print_post_info($post); ?>
				</tr>
			<?php }?> 
			</table>
	</div>
	<?php } ?>
	<form name="fetch_review" action="fetch_review" method="GET">
		Enter app name: <INPUT NAME="app_name" SIZE="100" value="<?php if(isset($app_name)) echo $app_name;?>"/ > <BR>
		<input type="submit" value="Fetch Review" />
	</form>
	<?php if(isset($appReviewArr)) { ?>
		<div class="curved-box">
			<table>
				<tr>	<td> AppName</td>
						<td> Review-Id </td>
						<td> Review </td>
				</tr>
				<?php foreach ($appReviewArr as $appReview) { ?>
				  	<tr> <?php print_review_info($appReview) ?>
					</tr>
				<?php }?>
			</table>
		</div>
	<?php } ?>

	<form name="add_schedule" action="add_schedule" mehthod="GET">
		App Name: <Input name="app_name" size="40" value="<?php if(isset($app_name)) echo $app_name; ?>" /><BR>
		App Review Id: <Input name="app_review_id" size="40" value="<?php if(isset($app_review_id)) echo $app_review_id; ?>" /><BR>
		Schedule date (YYYY-MM-DD):   <INPUT NAME="sched_on_date"   SIZE="40" value="<?php if(isset($sched_on_date)) echo $sched_on_date;?>" /> <BR>
		Valid till date (YYYY-MM-DD) :   <INPUT NAME="sched_till_date"   SIZE="40" value="<?php if(isset($sched_till_date)) echo $sched_till_date;?>" /> (leave empty if it is same as schedule date)  <BR>
		App Price: <Input name="app_price" size="40" value="<?php if(isset($app_price)) echo $app_price; else echo 0.0; ?>" /><BR>
		Refund Price: <Input name="refund_price" size="40" value="<?php if(isset($refund_price)) echo $refund_price; else echo 0.0; ?>" />(default is 0.0)<BR>
		
		<input type="submit" value="Add Schedule" />
	</form>

	<form name="update_schedule" action="update_schedule" method="GET">
			
	</form>

<?php } ?>

<?php if(strcmp($action,'reports') == 0) { 
	require_once($themeDir . DS. 'showArray.php');
	 if(isset($reportDataArr)) { 
		html_show_array($reportDataArr);
	}
} ?>

<?php if(strcmp($action,'sitemap') == 0) {
	require_once($themeDir . DS. 'sitemap.php');
}
?>
