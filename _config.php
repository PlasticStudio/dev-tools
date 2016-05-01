<?php

// define this directory
define('DEVTOOLS_DIR', 'dev-tools' );

// add functionality to SiteTree
ContentController::add_extension('DebugTools_ContentControllerExtension');
SiteConfig::add_extension('DebugTools_SiteConfigExtension');
Member::add_extension('DebugTools_MemberExtension');