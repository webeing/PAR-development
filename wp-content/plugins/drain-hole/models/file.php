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
 * Represents a single download-able entity
 *
 * @package Drain Hole
 * @author John Godley
 * @copyright Copyright (C) John Godley
 **/

class DH_File
{
	var $id;
	var $hole_id;
	var $file;
	var $version;
	var $hits;
	var $download_as;
	
	
	/**
	 * Create and initialize the file object
	 *
	 * @param array $values An array of database values to initialise the object, or empty string
	 * @return void
	 **/
	
	function DH_File ($values = '')
	{
		if (is_array ($values))
		{
			foreach ($values AS $key => $value)
				$this->$key = $value;
		}
		
		$this->updated_at = mysql2date ('U', $this->updated_at);
		$this->created_at = mysql2date ('U', $this->created_at);
		$this->options    = unserialize ($this->options);
		if (!is_array ($this->options))
			$this->options = array ();
	}
	
	
	/**
	 * Deletes a file from the database
	 *
	 * @static
	 * @param int $id File ID
	 * @return void
	 **/
	
	function delete ($id)
	{
		$file = DH_File::get ($id);
		$hole = DH_Hole::get ($file->hole_id);
		
		// Remove the file first
		$options = get_option ('drainhole_options');
		if (isset ($options['delete_file']) && $options['delete_file'])
			@unlink ($file->file ($hole));
		
		global $wpdb;
		$wpdb->query ("DELETE FROM {$wpdb->prefix}drainhole_files WHERE id='$id'");
	}
	
	function delete_version ($version, $hole)
	{
		DH_Access::delete_by_version ($version);
		
		$options = get_option ('drainhole_options');
		if (isset ($options['delete_file']) && $options['delete_file'] && file_exists ($this->file ($hole, $version)))
			@unlink ($this->file ($hole, $version));
	}
	
	/**
	 * Create a new file entry in the database
	 *
	 * @static
	 * @param DH_Hole $hole The DrainHole in which to put the file
	 * @param string $file The file's name
	 * @return boolean true if successfully created, false if the file already exists
	 **/
	
	function create ($hole, $file)
	{
		global $wpdb;
		
		$count = $wpdb->get_var ("SELECT COUNT(*) FROM {$wpdb->prefix}drainhole_files WHERE file='$file' AND hole_id=$hole");
		if ($count == 0 && $count !== false)
		{
			$options = get_option ('drainhole_options');
			$version = '0.1';
			$name    = $file;
			
			if (isset ($options['default_version']) && $options['default_version'] != '')
				$version = $options['default_version'];
				
			if (isset ($options['default_name']) && $options['default_name'] != '')
			{
				$name = $options['default_name'];
				
				$parts = pathinfo (basename ($file));
				if (!isset ($parts['filename']))
          $parts['filename'] = substr ($parts['basename'], 0, strpos ($parts['basename'], '.'));

				$name = str_replace ('$FILENAME$', $parts['filename'], $name);
				$name = str_replace ('$EXTENSION$', $parts['extension'], $name);
				
				$name = $wpdb->escape ($name);
			}

			// Now create the file
			$file = $wpdb->escape (DH_Hole::sanitize_dir (ltrim ($file, '/')));
			$wpdb->query ("INSERT INTO {$wpdb->prefix}drainhole_files (hole_id,file,updated_at,name) VALUES ($hole,'$file',NOW(),'$name')");

			// Create version information
			$file = DH_File::get ($wpdb->insert_id);
			$version = DH_Version::create ($file, $version);
			$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_files SET version_id='$version' WHERE id='{$file->id}'");
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Get all files, or all files in a given hole
	 *
	 * @static
	 * @param int $id Optional hole ID.  If not given then all files will be returned
	 * @return array Array of DH_File objects
	 **/
	
	function get_all ($id = -1)
	{
		global $wpdb;
		
		if ($id > 0)
			$id = "WHERE hole_id='$id'";
		else
			$id = '';
		
		$sql  = "SELECT @files.*,@version.version,@version.created_at FROM @files LEFT JOIN @version ON @files.version_id=@version.id $id ORDER BY @files.name,@files.file";
		$sql  = str_replace ('@', "{$wpdb->prefix}drainhole_", $sql);
		
		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_File ($row);
		}
		
		return $data;
	}
	
	function get_recent ($id,$max)
	{
		global $wpdb;
		
		$sql  = "SELECT @files.*,@version.version,@version.created_at FROM @files LEFT JOIN @version ON @files.version_id=@version.id WHERE hole_id='$id' ORDER BY @files.updated_at DESC LIMIT 0,$max";
		$sql  = str_replace ('@', "{$wpdb->prefix}drainhole_", $sql);
		
		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_File ($row);
		}
		
		return $data;
	}
	
	
	/**
	 * Returns all files in a given hole, restricted by a pager
	 *
	 * @static
	 * @param int $id Hole ID
	 * @param DH_Pager $pager A pager object
	 * @return array Array of DH_File objects
	 **/
	
	function get_by_hole ($id, &$pager)
	{
		global $wpdb;
	 
		$sql  = "SELECT SQL_CALC_FOUND_ROWS @files.*,@version.version,@version.created_at FROM @files LEFT JOIN @version ON @files.version_id=@version.id";
		$sql .= $pager->to_limits ("@files.hole_id='$id'", array ('file'));
		
		$sql  = str_replace ('@', "{$wpdb->prefix}drainhole_", $sql);

		$rows = $wpdb->get_results ($sql, ARRAY_A);
		$pager->set_total ($wpdb->get_var ("SELECT FOUND_ROWS()"));
		
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_File ($row);
		}
		
		return $data;
	}
	
	/**
	 * Get the specified file
	 *
	 * @static
	 * @param int $id File ID
	 * @return DH_File Return file object, or false
	 **/
	
	function get ($id)
	{
		global $wpdb;

		$row = $wpdb->get_row ("SELECT {$wpdb->prefix}drainhole_files.*,{$wpdb->prefix}drainhole_version.version,{$wpdb->prefix}drainhole_version.created_at FROM {$wpdb->prefix}drainhole_files INNER JOIN {$wpdb->prefix}drainhole_version ON ({$wpdb->prefix}drainhole_files.version_id={$wpdb->prefix}drainhole_version.id OR {$wpdb->prefix}drainhole_files.version_id=0) AND {$wpdb->prefix}drainhole_files.id='$id' GROUP BY id", ARRAY_A);
		if ($row)
			return new DH_File ($row);
		return false;
	}
	
	

	
	/**
	 * Update the information for a file
	 *
	 * @param array $data Array of values from a POST (file,version,hits,force,mime,icon)
	 * @return void
	 **/
	
	function update ($data)
	{
		global $wpdb;
		
		// Has the filename changed?
		$file = ltrim (DH_Hole::sanitize_dir ($data['file']), '/');
		if ($file != $this->file && $file != '')
		{
			// Yes, rename the file
			$hole = DH_Hole::get ($this->hole_id);
			$old  = $this->file ($hole);
			$this->file = $file;
			
			wp_mkdir_p (dirname ($this->file ($hole)));
			@rename ($old, $this->file ($hole));
		}
		
		// Extract data
		$this->name        = $data['name'];
		$this->description = $data['description'];
		$this->hits        = intval ($data['hits']);
		$this->updated_at  = time ();
		
		$this->mime = $this->icon = $this->svn = '';
		$svn = $icon = $mime = 'NULL';
		
		if ($data['svn'])
		{
			$this->svn = $data['svn'];
			$svn = "'".$wpdb->escape ($this->svn)."'";
		}

		$download_as = 'NULL';
		if ($data['download_as'] && $data['download_as'] != $this->download_as ())
		{
			$this->download_as = $data['download_as'];
			$download_as = "'".$wpdb->escape ($this->download_as)."'";
		}
		
		if ($data['mime'] != '-')
		{
			$this->mime = $data['mime'];
			$mime = "'".$wpdb->escape ($this->mime)."'";
		}
			
		if ($data['icon'] != '' && $data['icon'] != '-')
		{
			$this->icon = DH_Hole::sanitize_dir ($data['icon']);
			$icon = "'".$wpdb->escape ($this->icon)."'";
		}

		$file    = $wpdb->escape ($this->file);
		$name    = $wpdb->escape ($this->name);
		$desc    = $wpdb->escape ($this->description);
		
		$this->options = $data['options'];
		if (!is_array ($this->options))
			$this->options = array ($this->options);
			
		if (count ($this->options) > 0)
		{
			foreach ($this->options AS $value)
				$newoptions[$value] = $value;
			$this->options = $newoptions;
		}
			
		$options = $wpdb->escape (serialize ($this->options));
		$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_files SET file='$file', mime=$mime, svn=$svn, icon=$icon, options='{$options}', hits='{$this->hits}', updated_at=NOW(), name='$name', description='$desc', download_as=$download_as WHERE id='{$this->id}'");
	}
	
	
	/**
	 * Determine if the underlying file exists
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @return boolean true if it exists, false otherwise
	 **/
	
	function exists ($hole, $version = '')
	{
		if (file_exists ($this->file ($hole, $version)))
			return true;
		return false;
	}
	
	
	/**
	 * Updates the file object with the last modified time of the underlying file
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @return void
	 **/
	
	function sync_modified_time ($hole)
	{
		global $wpdb;
		if ($this->exists ($hole))
		{
			$this->updated_at = date ('Y-m-d H:i:s', @filemtime ($this->file ($hole)));
			$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_files SET updated_at='{$this->updated_at}' WHERE id='{$this->id}'");
		}
	}
	
	
	function icon_ref ($dir)
	{
		if ($this->icon == '')
			$this->icon = 'download.png';
			
		if (file_exists (TEMPLATEPATH."/view/drain-hole/icons/{$this->icon}"))
			$url  = get_bloginfo ('template_url').'/view/drain-hole/icons/'.$this->icon;
		else if (@file_exists (dirname (FILE)."/../icons/{$this->icon}"))
			$url  = $dir.'/icons/'.$this->icon;
		else
			$url  = $dir.'/images/download.png';
			
		return $url;
	}
	
	/**
	 * Returns an HTML tag for the file's icon.  The icon is first looked for inside the template directory
	 * <code>view/drain-hole/[chosen icon]</code>, and then inside the plugin's icon directory.
	 *
	 * If no icon can be found a default icon is supplied.
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @param string $dir XXX
	 * @param boolean $google Whether to add Google Analytics tracking code
	 * @return string HTML image tag complete with dimensions
	 **/
	
	function icon ($hole, $dir, $google = false)
	{
		if ($this->icon == '')
			$this->icon = 'download.png';
			
		if (file_exists (TEMPLATEPATH."/view/drain-hole/icons/{$this->icon}"))
		{
			$url  = get_bloginfo ('template_url').'/view/drain-hole/icons/'.$this->icon;
			$info = getimagesize (TEMPLATEPATH."/view/drain-hole/icons/{$this->icon}");
		}
		else if (file_exists (dirname (__FILE__)."/../icons/{$this->icon}"))
		{
			$url  = $dir.'/icons/'.$this->icon;
			$info = getimagesize (dirname (__FILE__).'/../icons/'.$this->icon);
		}
		else
		{
			$url  = $dir.'/images/download.png';
			$info = getimagesize (dirname (__FILE__).'/../images/download.png');
		}
		
		return $this->url ($hole, '<img src="'.$url.'" alt="download" '.$info[3].'/>', $google);
	}
	
	function available_icons ()
	{
		$files = glob (dirname (__FILE__).'/../icons/*');
		$files = array_merge ($files, glob (TEMPLATEPATH."/view/drain-hole/icons/*"));
		
		if (count ($files) > 0)
		{
			foreach ($files AS $file)
				$newfiles[basename ($file)] = ucwords (preg_replace ('/\.\w*$/', '', str_replace ('-', ' ', basename ($file))));
			$files = $newfiles;
		}
		
		return $files;
	}
	
	
	function name ($text = '')
	{
		$name = $text;
		if ($name == '')
			$name = $this->name ? str_replace ('$version$', $this->version, $this->name) : $this->file;
		return $name;
	}
	
	
	/**
	 * Returns an HTML anchor for the file
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @param string $text Text inside the anchor
	 * @param boolean $google Whether to add Google Analytics tracking code
	 * @return string HTML anchor tag
	 **/
	
	function url ($hole, $text = '', $google = false, $version = '')
	{
		$versioninfo = $this->version;
		if ($version)
			$versioninfo = $version->version;
		
		if ($versioninfo)
			$title = sprintf (__ ('Download version %s of %s', 'drain-hole'), $versioninfo, basename ($this->file));
		else
			$title = sprintf (__ ('Download %s', 'drain-hole'), basename ($this->file));
			
		$url = $this->url_ref ($hole, false, $version);
		if ($google)
			return '<a rel="nofollow" title="'.htmlspecialchars ($title).'" onclick="if (window.urchinTracker) urchinTracker (\''.$url.'\');" href="'.$this->url_ref ($hole).'">'.$this->name ($text)."</a>";
		else
			return '<a rel="nofollow" title="'.htmlspecialchars ($title).'" href="'.$url.'">'.$this->name ($text)."</a>";
	}
	
	function url_ref ($hole, $baseonly = false, $version = '')
	{
		$url = $hole->url.'/'.$this->file;

		if (isset($this->options['force_access']) && $this->options['force_access'] && !empty ($_COOKIE[USER_COOKIE]) && !empty ($_COOKIE[PASS_COOKIE]))
		{
			$user = $_COOKIE[USER_COOKIE].$_COOKIE[PASS_COOKIE].$this->file;
			$url .= '?id='.md5 ($user);
			if ($version && $version->id != $this->version_id)
				$url .= '&amp;version='.urlencode ($version->version);
		}
		else if ($version && $version->id != $this->version_id)
			$url .= '?version='.urlencode ($version->version);
		
		if ($baseonly)
		{
			$url = explode ('?', $url);
			return $url[0];
		}
		
		return get_option ('home').$url;
	}
	
	
	/**
	 * Returns the full path to the file
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @return string Path
	 **/
	
	function file ($hole, $version = '')
	{
		if ($version)
			return $hole->directory.DIRECTORY_SEPARATOR.'.versions'.DIRECTORY_SEPARATOR.sprintf ('file_%d_version_%s', $this->id, $version);
		return $hole->directory.DIRECTORY_SEPARATOR.$this->file;
	}
	

	/**
	 * Returns the size of the file
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @return int Bytes
	 **/
	
	function filesize ($hole, $version = '')
	{
		return @filesize ($this->file ($hole, $version));
	}
	
	
	/**
	 * Register a download against the file
	 *
	 * @return void
	 **/
	
	function hit ($version = '')
	{
		global $wpdb;
		
		if ($version == '')
			$version = $this->version_id;

		$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_files SET hits=hits+1 WHERE id='{$this->id}'");
		$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_version SET hits=hits+1 WHERE id='{$version}'");
		
		return DH_Access::create ($this->id, $version);
	}
	
	
	/**
	 * Determine the MIME type for the file.  Best attempt is with the 'finfo_file' command, if it exists.
	 * Next attempt is with the 'mime_content_type' command, then we check the extension, finally
	 * defaulting to a generic 'x-download'
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @return string Path
	 **/
	
	function mime_type ($hole)
	{
		$mime = '';
		if ($this->mime != '')
			return $this->mime;
			
		$info = pathinfo ($this->file ($hole));
		if (function_exists ('finfo_open'))
		{
			$finfo = finfo_open (FILEINFO_MIME);
		    $mime = finfo_file ($finfo, $this->file ($hole));
			finfo_close ($finfo);
		}
		else if (function_exists ('mime_content_type'))
			$mime = mime_content_type ($this->file ($hole));
		else
		{
			include (dirname (__FILE__).'/mime_types.php');

			if (isset ($extension_to_mime[$info['extension']]))
				$mime = $extension_to_mime[$info['extension']];
		}

		if (!$mime)
			$mime = 'application/octet-stream';
		
		return $mime;
	}
	
	
	/**
	 * Load the file and feed it to the user's browser (after registering a hit).  Execution is halted
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 **/
	
	function download ($hole, $version = '')
	{
		// Is this a local or a remote file?
		$download_as = $this->download_as (true);
		if (strpos ($download_as, '://') !== false && $version == '')
		{
			$id = $this->hit ($version);
			header ('Location: '.$download_as);
			exit;
		}
		else if ($this->exists ($hole, $version))
		{
			// Calculate download as name
			$download_as = $this->download_as (true);
			
			// Record a hit
			$id = $this->hit ($version);
			
			// Detect MIME type
			$mime = $this->mime_type ($hole);

			// Send out the data
			header ("Content-Type: $mime");
			header ("Last-Modified: ".gmdate ("D, d M Y H:i:s", mysql2date ('U', $this->updated_at))." GMT");
			header ("Cache-Control: must-revalidate, post-check=0, pre-check=0"); // HTTP/1.1
			header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   // Date in the past
			header ("Content-Length: ".$this->filesize ($hole, $version));
			
			if ($this->options['force_download'] == true)
				header ('Content-Disposition: attachment; filename="'.basename ($download_as).'"');
			header ("Content-Transfer-Encoding: binary");
	
			if (!ini_get ('safe_mode'))
				set_time_limit (0);
				
			readfile ($this->file ($hole, $version));

			if ($id)
				DH_Access::finished ($id, $this->filesize ($hole, $version));
			exit;
		}
	}
	
	
	/**
	 * Accepts a FORM-based upload and replaces the file's underlying data
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @return boolean true if successfully uploaded, false if the upload was invalid
	 **/
	
	function upload ($hole, $filename, $filedata)
	{
		global $wpdb;

		// If no filename is given then use the uploaded data
		if ($filename == '' && is_uploaded_file ($filedata['tmp_name']))
			$filename = DH_Hole::sanitize_dir (ltrim ($filedata['name'], '/'));
		
		if ($filename)
		{
			if (is_uploaded_file ($filedata['tmp_name']))
				move_uploaded_file ($filedata['tmp_name'], $hole->directory.DIRECTORY_SEPARATOR.$filename);
			
			return DH_File::create ($hole->id, $filename);
		}
		
		return false;
	}
	
	
	/**
	 * Returns a pretty-printed size in terms of the number of bytes, KB, MB, or GB (whichever is most appropriate)
	 *
	 * @static
	 * @param int $size Number of bytes
	 * @return string Pretty-print bytes
	 **/
	
	function bytes ($size)
	{
		if ($size == 0)
			return '0 bytes';
		else if ($size == 1)
			return '1 byte';
		else if ($size < 1024)
			return $size." bytes";
		else if ($size < (1024 * 1024))
			return round (($size / 1024), 2)." KB";
		else if ($size < (1024 * 1024 * 1024))
			return round (($size / (1024 * 1024)), 2). " MB";
		return round (($size / (1024 * 1024 * 1024)), 2)." GB";
	}
	
	
	/**
	 * Returns a pretty-printed version of a time duration.  Time will be in the largest units possible, or <1s
	 *
	 * @static
	 * @param int $seconds Number of seconds
	 * @return string Pretty-print duration
	 **/
	
	function timespan ($seconds)
	{
		if ($seconds == 0)
			return '&lt;1s';
		
		$hours = $mins = $secs = 0;	
		if ($seconds >= (60 * 60))
		{
			$hours = round ($seconds / (60 * 60));
			$mins  = round (($seconds % (60 * 60)) / 60);
			$secs  = ($seconds % (60 * 60)) % 60;
		}
		else if ($seconds >= 60)
		{
			$mins = round ($seconds / 60);
			$secs = $seconds % 60;
		}
		else
			$secs = $seconds;

		$parts = array ();
		if ($hours > 0)
			$parts[] = $hours.'h';
		
		if ($mins > 0)
			$parts[] = $mins.'m';
		
		if ($secs > 0)
			$parts[] = $secs.'s';
			
		return implode (' ', $parts);
	}
	
	
	/**
	 * Determine whether the current user has permission to download the file
	 *
	 * @param DH_Hole $hole Hole in which the file lives
	 * @return boolean true if they can access it, false otherwise
	 **/
	
	function have_access ($hole)
	{
		$user = wp_get_current_user ();
		
		// Check forced access
		if (isset($this->options['force_access']) && $this->options['force_access'])
		{
			if (preg_match ('/id=([a-zA-Z0-9]*)/', $_SERVER['REQUEST_URI'], $matches) > 0)
			{
				// Now check that we can find a user with the appropriate details
				global $wpdb;
				$id = $wpdb->escape ($matches[1]);
				$user = $wpdb->get_row ("SELECT * FROM {$wpdb->users} WHERE MD5(CONCAT(user_login,MD5(user_pass),'{$this->file}'))='$id'");
				if (!$user)
					return false;
			}
			else
				return false;
		}
		
		if ($hole->role != '')
		{
			if ($user->ID > 0)
			{
				if ($hole->role == 'paid' && class_exists ('SH_Cart'))
				{
					// See if user has paid for this
					if (!SH_Cart::has_user_purchased ($this->id))
						return false;
				}
				else
				{
					global $wp_roles;
					$caps = $wp_roles->get_role ($hole->role);
			
					// Get highest level of the role
					for ($x = 10; $x >= 0; $x--)
					{
						if (isset ($caps->capabilities['level_'.$x]))
							break;
					}

					// Can this user access that level
					if (!isset ($user->allcaps['level_'.$x]))
						return false;
				}
			}
			else
				return false;
		}
		
		// Check hotlinking
		if ($hole->hotlink)
		{
			// Check that the referrer is from our site
			if (isset ($_SERVER['HTTP_REFERER']) && strlen ($_SERVER['HTTP_REFERER']) > 0 && substr ($_SERVER['HTTP_REFERER'], 0, strlen (get_bloginfo ('home'))) != get_bloginfo ('home'))
				return false;
		}
		
		$result = apply_filters ('drain_hole_access', $this);
		if (is_object ($result))
			return true;
		return $result;
	}
	
	
	/**
	 * Helper function to delete a directory and all contents
	 *
	 * @static
	 * @param string $dir Directory to delete
	 * @return void
	 **/
	
	function rmdir ($dir)
	{
		$options = get_option ('drainhole_options');
		if (isset ($options['delete_file']) && $options['delete_file'])
		{
			$files = glob (rtrim ($dir,'/')."*");
			if (count ($files) > 0)
			{
				foreach ($files AS $file)
				{
					if (is_file ($file))
						@unlink ($file);
				}

				foreach ($files AS $file)
					@unlink ($file);
			}
		
			rmdir ($dir);
		}
	}
	
	
	/**
	 * Returns the most downloaded files across all holes
	 *
	 * @param int $count Maximum number of files
	 * @return array Array of DH_File objects
	 **/
	
	function get_top_downloads ($count)
	{
		global $wpdb;
	
		$rows = $wpdb->get_results ("SELECT * FROM {$wpdb->prefix}drainhole_files ORDER BY hits DESC LIMIT 0,$count", ARRAY_A);
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new DH_File ($row);
		}
		
		return $data;
	}
	
	function next_version ()
	{
		$parts = explode ('.', $this->version);
		if (count ($parts) > 1)
		{
			$parts[count ($parts) - 1]++;
			return implode ('.', $parts);
		}
		else if (is_integer ($this->version))
			return $this->version + 1;
		return $this->version;
	}
	
	function create_new_version ($version, $branch, $reason, $dontbranch = false, $svnupdate = false)
	{
		global $wpdb;
		
		$hole = DH_Hole::get ($this->hole_id);
		
		// Branch our copy
		if ($dontbranch === false && $branch)
		{
			if ($hole && $this->exists ($hole))
			{
				$target = $this->file ($hole, $this->version_id);
				@wp_mkdir_p (dirname ($target));
				@copy ($this->file ($hole), $target);
			}
		}

		// Sort out any SVN business
		if ($this->svn && $svnupdate)
		{
			$options = get_option ('drainhole_options');
			if ($options && isset ($options['svn']) && $options['svn'])
			{
				include (dirname (__FILE__).'/svn.php');

				$svn = new DH_SVN ($this->svn, $options['svn']);
				$svn->update ($this->file ($hole));

				if ($svn->version ())
					$version = $svn->version ();
			}
		}

		// Store details in a version branch
		if ($dontbranch === false)
		{
			$version = DH_Version::create ($this, $version, $this->hits - DH_Version::total_hits ($this->id), '', $reason);

			// Update our details
			$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_files SET updated_at=NOW(), version_id='$version' WHERE id={$this->id}");
		}
		else
			$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_version SET version='$version', created_at=NOW() WHERE id={$this->version_id}");
		
		return true;
	}

	function earliest ()
	{
		global $wpdb;
		
		$latest = $wpdb->get_var ("SELECT {$wpdb->prefix}drainhole_access.created_at FROM {$wpdb->prefix}drainhole_access WHERE {$wpdb->prefix}drainhole_access.file_id={$this->id} ORDER BY created_at ASC LIMIT 0,1");
		$time = mysql2date ('U', $latest);
		
		return mktime (0, 0, 0, date ('n', $time), date ('j', $time), date ('Y', $time));
	}
	
	function latest ()
	{
		global $wpdb;

		$latest = $wpdb->get_var ("SELECT {$wpdb->prefix}drainhole_access.created_at FROM {$wpdb->prefix}drainhole_access WHERE {$wpdb->prefix}drainhole_access.file_id={$this->id} ORDER BY created_at DESC LIMIT 0,1");
		$time = mysql2date ('U', $latest);
		
		return mktime (24, 0, 0, date ('n', $time), date ('j', $time), date ('Y', $time));
	}
	
	// Return true if the version has a file, false otherwise
	function has_version ($version, $hole)
	{
		if ($this->version_id == $version || file_exists ($this->file ($hole, $version)))
			return true;
		return false;
	}
	
	function svn ()
	{
		if (substr ($this->svn, 0, 4) == 'http')
			return '<a rel="nofollow" href="'.$this->svn.'">SVN</a>';
		return '';
	}
	
	function download_as ($fullparse = false)
	{
		$url = basename ($this->file);
		if ($this->download_as)
			$url = $this->download_as;
			
		if ($fullparse)
			$url = str_replace ('$version$', $this->version, $url);
		
		return $url;
	}
	
	/**
	* Returns the modification time of a file from fs
	*
	* @param DH_Hole $hole Hole in which the file lives
	* @return int Timestamp
	**/
	function fs_time ($hole, $version = '')
	{
		return @filemtime ($this->file ($hole, $version));
	}
}
?>
