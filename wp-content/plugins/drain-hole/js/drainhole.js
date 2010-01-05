function select_all ()
{
  jQuery('.item :checkbox').each (function ()
  {
    this.checked = (this.checked ? '' : 'checked');
  });

  return false;
}

function delete_items (type,nonce)
{
  var checked = jQuery('.item :checked');
  if (checked.length > 0)
  {
    if (confirm (wp_dh_areyousure))
    {
      var urltype = 'delete_stats';
      if (type == 'file')
        urltype = 'delete_files';
      else if (type == 'version')
        urltype = 'delete_versions';
      else if (type == 'hole')
        urltype = 'delete_holes';
        
      jQuery.post (wp_base + '?id=0&cmd=' + urltype + '&_ajax_nonce=' + nonce, checked.serialize (), function ()
        {
          checked.each (function ()
          {
            jQuery(this).parent ().parent ().remove ();
          });
        });
    }
  }
  else
    alert (wp_none_select);

  return false;
}

// Hole

function delete_hole (item)
{
	if (confirm (wp_dh_deletehole))
	{
	  jQuery('#loading').show ();
	  jQuery.post (wp_base + '?cmd=delete_hole&id=' + item, function ()
	    {
	      jQuery('#loading').hide ();
	      jQuery('#hole_' + item).remove ();
	    });
	}
	
	return false;
}

function edit_hole (object,item)
{
  jQuery('#dialog').html (jQuery('#loadingit').html ());
  jQuery('#dialog').dialog({ 
      modal: true,
      resizable: false,
      width: 600,
      height: 230,
      title: object.title,
      overlay: { opacity: 0.3, background: "black" }
  });
  
  jQuery.get (wp_base + '?id=' + item + '&cmd=edit_hole', {}, function (data, status)
    {
      jQuery('#dialog').html (data);
    });
  return false;
}


// File

function delete_file (item)
{
  if (confirm (wp_dh_deletefile))
	{
	  jQuery('#loading').show ();
	  jQuery.post (wp_base + '?cmd=delete_file&id=' + item, function ()
	    {
	      jQuery('#loading').hide ();
	      jQuery('#file_' + item).remove ();
	    });
	}
	
	return false;
}


function edit_file (item,object)
{
  jQuery('#dialog').html (jQuery('#loadingit').html ());
  jQuery('#dialog').dialog({ 
      modal: true,
      resizable: false,
      width: 600,
      height: 360,
      title: jQuery(object).attr('title'),
      overlay: { opacity: 0.3, background: "black" }
  });
  jQuery('.ui-dialog').show();
  
  jQuery.get (wp_base + '?id=' + item + '&cmd=edit_file', {}, function (data, status)
    {
      jQuery('#dialog').html (data);
    });
  return false;
}

// 

function delete_stat (item)
{
  jQuery('#loading').show ();
  jQuery.post (wp_base + '?cmd=delete_stat&id=' + item, function ()
    {
      jQuery('#loading').hide ();
      jQuery('#stat_' + item).remove ();
    });

	return false;
}


function print_chart ()
{
  document.charts.SetVariable ( 'print_chart', true );
  return false;
}


// Versions

function new_version (item,object)
{
  jQuery('#dialog').html (jQuery('#loadingit').html ());
  jQuery('#dialog').dialog({ 
      modal: true,
      resizable: false,
      width: 600,
      height: 280,
      title: object.title,
      overlay: { opacity: 0.3, background: "black" }
  });
  jQuery('.ui-dialog').show();

  jQuery.get (wp_base + '?id=' + item + '&cmd=new_version', {}, function (data, status)
    {
      jQuery('#dialog').html (data);
    });
  return false;
}

function edit_version (item,object)
{
  jQuery('#dialog').html (jQuery('#loadingit').html ());
  jQuery('#dialog').dialog({ 
      modal: true,
      resizable: false,
      width: 600,
      height: 220,
      title: object.title,
      overlay: { opacity: 0.3, background: "black" }
  });
  jQuery('.ui-dialog').show();

  jQuery.get (wp_base + '?id=' + item + '&cmd=edit_version', {}, function (data, status)
    {
      jQuery('#dialog').html (data);
    });
  return false;
}

function delete_version (item)
{
	if (confirm (wp_dh_deleteversion))
	{
	  jQuery('#loading').show ();
	  jQuery.post (wp_base + '?cmd=delete_version&id=' + item, function ()
	    {
	      jQuery('#loading').hide ();
	      jQuery('#version_' + item).remove ();
	    });
	}
	
	return false;
}



function update_dir_warning (text)
{
	if (text.beginsWith (wp_dh_base_home) == true && jQuery('#error_dir').css ('display') == 'none')
		jQuery('#error_dir').show ();
	else if (text.beginsWith (wp_dh_base_home) == false && jQuery('#error_dir').css ('display') != 'none')
		jQuery('#error_dir').hide ();
}

function dirKey (event)
{
	var element = '/' + event.target.value.replace (/^\/*/, '').replace (/\/*$/, '') + '/';
	
	update_dir_warning (element);
		
	jQuery('#base_dir').html (element.replace(/<\/?[^>]+>/gi, ''));
}

String.prototype.beginsWith = function(t, i)
{
  if (i==false)
    return (t == this.substring(0, t.length));
  else
    return (t.toLowerCase() == this.substring(0, t.length).toLowerCase());
}


function update_url_warning (text)
{
	if (text.beginsWith (wp_dh_home_url) == false && jQuery('#error_url').css ('display') == 'none')
		jQuery('#error_url').show ();
	else if (text.beginsWith (wp_dh_home_url) == true && jQuery('#error_url').css ('display') != 'none')
		jQuery('#error_url').hide ();
}

function urlKey (event)
{
	var element = event.target.value.replace (/^\/*/, '').replace (/\/*$/, '');

	update_url_warning (element);
		
	jQuery('#base_url').html (escape (element).replace('%3A', ':'));
}
