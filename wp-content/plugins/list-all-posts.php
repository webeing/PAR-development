<?php
/*
Plugin Name: List-All-Posts
Plugin URI: http://www.wpmudev.org/project/list-all-Posts
Description: Creates a list of most recent posts
Author: Andrew Billits
Author URI: http://wpmudev.org
Version: 0.0.2
*/

global $word_limit;
global $title_begin_wrap;
global $title_end_wrap;
global $begin_wrap;
global $end_wrap;
global $table_prefix;
global $read_more;
global $show_author;
global $show_blog;

function list_all_posts_summary($content, $limit){

$content = explode(' ',$content);
for($i=0; $i<$limit; $i++){
$summary[$i] = $content[$i];
}
$summary = implode(' ', $summary).'..';

return $summary;
}


function echoArrayPostList($arrayName) {
    global $wpdb;
	global $table_prefix;
    global $word_limit;
    global $title_begin_wrap;
    global $title_end_wrap;
    global $begin_wrap;
    global $end_wrap;
    global $read_more;
    global $show_author;
    global $show_blog;
    
    $intArrayCount = 0;
    $bid = '';
    foreach ($arrayName as $arrayElement) {
        if (count($arrayElement) > 1) {
            echoArrayPostList($arrayElement);
        } else {
            $intArrayCount = $intArrayCount + 1;
            if ($intArrayCount == 1) {
                $bid = $arrayElement;

				$tmp_post_content = $wpdb->get_var("SELECT post_content FROM " . "wp_" . $bid . "_posts" . " WHERE post_type = 'post' ORDER BY post_date_gmt DESC");
				$tmp_post_title = $wpdb->get_var("SELECT post_title FROM " . "wp_" . $bid . "_posts" . " WHERE post_type = 'post' ORDER BY post_date_gmt DESC");
				$tmp_post_author_id = $wpdb->get_var("SELECT post_author FROM " . "wp_" . $bid . "_posts" . " WHERE post_type = 'post' ORDER BY post_date_gmt DESC");
				$tmp_post_author = $wpdb->get_var("SELECT display_name FROM $wpdb->users WHERE ID = '" . $tmp_post_author_id . "'");
				$tmp_blog_name = $wpdb->get_var("SELECT option_value FROM " . "wp_" . $bid . "_options" . " WHERE option_name = 'blogname'");
				
				$tmp_domain = $wpdb->get_row("SELECT domain FROM $wpdb->blogs WHERE blog_id = '" . $bid . "'");
				$tmp_path = $wpdb->get_row("SELECT path FROM $wpdb->blogs WHERE blog_id = '" . $bid . "'");
				$tmp_siteurl = $wpdb->get_var("SELECT option_value FROM " . "wp_" . $bid . "_options" . " WHERE option_name = 'siteurl'");
				
				$info = ' - Posted ';
				if ($show_blog == "show"){
					$info = $info .'to ' . $tmp_blog_name . ' ';				
				}
				if ($show_author == "show"){
					$info = $info .'by ' . ucfirst($tmp_post_author);				
				}
				if ($show_author && $show_blog == 'hide'){
					$info = '';				
				}
				
				if ($bid == 1) {
					//skip if main blog
				} else {
					if( constant( 'VHOST' ) == 'yes' ) {
						echo $title_begin_wrap . "<a href='" . $tmp_siteurl . "'>" . $tmp_post_title . "</a>" . $info . $title_end_wrap;
						if ($word_limit == '') {
							echo $begin_wrap . strip_tags($tmp_post_content) . $end_wrap;					
						} else {
							if ($read_more == 'show') {
								echo $begin_wrap . strip_tags(list_all_posts_summary($tmp_post_content, $word_limit)) . " <a href='" . $tmp_siteurl . "'>(Read More)</a>" . $end_wrap;						
							} else {
								echo $begin_wrap . strip_tags(list_all_posts_summary($tmp_post_content, $word_limit)) . $end_wrap;
							}
						}
					} else {
						echo $title_begin_wrap . "<a href='" . $tmp_siteurl . "'>" . $tmp_post_title . "</a>" . $info . $title_end_wrap;
						if ($word_limit == '') {
							echo $begin_wrap . strip_tags($tmp_post_content) . $end_wrap;					
						} else {
							if ($read_more == 'show') {
								echo $begin_wrap . strip_tags(list_all_posts_summary($tmp_post_content, $word_limit)) . " <a href='" . $tmp_siteurl . "'>(Read More)</a>" . $end_wrap;						
							} else {
								echo $begin_wrap . strip_tags(list_all_posts_summary($tmp_post_content, $word_limit)) . $end_wrap;
							}
						}
					}
				}
            }
        }
    }
}

function list_all_wpmu_posts($tmp_limit, $tmp_word_limit, $tmp_begin_wrap, $tmp_end_wrap, $tmp_title_begin_wrap, $tmp_title_end_wrap, $tmp_read_more,$tmp_show_author,$tmp_show_blog) {
    global $wpdb;
    global $word_limit;
    global $title_begin_wrap;
    global $title_end_wrap;
    global $begin_wrap;
    global $end_wrap;
    global $read_more;
    global $show_author;
    global $show_blog;
    if ($tmp_show_blog == "show") {
        $show_blog = "show";
    } else {
		$show_blog = "hide";
    }
    if ($tmp_read_more == "show") {
        $read_more = "show";
    } else {
		$read_more = "hide";
    }
	if ($tmp_show_author == "show") {
        $show_author = "show";
    } else {
		$show_author = "hide";
    }		
    if ($tmp_word_limit == "") {
        //no limit
    } else {
        $word_limit = $tmp_word_limit;
    }
    if ($tmp_limit == "") {
        //no limit
    } else {
        $limit = "LIMIT " . $tmp_limit;
    }
    if (tmp_begin_wrap == "" || tmp_end_wrap == "" ) {
        $begin_wrap = "<p>";
        $end_wrap = "</p>";
    } else {
        $begin_wrap = $tmp_begin_wrap;
        $end_wrap = $tmp_end_wrap;
    }
    if (tmp_title_begin_wrap == "" || tmp_title_end_wrap == "" ) {
        $title_begin_wrap = "<p>";
        $title_end_wrap = "</p>";
    } else {
        $title_begin_wrap = $tmp_title_begin_wrap;
        $title_end_wrap = $tmp_title_end_wrap;
    }
    if ($tmp_order == "") {
        $order = "ORDER BY  last_updated DESC";
    } else {
        if ($tmp_order == "updated") {
            $order = "ORDER BY  last_updated DESC";
        }
        if ($tmp_order == "first_created") {
            $order = "ORDER BY  blog_id ASC";
        }
        if ($tmp_order == "last_created") {
            $order = "ORDER BY  blog_id DESC";
        }
    }
    $blog_list = $wpdb->get_results( "SELECT blog_id, last_updated FROM " . $wpdb->blogs. " WHERE public = '1' " . $order . " " . $limit . "", ARRAY_A );
    $check_blogs = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->blogs . "");
    if ($check_blogs == 0 || $check_blogs == 1 ){ // we don't want to display the admin blog so we return this even if there is one blog
        echo "<p>This are currently no active blogs</p>";
    } else {
        echoArrayPostList($blog_list);
    }
}
?>