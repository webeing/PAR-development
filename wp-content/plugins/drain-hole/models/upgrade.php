<?php

// versions are not linked to file
// version database is just a history, the files table is always the latest count
class DH_Upgrade
{
	function run ($desired)
	{
		global $wpdb;
		
		$version = get_option ('drainhole_version');
		
		// Here we upgrade from version 1.0, or we are installing as new
		if ($version === false)
		{
			// Is anything installed from version 1.0
			$result = $wpdb->get_row ("SHOW TABLES LIKE 'drainhole_files'");
			
			// Do we need to migrate anything?
			if ($result != '')
			{
				// Upgrading from version 1.0 => 1.1
				$this->create_tables_1 ();
				$this->upgrade_from_0 ();

				$version = 1;
			}
			else
				$this->create_tables_2 ();	// Installing from scratch
		}
		
		// Upgrading from version 1.1.x => 1.2
		if ($version == 1)
		{
			$this->create_tables_2 ();
			$this->upgrade_from_1 ();
		}
		else if ($version == 2 || $version == 3)
		{
			$this->create_tables_2 ();
			$this->upgrade_from_2 ();
		}
		else if ($version == 4)
		{
			$this->upgrade_from_3 ();
		}

		DH_Hole::flush ();
		update_option ('drainhole_version', $desired);
	}
	
	function create_tables_1 ()
	{
		// New tables
		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}drainhole_files` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `hole_id` int(11) unsigned NOT NULL,
		  `file` varchar(200) NOT NULL default '',
		  `version` varchar(20) NOT NULL default '0',
		  `downloads` int(11) unsigned NOT NULL default '0',
		  `updated_at` datetime NOT NULL,
		  `mime` varchar(50) default NULL,
		  `force_download` int(10) unsigned NOT NULL default '1',
		  `icon` varchar(50) default NULL,
		  PRIMARY KEY  (`id`),
		  KEY `file` (`file`)
		)");

		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}drainhole_access` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `file_id` int(10) unsigned NOT NULL,
		  `created_at` datetime NOT NULL,
		  `speed` int(10) unsigned default NULL,
		  `ip` int(10) unsigned NOT NULL,
		  `time_taken` int(10) unsigned default NULL,
		  `referrer` varchar(150) default NULL,
		  PRIMARY KEY  (`id`)
		)");

		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}drainhole_holes` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `url` varchar(200) NOT NULL default '',
		  `directory` varchar(200) NOT NULL,
		  `role` varchar(30) default NULL,
 			`role_error_url` varchar(100) default NULL,
		  PRIMARY KEY  (`id`)
		)");
	}
	
	function create_tables_2 ()
	{
		global $wpdb;
		
		// New tables
		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}drainhole_files` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `hole_id` int(11) unsigned NOT NULL,
	  	`version_id` int(11) unsigned NOT NULL,
		  `file` varchar(150) NOT NULL default '',
		  `name` varchar(150) NOT NULL default '',
		  `mime` varchar(50) default NULL,
		  `icon` varchar(50) default NULL,
		  `description` text default NULL,
			`hits` int(10) unsigned NOT NULL,
		  `updated_at` datetime NOT NULL,
		  `options` mediumtext,
		  `svn` varchar(150) default NULL,
	  	`download_as` varchar(150) default NULL,
		  PRIMARY KEY  (`id`),
		  KEY `file` (`file`)
		)");

		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}drainhole_access` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `file_id` int(10) unsigned NOT NULL,
		  `created_at` datetime NOT NULL,
		  `speed` int(10) unsigned default NULL,
		  `ip` int(10) unsigned NOT NULL,
		  `time_taken` int(10) unsigned default NULL,
		  `referrer` varchar(150) default NULL,
			`user_id` int(10) unsigned NOT NULL,
		  `version_id` int(10) unsigned NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		)");

		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}drainhole_holes` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `url` varchar(200) NOT NULL default '',
		  `directory` varchar(200) NOT NULL,
		  `role` varchar(30) default NULL,
 			`role_error_url` varchar(100) default NULL,
  		`hotlink` tinyint(4) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		)");
		
		$wpdb->query ("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}drainhole_version` (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `file_id` int(11) unsigned NOT NULL,
		  `version` varchar(40) NOT NULL default '',
		  `hits` int(11) unsigned NOT NULL,
		  `created_at` datetime NOT NULL,
		  `reason` mediumtext,
		  PRIMARY KEY  (`id`)
		)");
	}
	
	function upgrade_from_3 ()
	{
		global $wpdb;
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` ADD `download_as` varchar(150) DEFAULT NULL ");
	}
		
	function upgrade_from_2 ()
	{
		global $wpdb;
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_holes` ADD `hotlink` tinyint NOT NULL DEFAULT '0' ");
		$this->upgrade_from_3 ();
	}
	
	function upgrade_from_1 ()
	{
		global $wpdb;
		
		// Add columns to drainhole_access: version_id, user_id
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_access` ADD `user_id` int UNSIGNED NOT NULL;");

		// Add columns to drainhole_files: name, description, created_at
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` ADD `name` varchar(150) NOT NULL default '';");
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` ADD `description` TEXT default NULL;");
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` ADD	`version_id` int(11) unsigned NOT NULL;");
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` ADD `options` mediumtext DEFAULT NULL ;");
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` ADD `svn` varchar(150) DEFAULT NULL ;");
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_access` ADD `version_id` int UNSIGNED NOT NULL DEFAULT '0';");

		// Change columns in drainhole_files for: file
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` CHANGE `file` `file` varchar(150) NOT NULL default '';");
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` CHANGE `downloads` `hits` int(10) UNSIGNED NOT NULL DEFAULT '0';");
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_holes` CHANGE `role_error_id` `role_error_url` varchar(100) DEFAULT NULL;");

		$files = DH_File::get_all ();

		// Remove columns
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` DROP `version`;");
		$wpdb->query ("ALTER TABLE `{$wpdb->prefix}drainhole_files` DROP `force_download`;");
		
		// Create the first version for each file and link it back to the file
		// Also link all access stats to the latest version
		if (count ($files) > 0)
		{
			foreach ($files as $file)
			{
				$version = DH_Version::create ($file, $file->version, $file->hits, $file->updated_at, __ ('First version', 'drain-hole'));

				$options = $wpdb->escape (serialize (array ('force_download' => $file->force_download)));
				
				$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_files SET version_id='$version',options='$options' WHERE id='{$file->id}'");
				$wpdb->query ("UPDATE {$wpdb->prefix}drainhole_access SET version_id='$version' WHERE file_id='{$file->id}'");
			}
		}
	}
	
	// Upgrades from the original version, copying all data
	function upgrade_from_0 ()
	{
		// Copy old tables
		$old = $wpdb->get_results ("SELECT * FROM drainhole_files");
		if (count ($old) > 0)
		{
			DH_Hole::create (array ('url' => get_option ('drainhole_store'), 'directory' => realpath (ABSPATH).'/'.get_option ('drainhole_store')));
			$hole = DH_Hole::get ($wpdb->insert_id);

			foreach ($old AS $row)
			{
				$version = $wpdb->escape ($row->version);
				$file    = $wpdb->escape ($row->file);

				$wpdb->query ("INSERT INTO {$wpdb->prefix}drainhole_files (file,hole_id,version,downloads,updated_at) VALUES ('$file',{$hole->id},'$version','{$row->downloads}',NOW())");
				$file = DH_File::get ($wpdb->insert_id);
				$file->sync_modified_time ($hole);
			}
		}

		// Delete old tables
		$wpdb->query ("DROP TABLE drainhole_files");
		$wpdb->query ("DROP TABLE drainhole_access");
	}

	function delete ($plugin)
	{
		global $wpdb;
		
		$wpdb->query ("DROP TABLE {$wpdb->prefix}drainhole_files");
		$wpdb->query ("DROP TABLE {$wpdb->prefix}drainhole_access");
		$wpdb->query ("DROP TABLE {$wpdb->prefix}drainhole_holes");
		$wpdb->query ("DROP TABLE {$wpdb->prefix}drainhole_version");
		
		delete_option ('drainhole_store');
		delete_option ('drainhole_enable');
		delete_option ('drainhole_cache');
		delete_option ('drainhole_version');
		delete_option ('drainhole_options');
		delete_option ('widgets_config_drainhole');
		
		// Deactivate the plugin
		$current = get_option('active_plugins');
		array_splice ($current, array_search (basename (dirname ($plugin)).'/'.basename ($plugin), $current), 1 );
		update_option('active_plugins', $current);
	}
}

?>