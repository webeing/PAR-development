<?php

/*
============================================================================================================
This software is provided "as is" and any express or implied warranties, including, but not limited to, the
implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
consequential damages (including, but not limited to, procurement of substitute goods or services; loss of
use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.

This software is provided free-to-use, but is not free software.  The copyright and ownership remains entirely
with the author.  Please distribute and use as necessary, in a personal or commercial environment, but it cannot
be sold or re-used without express consent from the author.
============================================================================================================
*/

/**
 * Represents a 'Drain Hole' where many files can live and which can be assigned permissions
 *
 * @package Drain Hole
 * @author John Godley
 * @copyright Copyright (C) John Godley
 **/

class DH_Hole
{
	var $id;
	var $url;
	var $directory;
	
	
	/**
	 * Create and initialize the object
	 *
	 * @param array $values An array of database values to initialise the object, or empty string
	 * @return void
	 **/
	
	function DH_Hole ($values = '')
	{
		if (is_array ($values))
		{
			foreach ($values AS $key => $value)
				$this->$key = $value;
		}
	}
	
	
	
	/**
	 * Delete a hole, removing all files (both from the database and the disk)
	 *
	 * @static
	 * @param int $id ID of the hole to delete
	 * @return void
	 **/
	
	function delete ($id)
	{
		global $wpdb;
		
		$hole = DH_Hole::get ($id);
		
		$files = DH_File::get_all ($id);
		if (count ($files) > 0)
		{
			foreach ($files AS $file)
				DH_File::delete ($file->id);
		}
		
		$options = get_option ('drainhole_options');
		if (isset ($options['delete_file']) && $options['delete_file'] && is_writable ($hole->directory))
		{
			@unlink ($hole->directory.'/.htaccess');
			@rmdir ($hole->directory);
		}

		$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_holes WHERE id='$id'");
	}
	
	
	/**
	 * Get a particular hole
	 *
	 * @static
	 * @param int $id Hole ID
	 * @return DH_Hole Object, or false
	 **/
	
	function get ($id)
	{
		global $wpdb;
		
		$sql  = "SELECT @holes.*,SUM(@files.hits) AS hits,COUNT(@files.id) AS files FROM @holes ";
		$sql .= "LEFT JOIN @files ON @holes.id=@files.hole_id WHERE @holes.id=".$id;
		$sql .= " GROUP BY @holes.id ";
		
		$sql = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);
		$row = $wpdb->get_row ($sql, ARRAY_A);
		if ($row)
			return new DH_Hole ($row);
		return false;
	}
	
	
	function get_everything ()
	{
		global $wpdb;
		
		$sql  = "SELECT * FROM {$wpdb->prefix}drainhole_holes ORDER BY url";

		$rows = $wpdb->get_results ($sql, ARRAY_A);

		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_Hole ($row);
		}
		
		return $data;
	}
	
	/**
	 * Get all holes
	 *
	 * @static
	 * @return array Array of holes
	 **/
	
	function get_all (&$pager)
	{
		global $wpdb;
		
		$sql  = "SELECT SQL_CALC_FOUND_ROWS @holes.*,SUM(@files.hits) AS hits,COUNT(@files.id) AS files FROM @holes ";
		$sql .= "LEFT JOIN @files ON @holes.id=@files.hole_id ";
		$sql .= $pager->to_limits ('', array ('url'), '', 'GROUP BY @holes.id');
		
		$sql = str_replace ('@', $wpdb->prefix.'drainhole_', $sql);

		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$pager->set_total ($wpdb->get_var ("SELECT FOUND_ROWS()"));

		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_Hole ($row);
		}
		
		return $data;
	}
	
	
	function earliest ()
	{
		global $wpdb;
		
		$latest = $wpdb->get_var ("SELECT {$wpdb->prefix}drainhole_access.created_at FROM {$wpdb->prefix}drainhole_access,{$wpdb->prefix}drainhole_files WHERE {$wpdb->prefix}drainhole_access.file_id={$wpdb->prefix}drainhole_files.id AND {$wpdb->prefix}drainhole_files.hole_id={$this->id} ORDER BY created_at ASC LIMIT 0,1");
		$time = mysql2date ('U', $latest);
		
		return mktime (0, 0, 0, date ('n', $time), date ('j', $time), date ('Y', $time));
	}
	
	function latest ()
	{
		global $wpdb;

		$latest = $wpdb->get_var ("SELECT {$wpdb->prefix}drainhole_access.created_at FROM {$wpdb->prefix}drainhole_access,{$wpdb->prefix}drainhole_files WHERE {$wpdb->prefix}drainhole_access.file_id={$wpdb->prefix}drainhole_files.id AND {$wpdb->prefix}drainhole_files.hole_id={$this->id} ORDER BY created_at DESC LIMIT 0,1");
		$time = mysql2date ('U', $latest);
		
		return mktime (24, 0, 0, date ('n', $time), date ('j', $time), date ('Y', $time));
	}


	/**
	 * Create a new hole, making the appropriate directory and creating a default .htaccess to prevent direct access
	 *
	 * @static
	 * @param array $data Array of values (urlx,directoryx,role)
	 * @return boolean
	 **/
	
	function create ($data)
	{
		global $wpdb;

		$url       = $wpdb->escape (DH_Hole::sanitize_url ($data['urlx']));
		$directory = $wpdb->escape (DH_Hole::sanitize_dir (DH_Plugin::realpath ($data['directoryx'])));
		$redirect  = $wpdb->escape ($data['redirect_urlx']);
		if (isset ($data['role']) && ($data['role'] == '-' || $data['role'] == ''))
			$role = 'NULL';
		else
			$role = "'".$data['role']."'";

//		error_reporting (E_ALL);
//		ini_set("display_errors", 1);

		$hotlink = (isset ($data['hotlink']) ? true : false);
		if (strlen ($directory) > 0 && preg_match ('@https?://@', $directory, $matches) === 0)
		{
			if (strlen ($url) > 1 && $wpdb->get_var ("SELECT COUNT(*) FROM {$wpdb->prefix}drainhole_holes WHERE url LIKE '$url'") == 0)
			{
					// Try and create the directory and add a .htaccess file to protect it from nefarious users
					if (!@file_exists ($directory))
					{
						$last = error_get_last ();
						$result = DH_Hole::can_create_dir ($directory);
						if ($result === true)
						{
							if (wp_mkdir_p ($directory))
							{
								$options = get_option ('drainhole_options');
								if ($options === false || !isset ($options['htaccess']) || $options['htaccess'] == true)
								{
									$fp = @fopen ($directory.'/.htaccess', 'w+');
									fwrite ($fp, $this->capture_admin ('htaccess', array ('index' => DH_Plugin::realpath (ABSPATH).'/index.php')));
									@fclose ($fp);
								}
							}
							else
								return sprintf (__ ('could not create directory <code>%s</code><br/>You have insufficient permissions - create the directory with FTP or assign group/other write permissions to the parent directory and try again', 'drain-hole'), $directory);
						}
						else
							return $result;
					}
			
					$wpdb->query ("INSERT INTO {$wpdb->prefix}drainhole_holes (url,directory,role,role_error_url,hotlink) VALUES ('$url','$directory',$role,'$url','$hotlink')");
					return true;
			}
		}
		
		return __ ('you must supply a unique URL (without <code>http://</code> prefix) and directory', 'drain-hole');
	}
	
	function can_create_dir ($dir)
	{
		$paths = explode (PATH_SEPARATOR, ini_get ('open_basedir'));
		$allowed = array ();

		if (count ($paths) > 0)
		{
			foreach ($paths AS $path)
			{
				if (substr ($dir, $path, strlen ($path)) == $path)
					return true;
					
				$allowed[] = '<li><code>'.$path.'</code></li>';
			}
		}

		return sprintf (__ ('your host has restricted access to <code>%s</code> through the <code>open_basedir</code> setting.  You can ask your host to reconfigure the settings or you can try again in one of these allowed directories:', 'drain-hole'), $dir).'<ul>'.implode ('', $allowed).'</ul>';
	}
	
	/**
	 * Update a hole
	 *
	 * @static
	 * @param array $data Array of values (urlx,directoryx,role)
	 * @return boolean
	 **/
	
	function update ($data)
	{
		global $wpdb;
		
		$directory = DH_Hole::sanitize_dir ($data['directoryx']);
		if ($directory != $this->directory && $directory != '' && is_writable (dirname ($directory)) && file_exists ($this->directory))
		{
			wp_mkdir_p (dirname ($directory));
			@rename ($this->directory, $directory);
		}
		
		$this->hotlink   = isset ($data['hotlink']) ? true : false;
		$this->directory = $directory;
		
		$url = DH_Hole::sanitize_url ($data['urlx']);
		
		// Check for duplicate name
		if ($url != $this->url && $wpdb->get_var ("SELECT COUNT(*) FROM {$wpdb->prefix}drainhole_holes WHERE url LIKE '$url'") != 0)
			return false;
		
		if ($data['role'] == '-')
			$this->role = 'NULL';
		else
			$this->role = "'".$data['role']."'";

		$this->url            = $url;
		$this->role_error_url = $wpdb->escape ($data['redirect_urlx']);

		$url       = $wpdb->escape ($this->url);
		$directory = $wpdb->escape ($this->directory);
		
		return $wpdb->query ("UPDATE {$wpdb->prefix}drainhole_holes SET url='$url', directory='$directory', role={$this->role}, role_error_url='{$this->role_error_url}', hotlink='{$this->hotlink}' WHERE id='{$this->id}'");
	}
	
	
	/**
	 * Ensure a URL is valid
	 *
	 * @static
	 * @param string $url URL to sanitise
	 * @return string The sanitised URL
	 **/
	
	function sanitize_url ($url)
	{
		$url = explode ('?', $url);
		$url = $url[0];
		$url = preg_replace ('/[^A-Za-z0-9-\/_\.]/', '', $url);
		$url = trim ($url, '/');
		$url = '/'.$url;
		return $url;
	}
	
	
	/**
	 * Ensure a directory path is valid
	 *
	 * @static
	 * @param string $url Directory to sanitise
	 * @return string The sanitised directory
	 **/
	
	function sanitize_dir ($dir)
	{
		$dir = preg_replace ('@`~/\?\\"\'\*\$@', '', $dir);
		$dir = rtrim ($dir, DIRECTORY_SEPARATOR);
		return $dir;
	}
	
	
	/**
	 * Can we write to the hole's directory?
	 *
	 * @return boolean
	 **/
	
	function can_write ()
	{
		if (is_writable ($this->directory))
			return true;
		return false;
	}
	
	
	/**
	 * Get a list of all files stored within the hole's directory
	 *
	 * @static
	 * @param string $dir Directory to look in
	 * @return array Array of file paths
	 **/
	
	function get_files ($dir) 
	{ 
		if (function_exists ('escapeshellcmd'))
	  	$dir = escapeshellcmd ($dir);

	  $files = glob ("$dir/*"); 
		if (count ($files) > 0 && is_array ($files))
		{
		  foreach ($files AS $file) 
		  { 
				if (is_dir ($file))
		  		$files = array_merge ($files, DH_Hole::get_files ($file)); 
		  }
		} 

	  return $files; 
	}
	
	
	/**
	 * Scans a directory for newly added files
	 *
	 * @return int Number of items added
	 **/
	
	function scan ()
	{
		$added = 0;
		
		// Get list of files
		$files = DH_Hole::get_files ($this->directory);

		// Add any new files into the hole
		if (count ($files) > 0 && is_array ($files))
		{
			foreach ($files AS $file)
			{
				// Only interested in files
				if (is_file ($file) && !in_array (basename ($file), array ('.htaccess', '.versions', '.svn', 'Thumbs.db')))
				{
					$file = ltrim (substr ($file, strlen ($this->directory)), '/');
					if (DH_File::create ($this->id, $file) === true)
						$added++;
				}
			}
		}
		return $added;
	}
	

	
	/**
	 * Get all the holes
	 *
	 * @static
	 * @return array Array of hole URL => hole ID
	 * @todo Make this better
	 **/
	
	function get_as_list ()
	{
		global $wpdb;
		$rows = $wpdb->get_results ("SELECT id,url FROM {$wpdb->prefix}drainhole_holes");
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[$row->url] = $row->id;
		}
		return $data;
	}
	
	function flush ()
	{
		global $wp_rewrite;
		$wp_rewrite->flush_rules ();
	}
}

?>