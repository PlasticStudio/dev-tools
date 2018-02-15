<?php
/**
 * @package debugtools
 */
class DevTools extends Extension {
	
	/**
	 * On render of all pages, detect if a redirect is required
	 * @return array()
	 **/
	public function index($request){

		// Our config has told us we need to redirect
		// Our config has told us we need to redirect
		if ($this->ShouldRedirect()){
			
			// construct our destination redirect url
			$redirect = SS_PRIMARY_DOMAIN;
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

	/**
	 * Detect whether we should redirect to the primary domain
	 *
	 * @return boolean
	 **/
	public function ShouldRedirect(){
		// Construct our current request's domain name
		$current_request_domain = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

		// Destination not configured
		if (!defined('SS_PRIMARY_DOMAIN') ){
			return false;
		// Expressly disabled
		} elseif (Config::inst()->get('DevTools','disable_primary_domain_redirection')){
			return false;
		// Not on the right domain, do redirect!
		} elseif ($current_request_domain != SS_PRIMARY_DOMAIN ){
			return true;
		}
		// Default to not redirecting. If we've got this far it's likely we
		// encountered some kind of error.
		return false;
	}
}
