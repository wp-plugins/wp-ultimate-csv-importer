<?php
/**
 * @author fenzik
 * Smackcoders common Wordpress Handler       
 */
class SmackWpHandler {
	
	/**
	 */
	function __construct() {
		
		// TODO - Not for now
	}
	
	/**
	 * Smack translation
	 */
	function t($lstr) {
		global $slang;
		return $slang [$lstr];
	}
	
	/**
	 * Function to get user language
	 * TODO - Available on 3.0.x
	 */
	function user_language() {
		return "en_us";
	}
	
	/**
	 * Exit operation
	 * 
	 * @param $str string
	 *        	to display
	 */
	function freeze($str = "") {
		die ( $str );
	}
}

?>