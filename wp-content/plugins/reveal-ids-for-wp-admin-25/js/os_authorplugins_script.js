jQuery(document).ready(function() {
jQuery("#authorplugins-start").click(function() {
	jQuery("#authorplugins-wrap").hide();
	jQuery.ajax({ 
  		dataType: 'jsonp',
  		jsonp: 'jsonp_callback',
  		url: 'http://extend.schloebe.de/format/json/wordpress/',
  		success: function (j) {
			jQuery.each(j.plugins, function(i,plugin) {
				jQuery('#authorpluginsul').append( '<li><a href="http://extend.schloebe.de/incoming/' + plugin.uid + '/" target="_blank"><span class="post">' + plugin.os_script_title + '</span><span class="hidden"> - </span><cite>version ' + plugin.os_script_version + '</cite></a></li>' ).css("display", "none").fadeIn("slow");
    		});
		}
	});
});
});