<?php
function de($arg, $type = null, $die = true)
{
	echo '<pre>';
	if(!$type)
	{
		
		print_r($arg);
		
	}
	else
	{
		var_dump($arg);
	}
	echo'</pre>';
	if($die)
		die;
}

function def($string) {
	$myfile = fopen("./debug.txt", "a+") or die("Unable to open file!");
	ob_start();
	print_r($string);
	$stringT = ob_get_clean();
	fwrite($myfile, $stringT);
	fclose($myfile);
}