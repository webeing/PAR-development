<?
/*
 Plugin Name: List-All-Recent-Post-By-Category-Filter
 Plugin URI:
 Description: Creates a list of most recent post given a category across
 all active blogs
 Author: Daniel Gómez Didier
 Version: 0.1 beta.
 License: GNU General Public License version 3.
 ------------------------------------------------------------------------
 Copyright 2009  Daniel Gómez Didier  (email : dilaang@gmail.com)
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA
 02110-1301  USA
 ------------------------------------------------------------------------
 */

//Return an array whith the title and the permanent link, from the newest to the oldest post
//
function getAllPostByCategoryName($catName, $limit)
{
	global $wpdb;
	global $table_prefix;
	#$postFromAllPost = 1;
    $postFromAllPost = "";
    $blog_list = $wpdb->get_results("SELECT blog_id FROM wp_blogs", ARRAY_A);
    foreach ($blog_list as $ab)
    {
        $bid = $ab["blog_id"];
		
        if ($bid != 1)
        {
            $tempSQL = " SELECT post_title, post_date_gmt ,guid FROM `wp_".$bid."_posts` WHERE ID in
            (SELECT object_id FROM `wp_".$bid."_term_relationships` WHERE term_taxonomy_id =
            (SELECT term_taxonomy_id FROM `wp_".$bid."_term_taxonomy` WHERE term_id =
            (SELECT term_id FROM `wp_".$bid."_terms` WHERE name='".$catName."'))) ";
            if (strlen($newsFromAllPost) > 0)
            {
                $postFromAllPost .= " UNION ".$tempSQL;
            }
            $postFromAllPost .= $tempSQL;
        }
    }
    #$postFromAllPost .= $sqlGetPosts .= " ORDER BY post_date_gmt DESC LIMIT 0,$limit";

    $postList = $wpdb->get_results($postFromAllPost, ARRAY_A);
	return $postList;
}

//print the title of the post taht match the category name, as a link to the complete entry.
function echoAllPostByCategoryName($catName, $status='public', $limit, $tmp_beginWrap, $tmp_endWrap, $message = 'no entry for this item')
{	
	global $post;
	global $wpdb;
	global $table_prefix;
	
	global $endWrap;
	global $beginWrap;
	
	$post = null;
	$endWrap = $tmp_endWrap;
	$beginWrap = $tmp_beginWrap;
	$orderedby = 'regitered';
	
	if ($limit == 0) $limit = "";
	else $limit = "LIMIT 0,". $limit;
	
    $postFromAllPost = "";
    
	switch ($status){ 
    	case "public": {
    		$options = "public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted ='0' ";
    		#echo "SELECT blog_id, last_updated FROM " . $wpdb->blogs. " WHERE " . $options;
    		break;
    	}
    	case "archived" : {
    		$options = "public = '1' AND archived = '1' AND mature = '0' AND spam = '0' AND deleted ='0' ";
    		 #echo "SELECT blog_id, last_updated FROM " . $wpdb->blogs. " WHERE " . $options;
    		 break;
    	}
    }
    
    //echo "SELECT blog_id FROM wp_blogs WHERE " . $options;
    $blog_list = $wpdb->get_results("SELECT blog_id FROM wp_blogs WHERE " . $options . " ORDER BY '" . $orderedby . "' DESC" , ARRAY_A);
	//$blog_list = $wpdb->get_results("SELECT blog_id FROM wp_blogs", ARRAY_A);
    foreach ($blog_list as $ab)
    {
        $bid = $ab["blog_id"];
		//echo $bid . '<br />';
		//echo $catName . '<br />';
        if ($bid != 1)
        {
            $tempSQL = " SELECT option_value AS blogname, post_title, post_date_gmt ,guid 
            FROM `wp_".$bid."_posts`,`wp_".$bid."_options` 
            WHERE post_status = 'publish' 
            AND post_type = 'post'
            AND option_name = 'blogname'
            AND ID IN
            (SELECT object_id FROM `wp_".$bid."_term_relationships` WHERE term_taxonomy_id IN
            (SELECT term_taxonomy_id FROM `wp_".$bid."_term_taxonomy` WHERE term_id IN
            (SELECT term_id FROM `wp_".$bid."_terms` WHERE name='".$catName."'))) ";
            if (strlen($postFromAllPost) > 0)
            {
                $postFromAllPost .= " UNION ".$tempSQL;
            }
            else $postFromAllPost .= $tempSQL;
        }
        
        //echo '<b>query :</b> ' . $postFromAllPost . '<br />';
    }
    
    #$postFromAllPost .= $sqlGetPosts .= " ORDER BY post_date_gmt DESC LIMIT 0,$limit";
    $postList = $wpdb->get_results($postFromAllPost . $limit , ARRAY_A);
	#var_dump($postList);
	if ($postList):
    foreach ($postList as $post)
    {
    	//echo $beginWrap."<a href=".$post["guid"].">".$post["post_title"]."</a>". '<br /><small>'.$post["blogname"] .'</small>'.$endWrap;
		echo $beginWrap."<a href=".$post["guid"].">".$post["blogname"]."</a>". ' <small>'. $post["post_title"] .'</small>'.$endWrap;
		//echo $beginWrap."<span>" .$post["blogname"]."</span>". '<br /><small>'. $post["post_title"] .'</small>'.$endWrap;
    }
    else: echo '<p>' . $message . '</p>';
    endif;
}

?>
