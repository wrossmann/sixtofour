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
	SixToFour::convSixToFour($v6),
	SixToFour::convSixToFour('2002:0001:0002:0003:0004::'),
	SixToFour::convSixToFour('2002:1001:2002:3003:4004::')
);
