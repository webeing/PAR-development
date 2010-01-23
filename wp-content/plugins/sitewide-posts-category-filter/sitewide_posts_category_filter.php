<?
/*
 Plugin Name: SiteWide Posts Manager 
 Plugin URI:
 Description: Creates a list of most recent post given a category across  all active blogs
 Author: Daniel Gomez Didier, Corinti Enrico, Agata Cruciani, Webeing
 Version: 0.5 beta.
 License: GNU General Public License version 3.
 ------------------------------------------------------------------------
 Copyright 2009  Daniel Gomez Didier  (email : dilaang@gmail.com)
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

global $wp_version;

$exitMsg = "SiteWide Posts manager require Wordpress 2.8+";
if (version_compare($wp_version,"2.8","<")){
	exit($exitMsg);
}

if(!class_exists('SiteWidePostManager')):
class SiteWidePostsManager{
	
	private $pluginUrl;
	private $db_options = "SWPManager_options";
	
	/**
	 * Defaults Hardcoded
	 * ToDO: bring them on Plugin Settings page!!
	 */
	private $status='public'; 
	private $orderedby = 'regitered'; 
	private $order = "DESC";
	
	private $options;
	private $blogList;

	//function __construct($blogExclude = false, $sitewideLimit = 5, $status='public', $orderedby = 'regitered', $order = "DESC")
	function SiteWidePostsManager()
	{
		//Save plugin URL informations...
		
		$this->pluginUrl = WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__));
		#echo $this->pluginUrl;
		
		//Query for posts
		//$this->QueryBlogs($blogExclude, $sitewideLimit, $status, $orderedby, $order);
		$this->QueryBlogs();
	}
	
	function Install(){
		$this->get_swmp_options();
	}
	
	/**
	 * Query database for sitewide blogs informations
	 */
	//function QueryBlogs($blogExclude, $sitewideLimit = 5, $status='public', $orderedby = 'regitered', $order = "DESC"){
	function QueryBlogs()
	{
		global $post;
		global $wpdb;
		global $table_prefix;
		
		$status = $this->status;
		$orderedby = $this->orderedby;
		$order = $this->order;
		
		/*
		 * Blog to exclude option evaluated
		 */
		
		if ($blogExclude == false) $blogExclude="";
		else $blogExclude = " (NOT blog_id = $blogExclude) AND "; 
		
		/*
		 * Status option evalueted
		 */
		switch ($status){ 
	    	case "public": {
	    		$this->options = "public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted ='0' ";
	    		#echo "SELECT blog_id, last_updated FROM " . $wpdb->blogs. " WHERE " . $options;
	    		break;
	    	}
	    	case "archived" : {
	    		$this->options = "public = '1' AND archived = '1' AND mature = '0' AND spam = '0' AND deleted ='0' ";
	    		 #echo "SELECT blog_id, last_updated FROM " . $wpdb->blogs. " WHERE " . $options;
	    		 break;
	    	}
	    }
	    
	    /*
	     * OrderBy option evalueted
	     */
	    
	    $this->options .= " ORDER BY '" . $orderedby. "'";
	    
	    /*
	     * Order option evallueted
	     */
	    $this->options .= " " . $order;
	    
	    /*
		 * Limits option evalueted
		 */
		($sitewideLimit == 0) ? $sitewideLimit = "" :  $sitewideLimit = "LIMIT 0,". $sitewideLimit;
     	$this->options .= " " . $sitewideLimit;
		
     	/*
     	 * Query SiteWide Blogs
     	 */
     	$this->blog_list = $wpdb->get_results("SELECT blog_id FROM wp_blogs WHERE $blogExclude" . $this->options, ARRAY_A);
     	#echo "results: ";
     	#var_dump($this->blog_list);
     	#$this->blog_list = array_filter($this->blog_list, stripBlog);
     	#$this->blog_list = array_values($this->blog_list);
     	#var_dump($this->blog_list);
     	
	}
	
	function stripBlog($item){
		global $blogExclude;
		return ($item['blog_id'] == $blogExclude);	
     }	
	
	/**
	 * Get $singleLimit blogs' posts from all blogs by specified category name (NOR slug!)
	 */
	function getAllPostsByCategoryName($catName, $singleLimit = 1){
		global $post;
		global $wpdb;
		global $table_prefix;
		
		$post = null;
		
		$categories = explode(',',$catName);
		#var_dump($categories);
		$count = count($categories);
		if ($count>1):
			foreach ($categories as $category):
				$where .= "name='$category'"; 
				if ($count != 1) $where .= " OR ";
				$count --;
			endforeach;
		else:
			$where = "name='$categories[0]'";
		endif;
		
		/*
		 * Limits option evalueted
		 */
		($singleLimit == 0) ? $singleLimit = "" :  $singleLimit = "LIMIT 0,". $singleLimit;
						
	    foreach ($this->blog_list as $ab)
	    {
	        $bid = $ab["blog_id"];
			//echo $bid . '<br />';
			//echo $catName . '<br />';
	       
            $tempSQL = " (SELECT option_value AS blogname, post_content, post_title, post_date_gmt ,guid 
            FROM `wp_".$bid."_posts`,`wp_".$bid."_options` 
            WHERE post_status = 'publish' 
            AND post_type = 'post'
            AND option_name = 'blogname'
            AND ID IN
            (SELECT object_id FROM `wp_".$bid."_term_relationships` WHERE term_taxonomy_id IN
            (SELECT term_taxonomy_id FROM `wp_".$bid."_term_taxonomy` WHERE term_id IN
            (SELECT term_id FROM `wp_".$bid."_terms` WHERE $where" ."))) ".$singleLimit.")";
            //(SELECT term_id FROM `wp_".$bid."_terms` WHERE name='".$catName."'))) ".$singleLimit.")";
            if (strlen($postFromAllPost) > 0)
            {
                $postFromAllPost .= " UNION ".$tempSQL;
            }
            else $postFromAllPost .= $tempSQL;
	    }
	    #echo '<b>query :</b> ' . $postFromAllPost . '<br />';
	    #$postFromAllPost .= $sqlGetPosts .= " ORDER BY post_date_gmt DESC LIMIT 0,$limit";
	    $postList = $wpdb->get_results($postFromAllPost , ARRAY_A);
	    return $postList;
	}

	/**
	 * Output HTML structure for category based sitewide posts
	 */
	function AllPostsByCategoryName($catName, $status='public', $limit, $tmp_beginWrap, $tmp_endWrap, $message = 'no entry for this item'){
		global $endWrap;
		global $beginWrap;
		
		$endWrap = $tmp_endWrap;
		$beginWrap = $tmp_beginWrap;
		
		$this->getAllPostsByCategoryName($catName, $limit, $status);
		
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
	
	/***
	 * Time for Wordpress Plugin Options settings ...
	 */
	function get_swpm_options(){
		
		//defaults
		$options = array
		(
			'totalposts' => 5,
			'postsbycategory' => 1,
			'categories' => 'Featured',
			'blogtoexclude' => 1
		);
		
		$saved = get_option($this->db_options);
		
		if (!empty($saved))
		{
			foreach ($saved as $key => $option)
				$options[$key] = $option;
		}
		if ($saved != $options)
			update_option($this->db_option, $options);
		
		return $options;
		
	}

	function handle_options(){
		$options = $this->get_options();
		if (isset($_POST['submitted']))
		{
			check_admin_referer('swpcf_nonce');
			$options = array();
			$options['totalposts'] = $_POST['totalposts'];
			$options['postsbycategory'] = $_POST['postsbycategory'];
			$options['categories'] = $_POST['categories'];
			$options['blogtoexclude'] = $_POST['blogtoexclude'];

			update_options($this->db_options, $options);
			
			echo '<div class="updated fade"><p>Plugin Setting saved...</p></div>';
		}
		
		$totalposts = $options['totalposts'];
		$postsbycategory = $options['postsbycategory'];
		$categories = $options['categories'];
		$blogtoexclude = $options['blogtoexclude'];
		
		$action_url = $_SERVER['REQUEST_URI'];
		
		include 'swpcf_options.php';
	}
}
else :
	exit("Class already declared!");
endif;

//Create initial instance...
$blogsManager = new SiteWidePostsManager();
if(isset($blogsManager)){
	register_activation_hook(__FILE__, array(&$blogsManager,'Install'));
}
?>