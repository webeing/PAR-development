<?php


/**
 * DrainHole AJAX
 *
 * @package Drain Hole
 * @author John Godley
 * @copyright Copyright (C) John Godley
 **/

if (file_exists ('../../../wp-load.php'))
	include ('../../../wp-load.php');
else
	include ('../../../wp-config.php');

class DH_AJAX extends DH_Plugin
{
	function DH_AJAX ($id, $command)
	{
		if (!current_user_can ('edit_plugins'))
			die (__ ('<p style="color: red">You are not allowed access to this resource</p>', 'drain-hole'));
		
		$_POST = stripslashes_deep ($_POST);
		
		$this->register_plugin ('drain-hole', __FILE__);
		if (method_exists ($this, $command))
			$this->$command ($id);
		else
			die (__('<p style="color: red">That function is not defined</p>', 'drain-hole'));
	}

	function delete_holes ($id)
	{
		if (check_ajax_referer ('drainhole-delete_items'))
		{
			foreach ($_POST['checkall'] AS $fileid)
				DH_Hole::delete ($fileid);
				
			DH_Hole::flush ();
		}
	}
	
	function edit_hole ($id)
	{
		$hole = DH_Hole::get (intval ($id));
		$this->render_admin ('hole_edit', array ('hole' => $hole));
	}
	
	function show_hole ($id)
	{
		$hole = DH_Hole::get ($id);
		$this->render_admin ('hole_item', array ('hole' => $hole));
	}
	
	function save_hole ($id)
	{
		if (check_ajax_referer ('drainhole-save_hole'))
		{
			$hole = DH_Hole::get ($id);
			$hole->update ($_POST);

			DH_Hole::flush ();
		}
	}
	
	
	function edit_file ($id)
	{
		include (dirname (__FILE__).'/models/mime_types.php');
		
		$file = DH_File::get ($id);
		$this->render_admin ('files_edit', array ('file' => $file, 'types' => $mime_types));
	}
	
	function show_file ($id)
	{
		$file = DH_File::get ($id);
		$hole = DH_Hole::get ($file->hole_id);
		
		$this->render_admin ('files_item', array ('file' => $file, 'hole' => $hole));	
	}
	
	function save_file ($id)
	{
		if (check_ajax_referer ('drainhole-save_file'))
		{
			$file = DH_File::get ($id);
			$hole = DH_Hole::get ($file->hole_id);

			$file->update ($_POST);
		
			DH_Hole::flush ();
		}
	}
	
	function delete_files ($id)
	{
		if (check_ajax_referer ('drainhole-delete_items'))
		{
			foreach ($_POST['checkall'] AS $fileid)
				DH_File::delete ($fileid);
				
			DH_Hole::flush ();
		}
	}

	function delete_stats ($id)
	{
		if (check_ajax_referer ('drainhole-delete_items'))
			DH_Access::delete ($_POST['checkall']);
	}
	
	function new_version ($id)
	{
		$file = DH_File::get ($id);
		
		$this->render_admin ('new_version', array ('file' => $file));
	}
	
	function save_new_version ($id)
	{
		if (check_ajax_referer ('drainhole-version_new'))
		{
			$file = DH_File::get ($id);
			if ($file)
				$file->create_new_version ($_POST['new_version'], isset ($_POST['branch']) ? true : false, $_POST['reason'], isset ($_POST['donotbranch']) ? true : false, isset ($_POST['svn']) ? true : false);
		}
	}
	
	function edit_version ($id)
	{
		$this->render_admin ('versions_edit', array ('version' => DH_Version::get ($id)));
	}
	
	function save_version ($id)
	{
		if (check_ajax_referer ('drainhole-version_save'))
		{
			$version = DH_Version::get ($id);
			$version->update ($_POST);
		}
	}
	
	function show_version ($id)
	{
		$version = DH_Version::get ($id);
		$file    = DH_File::get ($version->file_id);
		$hole    = DH_Hole::get ($file->hole_id);
		
		$this->render_admin ('versions_item', array ('version' => $version, 'hole' => $hole, 'file' => $file));
	}
	
	function delete_version ($id)
	{
		if (check_ajax_referer ('drainhole-delete_items'))
		{
			foreach ($_POST['checkall'] AS $fileid)
			{
				$version = DH_Version::get ($id);
				$version->delete ();
			}
		}
	}
	
	function editor ($id)
	{
		$this->render_admin ('editor', array ('files' => DH_File::get_all ()));
	}
}

$id  = $_GET['id'];
$cmd = $_GET['cmd'];

$obj = new DH_AJAX ($id, $cmd);

?>