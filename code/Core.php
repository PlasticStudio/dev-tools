<?php

namespace PlasticStudio\DevTools;

use SilverStripe\Core\Extension;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\Director;
use SilverStripe\View\Requirements;
use SilverStripe\View\ArrayData;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Member;

class Core extends Extension {

	public $logger;

    private static $dependencies = [
        'logger' => '%$Psr\Log\LoggerInterface',
    ];

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
		if ($this->ShouldRedirect()){
			
			// construct our destination redirect url
			$redirect = Environment::getEnv('SS_BASE_URL');
			if ($url = $request->getURL()){
                if ($url != 'home'){
                    $redirect .= '/'.$request->getURL();
                }
            }
			
			return $this->owner->redirect($redirect, 301);
		}

		return array();
	}
	
	
	/**
	 * Once the ContentController has been initiated, plug in our CSS (if debug enabled)
	 * @return null
	 **/
	public function onAfterInit(){

		// Include our dev-tools CSS
		if (Config::inst()->get('DevTools','debug')){
			Requirements::css('/resources/plasticstudio/dev-tools/css/dev-tools.css');
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
	 * Action to emulate a specific user
	 * @param $request = HTTPRequest
	 * @return redirect
	 **/
	public function emulateuser($request){
		
		Requirements::clear();
		Requirements::css('/resources/dev-tools/css/dev-tools.css');
		
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
	 * Detect whether we should redirect to the primary domain
	 *
	 * @return boolean
	 **/
	public function ShouldRedirect(){

		// Construct our current request's domain name
		$current_request_domain = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

		// Destination not configured
		if (!Environment::getEnv('SS_BASE_URL')){
			return false;

		// Expressly disabled
		} elseif (Config::inst()->get('DevTools','disable_primary_domain_redirection')){
			return false;

		// Not on the right domain, do redirect!
		} elseif ($current_request_domain != Environment::getEnv('SS_BASE_URL')){
			return true;
		}

		// Default to not redirecting. If we've got this far it's likely we
		// encountered some kind of error.
		return false;
	}
	
	
	/**
	 * Build the debug tools markup for template use
	 * @return HTMLText
	 **/
	public function DebugInfo(){
		
		if (Config::inst()->get('DevTools','debug')){			
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
