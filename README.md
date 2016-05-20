# SixToFour

Library for conversion of IPv4 addresses to 6to4-encapsulated IPv6.

## Example Usage

	<?php 
	require('vendor/autoload.php');
	use \wrossmann\sixtofour\SixToFour;
	
	$v4 = '222.173.190.239';
	$v6 = SixToFour::convFourToSix($v4);
	$bin = SixToFour::str2bin($v4);
	var_dump(
		bin2hex($bin),
		SixToFour::bin2str($bin),
		SixToFour::bin2str($bin, true),
		SixToFour::convSixToFour($v6)
	);
	
Output:

	string(32) "200200de00ad00be00ef000000000000"
	string(15) "222.173.190.239"
	string(18) "2002:de:ad:be:ef::"
	string(15) "222.173.190.239"
