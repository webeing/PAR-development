<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: Related Posts
Version: 0.1
Plugin URI: http://www.rene-ade.de/inhalte/wordpress-plugin-relatedposts.html
Description: This wordpress plugin provides tagcloud that shows the related posts of a post, and can replace a keyword within a post to a list of related posts.
Author: Ren&eacute; Ade
Author URI: http://www.rene-ade.de
Min WP Version: 2.3
*/
//-----------------------------------------------------------------------------
?>
<?php

/*
PUBLIC FUNCTIONS:
  rp_related_posts( $args ) displays related posts of the current post
ARGS:
  'limit' => 5, // limit number of related posts to display
  'title' => '',  // the title
  'beforeposts' => '', 'afterposts' => '', // text before and after the list 
  'eachpost' => '<li><a href="%permalink%">%title%</a></li>', // for each related post
  'noposts' => '' // can be a string to display if there are no related posts
*/

//-----------------------------------------------------------------------------

// get related posts
function rp_get_related_posts( $post, $limit ) {
  global $wpdb; // wordpress database access
  
  // limit has to be a number
  $limit = (int)$limit;
  
  // get tags of the post
  $tags = wp_get_post_tags( $post->ID );
  if( is_wp_error($tags) )
    return false; // error
  if( count($tags)<=0 ) // we cannot get related posts without tags
    return array(); // no related posts

  // get term ids
  $termids = array();
  foreach( $tags as $tag ) {
    $termids[ $tag->term_id ] = $tag->term_id;
  }
  if( count($termids)<=0 ) // we cannot get related posts without the termids
    return array(); // no related posts
  
  // the query to get the related posts
  $query = "SELECT DISTINCT $wpdb->posts.*, COUNT( tr.object_id) AS cnt " // get posts and count
          ."FROM $wpdb->term_taxonomy tt, $wpdb->term_relationships tr, $wpdb->posts "
          ."WHERE 1 "
            ."AND tt.taxonomy = 'post_tag' " // search for tags
            ."AND tt.term_taxonomy_id = tr.term_taxonomy_id " // get relations
            ."AND tr.object_id = $wpdb->posts.ID " // get posts
            ."AND tt.term_id IN( ".implode(',',$termids)." ) " // only with the same tags
            ."AND $wpdb->posts.ID != $post->ID " // only other posts, not the post selfe
            ."AND $wpdb->posts.post_status = 'publish' " // only published posts
          ."GROUP BY tr.object_id " // group by relation
          ."ORDER BY cnt DESC, $wpdb->posts.post_date_gmt DESC " // order by count best matches first, if same order by date
          ."LIMIT $limit "; // get only the top x
  
  // run the query and return the result
  return $wpdb->get_results( $query );
}

//-----------------------------------------------------------------------------

// replace placeholders
function rp_replace_placeholders( $post, $string ) {

  // replace placeholders
  $string = str_replace( '%title%',
    get_the_title($post->ID), $string );
  $string = str_replace( '%permalink%',
    get_permalink($post->ID), $string );

  // return 
  return $string;
}

//-----------------------------------------------------------------------------

// get related posts of a post as string
function rp_getstring_related_posts( $post, $args ) {
 
  // args
  $defaults = array(
    'limit' => 3, // limit number of related posts to display
    'title' => '',  // the title
    'beforeposts' => '', 'afterposts' => '', // text before and after the list 
    'eachpost' => '<li><a href="%permalink%">%title%</a></li>', // for each related post
    'noposts' => '' // can be a string to display if there are no related posts
  );
  $args = wp_parse_args( $args, $defaults );
  
  // no posts string
  $noposts = '';
  if( strlen($args['noposts'])>0 ) {
    $noposts = rp_replace_placeholders( $post, $args['title'] )
              .rp_replace_placeholders( $post, $args['noposts'] );
  }
  
  // get related posts
  $relatedposts = rp_get_related_posts( $post, $args['limit'] );
  if( is_wp_error($relatedposts) || !is_array($relatedposts) )
    return $noposts;

  // print only if there are related posts
  if( count($relatedposts)<=0 )  
    return $noposts;
    
  // the string
  $string = '';
	// print title and before
  $string.= rp_replace_placeholders( $post, $args['title'] );
  $string.= rp_replace_placeholders( $post, $args['beforeposts'] );
  // print related posts
  foreach( $relatedposts as $relatedpost ) {
    $string.= rp_replace_placeholders( $relatedpost, $args['eachpost'] );
  }
  // print after
  $string.= rp_replace_placeholders( $post, $args['afterposts'] );

  // return string
  return $string;  
}
// output related posts of post
function rp_print_related_posts( $post, $args ) {
  
  // display if there is something to display
  $string = rp_getstring_related_posts( $post, $args );
  if( strlen($string)>0 )
    echo $string;
  
  // output done
  return;
}

//-----------------------------------------------------------------------------

// output related posts for the current post
function rp_related_posts( $title, $args=null ) {
  global $post;
  
  if( !is_array($args) ) 
    $args = array();
  $args['title'] = $title;
  
  rp_print_related_posts( $post, $args );
}

//-----------------------------------------------------------------------------

// find the post content relatedposts placeholder
function rp_filter_the_content( $content ) {
  global $post; // the current post
 
  // replace placeholders
  $content = str_replace( '%RELATEDPOSTS%', rp_getstring_related_posts($post,array()), $content );
   
  return $content;
}

//-----------------------------------------------------------------------------

// the sidebar widget
function rp_widget( $args ) {
  global $post; // the current post
  
  // check if viewing a post
  if( !is_single() ) // show widget only on post page
    return;
  
  // comment // if you dont like this comment, you may remove it :-(
  echo '<!-- ';
  echo 'WordPress Plugin RelatedPosts by René Ade';
  echo ' - ';
  echo 'http://www.rene-ade.de/inhalte/wordpress-plugin-relatedposts.html';
  echo ' -->';

  // args
  extract( $args ); // extract args
  
  // options
  $options = get_option( 'rp_widget' ); // get options

  // get related posts string
  $relatedposts_string = rp_getstring_related_posts( $post, $options['args'] );
  if( strlen($relatedposts_string)<=0 )
    return; // nothing to display
    
  echo $before_widget;
  echo $before_title . $options['title'] . $after_title;
  echo $relatedposts_string;
  echo $after_widget;
  
  // output done
  return;  
}

function rp_widget_control() {

  // options
  $options = $newoptions = get_option('rp_widget'); // get options
  
  // set new options
  if( $_POST['rp-widget-submit'] ) {
    $newoptions['title'] = strip_tags( stripslashes($_POST['rp-widget-title']) );
    $newoptions['args']['beforeposts'] = stripslashes( $_POST['rp-widget-args-beforeposts'] );
    $newoptions['args']['afterposts'] = stripslashes( $_POST['rp-widget-args-afterposts'] );
    $newoptions['args']['eachpost'] = stripslashes( $_POST['rp-widget-args-eachpost'] );
    $newoptions['args']['noposts'] = stripslashes( $_POST['rp-widget-args-noposts'] );
    $newoptions['args']['limit'] = (int) $_POST['rp-widget-args-limit'];
  }
  
  // update options if needed
  if( $options != $newoptions ) {
    $options = $newoptions;
    update_option('rp_widget', $options);
  }
  
  // output  
  echo '<p>'._e('This widget only appears on post pages!').'</p>';
  echo '<p>'._e('Title');
    echo '<input type="text" style="width:300px" id="rp-widget-title" name="rp-widget-title" value="'.attribute_escape($options['title']).'" />'.'<br />';
  echo '</p>';
  echo '<p>'._e('Postlist');
    echo '<input type="text" style="width:300px" id="rp-widget-args-limit" name="rp-widget-args-limit" value="'.$options['args']['limit'].'" />'._e('Number of related posts to display').'<br />';
    echo '<input type="text" style="width:300px" id="rp-widget-args-beforeposts" name="rp-widget-args-beforeposts" value="'.attribute_escape($options['args']['beforeposts']).'" />'._e('Output before postlist').'<br />';
    echo '<input type="text" style="width:300px" id="rp-widget-args-afterposts" name="rp-widget-args-afterposts" value="'.attribute_escape($options['args']['afterposts']).'" />'._e('Output after postlist').'<br />';
    echo '<input type="text" style="width:300px" id="rp-widget-args-eachpost" name="rp-widget-args-eachpost" value="'.attribute_escape($options['args']['eachpost']).'" />'._e('Output for each related post').'<br />';
  echo '</p>';        
  echo '<p>'._e('Widget');
    echo '<input type="text" style="width:300px" id="rp-widget-args-noposts" name="rp-widget-args-noposts" value="'.attribute_escape($options['args']['noposts']).'" />'._e('Output if there are no related posts. Leave blank to hide the Widget if there are no posts to display.').'<br />';
  echo '</p>';
  echo '<input type="hidden" name="rp-widget-submit" id="rp-widget-submit" value="1" />';
}

//-----------------------------------------------------------------------------

// activate and deactivate plugin
function rp_activate() {

  // options, defaultvalues
  $options = array( 
    'widget' => array( 
      'title' => 'Related Posts',
      'args' => array(
        'limit' => 3,
        'beforeposts' => '<ul>', 'afterposts' => '</ul>',
        'eachpost' => '<li><a href="%permalink%">%title%</a></li>',
        'noposts' => ''
      )
    )
  );
  
  // register option
  add_option( 'rp_widget', $options['widget'] );
  
  // activeted
  return;
}
function rp_deactivate() {

  // unregister option
  delete_option('rp_widget'); 
  
  // deactivated
  return;
}

// initialization
function rp_init() {  

  // register widget
  $class['classname'] = 'rp_widget';
  wp_register_sidebar_widget('related_posts', __('Related Posts'), 'rp_widget', $class);
  wp_register_widget_control('related_posts', __('Related Posts'), 'rp_widget_control', 'width=300&height=500');
  
  // initialization done
  return;  
}

//-----------------------------------------------------------------------------

// actions
add_action( 'activate_'.plugin_basename(__FILE__),   'rp_activate' );
add_action( 'deactivate_'.plugin_basename(__FILE__), 'rp_deactivate' );
add_action( 'init', 'rp_init');

// filter text to replace relatedposts placeholder
add_filter( 'the_content',     'rp_filter_the_content', 5 );
add_filter( 'the_content_rss', 'rp_filter_the_content', 5 );
add_filter( 'the_excerpt',     'rp_filter_the_content', 5 );
add_filter( 'the_excerpt_rss', 'rp_filter_the_content', 5 );
add_filter( 'widget_text',     'rp_filter_the_content', 5 );

//-----------------------------------------------------------------------------

?>