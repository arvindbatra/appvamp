<?php



class AdminController extends Controller 
{

	function __construct(&$qpacket)
	{
		parent::__construct($qpacket);
		$themeName = 'admin';
		$this->set('themes', $themeName);
		$this->set('themeDir',  '/views' . DS . 'themes' . DS . $themeName );
	
		if(array_key_exists('attribute_1', $qpacket))
		{
			$action = $qpacket['attribute_1'];
			$this->set('action', $action);
			$qpacket['action'] = $action;
		}
	}

	function view()
	{
	  	$this->logger->debug( "Inside admin controller view action");
		$this->set('title', 'appvamp');
	
		

	}


	function add_review()
	{
	  	$this->logger->debug( "Inside admin controller add_review action");
		$this->set('title', 'add review');


	}

	function fetch_app()
	{


	  	$this->logger->debug( "Inside fetch_app action");
		$this->set('title', 'add review');
		$appModel = new AppModel();
		
		$fetchAppName = $this->get('app_name', '');
		$fetchAppUrl = $this->get('app_url','');


		$checkUrl = false;
		//check if app name is present
		if(isset($fetchAppName))
		{
		  	$this->logger->info("Querying db for app " . $fetchAppName);
			$appInfo = $appModel->getAppInfo($fetchAppName);
			if(isset($appInfo))
			{
			  	$this->set('fetchAppInfo', $appInfo);
				$this->logger->debug('Setting fetchAppInfo');
				return;
			}
			else
			  	$checkUrl = true;
			
		}
		else
		  	$checkUrl = true;
		if(!isset($fetchAppUrl) || empty($fetchAppUrl)) 
		  	$checkUrl = false;
		
	


		if($checkUrl)
		{
		  	$this->logger->info("Fetching url " . $fetchAppUrl);
			$resp = $appModel->fetchApp($fetchAppUrl);

			if(strcmp($resp, 'true') == 0)
			{
		  		$this->logger->info("Successful in Fetching url " . $fetchAppUrl);
				$appInfo = $appModel->getAppInfoByUrl($fetchAppUrl);
				if(isset($appInfo))
				{
				  	$this->set('fetchAppInfo', $appInfo);
					$this->logger->debug('Setting fetchAppInfo');
					return;
				}
	
			}
			else
			{
				$this->set('errorMsg', 'Failed in fetching app url : '.$fetchAppUrl . "Please contact sysadmin");
			}
		
		}
		else
		{
				$this->set('errorMsg', 'App not found in DB. Please enter a valid url to fetch it. Thanks! ');
				

		}
		//$this->_template->render();

	}

	public function submit_review()
	{
		$this->set('title', 'Submit review');
		$appModel = new AppModel();
		$appName = $this->get('app_name', '');
		if(isset($appName))
		{
		  	$this->logger->info("Querying db for app " . $appName);
			$appInfo = $appModel->getAppInfo($appName);
			if(isset($appInfo))
			{
			  	$this->set('fetchAppInfo', $appInfo);
				$this->logger->debug('Setting fetchAppInfo');
			}
		}

		$appReview = $this->get('app_review','');
		$appReviewTitle  = $this->get('app_review_title','');
		$appReviewer = $this->get('app_reviewer','');
		$appId = $this->get('app_id', '');
		if(empty($appId))
		{
			$this->set('errorMsg', 'App_Id mising, review is not inserted');
			return;
		}
		$this->logger->debug($appReview);

		if($appModel->insertAppReview($appId, $appName, $appReviewer, $appReviewTitle, $appReview))
		{
			$this->set('infoMsg', 'Review insertion succesful!!');
			return;
		}
		else
			$this->set('errorMsg', 'Error in inserting Review');



	}


	function view_schedule()
	{
	  	$this->logger->debug( "Inside admin controller view schedule action");
		$this->set('title', 'view schedule');

	}


	function fetch_schedule()
	{
		$this->set('title', 'Submit review');
		$appModel = new AppModel();
		$scheduleStartDate = $this->get('sched_start_date', '' );
		$scheduleEndDate = $this->get('sched_end_date', '' );


		if((!empty($scheduleStartDate)) && (!preg_match("/^([0-9]){4}\-([0-9]){2}\-([0-9]){2}$/i", $scheduleStartDate))) {
			$this->set('errorMsg', 'Error in start date format');
			return;
		}

		if((!empty($scheduleEndDate)) && (!preg_match("/^([0-9]){4}\-([0-9]){2}\-([0-9]){2}$/i", $scheduleEndDate))) {
			$this->set('errorMsg', 'Error in end date format');
			return;
		}


		$this->logger->debug('Start date: ' . $scheduleStartDate . '. End date: ' .$scheduleEndDate);


		$postArr = $appModel->getAppSchedule($scheduleStartDate, $scheduleEndDate);


		if(isset($postArr))
		{
			$num = count($postArr);
			$this->logger->debug('num posts' . $num); 
		  	$this->set('scheduledPosts', $postArr);
		}	
		
	}



	function add_schedule()
	{
		$this->set('title', 'Submit review');
		$appModel = new AppModel();
		$appName = $this->get('app_name', '');
		$appReviewId = $this->get('app_review_id','');
		$scheduleOnDate = $this->get('sched_on_date','');
		if(!isset($appName) || empty($appName))
		{
			$this->set('errorMsg', 'Error: App name not set');
			return;
		}
		if(!isset($appReviewId) || empty($appReviewId))
		{
			$this->set('errorMsg', 'Error: App Review Id not set');
			return;
		}

		$this->logger->info("Querying db for app " . $appName);
		$appInfo = $appModel->getAppInfo($appName);
		$appReview = $appModel->getAppReviewById($appReviewId);

		if(!isset($appInfo))
		{
			$this->set('errorMsg', 'Error: App Info not found for ' . $appName);
			return;
		}
		if(!isset($appReview))
		{
			$this->set('errorMsg', 'Error: App Review not found for ' . $appReviewId);
			return;
		}

		if(strcmp($appInfo->appName,$appReview->appName) != 0)
		{
			$this->set('errorMsg', 'Error: App name - to review mismatch. App Name ' . $appInfo->appName . " app review name:" .  $appReview->appName);
			return;
		}	

		if(!preg_match("/^([0-9]){4}\-([0-9]){2}\-([0-9]){2}$/i", $scheduleOnDate)) {
			$this->set('errorMsg', 'Error in Schedule date format');
			return;
		}
	
		if($appModel->insertAppLineRecord($appInfo->id, $appReviewId, $appName, $scheduleOnDate))
		{
			$this->set("infoMsg", "App schedule record add successful");

		}
		else
			$this->set('errorMsg', 'Error in adding schedule');


	}



};
