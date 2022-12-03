<?php
require('stats.php');

function getSystemMemInfo() {
	$meminfo = [];
	foreach (explode("\n", trim(file_get_contents("/proc/meminfo"))) as $line) {
		list($key, $val) = explode(":", $line);
		$meminfo[$key] = trim($val, " \t\n\rkB");
	}
	return $meminfo;
}
$meminfo = getSystemMemInfo();

barGraph([
	'free' => $meminfo['MemAvailable']*1024,
	'total' => $meminfo['MemTotal']*1024,
	'label' => 'RAM Usage',
	'colour' => 'red',
]);
