<?php

// define this directory
define('DEVTOOLS_DIR', 'dev-tools' );

// create definition of site name (based on SS_PRIMARY_DOMAIN)
$siteName = SS_PRIMARY_DOMAIN;
$siteName = str_replace('http://','',$siteName);
$siteName = str_replace('https://','',$siteName);
$siteName = str_replace('.','_',$siteName);
define('SS_SITE_NAME', $siteName );

if (ShouldRedirect()){

	// Set our destination to our primary domain
	define('DEVTOOLS_REDIRECT_DESTINATION', SS_PRIMARY_DOMAIN);
	
	// if we're disabled, include our cms-disabling javascript
	Requirements::customScript('var ss_primary_domain = "'.SS_PRIMARY_DOMAIN.'";');
	LeftAndMain::require_javascript(DEVTOOLS_DIR . '/js/disable-cms.js');

} else {
	define('DEVTOOLS_REDIRECT_DESTINATION', false);
}

// Plug our BugHerd requirements in to the CMS (if enabled)
if ($project_key = Config::inst()->get('DevTools','bugherd_project_key')){

	// Pre-populate the email address with the current logged-in user
	Requirements::customScript('var BugHerdConfig = "'.$config.'";');
	LeftAndMain::require_javascript('https://www.bugherd.com/sidebarv2.js?apikey='.$project_key);
	LeftAndMain::require_css(DEVTOOLS_DIR . '/css/cms-bugherd.css');
}

// add functionality to SiteTree
ContentController::add_extension('DevTools');
SiteConfig::add_extension('DevTools_SiteConfigExtension');
Member::add_extension('DevTools_MemberExtension');

// include css
LeftAndMain::require_css(DEVTOOLS_DIR . '/css/cms.css');

// specify editor css file (this needs to be pure CSS, not SCSS)
HtmlEditorConfig::get('cms')->setOption('content_css', '/site/cms/editor.css');

// enable our log jam logger
LogJam::EnableLog();


/**
 * Detect whether we should redirect to the primary domain
 *
 * @return boolean
 **/
function ShouldRedirect(){

	// Construct our current request's domain name
	$current_request_domain = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

	// Destination not configured
	if (!defined('SS_PRIMARY_DOMAIN')){
		return false;

	// Expressly disabled
	} elseif (!Config::inst()->get('DevTools','disable_primary_domain_redirection')){
		return false;

	// Not in LIVE mode
	} else if (SS_ENVIRONMENT_TYPE != 'live'){
		return false;

	// Not on the right domain, do redirect!
	} elseif ($current_request_domain != SS_PRIMARY_DOMAIN){
		return true;
	}

	// Default to not redirecting. If we've got this far it's likely we
	// encountered some kind of error.
	return false;
}