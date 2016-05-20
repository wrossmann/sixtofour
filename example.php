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