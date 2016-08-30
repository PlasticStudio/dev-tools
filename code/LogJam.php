<?php
class LogJam {
		
	protected static $enabled;
		
	/**
	 * Enable logging
	 **/
	static function EnableLog(){
		static::$enabled = true;	
	}
	
	/**
	 * Log something into our log file
	 * @param $environment = string (minimum environment state for logging this message)
	 * @param $message = string (the message body)
	 **/
	static function Log( $message = '', $environment = 'Dev' ){	
		
		// trace the origin call
		$trace = SS_Backtrace::filtered_backtrace();
		$trace = $trace[0];		
		$file = isset($trace['file']) ? $trace['file'] : '';
		$line = isset($trace['line']) ? $trace['line'] : '';
	
		// construct the line in our file
		$output = 'LogJam: ';
		$output.= $message;
		$output.= ' (line '.$line.' in '.$file.')';
		
		// piggy-back PHP error_log
		error_log( $output );
	}
}