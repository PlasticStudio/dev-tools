<?php

SilverStripe\Security\Member::add_extension('PlasticStudio\DevTools\MemberExtension');
SilverStripe\CMS\Controllers\ContentController::add_extension('PlasticStudio\DevTools\Core');
SilverStripe\SiteConfig\SiteConfig::add_extension('PlasticStudio\DevTools\SiteConfigExtension');