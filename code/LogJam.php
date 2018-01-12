<?php

namespace PlasticStudio\DevTools;

use SilverStripe\Control\Director;
use SilverStripe\Dev\Backtrace;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

class LogJam {

	/** 
	 * Actually write the log message
	 **/
	static function write($level, $message, $log_file = null){

		// trace the origin call
		$trace = Backtrace::filtered_backtrace();
		$trace = $trace[1];		
		$file = isset($trace['file']) ? $trace['file'] : '';
		$line = isset($trace['line']) ? $trace['line'] : '';
	
		// construct the line in our file
		$output = date('d/m/Y H:i:s')." LogJam ".$level.": ";
		$output.= $message;
		$output.= ' (line '.$line.' in '.$file.')'.PHP_EOL;

		if ($log_file){
			return error_log($output, 3, $log_file);
		} else {
			return error_log($output);
		}
	}
	
	/**
	 * Log something into our log file
	 * @param $environment = string (minimum environment state for logging this message)
	 * @param $message = string (the message body)
	 **/
	static function debug($message = ''){

		$log_file = Config::inst()->get('DevTools','log_jam_debug');
		if (!$log_file){
			return false;
		}

		return LogJam::write("Debug", $message, $log_file);
	}

	
	/**
	 * Log something into our log file
	 * @param $message = string (the message body)
	 **/
	static function info($message = ''){

		// Make sure we're enabled
		$log_file = Config::inst()->get('DevTools','log_jam_info');
		if (!$log_file){
			return false;
		}

		return LogJam::write("Debug", $message, $log_file);
	}

	
	/**
	 * Log something into our log file
	 * @param $message = string (the message body)
	 **/
	static function error($message = ''){

		// Make sure we're enabled
		$log_file = Config::inst()->get('DevTools','log_jam_error');
		if (!$log_file){
			return false;
		}

		return LogJam::write("Error", $message, $log_file);
	}

	
	/**
	 * Log something into our log file
	 * @param $message = string (the message body)
	 * @param $environment = string (minimum environment state for logging this message)
	 **/
	static function log($message = '', $environment = 'live'){
		user_error('Deprecation notice: LogJam::log is deprecated, please use LogJam::info or LogJam::debug instead', E_USER_WARNING);
		LogJam::info($message);
	}
}

