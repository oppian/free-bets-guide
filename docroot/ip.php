<?php

$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];

if (empty($ip))
{
	$ip = $_SERVER["REMOTE_ADDR"];
}

echo $ip;
?>