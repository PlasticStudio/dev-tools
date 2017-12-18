<?php

namespace PlasticStudio\DevTools;

use SilverStripe\ORM\DataExtension;

/**
 * Injects our betterbutton into the Member dataobject
 **/
class MemberExtension extends DataExtension {
/*
	public function updateBetterButtonsActions( $fields ){
    	$emulateUserButton = EmulateUser_BetterButton::create('Emulate user', '/home/emulateuser/'.$this->owner->ID);
    	$emulateUserButton->removeExtraClass('cms-panel-link');
		$fields->push(
			$emulateUserButton
		);
    }*/
}