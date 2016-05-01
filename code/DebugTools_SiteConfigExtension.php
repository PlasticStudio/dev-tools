<?php
class DebugTools_SiteConfigExtension extends DataExtension {
	
	public static $db = array(
		'DebugToolsDisabled' => 'Boolean',
		'EmulateUserDisabled' => 'Boolean'		
	);
 
    public function updateCMSFields(FieldList $fields) {
	
		$fields->addFieldToTab("Root.Main", $checkboxField = CheckboxField::create('DebugToolsDisabled', 'Disable debug tools'));
		$checkboxField->setDescription('When enabled, debug will only show if set to <code>DEV</code> or <code>TEST</code> in <code>_ss_environment.php</code>');
		
		$fields->addFieldToTab("Root.Main", $checkboxField = CheckboxField::create('EmulateUserDisabled', 'Disable user emulation'));
		$checkboxField->setDescription('When enabled, <strong>only admins</strong> can log in as any other <code>Member</code> object');
    }
	
	public function EnvironmentIcon(){
	
		if(Director::isLive())
			$mode = 'live';
		else if(Director::isTest())
			$mode = 'test';
		else if(Director::isDev())
			$mode = 'dev';
		
		return '<span class="environment-icon '.$mode.'"></span>';
	}
}