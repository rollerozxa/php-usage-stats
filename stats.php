<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

error_reporting(~E_WARNING & ~E_DEPRECATED);

function imagestringshadow($img, $font, $x, $y, $string, $color, $colorShadow) {
	// naive text outlining
	for ($i = -1; $i < 2; $i++)
		for ($j = -1; $j < 2; $j++)
			imagestring($img, $font, $x+$i, $y+$j, $string, $colorShadow);

	imagestring($img, $font, $x, $y, $string, $color);
}

function readable_size($size) {
	if ($size < 1024)
		return $size . ' B';

	$units = array("kB", "MB", "GB", "TB");
	foreach ($units as $unit) {
		$size = $size / 1024;
		if ($size < 1024)
			break;
	}
	$size = number_format($size, 2);
	return $size . $unit;
}

function barGraph($arg) {
	$width = 400;
	$height = 30;
	$barheight = $height-13;
	$textoffset = 3;

	$upscale = 2;

	$totalspace = $arg['total'];
	$freespace	= $arg['free'];
	$usedspace	= $totalspace - $freespace;

	$barwidth = round(($usedspace/$totalspace) * $width-3)+1;

	// Create GD image object
	$img = ImageCreateTrueColor($width, $height);

	// Allocate colours
	$bg		= imagecolorallocate($img, 50, 50, 50);
	$border	= imagecolorallocate($img, 0, 0, 0);
	switch ($arg['colour']) {
		case 'red':
			$baroff	= imagecolorallocate($img, 70, 25, 25);
			$text	= imagecolorallocate($img, 255, 200, 200);
			$bar	= imagecolorallocate($img, 200, 43, 78);
		break;
		case 'green':
			$baroff	= imagecolorallocate($img, 25, 70, 25);
			$text	= imagecolorallocate($img, 200, 255, 200);
			$bar	= imagecolorallocate($img, 43, 200, 78);
		break;
	}

	// Draw background
	imagefilledrectangle($img, 0, 0, $width, $height, $bg);
	// Draw black border
	imagefilledrectangle($img, 0, 0, $width-1, $barheight+1, $border);
	// Draw entire bar
	imagefilledrectangle($img, 1, 1, $width-2, $barheight, $baroff);
	// Draw filled bar (space used)
	imagefilledrectangle($img, 1, 1, $barwidth, $barheight, $bar);

	// Draw used space and percentage text
	imagestringshadow($img, 1, $barwidth+3, ($barheight/3), number_format(($usedspace/$totalspace) * 100, 2) ."%", $text, $border);
	imagestringshadow($img, 1, 3, $barheight+$textoffset, readable_size($usedspace), $text, $border);

	// Draw right-aligned total space text
	$fff = strlen(readable_size($totalspace)) * 5;
	imagestringshadow($img, 1, ($width-3)-$fff, $barheight+$textoffset, readable_size($totalspace), $text, $border);

	// Draw image label
	$fff2 = strlen($arg['label']) * 5 / 2;
	imagestringshadow($img, 1, (($width-2)/2)-$fff2, $barheight+$textoffset, $arg['label'], $text, $border);

	header("Content-type: image/png");
	if (isset($upscale)) {
		$img2 = imagescale($img, $width*$upscale, $height*$upscale, IMG_NEAREST_NEIGHBOUR);
		imagecolortransparent($img2, $bg);
		imagepng($img2);
	} else {
		imagecolortransparent($img, $bg);
		imagepng($img);
	}
}
