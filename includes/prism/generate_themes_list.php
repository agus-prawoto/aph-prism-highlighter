<?php
$files = scandir('themes');

$list = '';
foreach ($files as $file)
{
	if ($file == '.' || $file == '..')
		continue;
	
	$theme = str_replace(array('-', 'prism', '.css'), '', $file);
	$list .= "'" . $theme . "' => '" . ucfirst ($theme) . "',\r\n";
}
file_put_contents ('themes-list.txt', $list);