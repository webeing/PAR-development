<?php
	
include (dirname (__FILE__).'/../lib/charts/charts.php');

class Charts extends DH_Plugin
{
	var $swf;
	var $lib;
	var $source;
	
	function Charts ($base)
	{
		$this->swf = $base.'/lib/charts/charts.swf';
		$this->lib = $base.'/lib/charts/charts_library/';
		
		$this->register_plugin ('drain-hole', (dirname (__FILE__)));
	}
	
	function set_source ($source)
	{
		$this->source = $source;
	}
	
	function get ()
	{
		if ($this->source)
			return InsertChart ($this->swf, $this->lib, $this->source, 800, 400, 'eeeeee');
		return '';
	}
	
	
	
	function next ($display, $item)
	{
		$base = $_SERVER['REQUEST_URI'];
		$base = preg_replace ('/&year=\d*/', '', $base);
		$base = preg_replace ('/&month=\d*/', '', $base);
		$base = preg_replace ('/&day=\d*/', '', $base);
		$base = str_replace ('&show=Show', '', $base);
		
		$url = '';
		$times = $this->get_time ($item);
		if ($display == 'hourly')
		{
			$time = mktime (0, 0, 0, $times['month'], $times['day'] + 1, $times['year']);
	
			$latest = $item->latest ();
			if ($time < $latest)
				$url = $base.'&year='.date ('Y', $time).'&month='.date ('n', $time).'&day='.date ('j', $time);
		}
		else if ($display == 'daily')
		{
			$time = mktime (0, 0, 0, $times['month'] + 1, 1, $times['year']);
			$latest = $item->latest ();
			
			if ($time < $latest)
				$url = $base.'&year='.date ('Y', $time).'&month='.date ('n', $time);
		}
		else if ($display == 'monthly')
		{
			$time = mktime (0, 0, 0, 1, 1, $times['year'] + 1);
			$latest = $item->latest ();
			if ($time < $latest)
				$url = $base.'&year='.date ('Y', $time);
		}
		
		if ($url)
			return '<div class="right"><a href="'.str_replace ('&', '&amp;', $url).'">'.__ ('next', 'drain-hole').' &raquo;</a></div>';
		return '';
	}
	
	function previous ($display, $item)
	{
		$base = $_SERVER['REQUEST_URI'];
		$base = preg_replace ('/&year=\d*/', '', $base);
		$base = preg_replace ('/&month=\d*/', '', $base);
		$base = preg_replace ('/&day=\d*/', '', $base);
		$base = str_replace ('&show=Show', '', $base);
		
		$url   = '';
		$times = $this->get_time ($item);
		if ($display == 'hourly')
		{
			$time = mktime (0, 0, 0, $times['month'], $times['day'] - 1, $times['year']);
			$earliest = $item->earliest ();
			
			if ($time >= $earliest)
				$url = $base.'&year='.date ('Y', $time).'&month='.date ('n', $time).'&day='.date ('j', $time);
		}
		else if ($display == 'daily')
		{
			$time = mktime (0, 0, 0, $times['month'], 0, $times['year']);
			$earliest = $item->earliest ();
	
			if ($time >= $earliest)
				$url = $base.'&year='.date ('Y', $time).'&month='.date ('n', $time);
		}
		else if ($display == 'monthly')
		{
			$time = mktime (0, 0, 0, 12, 31, $times['year'] - 1);
			$earliest = $item->earliest ();
			if ($time >= $earliest)
				$url = $base.'&year='.date ('Y', $time);
		}
		
		if ($url)
			return '<div class="left"><a href="'.str_replace ('&', '&amp;', $url).'">&laquo; '.__ ('previous', 'drain-hole').'</a></div>';
		return '';
	}
	
	
	function show_time ($display, $item)
	{
		$earliest = $item->earliest ();
		$times    = $this->get_time ($item);
		
		if ($display == 'hourly')
		{
			$this->render_admin ('time_years', array ('current' => $times['year'], 'locale' => $wp_locale, 'start' => date ('Y', $earliest), 'end' => date ('Y')));
			$this->render_admin ('time_months', array ('current' => $times['month'], 'locale' => $wp_locale));
			$this->render_admin ('time_days', array ('current' => $times['day'], 'locale' => $wp_locale));
		}
		else if ($display == 'daily')
		{
			$this->render_admin ('time_years', array ('current' => $times['year'], 'locale' => $wp_locale, 'start' => date ('Y', $earliest), 'end' => date ('Y')));
			$this->render_admin ('time_months', array ('current' => $times['month'], 'locale' => $wp_locale));	
		}
		else if ($display == 'monthly')
			$this->render_admin ('time_years', array ('current' => $times['year'], 'locale' => $wp_locale, 'start' => date ('Y', $earliest), 'end' => date ('Y')));
	}

	
	function time_to_query ($times)
	{
		$str = '';
		foreach ($times AS $key => $value)
			$str .= '&'.$key.'='.$value;
		return $str;
	}
	
	function get_time ($item)
	{
		$times = array ();
		
		$year = date ('Y');
		if (isset ($_GET['year']) && intval ($_GET['year']) >= 2006 && intval ($_GET['year']) <= date ('Y'))
			$year = intval ($_GET['year']);
		
		$month = intval (date ('m'));
		if (isset ($_GET['month']) && intval ($_GET['month']) >= 1 && intval ($_GET['month']) <= 12)
			$month = intval ($_GET['month']);
			
		$day = intval (date ('d'));
		if (isset ($_GET['day']) && intval ($_GET['day']) >= 1 && intval ($_GET['day']) <= 31)
			$day = intval ($_GET['day']);
	
		// Validate we have data in the times
		$earliest = $item->earliest ();
		$latest   = $item->latest ();

		if ($year < date ('Y', $earliest) || ($year == date ('Y', $earliest) && $month < date ('n', $earliest)) || ($year == date ('Y', $earliest) && $month == date ('n', $earliest) && $day < date ('j', $earliest)))
		{
			$year  = date ('Y', $earliest);
			$month = date ('n', $earliest);
			$day   = date ('j', $earliest);
		}
		
		if ($year > date ('Y', $latest) || ($year == date ('Y', $latest) && $month > date ('n', $latest)) || ($year == date ('Y', $latest) && $month == date ('n', $latest) && $day >= date ('j', $latest)))
		{
			$year  = date ('Y', $latest);
			$month = date ('n', $latest);
			$day   = date ('j', $latest) - 1;
		}
		
		$time = mktime (0, 0, 0, $month, $day, $year);
		
		return array ('year' => date ('Y', $time), 'month' => date ('n', $time), 'day' => date ('j', $time));
	}
}

	


?>