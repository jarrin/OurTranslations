<?php
define("MYSQL_DATE", "Y-m-d H:i:s");
$start = 0;



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