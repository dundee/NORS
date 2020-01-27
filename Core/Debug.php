<?php

/**
 * Class for easy debugging. Exception and error handling, time metering.
 *
 * @author Daniel Milde <daniel@milde.cz>
 * @package Core
 */
class Core_Debug
{
	protected static $start_time;
	protected static $end_time;
	protected static $config;

	public static function start(){
		self::$config = Core_Config::singleton();

		// Some settings
		set_error_handler(array('Core_Debug', 'showError'));
		set_exception_handler(array('Core_Debug', 'showException'));
		register_shutdown_function(array('Core_Debug', 'showFatal'));

		ini_set('display_errors', self::$config->debug->display_errors);
		if ( self::$config->debug->time_management ) self::startTimer();
	}

	/**
 	 * starts timing
 	 *
 	 * @return void
 	 */
	public static function startTimer(){
		self::$start_time = mtime();;
	}

	/**
	 * end timing and prints results
	 *
	 * @return void
	 */
	public static function endTimer(){
		self::$end_time = mtime();;

		$request = Core_Request::factory();
		$cache = $request->getVar('cacheTime')
		         ? __('yes')
		           . ' ('
		           . date("d.m.Y H:i:s", $request->getVar('cacheTime'))
		           . ')'
		         : __('no');

		echo __('gen_time') . ': '
		     . round(self::$end_time-self::$start_time, 4)
		     . ' ' . __('seconds') . ', '
		     . __('sql_queries') . ': ' . Core_DB::singleton()->counter . ', '
		     . __('cache') . ': ' . $cache . ', ';
		if(function_exists('memory_get_peak_usage'))
			echo __('memory') . ': '
			     . floor(memory_get_peak_usage() / 1000) . 'kB';
		if(function_exists('get_included_files'))
			echo ', ' . __('included_files')
			     . ': ' . count(get_included_files());
		if ($request->getVar('browser') == 'IE')
			echo '<br />' . __('stop_ie');
	}

	/**
	 * Prints object on screen redable for humans
	 * @param mixed $array Object to be printed
	 * @param boolean $return Should I print result to screen or return it?
	 */
	public static function dump($array, $return = FALSE){
		$output = '<div style="width: 100%;'
		        . 'text-align: left; background: #fff; color: #000;">';
		$output .= '<pre>' . "\n";
		$output .= print_r($array, TRUE);
		$output .= '</pre>';
		$output .= '</div>';
		if ($return) return $output;
		echo $output;
	}

	/**
	 * Prints SQL queries with timing
	 */
	public static function sqlQueries(){
		$db = Core_DB::singleton();
		$request = Core_Request::factory();
		$locale = Core_Locale::factory($request->locale);

		echo '<table id="debug_queries">';
		echo '<tr><th>' . __('queries') . '('
		     . $db->counter . ')</th><th>' . __('time')
		     . '</th><th>' . __('rows').'</th></tr>';
		foreach ($db->queries as $query) {
			echo '<tr>';
			echo '<td>' . $query['query'] . '</td>';
			echo '<td>' . $query['time'] . '</td>';
			echo '<td>' . $query['rows'] . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}

	/**
	 * Prints included files with timing and memory used
	 */
	public static function includedFiles()
	{
		global $included_files;
		if (HIGH_PERFORMANCE) return;
		echo '<table style="margin: 10px auto;">';
		echo '<tr><th>' . __('file') . '</th><th>'
		     . __('time') . '</th><th>' . __('memory') . '</th></tr>';
		foreach($included_files as $include){
			echo '<tr>';
			echo '<td>' . $include['name'] . '</td>';
			echo '<td>' . $include['time'] . ' s</td>';
			echo '<td>' . $include['memory'] . ' kB</td>';
			echo '</tr>';
		}
		echo '</table>';
	}

	/**
	 * Shows debugging info (included files, SQL queries, timer, memory...) according to configuration
	 */
	public static function showInfo(){
		if (!self::$config->debug->enabled) return;

		if (self::$config->debug->included_files)  self::includedFiles();
		if (self::$config->debug->sql_queries)     self::sqlQueries();
		if (self::$config->debug->time_management) self::endTimer();
	}

	/**
	 * Exception handler
	 */
	public static function showException($ex)
	{
		Core_Debug::showError(E_ERROR,
		                      $ex->getMessage(),
		                      $ex->getFile(),
		                      $ex->getLine(),
		                      NULL,
		                      $ex->getCode());
		return FALSE;
	}

	public static function showFatal()
	{
		$err = error_get_last();
		if ($err['type'] & (E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_PARSE)) {
			Core_Debug::showError($err['type'], $err['message'], $err['file'], $err['line'], FALSE);
		}
	}

	/**
	 * Error handler
	 */
	public static function showError($errno,
	                                 $errstr ,
	                                 $errfile ,
	                                 $errline,
	                                 $context,
	                                 $code = FALSE)
	{
		$config = Core_Config::singleton();

		if (!($config->debug->error_reporting & $errno))
			return FALSE; //errno which should not be reported (NOTICE)

		switch ($errno) {
			case E_PARSE:
				$text = '<p><strong>[PARSE ERROR]</strong> ';
				break;
			case E_COMPILE_ERROR:
				$text = '<p><strong>[COMPILE ERROR]</strong> ';
				break;
			case E_RECOVERABLE_ERROR:
				$text = '<p><strong>[RECOVERABLE ERROR]</strong> ';
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$text = '<p><strong>[ERROR]</strong> ';
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$text = '<p><strong>[WARNING]</strong> ';
				break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$text = '<p><strong>[NOTICE]</strong> ';
				break;
			case E_STRICT:
				$text = '<p><strong>[STRICT NOTICE]</strong> ';
				break;
			default:
				$text = '<p><strong>[UNKNOWN - ' . $errno . ']</strong> ';
				break;
		}

		$codes = array(400 => array('name'=>'Bad Request',
		                            'text' => 'we_are_very_sorry'),
		               401 => array('name'=>'Unathorized',
		                            'text' => 'not_enough_rights'),
		               403 => array('name'=>'Forbidden',
		                            'text' => 'we_are_very_sorry'),
		               404 => array('name'=>'Not Found',
		                            'text' => 'we_are_very_sorry'),
		               415 => array('name'=>'Unsuported Media Type',
		                            'text' => 'we_are_very_sorry'),
		               500 => array('name'=>'Internal Server Error',
		                            'text' => 'we_are_very_sorry'),
		               501 => array('name'=>'Not Implemented',
		                            'text' => 'we_are_very_sorry'),
		               502 => array('name'=>'Bad Gateway',
		                            'text' => 'we_are_very_sorry'),
		               503 => array('name'=>'Service Unavailable',
		                            'text' => 'out_of_order'));
		if ( !$code || !array_key_exists($code, $codes) ) $code = 500;

		if (!headers_sent()) {
			header('HTTP/1.1 '.$code.' '.$codes[$code]['name'], TRUE, $code);
			Core_Response::factory()->sendHeaders();
		}
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . ENDL;
		echo '<head><title>' . $codes[$code]['name'] . ' - ' . $config->name . '</title>';
		echo '<link rel="Stylesheet" type="text/css" href="' . APP_URL . '/styles/default/css/errors.css" />';
		echo '</head><body>';


		if ( !$config->debug->enabled || $code == 503) { //production mode

			echo '<div id="message">';
			echo '<h1>' . $code . ' - ' . $codes[$code]['name'] . '</h1>';
			echo '<p>' . __($codes[$code]['text']) . '</p>';
			echo '<div id="footer">'
			     . 'Powered by <a href="http://norsphp.com/">NORS'
			     . '</a> ' . norsVersion()
			     . ' &copy; 2007-' . date("Y")
			     . ' <a href="http://milde.cz">Daniel Milde</a> '
			     . 'aka Dundee</div>';
			echo '</div>';
			echo '</body></html>';

		} elseif ($code != 401) { //development mode

			echo '<div id="message">';
			echo '<h1>Error occured</h1>';
			echo $text . $errstr . ' in <strong>'
			     . $errfile . '</strong> at line <strong>'
			     . $errline . '</strong></p>';
			echo '<code>';
			$lines = file($errfile);
			for ($i=$errline-4; $i < $errline+2; $i++) {
				if ($i+1 == $errline) echo '<span class="errline">';
				echo '<span class="gray">'
				     . ($i+1) . '</span>  '
				     . ltrim(htmlspecialchars($lines[$i]));
				if ($i+1 == $errline) echo '</span>';
			}
			echo '</code>';
			echo '<div id="footer">';
			self::endTimer();
			echo '<br />Powered by <a href="http://norsphp.com/">'
			     . 'NORS</a> ' . norsVersion()
			     . ' &copy; 2007-' .
			     date("Y")
			     . ' <a href="http://milde.cz">Daniel Milde</a>'
			     .' aka Dundee</div>';
			echo '</div>';
			echo '</body></html>';

		} else {  //unathorized

			echo '<div id="message">';
			echo '<h1>' . $code . ' - ' . $codes[$code]['name'] . '</h1>';
			echo '<p>' . __($codes[$code]['text']) . '</p>';
			echo '<p>Message: ' . $errstr . '</p>';
			echo '<div id="footer">'
			     . 'Powered by <a href="http://norsphp.com/">NORS'
			     . '</a> ' . norsVersion()
			     . ' &copy; 2007-' . date("Y")
			     . ' <a href="http://milde.cz">Daniel Milde</a> '
			     . 'aka Dundee</div>';
			echo '</div>';
			echo '</body></html>';
		}

		$text .= ' ' . $errstr . ' in ' . $errfile
		       . ' at line ' . $errline.'.';
		$log = new Core_Log();
		$log->log(strip_tags($text));

		if ($config->debug->die_on_error) {
			die(1);
		}
		return FALSE;
	}
}
