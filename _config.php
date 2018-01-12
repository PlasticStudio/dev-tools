<?php

use SilverStripe\Security\Member;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\SiteConfig\SiteConfig;

Member::add_extension('PlasticStudio\DevTools\MemberExtension');
ContentController::add_extension('PlasticStudio\DevTools\Core');
SiteConfig::add_extension('PlasticStudio\DevTools\SiteConfigExtension');