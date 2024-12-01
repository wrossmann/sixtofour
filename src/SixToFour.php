<?php
namespace wrossmann\sixtofour;

/**
 * SixToFour
 * 
 * Static functions to handle conversion of IPv4 addresses to 6to4-encapsulated IPv6.
 */
class SixToFour {

	private static $prefix = '2002';

	/**
	 * Set a custom prefix
	 * 
	 * @param	string	$prefix
	 */
	public static function setPrefix($prefix) {
		if( strlen($prefix) > 4 || hex2bin($prefix) === false ) {
			throw new \Exception('Invalid prefix specified.');
		}
		self::$prefix = $prefix;
	}

	/**
	 * Check if a string is an IPv6 address
	 * 
	 * @param	string	$addr
	 * @return	bool	
	 */
	public static function isSix($addr) {
		return inet_pton($addr) && ! self::isFour($addr);
	}
	
	/**
	 * Check if a string is an IPv4 address
	 * 
	 * @param	string	$addr
	 * @return	bool
	 */
	public static function isFour($addr) {
		return ip2long($addr) !== false;
	}
	
	/**
	 * Check if a string is a 6to4-encapsulated address
	 * 
	 * @param	string	$addr
	 * @return	bool
	 */
	public static function isSixToFour($addr) {
		if( ! self::isSix($addr)) { return false; }
		if( strlen(self::getSixToFourHextets($addr)) > 8 ) { return false; }
		return explode(':', $addr)[0] == self::$prefix;
	}

	/**
	 * Get concatenated hextexts 2-5 of a given IPv6 string
	 *
	 * @param	string	$addr
	 * @return	string
	 */
	protected static function getSixToFourHextets($addr) {
		// the double hexdec will reduce '0001' to '1'
		return implode(
			'',
			array_map(
				function($a){return str_pad(dechex(hexdec($a)), 2, '0', STR_PAD_LEFT);},
				array_slice(
					explode(':', $addr),
					1, 4
				)
			)
		);
	}
	
	/**
	 * Convert an IPv4 address to a 6to4 IPv6 address
	 * 
	 * @param	string	$addr
	 * @return	string|boolean
	 */
	public static function convFourToSix($addr) {
		if( ! self::isFour($addr) ) { return false; }
		return sprintf('%s:%s::', self::$prefix, implode(':', str_split(str_pad(dechex(ip2long($addr)), 8, '0', STR_PAD_LEFT), 2)));
	}
	
	/**
	 * Convert a 6to4 IPv6 address to an IPv4 address
	 * 
	 * @param	string	$addr
	 * @return	string|boolean
	 */
	public static function convSixToFour($addr) {
		if( ! self::isSixToFour($addr) ) { return false; }
		return long2ip(hexdec(self::getSixtoFourHextets($addr)));
	}
	
	/**
	 * Convert a binary-encoded address to string
	 * 
	 * @param	string	$bin_addr
	 * @param	string	$always_v6
	 * @return	string|boolean
	 */
	public static function bin2str($bin_addr, $always_v6=false) {
		$str_addr = inet_ntop($bin_addr);
		if( !$always_v6 && self::isSixToFour($str_addr)) {
			return self::convSixToFour($str_addr);
		} else {
			return $str_addr;
		}
	}
	
	/**
	 * Convert an IPv4/6 address to a 16-byte binary string.
	 * 
	 * @param	string	$str_addr
	 * @return	string|boolean
	 */
	public static function str2bin($str_addr) {
		if( self::isFour($str_addr) ) {
			$str_addr = self::convFourToSix($str_addr);
		}
		return inet_pton($str_addr);
	}
}
