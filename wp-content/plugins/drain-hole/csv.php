<?php

/**
 * DrainHole CSV
 *
 * @package Drain Hole
 * @author John Godley
 * @copyright Copyright (C) John Godley
 **/

include ('../../../wp-config.php');

if (!current_user_can ('edit_plugins'))
	die ('<p style="color: red">You are not allowed access to this resource</p>');
		
$id   = intval ($_GET['id']);
$type = $_GET['type'];

header ("Content-Type: application/vnd.ms-excel");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past


/**
 * Escape CSV values
 *
 * @param string $value Original value to escape
 * @return string Escaped value
 **/

global $drainhole;

if ($type == 'stats')
{
	$file = DH_File::get ($id);
	header ('Content-Disposition: attachment; filename="'.basename ($file->file).'.csv"');

	$stats = DH_Access::get_all ($id);
	if (count ($stats) > 0)
	{
		foreach ($stats AS $stat)
		{
			$csv = array ();
			
			$csv[] = $drainhole->csv_escape (date ('Y-m-d', $stat->created_at));
			$csv[] = $drainhole->csv_escape (date ('H:i', $stat->created_at));
			$csv[] = $drainhole->csv_escape ($stat->ip);
			$csv[] = $drainhole->csv_escape ($stat->speed);
			$csv[] = $drainhole->csv_escape ($stat->time_taken);
			
			echo implode (',', $csv)."\r\n";
		}
	}
}
else if ($type == 'files')
{
	$hole = DH_Hole::get ($id);
	header ('Content-Disposition: attachment; filename="'.basename ($hole->url).'.csv"');

	$files = DH_File::get_all ($id);
	if (count ($files) > 0)
	{
		foreach ($files AS $file)
		{
			$csv = array ();
			
			$csv[] = $drainhole->csv_escape ($file->file);
			$csv[] = $drainhole->csv_escape ($file->version);
			$csv[] = $drainhole->csv_escape ($file->hits);
			$csv[] = $drainhole->csv_escape (date ('Y-m-d', $file->updated_at));
			$csv[] = $drainhole->csv_escape (date ('H:i', $file->updated_at));
			
			echo implode (',', $csv)."\r\n";
		}
	}
}
else if ($type == 'holes')
{
	header ('Content-Disposition: attachment; filename="drain-holes.csv"');

	$holes = DH_Hole::get_all ($id);
	if (count ($holes) > 0)
	{
		foreach ($holes AS $hole)
		{
			$hits = $files = 0;
			$hole->hole_stats ($files, $hits);
			
			$csv = array ();
			$csv[] = $drainhole->csv_escape (date ('Y-m-d'));
			$csv[] = $drainhole->csv_escape (date ('H:i'));
			$csv[] = $drainhole->csv_escape ($hole->url);
			$csv[] = $drainhole->csv_escape ($hits);
			echo implode (',', $csv)."\r\n";
			
		}
	}
}

?>
