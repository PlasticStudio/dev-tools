<?php

namespace PlasticStudio\DevTools;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Control\Director;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;

class SiteConfigExtension extends DataExtension {

	public function updateCMSFields(FieldList $fields){
		if (Director::isLive()){
			$fields->addFieldToTab('Root.Main', LiteralField::create('html','<p class="message good">Your site is running in LIVE/PROD mode.</p>'), 'Title');
		} else if (Director::isTest()){
			$fields->addFieldToTab('Root.Main', LiteralField::create('html','<p class="message notice">Your site is running in TEST/UAT mode. This should only be enabled for testing and development instances.</p>'), 'Title');
		} else if (Director::isDev()){
			$fields->addFieldToTab('Root.Main', LiteralField::create('html','<p class="message error">Your site is running in DEV mode. This should only be enabled for development instances.</p>'), 'Title');
		}
	}


	/**
	 * Extend the title method to prefix the environment type
	 * @return string
	 **/
	public function Title(){	
		if (Director::isLive()){
			$mode = 'PROD';
		} else if (Director::isTest()){
			$mode = 'TEST';
		} else if (Director::isDev()){
			$mode = 'DEV';
		}

		return "[".$mode."] ".$this->owner->Title;
	}
}