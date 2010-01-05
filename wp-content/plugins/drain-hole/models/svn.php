<?php

include (dirname (__FILE__).'/zip.lib.php');

class DH_SVN
{
	var $url     = null;
	var $version = '';
	var $svn     = null;
	var $command = null;
	
	function DH_SVN ($url, $command)
	{
		$this->command = $command;
		$this->url     = $url;
		$this->svn     = 'svn';
		
		if (!ini_get ('safe_mode'))
			set_time_limit (0);
	}
	
	function update ($file)
	{
		$response = array ();
		$result   = 0;
		
		// Create a temporary directory for all the files
		wp_mkdir_p ($file.'.tmp');
		
		exec ("{$this->command} export {$this->url} {$file}.tmp --force", $response, $result);
		if ($result === 0)
		{
			// Check if this is a plugin by scanning for a plugin header
			$files = glob ($file.'.tmp/*.php');
			if (count ($files) > 0)
			{
				foreach ($files AS $tmp)
				{
					$data = file_get_contents ($tmp);
					if (strpos ($data, 'Plugin Name:') !== false)
					{
						if (preg_match ('/Version:\s*(.*)/', $data, $matches) > 0)
							$this->version = trim ($matches[1]);
						break;
					}
				}
				
				if (count ($files) > 1)
				{
					// Zip the directory
					$zip = new zipfile ();

					$this->zip ($zip, $file.'.tmp', $file.'.tmp');

					$fd = fopen ($file, 'w');
					fwrite ($fd, $zip->file ());
					fclose ($fd);
				}
			}
			
			$this->cleanup ($file.'.tmp');
		}
	}
	
	function version ()
	{
		return $this->version;
	}
	
	function zip (&$zipper, $dir, $base)
	{
		$files = glob ($dir.'/*');
		if (count ($files) > 0)
		{
			foreach ($files AS $item)
			{
				if (is_dir ($item))
					$this->zip ($zipper, $item, $base);
				else
				{
					$data = file_get_contents ($item);
					$zipper->addFile ($data, str_replace ('.zip.tmp', '', basename ($base)).DIRECTORY_SEPARATOR.substr ($item, strlen ($base) + 1));
				}
			}
		}
	}
	
	function cleanup ($dir)
  {
		if (!is_writable ($dir))
		{
			if (!@chmod ($dir, 0777))
				return false;
		}

		$d = dir ($dir);
		while (false !== ($entry = $d->read ()))
		{
			if ($entry == '.' || $entry == '..')
				continue;

			$entry = $dir.'/'.$entry;
			if (is_dir ($entry))
			{
				if (!$this->cleanup ($entry))
					return false;
			
				continue;
			}

			if (!@unlink ($entry))
			{
				$d->close();
				return false;
			}
		}

		$d->close ();

		rmdir ($dir);
		return true;
  }
}
?>