<?php
/**
 * @package debugtools
 */
class DebugTools_ContentControllerExtension extends Extension {

	private static $allowed_actions = array(
		'emulateuser',
		'olddomain'
	);
	
	/**
	 * On render of all pages, detect if a redirect is required
	 * @return array()
	 **/
	public function index(){		
		if( DEVTOOLS_ISOLDDOMAIN && SS_PRIMARY_DOMAIN != 'SS_PRIMARY_DOMAIN' ){
			return $this->owner->redirect( SS_PRIMARY_DOMAIN );
		}
		return array();
	}
	
	
	/**
	 * Current client's IP address
	 * @return string
	 **/
	public function ClientIPAddress(){
		$request = $this->owner->getRequest();
		return $_SERVER['REMOTE_ADDR'];
	}
	
	
	
	/** 
	 * Action to emulate a specific user
	 * @param $request = HTTPRequest
	 * @return redirect
	 **/
	public function emulateuser( $request ){
		
		Requirements::clear();
		Requirements::css( DEVTOOLS_DIR .'/css/dev-tools.css');
		
		// not enabled, or not allowed >> get out
		if( !$this->CanEmulateUser() ){
			echo 'You cannot do that';
			die();
		}

		// get URL parameters
		$params = $this->owner->getRequest()->params();
		
		// URL attribute?
		if( !isset($params['ID']) ){
			
			$members = Member::get();
			$membersList = array();
			foreach($members as $member) {
				$membersList[$member->ID] = $member;
			}
			$membersList = ArrayList::create($membersList);			
			$membersList = PaginatedList::create($membersList, $this->owner->getRequest());
			$membersList->setPageLength(20);
			
			return $this->owner->customise( array('Users' => $membersList) )->renderWith('EmulateUserPage');
		}
	
		$member = Member::get()->byID( $params['ID'] );
		
		if( !isset($member->ID) ){
			echo 'Could not find user by #'. $params['ID'];
			die();
		}
		
		$member->logIn();
		
		return $this->owner->redirect($this->owner->Link());
	}
	
	
	/**
	 * Once the ContentController has been initiated, plug in our CSS (if debug enabled)
	 * @return null
	 **/
	public function onAfterInit(){
		if( $this->DebugEnabled() )
			Requirements::css( DEVTOOLS_DIR .'/css/debugtools.css');
		
		return false;
	}
	
	
	/**
	 * Check if we're enabled
	 * @return boolean
	 **/
	public function DebugEnabled(){
		
		// make sure we're in DEV/TEST mode
		if( !Director::isTest() && !Director::isDev() )
			return false;
		
		// also, make sure we haven't been manually disabled
		$config = SiteConfig::current_site_config();
		if( $config->DebugToolsDisabled )
			return false;
			
		return true;
	}
	
	
	/**
	 * Can the current user emulate another user?
	 * @return boolean
	 **/
	public function CanEmulateUser(){
	
		// if we're not an admin, GET OUT	
		if( !Permission::check('ADMIN') )
			return false;
		
		// also, make sure we haven't been manually disabled
		$config = SiteConfig::current_site_config();
		if( $config->EmulateUserDisabled )
			return false;
			
		return true;
	}
	
	
	/**
	 * Build the debug tools markup for template use
	 * @return HTMLText
	 **/
	public function DebugTools(){
		
		if( $this->DebugEnabled() )
			return $this->PageInfo()->renderWith('PageInfo');
		
		return false;
	}
	

	/** 
	 * Once ContentController has been initiated, do our stuff
	 * @return ArrayList
	 **/
	public function PageInfo(){
	
		$mode = 'DEV';
		if( Director::isTest() ) $mode = 'TEST';
		
		$data = array(
			'CanEmulateUser' => $this->CanEmulateUser(),
			'Mode' => $mode,
			'TimeToLoad' => round( ( microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"] ), 2)
		);
		
		// dump out our debug data
		return ArrayData::create($data);
	}
}
