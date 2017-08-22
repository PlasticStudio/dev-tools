<?php
class DevTools_SiteConfigExtension extends DataExtension {
	
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