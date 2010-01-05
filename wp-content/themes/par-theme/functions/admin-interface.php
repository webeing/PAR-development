<?php
// WooThemes Admin Interface: Setup, Pages, Machine

function handleError() {
    trigger_error('MY ERROR');
}

function woothemes_add_admin() {

    global $query_string;
    
    $options =  get_option('woo_template');      
    $themename =  get_option('woo_themename');      
    $shortname =  get_option('woo_shortname');      

    $page_advertising = false;
    $page_image_resizing = false;
    $page_nav = false;
    
    foreach  ($options as $value){
        if($value['page'] == 'advertising') $page_advertising = true; 
        if($value['page'] == 'image') $page_resizing = true;
        if($value['page'] == 'nav') $page_nav = true;
    }

    if ( $_GET['page'] == 'woothemes_home' ||
         $_GET['page'] == 'woothemes_advertising' ||
         $_GET['page'] == 'woothemes_uploader' ||
         $_GET['page'] == 'woothemes_image') 
    {
           
        if ( 'save' == $_REQUEST['action'] ) {
    
                foreach ($options as $value) {
                
                    
                    $live_page = $_GET['page'];
                    $current_page = 'woothemes_'.$value['page'];
                    
                    if($current_page == 'woothemes_'){$current_page = 'woothemes_home';}

                    if($current_page === $live_page){
                                         
                        if ( is_array($value['type'])) {
                        
                            foreach($value['type'] as $array){
                                if($array['type'] == 'text'){
                                $id = $array['id']; 
                                update_option( $id, $_REQUEST[ $id ]);
                                }
                            }                 
                        }
                     
                        elseif($value['type'] == 'checkbox'){
                             if(isset( $_REQUEST[ $value['id'] ])){update_option( $value['id'], $_REQUEST[ $value['id'] ] );} else { update_option( $value['id'] , 'false' ); }
                        } 
                        

                        elseif($value['type'] == 'select'){
                             if(isset( $_REQUEST[ $value['id'] ])){update_option( $value['id'], htmlentities($_REQUEST[ $value['id'] ] ));} else { delete_option( htmlentities($value['id'] )); }
                               
                        } 
                        elseif($value['type'] != 'multicheck'){
                             if(isset( $_REQUEST[ $value['id'] ])){update_option( $value['id'], $_REQUEST[ $value['id'] ] );} else { delete_option( $value['id'] ); }
                               
                        }
                        else  // Multicheck
                        {
                            foreach($value['options'] as $mc_key => $mc_value){
                                $up_opt = $value['id'].'_'.$mc_key;
                                 if(isset( $_REQUEST[ $up_opt ])){update_option( $up_opt, $_REQUEST[ $up_opt ] );}  else {update_option($up_opt, 'false' );}
                            }
                        }
                     }
                }

                 // Start Uploader
                
                foreach($options as $key => $value){

                $uploaddir = ABSPATH . "/wp-content/woo_uploads/" ;
                $loc = get_bloginfo('wpurl').'/wp-content/woo_uploads/';
                
                if(!is_dir($uploaddir)){
                    mkdir($uploaddir,0777);
                }
                $dir = @opendir($uploaddir);
                if ($dir == false){
                    $uploaddir = ABSPATH . "/wp-content/uploads/" ;
                    $loc = get_bloginfo('wpurl').'/wp-content/uploads/';
                }
                $files = array();
                    
                update_option('functions_post',$_POST);    
                if($value['type'] == 'upload' ){
                 $id = $value['id'];

                  if(isset($_FILES['attachement_'.$id]) && !empty($_FILES['attachement_'.$id]['name'])) 
                  {
                      if(!eregi('image/', $_FILES['attachement_'.$id]['type']))
                      {
                             echo 'The uploaded file is not an image please upload a valide file. Please go <a href="javascript:history.go(-1)">go back</a> and try again.';
                      } 
                      else 
                      {
                        while($file = readdir($dir)) { array_push($files,$file); } closedir($dir);
                        
                        $name = $_FILES['attachement_'.$id]['name'];
                        $file_name = substr($name,0,strpos($name,'.'));
                        $file_name = str_replace(' ','_',$file_name);
                         
                        $_FILES['attachement_'.$id]['name'] = $loc . ceil(count($files) + 1).'-'. $file_name .''.strrchr($name, '.');
                        $uploadfile = $uploaddir . basename($_FILES['attachement_'.$id]['name']);
                    
                         if(move_uploaded_file($_FILES['attachement_'.$id]['tmp_name'], $uploadfile)) {
                                  update_option($id,$_FILES['attachement_'.$id]['name']);
                                
                                  $new_file = $_FILES['attachement_'.$id]['name'];
                                  $old_files = get_option('woo_uploads');
                                  if($old_files){
                                    if(!is_array($old_files))
                                      {
                                      $all_files = array(get_option('woo_uploads'));
                                      }
                                      else
                                      {
                                      $all_files = $old_files;
                                      }
                                      array_unshift($all_files,$new_file);
                                  } else {
                                  $all_files = $new_file;
                                  }
                                  update_option('woo_uploads',$all_files);
                          }
                        }
                      }
                    }
                }
                
                //Create, Encrypt and Update the Saved Settings
                 
                    global $wpdb;
                    $query = "SELECT * FROM $wpdb->options WHERE option_name LIKE 'woo_%' AND NOT option_name = 'woo_template' AND NOT option_name = 'woo_custom_template' AND NOT option_name = 'woo_settings_encode'";
                    $results = $wpdb->get_results($query);
                    
                    //print_r($out);
                    $output = "<ul>";
                    foreach ($results as $result){
                            $output .= '<li><strong>' . $result->option_name . '</strong> - ' . $result->option_value . '</li>';
                    }
                    $output .= "</ul>";
                    $output = base64_encode($output);
                    update_option('woo_settings_encode',$output);
                //End

                $send = $_GET['page'];
                header("Location: admin.php?page=$send&saved=true");                                
            
            die;

        } else if ( 'reset' == $_REQUEST['action'] ) {
            global $wpdb;
            $query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'woo_%'";
            $wpdb->query($query);
            
            $send = $_GET['page'];
            header("Location: admin.php?page=$send&reset=true");
            die;
        }

    }

// Check all the Options, then if the no options are created for a ralitive sub-page... it's not created.

    if(function_exists(add_object_page))
    {
        add_object_page ('Page Title', $themename, 8,'woothemes_home', 'woothemes_page_gen', 'http://www.woothemes.com/favicon.png');
    }
    else
    {
        add_menu_page ('Page Title', $themename, 8,'woothemes_home', 'woothemes_page_gen', 'http://www.woothemes.com/favicon.png'); 
    }
         add_submenu_page('woothemes_home', $themename, 'Theme Options', 8, 'woothemes_home','woothemes_page_gen'); // Default
         if ($page_advertising){ add_submenu_page('woothemes_home', $themename, 'Advertising', 8, 'woothemes_advertising', 'woothemes_advertising'); }
         if ($page_nav){ add_submenu_page('woothemes_home', $themename, 'Navigation', 8, 'woothemes_nav', 'woothemes_nav'); }
         if ($page_resizing){  add_submenu_page('woothemes_home', $themename, 'Image Resizing', 8, 'woothemes_image', 'woothemes_image'); }
         add_submenu_page('woothemes_home', 'Available WooThemes', 'Buy Themes', 8, 'woothemes_themes', 'woothemes_more_themes_page');  
    }

 


function woothemes_advertising(){ woothemes_page_gen('advertising'); }
function woothemes_nav(){ woothemes_page_gen('nav'); }
function woothemes_image(){ woothemes_page_gen('image'); }

function woothemes_page_gen($page){
 
    $options =  get_option('woo_template');      
    $themename =  get_option('woo_themename');      
    $shortname =  get_option('woo_shortname');
    $manualurl =  get_option('woo_manual'); 
    
//Version

$theme_data = get_theme_data(ABSPATH . 'wp-content/themes/'. get_option('template') .'/style.css');
$local_version = $theme_data['Version'];
$update_message = '<span class="update">v.'. $local_version .'</span>';

?>
</strong>
<?php
// END
?>
<div class="wrap" id="woo_options">
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post"  enctype="multipart/form-data">
    
        <div id="scrollme"><p class="submit"><input name="save" type="submit" value="Save All Changes" /></p></div>
        <div class="icon32" id="woo-icon">&nbsp;</div>
        <h2><?php echo $themename; ?> Options <?php echo $update_message; ?></h2>

        <div class="info"><strong>Stuck on these options?</strong> <a href="<?php echo $manualurl; ?>" target="_blank">Read The Documentation Here</a> or <a href="http://forum.woothemes.com" target="blank">Visit Our Support Forum</a></div>    

        <?php if ( $_REQUEST['saved'] ) { ?><div style="clear:both;height:20px;"></div><div class="happy"><?php echo $themename; ?>'s Options has been updated!</div><?php } ?>
        <?php if ( $_REQUEST['reset'] ) { ?><div style="clear:both;height:20px;"></div><div class="warning"><?php echo $themename; ?>'s Options has been reset!</div><?php } ?>                        

        <div style="clear:both;height:10px;"></div>

        <?php 
            echo woothemes_machine($options,$page);  //The real work horse  
        ?>

        <div style="clear:both;"></div>

        <?php  wp_nonce_field('reset_options'); echo "\n"; ?>

        <p class="submit submit-footer">
        <input name="save" type="submit" value="Save All Changes" />
        <input type="hidden" name="action" value="save" />
        </p>
        </form>
        
        <form action="<?php echo wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
        <p class="submit submit-footer submit-footer-reset">
        <input name="reset" type="submit" value="Reset Options" class="reset-button" onclick="return confirm('Click OK to reset. Any settings will be lost!');" />
        <input type="hidden" name="action" value="reset" />
        </p>
        </form>
        
    
<div style="clear:both;"></div>    
</div><!--wrap-->

 <?php
}

function woothemes_machine($options,$page) {
    
    $counter = 0;
    foreach ($options as $value) {
    
    if($page != $value['page']){
    $counter = 0; //Reset the Counter once a new page settings page is selected
    }
    elseif($page == $value['page']){
    $counter++;
    $val = '';
    //Start Heading
     if ( $value['type'] != "heading" )
     {
         $output .= '<div class="option option-'. $value['type'] .'">'."\n".'<div class="option-inner">'."\n";
        $output .= '<label class="titledesc">'. $value['name'] .'</label>'."\n";
        $output .= '<div class="formcontainer">'."\n".'<div class="forminp">'."\n";
     } 
     //End Heading
    $select_value = '';                                   
    switch ( $value['type'] ) {
    case 'text':
        $val = $value['std'];
        if ( get_settings( $value['id'] ) != "") { $val = get_settings($value['id']); }
        $output .= '<input name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. $val .'" />';
    break;
    case 'select':

        $output .= '<select name="'. $value['id'] .'" id="'. $value['id'] .'">';
    
        $select_value = get_settings( $value['id']);
         
        foreach ($value['options'] as $option) {
            
            $selected = '';
            
               if($select_value != '') {
                    if ( $select_value == $option) { $selected = ' selected="selected"';} 
               } else {
                if ($value['std'] == $option) { $selected = ' selected="selected"'; }
               }
              
            $output .= '<option'. $selected .'>';
            $output .= $option;
            $output .= '</option>';
         } 
         $output .= '</select>';

        
    break;
    case 'textarea':
        $ta_options = $value['options'];
        $ta_value = $value['std'];
        if( get_settings($value['id']) != "") { $ta_value = stripslashes(get_settings($value['id'])); }
        $output .= '<textarea name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $ta_options['cols'] .'" rows="8">'.$ta_value.'</textarea>';
        
    break;
    case "radio":
        
         $select_value = get_settings( $value['id']);
               
         foreach ($value['options'] as $key => $option) 
         { 

             $checked = '';
               if($select_value != '') {
                    if ( $select_value == $key) { $checked = ' checked'; } 
               } else {
                if ($value['std'] == $key) { $checked = ' checked'; }
               }
            $output .= '<input type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'<br />';
        
        }
         
    break;
    case "checkbox": 
    
       $std = $value['std'];  
       
       $saved_std = get_option($value['id']);
       
       $checked = '';
        
        if(!empty($saved_std)) {
            if($saved_std == 'true') {
            $checked = 'checked="checked"';
            }
            else{
               $checked = '';
            }
        }
        elseif( $std == 'true') {
           $checked = 'checked="checked"';
        }
        else {
            $checked = '';
        }

        $output .= '<input type="checkbox" class="checkbox" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';

    break;
    case "multicheck":
    
        $std =  $value['std'];         
        
        foreach ($value['options'] as $key => $option) {
                                         
        $woo_key = $value['id'] . '_' . $key;
        $saved_std = get_option($woo_key);
                
        if(!empty($saved_std)) 
        { 
              if($saved_std == 'true'){
                 $checked = 'checked="checked"';  
              } 
              else{
                  $checked = '';     
              }    
        } 
        elseif( $std == $key) {
           $checked = 'checked="checked"';
        }
        else {
            $checked = '';                                                                                                                                                                                                                          }
        
        
        $output .= '<input type="checkbox" class="checkbox" name="'. $woo_key .'" id="'. $woo_key .'" value="true" '. $checked .' /><label for="'. $woo_key .'">'. $option .'</label><br />';
                                    
        }
        
    break;
    case "upload":
        
        $output .= woothemes_uploader_function($value['id'],$value['std'],'options');
        
    break;
    case "heading":
        
        if($counter >= 2){
           $output .= '</div>'."\n";
        }
 
        $output .= '<div class="title">';
        $output .= '<p class="submit"><input name="save" type="submit" value="Save Changes" /><input type="hidden" name="action" value="save" /></p>';
        $output .= '<h3><span class="woo-expand">+</span>'. $value['name'] .'</h3>'."\n";    
        $output .= '</div>'."\n";
        $output .= '<div class="option_content">'."\n";
    break;                                    
    } 
    
    
    // if TYPE is an array, formatted into smaller inputs... ie smaller values
    
    if ( is_array($value['type'])) {
        
        foreach($value['type'] as $array){
        
                $id =   $array['id']; 
                $std =   $array['std'];
                $saved_std = get_option($id);
                if($saved_std != $std && !empty($saved_std) ){$std = $saved_std;} 
                $meta =   $array['meta'];
                
                if($array['type'] == 'text') { // Only text at this point
                     
                     $output .= '<input class="input-text-small" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';  
                     $output .= '<span class="meta-two">'.$meta.'</span>';
                }
                
            }
 
    }
    
    if ( $value['type'] != "heading" ) { 
        if ( $value['type'] != "checkbox" ) 
            { 
            $output .= '<br/>';
            }
            
        $output .= '</div><div class="desc">'. $value['desc'] .'</div></div>'."\n";
        $output .= '</div></div><div class="clear"></div>'."\n";
    
        }
    }
    }
    $output .= '</div>';
    return $output;
    
}


// WooThemes Uploader

function woothemes_uploader_function($id,$std){
    

$uploader .= '<input type="file" name="attachement_'.$id.'" class="upload_input"></input>';
$uploader .= '<span class="submit"><input name="save" type="submit" value="Upload" class="button upload_save" /></span>';
$uploader .= '<input type="hidden" name="attachement_loos_'.$id.'" value="' . $globals['attachement_'.$id] .'"></input>';


$upload = get_option($id);

    $uploader .= '<div class="clear"></div>';
    if (empty($upload) || $upload == $std)
    {
    $uploader .= '<input class="upload-input-text" name="'.$id.'" value="'.$std.'"/>';
    }
    else
    {
    $uploader .= '<input class="upload-input-text" name="'.$id.'" value="'.$upload.'"/>';
    $uploader .= '<div class="clear"></div>';
    $uploader .= '<a href="'. $upload . '">';
    $uploader .= '<img src="'.get_bloginfo('template_url').'/thumb.php?src='.$upload.'&w=290&h=200&zc=1" alt="" />';
    $uploader .= '</a>'; 
    }
    

return $uploader;
}

function wf_admin_head() { 
?>
<script type="text/javascript">
    jQuery(document).ready(function(){

        try {
        
        var timer = null;  
        var offset = jQuery('#scrollme').offset().top;
          
        jQuery(document).scroll(function(e){
            clearTimeout(timer);
            timer = setTimeout(function(){
                jQuery('#scrollme').animate({
                    top: jQuery(document).scrollTop() + offset 
                }, 'fast');
            }, 200);
        });
          
        } catch(exception) {
          // #scrollme is not on page load.
        }
        
        jQuery('#woo_options .title h3').parent().next('.option_content').slideUp();
        
        var initChar;
        jQuery('#woo_options .title h3').hover(function(){
            initChar = jQuery(this).children('span').html();
            if (jQuery(this).parent().next('.option_content').css('display') == 'none'){  
                jQuery(this).children('span').html("&darr;");
            }
            else
            {
               jQuery(this).children('span').html("&uarr;");  
            }
        }
        ,function(){
            jQuery(this).children('span').html(initChar)  
        })   
  
        jQuery('#woo_options .title h3').click(function(){
            if (jQuery(this).parent().next('.option_content').css('display') == 'none'){
             jQuery(this).children('span').html("+");
             initChar = "-"; 
            }
            else{
            jQuery(this).children('span').html("-");
            initChar = "+";   
            
            }
            
            jQuery(this).parent().next('.option_content').slideToggle('slow');
            
        });
      
      
    });
</script>

<?php }

add_action('admin_head', 'wf_admin_head');    

?>