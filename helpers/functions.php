<?php
define("MYSQL_DATE", "Y-m-d H:i:s");
$start = 0;
function replaceTag($tag, $replace, $subject)
{

	return str_replace("[". $tag ."]", $replace, $subject);
}
function replaceLanguageTags($locale, $subject)
{
	$file = BASE . "variables/languages/".$locale.".php";

	if(file_exists($file))
	{
		$tags = require $file;
		foreach ($tags as $key => $value) 
		{
			$subject = replaceTag($key , $value, $subject);
		}
	}
	return $subject;
}
function loopTag($looptag, $array, $subject)
{
	$looptag_begin = "[". $looptag ."]";
	$looptag_end   = "[/". $looptag ."]";

	//Get context length start and end
	$context_start = strpos($subject, $looptag_begin) + strlen($looptag_begin);
	$context_end   = strpos($subject, $looptag_end);

	$context = substr($subject, $context_start, $context_end - $context_start);
	

	$add = "";
	//loop items
	foreach ($array as $item) 
	{
		$tmp = $context;
		//loop tag according to the array indexes
		foreach ($item as $tag => $replace)
		{
			$tmp = replaceTag($tag, $replace, $tmp);
		}
		//concat
		$add .= $tmp;
	}


	//Set the replacement positions
	$tag_start = strpos($subject, $looptag_begin);
	$tag_end   = strpos($subject, $looptag_end) + strlen($looptag_end);
	//finaly replace the new code in the subject
	return substr_replace($subject, $add, $tag_start, $tag_end - $tag_start);

}
function replaceTagByRegex($regex, $replace, $subject)
{
	preg_match_all("^\[" . $regex . "\]^", $subject, $out);
	foreach ($out[0] as $tag) 
	{
		//Strip brackets and replace
		$subject = replaceTag(substr($tag, 1, -1), $replace, $subject);
	}
	return $subject;
}
function calcStart()
{
	$time = microtime();
	$time = explode(' ', $time);
	return $time[1] + $time[0];
}
function calcStop($start)
{
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$total_time = round(($time - $start), 4);
}
function myErrorHandler($error) {
	var_dump($error);
}