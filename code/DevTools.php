<?php
/**
 * @package debugtools
 */
class DevTools extends Extension {

	private static $allowed_actions = array(
		'emulateuser',
		'olddomain'
	);
	
	/**
	 * On render of all pages, detect if a redirect is required
	 * @return array()
	 **/
	public function index($request){

		// Our config has told us we need to redirect
		if (DEVTOOLS_REDIRECT_DESTINATION){
			
			// construct our destination redirect url
			$redirect = DEVTOOLS_REDIRECT_DESTINATION;
			if ($url = $request->getURL()){
                if ($url != 'home'){
                    $redirect .= '/'.$request->getURL();
                }
            }
			
			return $this->owner->redirect(DEVTOOLS_REDIRECT_DESTINATION, 301);
		}

		return array();
	}
	
	
	
	/** 
	 * Action to emulate a specific user
	 * @param $request = HTTPRequest
	 * @return redirect
	 **/
	public function emulateuser($request){
		
		Requirements::clear();
		Requirements::css(DEVTOOLS_DIR .'/css/dev-tools.css');
		
		// not enabled, or not allowed >> get out
		if (!Permission::check('ADMIN')){
			echo 'Permission denied';
			die();
		} elseif (!Config::inst()->get('DevTools','user_emulation')){
			echo 'User emulation not enabled';
			die();
		}

		// get URL parameters
		$params = $this->owner->getRequest()->params();
		
		// URL attribute?
		if (!isset($params['ID'])){
			
			$members = Member::get();
			$membersList = array();
			foreach ($members as $member){
				$membersList[$member->ID] = $member;
			}
			$membersList = ArrayList::create($membersList);			
			$membersList = PaginatedList::create($membersList, $this->owner->getRequest());
			$membersList->setPageLength(20);
			
			return $this->owner->customise(array('Users' => $membersList))->renderWith('EmulateUserPage');
		}
	
		$member = Member::get()->byID( $params['ID'] );
		
		if (!isset($member->ID)){
			echo 'Could not find user by #'. $params['ID'];
			die();
		}
		
		$member->logIn();
		
		return $this->owner->redirect($this->owner->Link());
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
	 * Once the ContentController has been initiated, plug in our CSS (if debug enabled)
	 * @return null
	 **/
	public function onAfterInit(){

		// Include our dev-tools CSS
		if ($this->DebugEnabled()){
			Requirements::css( DEVTOOLS_DIR .'/css/dev-tools.css');
		}

		// Plug in our BugHerd requirements (if enabled)
		if ($project_key = Config::inst()->get('DevTools','bugherd_project_key')){

			// Pre-populate the email address with the current logged-in user
			$config = null;
			if (Member::currentUserID()){
				$config = '
					var BugHerdConfig = {
						"reporter": {
							"email":"'.Member::currentUser()->Email.'",
							"required":"true"
						}
					};';
			}

			Requirements::customScript('
				'.$config.'
				(function (d, t) {
				var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
				bh.type = "text/javascript";
				bh.src = "https://www.bugherd.com/sidebarv2.js?apikey='.$project_key.'";
				s.parentNode.insertBefore(bh, s);
				})(document, "script");
			');
		}
		
		return false;
	}
	
	
	/**
	 * Check if we're enabled
	 * @return boolean
	 **/
	public function DebugEnabled(){

		// We're NOT in dev or test mode
		if (!Director::isTest() && !Director::isDev()){
			return false;

		// Debug not enabled in config
		} elseif (!Config::inst()->get('DevTools','debug')){
			return false;
		}
			
		return true;
	}
	
	
	/**
	 * Build the debug tools markup for template use
	 * @return HTMLText
	 **/
	public function DebugInfo(){
		
		if ($this->DebugEnabled()){			
			$info = ArrayData::create(array(
				'Mode' => (Director::isTest() ? 'TEST' : 'DEV'),
				'TimeToLoad' => round( ( microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"] ), 2)
			));
			return $info->renderWith('DebugInfo');
		}
		
		return false;
	}


	/**
	 * Deprecated methods
	 **/
	public function DebugTools(){
		user_error('Deprecation notice: $DebugTools is deprecated, please use $DebugInfo', E_USER_WARNING);
	}
}
