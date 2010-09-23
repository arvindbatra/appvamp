<?php



class RecoController extends Controller
{
	function __construct(&$qpacket)
	{
		parent::__construct($qpacket);
		$themeName = 'reco';
		$this->set('themes', $themeName);
		$this->set('themeDir',  '/views' . DS . 'themes' . DS . $themeName );
		
		
		if(array_key_exists('attribute_1', $qpacket))
		{
			$action = $qpacket['attribute_1'];
			$this->set('action', $action);
			$qpacket['action'] = $action;
		}
		
		if(array_key_exists('attribute_2', $qpacket))
		{
			$appId = $qpacket['attribute_2'];
			$this->set('appId', $appId);
			$qpacket['appId'] = $appId;
		}
		
	

	}



	function view()
	{
	  	$this->logger->debug( "Inside reco controller view action");
		$this->set('title', 'Similar App Finder');



	}

	function show()
	{
	  	$this->logger->debug( "Inside reco controller view action");
		$this->set('title', 'Similar App Finder');
		$appModel = new AppModel();

		$appId = $this->get('appId', '');

		if(isset($appId))
		{
			$appInfo = $appModel->getAppInfoById($appId);
			if(isset($appInfo)) {
				$this->set('appInfo', $appInfo);
			}
			else
				$this->set('errorMsg', "Sorry, We couldn't locate the app information");
			$appRecoArr = $appModel->getAppRecommendations($appId);
			if(isset($appRecoArr)) {
				$this->set('appRecoArr', $appRecoArr);
			}
			else
				$this->set('errorMsg', "Sorry, No recommendations found. Please try some other app");


		}
	}

	
	function app_search()
	{
	  	$this->logger->debug( "Inside fetch_app action");
		$this->set('title', 'App Search');
		$appModel = new AppModel();
		
		$fetchAppName = $this->get('app_name', '');
		
		if(isset($fetchAppName))
		{
		  	$this->logger->info("Querying db for app " . $fetchAppName);
			$appInfoArr = $appModel->getAllAppInfosByName($fetchAppName);
			if(isset($appInfoArr))
			{
			  	$this->set('fetchAppInfoArr', $appInfoArr);
				$this->logger->debug('Setting fetchAppInfo' . count($appInfoArr));
			}
			else
				$this->set('errorMsg', 'App not found in DB. Please try something different ');
		}

	}












};
