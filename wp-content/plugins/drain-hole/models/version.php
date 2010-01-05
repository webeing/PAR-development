<?php

class DH_Version
{
	function DH_Version ($values = '')
	{
		if (is_array ($values))
		{
			foreach ($values AS $key => $value)
				$this->$key = $value;
		}
		
		$this->created_at = mysql2date ('U', $this->created_at);
	}
	
	
	function create ($file, $version, $hits = 0, $created_at = '', $reason = '')
	{
		global $wpdb;

		if ($created_at == '')
			$created_at = 'NOW()';
		else
			$created_at = '"'.date ('Y-m-d H:i:s', $created_at).'"';
		
		if ($reason)
			$reason = '"'.$wpdb->escape ($reason).'"';
		else
			$reason = 'NULL';

		$version = $wpdb->escape ($version);
		$wpdb->query ("INSERT INTO {$wpdb->prefix}drainhole_version (file_id,version,hits,created_at,reason) VALUES ('{$file->id}','$version','$hits',$created_at,$reason)");
		return $wpdb->insert_id;
	}
	
	function get_history ($id, $current, $limit)
	{
		global $wpdb;
		
		$data = array ();
		$rows = $wpdb->get_results ("SELECT * FROM {$wpdb->prefix}drainhole_version WHERE file_id=$id ORDER BY created_at DESC LIMIT 0,$limit", ARRAY_A);
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_Version ($row);
		}
		
		return $data;
	}
	
	function get_by_file ($id, &$pager)
	{
		global $wpdb;
		
		$data = array ();
		$rows = $wpdb->get_results ("SELECT SQL_CALC_FOUND_ROWS * FROM {$wpdb->prefix}drainhole_version".$pager->to_limits ("file_id='$id'", array ('reason', 'version')), ARRAY_A);
		if ($rows)
		{
			$pager->set_total ($wpdb->get_var ("SELECT FOUND_ROWS()"));
			foreach ($rows AS $row)
				$data[] = new DH_Version ($row);
		}
		
		return $data;
	}
	
	function get_by_file_and_version ($id, $version)
	{
		global $wpdb;

		$version = $wpdb->escape ($version);
		$row = $wpdb->get_row ("SELECT * FROM {$wpdb->prefix}drainhole_version WHERE file_id='$id' AND version='$version'", ARRAY_A);
		if ($row)
			return new DH_Version ($row);
		return false;
	}
	
	function get ($id)
	{
		global $wpdb;

		$row = $wpdb->get_row ("SELECT * FROM {$wpdb->prefix}drainhole_version WHERE {$wpdb->prefix}drainhole_version.id='$id'", ARRAY_A);
		if ($row)
			return new DH_Version ($row);
		return false;
	}

	function update ($data)
	{
		global $wpdb;
		
		$version    = $wpdb->escape ($data['version']);
		$reason     = $wpdb->escape ($data['reason']);
		$hits       = intval ($data['hits']);
		$created_at = date ('Y-m-d H:i:s', mktime (0, 0, 0, intval ($data['month']), intval ($data['day']), intval ($data['year'])));
		
		$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_version SET version='$version', hits='$hits', created_at='$created_at', reason='$reason' WHERE id='{$this->id}'");
	}
	
	function get_totals_by_hole ($holeid)
	{
		// Returns all files in a hole with the hits totalled
		global $wpdb;
		
		$sql  = "SELECT @files.*,SUM(@version.hits) AS hits FROM @version,@files,@holes WHERE ";
		$sql .= "@version.file_id=@files.id AND @files.hole_id=$holeid ";
		$sql .= "GROUP BY file_id ORDER BY SUM(@version.hits) DESC";
		
		$sql = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);
				
		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_File ($row);
		}
		
		return $data;
	}
	
	// Hits per hour over day
	function get_hits_per_day ($id, $year, $month, $day)
	{
		global $wpdb;
		
		// Two days worth of data
		$startdate = date ('Y-m-d H:i:s', mktime (0, 0, 0, $month, $day, $year));
		$enddate   = date ('Y-m-d H:i:s', mktime (24, 0, 0, $month, $day + 1, $year));
		
		$sql = "SELECT YEAR(@access.created_at) AS year,MONTH(@access.created_at) AS month,DAYOFMONTH(@access.created_at) AS day,HOUR(@access.created_at) AS hour,COUNT(@access.id) AS hits FROM @access,@files WHERE @access.file_id=@files.id AND @files.hole_id=$id AND @access.created_at >= '$startdate' AND @access.created_at <= '$enddate' GROUP BY 4,3,2,1 ORDER BY @access.created_at";
		$sql = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);

		$results = array ();
		$rows = $wpdb->get_results ($sql);

		if ($rows)
		{
			foreach ($rows AS $row)
				$results[$row->year][$row->month][$row->day][$row->hour] = $row->hits;
				
			// Fill in missing hours
			foreach ($results AS $year => $months)
			{
				foreach ($months AS $month => $days)
				{
					foreach ($days AS $day => $hours)
					{
						for ($hour = 0; $hour <= 24; $hour++)
						{
							if (!isset ($results[$year][$month][$day][$hour]))
								$results[$year][$month][$day][$hour] = 0;
						}
						
						ksort ($results[$year][$month][$day]);
					}
				}
			}
		}
		return $results;
	}
	

	
	// Hits per day over month
	function get_hits_per_month ($id, $year, $month)
	{
		global $wpdb;
		
		// Two months worth of data
		$startdate = date ('Y-m-d H:i:s', mktime (0, 0, 0, $month, 1, $year));
		$enddate   = date ('Y-m-d H:i:s', mktime (24, 0, 0, $month + 1, 31, $year));
		
		$sql = "SELECT MONTH(@access.created_at) AS month,DAYOFMONTH(@access.created_at) AS day,COUNT(@access.id) AS hits FROM @access,@files WHERE @access.file_id=@files.id AND @files.hole_id=$id AND @access.created_at >= '$startdate' AND @access.created_at <= '$enddate' GROUP BY 2,1 ORDER BY @access.created_at";
		$sql = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);
		
		$results = array ();
		$rows = $wpdb->get_results ($sql);
		if ($rows)
		{
			foreach ($rows AS $row)
				$results[$row->month][$row->day] = $row->hits;
		}
		return $results;
	}
	
	// Hits per month over year
	function get_hits_per_year ($id, $year)
	{
		global $wpdb;
		
		$startdate = date ('Y-m-d H:i:s', mktime (0, 0, 0, 1, 1, $year));
		$enddate   = date ('Y-m-d H:i:s', mktime (24, 0, 0, 12, 31, $year));
		
		$sql = "SELECT MONTH(@access.created_at) AS month,COUNT(@access.id) AS hits FROM @access,@files WHERE @access.file_id=@files.id AND @files.hole_id=$id AND @access.created_at >= '$startdate' AND @access.created_at <= '$enddate' GROUP BY 1 ORDER BY @access.created_at";
		$sql = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);
		
		$results = array ();
		$rows = $wpdb->get_results ($sql);
		if ($rows)
		{
			foreach ($rows AS $row)
				$results[$row->month] = $row->hits;
		}
		return $results;
	}

	function total_hits ($file)
	{
		global $wpdb;
		
		return $wpdb->get_var ("SELECT SUM(hits) FROM {$wpdb->prefix}drainhole_version WHERE file_id=$file");
	}
	
	function delete ()
	{
		global $wpdb;
		
		$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_version WHERE id='{$this->id}'");
		
		$file = DH_File::get ($this->file_id);
		$hole = DH_Hole::get ($file->hole_id);
		
		$file->delete_version ($this->id, $hole);
	}
}

?>