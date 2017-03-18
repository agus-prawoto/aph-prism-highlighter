<?php
$files = scandir('components');

$list = '';
foreach ($files as $file)
{
	if ($file == '.' || $file == '..' || strpos($file, 'min') !== false )
		continue;
	
	$comp = str_replace(array('prism-', '.js'), '', $file);
	$list .= "'" . $comp . "' => '" . ucfirst ($comp) . "',\r\n";
}
file_put_contents ('components-list.txt', $list);