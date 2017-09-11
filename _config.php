<?php

// define this directory
define('DEVTOOLS_DIR', 'dev-tools' );

// create definition of site name (based on SS_PRIMARY_DOMAIN)
$siteName = SS_PRIMARY_DOMAIN;
$siteName = str_replace('http://','',$siteName);
$siteName = str_replace('https://','',$siteName);
$siteName = str_replace('.','_',$siteName);
define('SS_SITE_NAME', $siteName );

// detect if we're using the old domain and need to flag the issue
$fullDomain = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

if (defined('SS_PRIMARY_DOMAIN') && $fullDomain != SS_PRIMARY_DOMAIN && SS_ENVIRONMENT_TYPE == 'live'){
	define('DEVTOOLS_ISOLDDOMAIN', true);
	
	// if we're disabled, include our cms-disabling javascript
	Requirements::customScript('var ss_primary_domain = "'.SS_PRIMARY_DOMAIN.'";');
	LeftAndMain::require_javascript(DEVTOOLS_DIR . '/js/disable-cms.js');
} else {
	define('DEVTOOLS_ISOLDDOMAIN', false);
}

// Plug our BugHerd requirements in to the CMS (if enabled)
if ($project_key = Config::inst()->get('DevTools','bugherd_project_key')){

	// Pre-populate the email address with the current logged-in user
	Requirements::customScript('var BugHerdConfig = "'.$config.'";');
	LeftAndMain::require_javascript('https://www.bugherd.com/sidebarv2.js?apikey='.$project_key);
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
