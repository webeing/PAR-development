<?php

/* ============================================================================================================
	 This software is provided "as is" and any express or implied warranties, including, but not limited to, the
	 implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
	 the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
	 consequential damages (including, but not limited to, procurement of substitute goods or services; loss of
	 use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
	 contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of
	 this software, even if advised of the possibility of such damage.
   
	 This software is provided free-to-use, but is not free software.  The copyright and ownership remains
	 entirely with the author.  Please distribute and use as necessary, in a personal or commercial environment,
	 but it cannot be sold or re-used without express consent from the author.
   ============================================================================================================ */

/**
 * Manage statistics when a file is accessed
 *
 * @package Drain Hole
 * @author John Godley
 * @copyright Copyright (C) John Godley
 **/

class DH_Access
{
	var $id;
	var $ip;
	var $created_at;
	var $finished_at;
	var $speed;
	var $time_taken;
	
	
	/**
	 * Create and initialize the object
	 *
	 * @param array $values An array of database values to initialise the object, or empty string
	 * @return void
	 **/
	
	function DH_Access ($values = '')
	{
		if (is_array ($values))
		{
			foreach ($values AS $key => $value)
				$this->$key = $value;
		}
		
		$this->ip         = long2ip ($this->ip);
		$this->created_at = mysql2date ('U', $this->created_at);
	}
	
	
	/**
	 * Get a particular access statistic
	 *
	 * @static
	 * @param int $id ID of the statistic to return
	 * @return DH_Access Object, or false
	 **/
	
	function get ($id)
	{
		global $wpdb;
		$row = $wpdb->get_row ("SELECT * FROM {$wpdb->prefix}drainhole_access WHERE id=$id", ARRAY_A);
		if ($row)
			return new DH_Access ($row);
		return false;
	}
	
	
	/**
	 * Create a new statistic item in the database.  Certain data is extracted from the web environment (REMOTE_ADDR and HTTP_REFERER)
	 *
	 * @static
	 * @param int $file File ID
	 * @return int Access statistic ID
	 **/
	
	function create ($file, $version)
	{
		global $wpdb;

		$user = wp_get_current_user ();
		if ($user)
			$user = $user->data->ID;
		else
			$user = 0;

		if (isset ($_SERVER['REMOTE_ADDR']))
		  $ip = $_SERVER['REMOTE_ADDR'];
		else if (isset ($_SERVER['HTTP_X_FORWARDED_FOR']))
		  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	
		$ip = sprintf ('%u', ip2long ($ip));
		$referrer = DH_Access::get_referrer ($wpdb->escape ($_SERVER['HTTP_REFERER']));
		
		$wpdb->query ("INSERT INTO {$wpdb->prefix}drainhole_access (file_id,created_at,ip,referrer,version_id,user_id) VALUES ($file,NOW(),$ip,'$referrer','$version','$user')");
		return $wpdb->insert_id;
	}
	
	
	/**
	 * Deletes all statistics that are over a given number of days old
	 *
	 * @static
	 * @param int $daysold Number of days
	 * @return void
	 **/
	
	function clear ($daysold)
	{
		global $wpdb;
		
		$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_access WHERE DATE_SUB(CURDATE(),INTERVAL $daysold DAY) > created_at");
	}
	
	
	/**
	 * Updates access statistic with a download speed value and duration
	 *
	 * @static
	 * @param int $id ID of the statistic
	 * @param int $size Size of the file
	 * @return void
	 **/
	
	function finished ($id, $size)
	{
		global $wpdb;

		$speed = $taken = 0;
		$row   = $wpdb->get_row ("SELECT *,NOW() AS now FROM {$wpdb->prefix}drainhole_access WHERE id=$id");
		
		$start = mysql2date ('U', $row->created_at);
		$end   = mysql2date ('U', $row->now);
		
		$taken = $end - $start;
		if ($taken == 0)
			$speed = $size;
		else
			$speed = round ($size / $taken);
		
		$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_access SET speed=$speed, time_taken=$taken WHERE id=$id");
	}
	
	
	/**
	 * Get all statistics associated with a particular file
	 *
	 * @static
	 * @param int $id  File ID
	 * @return array Array of DH_Access objects
	 **/
	
	function get_all ($id)
	{
		global $wpdb;
		
		$rows = $wpdb->get_results ("SELECT * FROM {$wpdb->prefix}drainhole_access WHERE file_id='$id'", ARRAY_A);
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_Access ($row);
		}
		
		return $data;
	}
	
	
	/**
	 * Get all statistics, restricted by a pager
	 *
	 * @static
	 * @param DH_Pager $pager Pager object
	 * @return array Array of DH_Access objects
	 **/
	
	function get_everything (&$pager)
	{
		global $wpdb;
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS @access.*,@files.file,{$wpdb->users}.user_login FROM @access ";
		$sql .= "LEFT JOIN @files ON @access.file_id=@files.id ";
		$sql .= "LEFT JOIN {$wpdb->users} ON @access.user_id={$wpdb->users}.ID";
		$sql .= $pager->to_limits ('', array ('@files.file'));
		
		$sql = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);

		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$pager->set_total ($wpdb->get_var ("SELECT FOUND_ROWS()"));
		
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_Access ($row);
		}
		
		return $data;
	}
	
	
	/**
	 * Get all statistics associated with a particular file, restricted by a pager
	 *
	 * @static
	 * @param int $id  File ID
	 * @param DH_Pager $pager Pager object
	 * @return array Array of DH_Access objects
	 **/
	
	function get_by_file ($id, &$pager)
	{
		global $wpdb;
		
		$sql  = "SELECT SQL_CALC_FOUND_ROWS * FROM @access ";
		$sql .= "LEFT JOIN {$wpdb->users} ON @access.user_id={$wpdb->users}.ID ";
		$sql .= $pager->to_limits ("file_id='$id'", array ('referrer'));
		
		$sql  = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);
		
		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$pager->set_total ($wpdb->get_var ("SELECT FOUND_ROWS()"));
	
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_Access ($row);
		}
		
		return $data;
	}
	
	function get_file_hits_per_day ($id, $year, $month, $day)
	{
		global $wpdb;

		// Two days worth of data
		$startdate = date ('Y-m-d H:i:s', mktime (0, 0, 0, $month, $day, $year));
		$enddate   = date ('Y-m-d H:i:s', mktime (24, 0, 0, $month, $day + 1, $year));

		$sql  = "SELECT YEAR(@access.created_at) AS year,MONTH(@access.created_at) AS month,DAYOFMONTH(@access.created_at) AS day,HOUR(@access.created_at) AS hour,";
		$sql .= "COUNT(@access.id) AS hits FROM @access WHERE @access.file_id=$id ";
		$sql .= "AND @access.created_at >= '$startdate' AND @access.created_at <= '$enddate' ";
		$sql .= "GROUP BY 4,3,2,1 ORDER BY @access.created_at";

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
	function get_file_hits_per_month ($id, $year, $month)
	{
		global $wpdb;
		
		// Two months worth of data
		$startdate = date ('Y-m-d H:i:s', mktime (0, 0, 0, $month, 1, $year));
		$enddate   = date ('Y-m-d H:i:s', mktime (24, 0, 0, $month + 1, 31, $year));
		
		$sql = "SELECT MONTH(@access.created_at) AS month,DAYOFMONTH(@access.created_at) AS day,COUNT(@access.id) AS hits FROM @access WHERE @access.file_id=$id AND @access.created_at >= '$startdate' AND @access.created_at <= '$enddate' GROUP BY 2,1 ORDER BY @access.created_at";
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
	function get_file_hits_per_year ($id, $year)
	{
		global $wpdb;
		
		$startdate = date ('Y-m-d H:i:s', mktime (0, 0, 0, 1, 1, $year));
		$enddate   = date ('Y-m-d H:i:s', mktime (24, 0, 0, 12, 31, $year));
		
		$sql = "SELECT MONTH(@access.created_at) AS month,COUNT(@access.id) AS hits FROM @access WHERE @access.file_id=$id AND @access.created_at >= '$startdate' AND @access.created_at <= '$enddate' GROUP BY 1 ORDER BY @access.created_at";
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
		
	/**
	 * Get all statistics associated with a particular hole, restricted by a pager
	 *
	 * @static
	 * @param int $id  File ID
	 * @param DH_Pager $pager Pager object
	 * @return array Array of DH_Access objects
	 **/
	
	function get_by_hole ($id, &$pager)
	{
		global $wpdb;
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM @access INNER JOIN @files ON @access.file_id=@files.id AND @files.hole_id=$id".$pager->to_limits ();
		$sql = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);
		
		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$pager->set_total ($wpdb->get_var ("SELECT FOUND_ROWS()"));
		
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_Access ($row);
		}
		
		return $data;
	}
	
	/**
	 * Return the time taken for the file to be downloaded
	 *
	 * @return int Number of seconds
	 **/
	
	function time_taken ()
	{
		if ($this->finished_at == 0)
			return false;
		return $this->finished_at - $this->created_at;
	}
	
	
	/**
	 * Return the speed the file was downloaded
	 *
	 * @return int Size of file
	 **/
	
	function speed ($size)
	{
		if ($this->finished_at == 0)
			return 0;  // Never finished
		else if ($this->finished_at == $this->created_at)
			return $size;
		return round ($size / ($this->finished_at - $this->created_at));
	}
	
	
	/**
	 * Delete an access statistic
	 *
	 * @static
	 * @param int $id Access ID
	 * @return void
	 **/
	
	function delete ($id)
	{
		global $wpdb;
		
		if (is_array ($id))
		{
			$id = implode (',', array_filter ($id, 'intval'));
			$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_access WHERE id IN ('$id')");
		}
		else
			$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_access WHERE id='$id'");
	}
	
	
	/**
	 * Delete all access statistics
	 *
	 * @static
	 * @return void
	 **/
	
	function delete_all ()
	{
		global $wpdb;
		$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_access");
	}
	
	
	/**
	 * Delete all access statistics for a given file
	 *
	 * @static
	 * @param int $id File ID
	 * @return void
	 **/
	
	function delete_by_file ($id)
	{
		global $wpdb;
		$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_access WHERE file_id='$id'");
	}
	
	function delete_by_version ($id)
	{
		global $wpdb;
		$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_access WHERE version_id='$id'");
	}
	
	/**
	 * Get full URL for the referrer of the access
	 *
	 * @return string URL
	 **/
	
	function get_referrer ($referrer = '')
	{
		if ($referrer == '')
			$referrer = $this->referrer;
			
		$home = get_option ('home');
		if (substr ($referrer, 0, strlen ($home)) == $home)
		{
			$info = parse_url ($referrer);
			return $info['path'];
		}
		return $referrer;
	}
	
	function referrer_as_link ()
	{
		if ($this->referrer)
		{
			$referrer = $this->get_referrer ();
			$parts = explode ('?', $referrer);
			
			return '<a href="'.DH_Plugin::url ($referrer).'">'.$parts[0].'</a>';
		}
		return '';
	}
	
	function user ()
	{
		if ($this->user_id > 0)
		{
			$user = $this->user_id;
			if ($this->user_login)
				$user = $this->user_login;
				
			return '<a href="user-edit.php?user_id='.$this->user_id.'&wp_http_referer='.urlencode ($_SERVER['REQUEST_URI']).'">'.$user.'</a>';
		}
	}
}

?>