<?php

namespace PlasticStudio\DevTools;

use SilverStripe\Control\Director;
use SilverStripe\Dev\Backtrace;

class LogJam {
		
	protected static $enabled;
		
	/**
	 * Enable logging
	 **/
	static function EnableLog(){
		static::$enabled = true;	
	}
		
	/**
	 * Disable logging (enabled by default)
	 **/
	static function DisableLog(){
		static::$enabled = false;	
	}
	
	/**
	 * Log something into our log file
	 * @param $environment = string (minimum environment state for logging this message)
	 * @param $message = string (the message body)
	 **/
	static function Log( $message = '', $environment = 'dev' ){

		// only proceed if LogJam is enabled
		if( static::$enabled ){
			
			// make sure our log level matches our environment level, otherwise break out
			if (Director::isLive() && $environment != 'live'){
				return false;
			}else if (Director::isTest() && ( $environment != 'live' || $environment != 'test')){
				return false;
			}
			
			// trace the origin call
			$trace = Backtrace::filtered_backtrace();
			$trace = $trace[0];		
			$file = isset($trace['file']) ? $trace['file'] : '';
			$line = isset($trace['line']) ? $trace['line'] : '';
		
			// construct the line in our file
			$output = 'LogJam: ';
			$output.= $message;
			$output.= ' (line '.$line.' in '.$file.')';
			
			// piggy-back PHP error_log
			return error_log( $output );
		}
		
		return false;
	}
}