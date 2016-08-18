<?php

// define this directory
define('DEVTOOLS_DIR', 'dev-tools' );

// detect if we're using the old domain and need to flag the issue
$fullDomain = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
if( $fullDomain != SS_PRIMARY_DOMAIN && SS_ENVIRONMENT_TYPE == 'live' ){
	define('DEVTOOLS_ISOLDDOMAIN', true);
	
	// if we're disabled, include our cms-disabling javascript
	Requirements::customScript('var ss_primary_domain = "'.SS_PRIMARY_DOMAIN.'";');
	LeftAndMain::require_javascript(DEVTOOLS_DIR . '/js/disable-cms.js');
}else{
	define('DEVTOOLS_ISOLDDOMAIN', false);
}

// add functionality to SiteTree
ContentController::add_extension('DebugTools_ContentControllerExtension');
SiteConfig::add_extension('DebugTools_SiteConfigExtension');
Member::add_extension('DebugTools_MemberExtension');

// include css
LeftAndMain::require_css(DEVTOOLS_DIR . '/css/cms.css');

// log my errors
$path = BASE_PATH.'/../logs';
SS_Log::add_writer(new SS_LogFileWriter($path.'/info.log'), SS_Log::WARN, '<=');
SS_Log::add_writer(new SS_LogFileWriter($path.'/errors.log'), SS_Log::ERR);