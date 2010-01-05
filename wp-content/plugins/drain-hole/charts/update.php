<?php

include (dirname (__FILE__)."/../lib/charts/charts.php");

$chart [ 'chart_type' ]  = $_GET['display'];
$chart [ 'live_update' ] = array (   'url'    =>  str_replace ('update.php', $_GET['chart'].'.php', $_SERVER['REQUEST_URI'])); 

SendChartData ( $chart );
?>