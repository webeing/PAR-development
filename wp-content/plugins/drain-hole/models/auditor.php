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
 * Provide monitoring facilities for Audit Trail (http://urbangiraffe.com/plugins/audit-trail/)
 *
 * @package Drain Hole
 * @author John Godley
 * @copyright Copyright (C) John Godley
 **/

class DH_Auditor extends DH_Plugin
{
	function DH_Auditor ()
	{
		$this->register_plugin ('drain-hole', dirname (__FILE__));
		
		$this->add_filter ('audit_collect');
		$this->add_action ('audit_listen');
		$this->add_filter ('audit_show_operation');
		$this->add_filter ('audit_show_item');
	}
	
	function audit_collect ($items)
	{
		$items['drainhole'] = __ ('Drain Hole management', 'drain-hole');
		return $items;
	}
	
	function audit_listen ($method)
	{
		if ($method == 'drainhole')
		{
			$this->add_action ('drainhole_installed');
			$this->add_action ('drainhole_hole_created');
			$this->add_action ('drainhole_scan');
			$this->add_action ('drainhole_upload');
		}
	}
	
	function audit_show_item ($item)
	{
		switch ($item->operation)
		{
			case 'drainhole_hole_created' :
			case 'drainhole_upload' :
			case 'drainhole_scan' :
				$hole = unserialize ($item->data);
				$item->message = $hole->url;
				break;
		}
		
		return $item;
	}
	
	function audit_show_operation ($item)
	{
		switch ($item->operation)
		{
			case 'drainhole_installed' :
				$item->message = __ ('Drain Hole database installed', 'drain-hole');
				break;
				
			case 'drainhole_hole_created' :
				$item->message = __ ('Drain Hole created', 'drain-hole');
				break;
			
			case 'drainhole_scan' :
				$item->message = __ ('Drain Hole scan', 'drain-hole');
				break;
				
			case 'drainhole_upload' :
				$item->message = __ ('Drain Hole file upload', 'drain-hole');
				break;
		}
		
		return $item;
	}
	
	function drainhole_installed ()
	{
		AT_Audit::create ('drainhole_installed');
	}
	
	function drainhole_hole_created ()
	{
		AT_Audit::create ('drainhole_hole_created');
	}
	
	function drainhole_scan ($hole)
	{
		AT_Audit::create ('drainhole_scan', $hole->id, serialize ($hole));
	}
	
	function drainhole_upload ($hole)
	{
		AT_Audit::create ('drainhole_upload', $hole->id, serialize ($hole));
	}
}

?>