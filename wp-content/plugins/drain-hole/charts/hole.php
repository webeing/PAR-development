<?php

include (dirname (__FILE__).'/../../../../wp-config.php');
include (dirname (__FILE__)."/../lib/charts/charts.php");

$csv = false;
if (isset ($_GET['csv']))
	$csv = true;
	
if (current_user_can ('administrator'))
{
	$chart = array ();
	
	if ($_GET['type'] == 'percent')
	{
		$files = DH_Version::get_totals_by_hole (intval ($_GET['hole']));
	
		if (count ($files) > 0)
		{
			// Calculate total
			$total = $largest = 0;
			foreach ($files AS $file)
			{
				$total += $file->hits;
				$largest = max ($largest, $file->hits);
			}
			
			$merged = array ();	
			foreach ($files AS $pos => $file)
			{
				// Remove any files that have no hits
				if ($file->hits == 0)
					unset ($files[$pos]);
				else if ($file->hits / $total <= 0.03)
				{
					$merged[] = $file;
					$total -= $file->hits;
					unset ($files[$pos]);
				}
			}
			
			// Now calculate the merged files total
			$merged_total = 0;
			if (count ($merged) > 0)
			{
				foreach ($merged AS $file)
					$merged_total += $file->hits;
			}
			
			$axis = array ("");
			$data = array ("Percent");
			
			foreach ($files AS $file)
			{
				$axis[] = substr ($file->name (), 0, 20).(strlen ($file->name ()) > 20 ? '...' : '');
				$data[] = $file->hits;
			}

			// Add on merged files, if any
			if ($merged_total > 0)
			{
				$axis[] = 'Other files';
				$data[] = $merged_total;
				$total += $merged_total;
			}
			
			// Add % value to text
			foreach ($axis AS $pos => $label)
			{
				if ($_GET['display'] == 'bar')
					$data[$pos] = number_format ((($data[$pos] / $total) * 100.0), 0);
				else
					$axis[$pos] .= ' ('.number_format ((($data[$pos] / $total) * 100.0), 0).'%)';
			}
			
			// Generate a pie chart that shows the percentage each file in a hole has
			if ($_GET['display'] == 'pie')
			{
				$chart['chart_type'] = "pie";
				$chart['chart_value'] = array ('as_percentage' => true, 'bold' => false, 'position' => 'outside', 'size' => 14);
				$chart['legend_label']  = array ('size' => 14);
				$chart['legend_rect'] = array ('x' => 10, 'y' => 50);
			}
			else
			{
				$chart['chart_type'] = 'bar';
				$chart['chart_value'] = array ('bold' => false, 'size' => 14, 'position' => 'right', 'suffix' => '%');
				$chart['axis_value'] = array ('min' => 0, 'max' => ($largest / $total) * 100.0, 'size' => 14, 'suffix' => '%');
				$chart['legend_rect'] = array ('x' => 1000, 'y' => 1000);
				$chart['axis_category'] = array ('size' => 14);
				$chart['chart_pref'] = array ('reverse' => true);
				$chart['chart_rect'] = array ('x' => 180, 'y' => 40, 'width' => 580, 'height' => 310);
				$chart['chart_border'] = array ('left_thickness' => 2, 'bottom_thickness' => 2);
				$chart['axis_ticks'] = array ('value_ticks' => true);
				$chart['chart_grid_h'] = array ('thickness' => 0);
			}

			$chart['chart_data'] = array ($axis, $data);
			$chart['draw'] = array (array ('type' => 'text', 'x' => 0, 'y' => 5, 'h_align' => 'center', 'v_align' => 'center', 'width' => 800, 'height' => 40, 'text' => 'Downloads shown as overall percentage'));
		}
	}
	else if ($_GET['type'] == 'time')
	{
		global $wp_locale;

		$axis = array ("");
		$data = array ("Downloads");

		if ($_GET['display'] == 'hourly')
		{
			$items = DH_Version::get_hits_per_day (intval ($_GET['hole']), intval ($_GET['year']), intval ($_GET['month']), intval ($_GET['day']));

			if (!empty ($items))
			{
				foreach ($items AS $year => $months)
				{
					foreach ($months AS $month => $days)
					{
						$first = 0;
						foreach ($days AS $day => $hours)
						{
							foreach ($hours AS $hour => $hits)
							{
								$str = sprintf ('%02d:00', $hour);
								if ($first != $day)
								{
									$str .= "\r".sprintf ('%s %s', $wp_locale->get_month_abbrev ($wp_locale->get_month ($month)), $day);
									$first = $day;
								}
								
								$axis[] = $str;
								$data[] = $hits;
							}
						}
					}
				}

				// Work out the max and min values
				$max = max (array_slice ($data, 1));
				$min = min (array_slice ($data, 1));
				$max = $max + (10 - ($max % 10));
				$min = $min - ($min % 10);

				$chart['chart_data'] = array ($axis, $data);
				$chart['axis_value'] = array ('min' => $min, 'max' => $max, 'size' => 14, 'show_min' => true, 'steps' => 10);
				$chart['axis_category'] = array ('size' => 14, 'orientation' => 'horizontal', 'skip' => 4);
				$chart['legend_label'] = array ('alpha' => 0, 'size' => 0);
				$chart['chart_type'] = 'line';
				
				$text = sprintf ('Hourly downloads over time (%1s %2d, %3d)', $wp_locale->get_month (intval ($_GET['month'])), intval ($_GET['day']), intval ($_GET['year']));
			}
		}
		else if ($_GET['display'] == 'daily')
		{
			$items = DH_Version::get_hits_per_month (intval ($_GET['hole']), intval ($_GET['year']), intval ($_GET['month']));
			if (count ($items) > 0)
			{
				foreach ($items AS $month => $days)
				{
					foreach ($days AS $day => $hits)
					{
						$axis[] = sprintf ('%1s %2s', $wp_locale->get_month_abbrev ($wp_locale->get_month ($month)), $day);
						$data[] = $hits;
					}
				}
		
				// Work out the max and min values
				$max = max (array_slice ($data, 1));
				$min = min (array_slice ($data, 1));
				$max = $max + (10 - ($max % 10));
				$min = $min - ($min % 10);
		
				$chart['chart_data'] = array ($axis, $data);
				$chart['axis_value'] = array ('min' => 0, 'max' => $max, 'size' => 14, 'show_min' => true, 'steps' => 10);
				$chart['axis_category'] = array ('size' => 14, 'orientation' => 'horizontal', 'skip' => count ($data) > 5 ? 2 : 0);
				
				if (count ($data) > 4)
					$chart['chart_type'] = 'line';
				else
					$chart['chart_type'] = 'column';
				
				$text = sprintf (__ ('Daily downloads over time (%1s %2s)', 'drain-hole'), $wp_locale->get_month (intval ($_GET['month'])), intval ($_GET['year']));
			}
		}
		else if ($_GET['display'] == 'monthly')
		{
			$items = DH_Version::get_hits_per_year (intval ($_GET['hole']), intval ($_GET['year']));
			if (count ($items) > 0)
			{
				foreach ($items AS $month => $hits)
				{
					$axis[] = sprintf ('%1s %2s', $wp_locale->get_month_abbrev ($wp_locale->get_month ($month)), $day);
					$data[] = $hits;
				}
		
				// Work out the max and min values
				$max = max (array_slice ($data, 1));
				$min = min (array_slice ($data, 1));
				$max = $max + (10 - ($max % 10));
				$min = $min - ($min % 10);
		
				$chart['chart_data'] = array ($axis, $data);
				$chart['axis_value'] = array ('min' => 0, 'max' => $max, 'size' => 14, 'show_min' => true, 'steps' => 10);
				$chart['axis_category'] = array ('size' => 14, 'orientation' => 'horizontal', 'skip' => count ($data) > 5 ? 2 : 0);
				
				if (count ($data) > 4)
					$chart['chart_type'] = 'line';
				else
					$chart['chart_type'] = 'column';
				
				$text = sprintf (__ ('Monthly downloads over time (%2s)', 'drain-hole'), intval ($_GET['year']));
			}
			
		}

		$chart['chart_border'] = array ('left_thickness' => 2, 'bottom_thickness' => 2);
		$chart['legend_label'] = array ('alpha' => 0, 'size' => 0);
		$chart['axis_ticks'] = array ('value_ticks' => true);
		$chart['chart_grid_h'] = array ('thickness' => 1);
		$chart['chart_rect'] = array ('x' => 50, 'y' => 40, 'width' => 720, 'height' => 310);
		$chart['draw'] = array (array ('type' => 'text', 'x' => 0, 'y' => 5, 'h_align' => 'center', 'v_align' => 'center', 'width' => 800, 'height' => 40, 'text' => $text));
	}
	
	if ($csv)
	{
		header ("Content-Type: application/vnd.ms-excel");
		header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header ('Content-Disposition: attachment; filename="'.'drainhole.csv"');
		
		foreach ($chart['chart_data'] AS $line)
		{
			$items = array ();
			
			foreach ($line AS $data)
				$items[] = $drainhole->csv_escape (trim ($data));
			echo implode (',', $items)."\r\n";
		}
	}
	else if (count ($chart) > 0)
	 	SendChartData ($chart);
}
?>