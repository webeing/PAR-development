<?php

/*
Plugin Name: AHP Sitewide Recent Posts for WordPress MU
Plugin URI: http://www.metablog.us/blogging/ahp-recent-posts-plugin-for-wordpress-mu/
Description: Retrieves a highly customizable list of the most recent sitewide posts in a WordPress MU installation. Automatically excludes blog ID 1 (main blog), and post ID 1 (first "Hello World" posts of new blogs). 
Author: Aziz Poonawalla
Author URI: http://metablog.us

FUNCTION ARGUMENTS

$how_many: how many recent posts are being displayed
$how_long: time frame to choose recent posts from (in days)
$optmask: bitmask for various display options (default: 255)
	DISPLAY OPTIONS BITMASK
	1;  // gravatar
	2;  // date
	4;  // author name
	8;  // comment count
	16; // blog name	
	32; // post name	
	64; // post excerpt
	128; // excerpt capitalization
$exc_size: size of excerpt in words (default: 30)
$begin_wrap: start html code (default: <li class="ahp_recent-posts">)
$end_wrap: end html code to adapt to different themes (default: </li>)

SAMPLE FUNCTION CALL

to show 5 posts from recent 30 days: <?php ahp_recent_posts(5, 30);  ?>

SAMPLE CSS

gravatar styling:  img.avatar-24 { float: left; padding: 0px; border: none; margin: 4px; clear: left; }
LI styling: li.ahp-recent-posts { list-style-type: none ;}
excerpt styling: .ahp-excerpt { margin-top: 2px } 

TODO: 

- link gravatar icon to Extended Profile in buddypress, if installed
- widgetize
- show more than one post per blog

CHANGELOG

Version 0.6
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- added comment count display option
- added enable/disable excerpt capitalization
- consolidated title/name of post display options into bitmask
- reduced number of required arguments
- added class name ahp-recent-posts to default start html LI tags
- added class ahp-excerpt to excerpt block

Version 0.5
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- changed gravatar link to point to all posts by author on main blog (ID = 1). 
- added date string, author output
- implemented bitmask to control gravatar, date, author output
- consolidated numwords argument with display argument

Version 0.4.1
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- added gravatar support, icon size 24px
- gravatar can be styled by img.avatar-24 in your css file
- gravatar image links to author's blog
- capitalization of first five words of the excerpt

Version 0.4.0 
Update Author: Aziz Poonawalla
Update Author URI: http://metablog.us
- added exclusions for first blog, first post, enabled post excerpt

Version: 0.32
Update Author: G. Morehouse
Update Author URI: http://wiki.evernex.com/index.php?title=Wordpress_MU_sitewide_recent_posts_plugin

Version: 0.31
Update Author: Sven Laqua
Update Author URI: http://www.sl-works.de/

Version: 0.3
Author: Ron Rennick
Author URI: http://atypicalhomeschool.net/
*/

function ahp_recent_posts($how_many, $how_long, $optmask = 255, $exc_size = 30, $begin_wrap = '<li class="ahp_recent-posts">', $end_wrap = '</li>') {
	global $wpdb;
	$counter = 0;
	
	// EDIT THESE TO CUSTOMIZE THE OUTPUT
	$debug = 0;
	$blog_prefix = "@";
	$post_prefix = " | ";
	$auth_prefix = ' by ';
	$com_prefix = ' | ';
	$date_format = 'D M jS, Y';
	$grav_size = 24;
	
	// DISPLAY OPTIONS BITMASK
	$grav_flag = 1;  // gravatar
	$date_flag = 2;  // date
	$auth_flag = 4;  // author name
	$com_flag  = 8;  // comment count
	$name_flag = 16; // blog name	
	$post_flag = 32; // post name	
	$exc_flag  = 64; // post excerpt
	$cap_flag  = 128; // excerpt capitalization
	
	// set the various option flags
	if ($optmask & $grav_flag) { $use_grav = 1; } else { $use_grav = 0; } 
	if ($optmask & $date_flag) { $use_date = 1; } else { $use_date = 0; } 
	if ($optmask & $auth_flag) { $use_auth = 1; } else { $use_auth = 0; } 
	if ($optmask & $com_flag)  { $use_com  = 1; } else { $use_com = 0;  } 
	if ($optmask & $name_flag) { $use_name = 1; } else { $use_name = 0; } 
	if ($optmask & $post_flag) { $use_post = 1; } else { $use_post = 0; } 
	if ($optmask & $exc_flag)  { $use_exc  = 1; } else { $use_exc = 0;  } 
	if ($optmask & $cap_flag)  { $use_cap  = 1; } else { $use_cap = 0;  } 
	
	// debug block
	if ($debug) { 
		echo '<hr>'.'opt = '.$optmask.': grav = '.$use_grav.', date = '.$use_date
		.', auth = '.$use_auth.', use_com = '.$use_com.', use_name = '.$use_name
		.', use_post = '.$use_post.', use_exc = '.$use_exc.', use_cap = '.$use_cap;
	}
	
	// get a list of blogs in order of most recent update. show only public and nonarchived/spam/mature/deleted
	$blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE
		public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' AND blog_id != '1' AND
		last_updated >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
		ORDER BY last_updated DESC");
		
	if ($blogs) {
		
		foreach ($blogs as $blog) {

			// we need _posts, _comments, and _options tables for this to work
			$blogOptionsTable = "wp_".$blog."_options";
		    $blogPostsTable = "wp_".$blog."_posts";
		    $blogCommentsTable = "wp_".$blog."_comments";
							
			// debug block
			if ($debug) { 
				echo '<hr>processing blog '.$blog.' = <a href="'.
				$options[0]->option_value.'">'.$options[1]->option_value.'</a> <br>';
			}

			// fetch the ID, post title, post content, post date, and user's email for the latest post
			$thispost = $wpdb->get_results("SELECT $blogPostsTable.ID, $blogPostsTable.post_title, 
				$blogPostsTable.post_content, $blogPostsTable.post_date, wp_users.display_name, 
				wp_users.user_email, wp_users.user_login
				FROM $blogPostsTable, wp_users
				WHERE wp_users.ID = $blogPostsTable.post_author
				AND post_status = 'publish' AND post_type = 'post' 
				AND post_date >= DATE_SUB(CURRENT_DATE(), INTERVAL $how_long DAY)
				AND $blogPostsTable.id > 1 
				ORDER BY $blogPostsTable.id DESC limit 0,1");

			// if it is found put it to the output
			if($thispost) {
			
				// debug block
				if ($debugflag) {
					echo 'processing thispost ID = '.$thispost[0]->ID.'<br>';
					echo 'post_title = '.$thispost[0]->post_title.'<br>';
					//echo 'post_content = '.$thispost[0]->post_content.'<br>';
					echo 'post_date = '.$thispost[0]->post_date.'<br>';
					echo 'display_name = '.$thispost[0]->display_name.'<br>';
					echo 'user_email = '.$thispost[0]->user_email.'<br>';
					echo 'user_login = '.$thispost[0]->user_login.'<br>';
				}
			
				// get post ID
				$thispostID = $thispost[0]->ID;
				
				// get permalink by ID.  check wp-includes/wpmu-functions.php
				$thispermalink = get_blog_permalink($blog, $thispostID);
				
				// get blog name,  URL
				if ($use_name) { 

					$options = $wpdb->get_results("SELECT option_value FROM
					$blogOptionsTable WHERE option_name IN ('siteurl','blogname') 
					ORDER BY option_name DESC");

					$blog_link = $options[0]->option_value;
					$blog_name = $options[1]->option_value;
					$this_blogname = $blog_prefix.'<a href="'.$blog_link.'">'.$blog_name.'</a>';

				} else { $this_blogname = ''; }

				// get comments
				if ($use_com) {
					
					// sql query for all comments on the current post
					$thesecomments = $wpdb->get_results("SELECT comment_ID
					FROM $blogCommentsTable
					WHERE comment_post_ID = $thispostID");
					
					// count total number of comments
					$num_comments = sizeof($thesecomments);
					
					// pretty text
					if ($num_comments == 0) { $thiscomment = $com_prefix.'no comments'; }
					elseif ($num_comments == 1) { $thiscomment = $com_prefix.'one comment'; }
					else { $thiscomment = $com_prefix.$num_comments.' comments'; } 
					
				} else { $thiscomment = ''; }
									
				// get author
				if ($use_auth) { 
					$thisauthor = $auth_prefix.$thispost[0]->display_name;
				} else { $thisauthor = ''; } 
				
				// get author's posts link 
				$thisuser = $thispost[0]->user_login;
				$thisuser_url = $thisbloglink."author/".$thisuser;
				
				// get gravatar
				if ($use_grav) { 
					$grav_img = get_avatar( $thispost[0]->user_email , $grav_size ); 
					$thisgravatar = '<a href="'.$thisuser_url.'">'.$grav_img.'</a>';
				} else { $thisgravatar = ''; }
				
				// get post date (nicely formatted)
				if ($use_date) { 
					$thisdate = date($date_format, strtotime( $thispost[0]->post_date )) ; 					
				} else { $thisdate = ''; }
				
				// get post name 
				if ($use_post) { 
					$this_postname = $post_prefix.'<a href="'.$thispermalink.'">'.$thispost[0]->post_title.'</a><br>';
				} else { $this_postname = ''; }
				
				if ($use_exc) { 

					if ($exc_size == 0) { $thisexcerpt = ''; }
					else { 
						// get post content and truncate to (numwords) words
						$thiscontent = strip_tags( $thispost[0]->post_content );
						preg_match("/([\S]+\s*){0,$exc_size}/", $thiscontent, $regs);
						
						if ($use_cap) {
							// build the excerpt html block, capitalize first five words
							$trunc_content = explode( ' ', trim($regs[0]) , 6 );
							$exc_str = strtoupper($trunc_content[0]).' '
							.strtoupper($trunc_content[1]).' '
							.strtoupper($trunc_content[2]).' '
							.strtoupper($trunc_content[3]).' '
							.strtoupper($trunc_content[4]).' '
							.$trunc_content[5].'... ';
						} else { $exc_str = trim($regs[0]); } 
							
						$thisexcerpt = '<span style="ahp-excerpt">'.$exc_str
						.'<a href="'.$thispermalink.'">'					
						.'&raquo;&raquo;MORE'.'</a></span>';
					}
				} else { $thisexcerpt = ''; }
												
					echo $begin_wrap
					.$thisgravatar
					.'<small>'.$thisdate.$thisauthor.$thiscomment.'</small><br>'
					.$this_blogname
					.$this_postname
					.'<small>'.$thisexcerpt.'</small>'
					.$end_wrap;
					
					$counter++;
			}
						
			// don't go over the limit
			if($counter >= $how_many) { 
				break; 
			}
		}
	}
	else { 
		//echo "no recent posts meet the criteria...<br>";
	}
}
?>