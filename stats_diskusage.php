<?php
require('stats.php');

barGraph([
	'free' => disk_free_space("/"),
	'total' => disk_total_space("/"),
	'label' => 'Disk Usage',
	'colour' => 'green',
]);
