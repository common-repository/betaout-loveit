<?php
/*
Plugin Name: Betaout-LoveIt
Plugin URI:http://access.betaout.com
Description: Adds an AJAX rating system for your WordPress blog's post/page.
Version: 1.0
Author: Rohit Tyagi,Amit Kumar Srivastava
Author URI: 
*/

register_activation_hook( __FILE__, 'betaoutloveit_activate' );
function betaoutloveit_activate(){
   $name=get_bloginfo('url');
    $url="http://api.betasa.info/user/wordpressplugin/?pluginName=betaout-loveit&url=$name";
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_exec($ch);
}

function betaout_ratings($start_tag = 'div', $custom_id = 0, $display = true) {
	global $id;
	// Allow Custom ID
	if(intval($custom_id) > 0) {
		$ratings_id = $custom_id;
	} else {
		$ratings_id = $id;
	}
	
         $post = get_post($id);
          $post_ratings = get_post_custom($id);
          $loading.='<div  style="margin:0pt 0pt 0pt 62px" >';
          $loading.='<'.$start_tag .' rel-data="'.$ratings_id .'" class="heartSprite loveIt likeitheart " onclick= "loveIt('.$ratings_id.')" id="likeClass_'.$ratings_id.'" data-html="true" data-placement="below" rel="twipsy" data-original-title="Love It!">';
               $loading.='<span id="totalLike_'.$ratings_id.'" class="totalLikes">'.$post_ratings['loveit_loveitcount'][0].'</span> </'.$start_tag.'></div>';
                

                 if(!$display) {
                 return $loading;
                 }else{
                 echo $loading;
                 return;
                 }
}


add_action('wp_head', 'loveit_javascripts_header');
function loveit_javascripts_header() {
	wp_print_scripts('jquery');
}
add_action('wp_enqueue_scripts', 'ratings_scripts');
function ratings_scripts() {

	if(@file_exists(TEMPLATEPATH.'/loveit.css')) {
		wp_enqueue_style('betaout-loveit', get_stylesheet_directory_uri().'/loveit.css', false, '1.0', 'all');
	} else {
		wp_enqueue_style('betaout-loveit', plugins_url('betaout-loveit/loveit.css'));
	}
//        wp_enqueue_script('betaout-lovemin', plugins_url('betaout-loveit/jquery-latest.min.js'), array('jquery'), '', false);
	wp_enqueue_script('betaout-loveit', plugins_url('betaout-loveit/loveit.js'), array('jquery'), '', false);
	wp_localize_script('betaout-loveit', 'loveitL10n', array(
		'plugin_url' => plugins_url('betaout-loveit'),
		'ajax_url' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')),
		'text_wait' => __('Please rate only 1 post at a time.', 'betaout-loveit'),

	));
}


function check_rated($post_id) {

	if(isset($_COOKIE["rated_$post_id"])) {
		return true;
	} else {
		return false;
	}

}

add_action('wp_ajax_loveit', 'process_loveit');
add_action('wp_ajax_nopriv_loveit', 'process_loveit');
function process_loveit() {
	global $wpdb, $user_identity, $user_ID;

	if(isset($_GET['action']) && $_GET['action'] == 'loveit')
	{
		$post_id = intval($_GET['pid']);

		if($post_id > 0 ) {
			// Check For Bot
			$bots_useragent = array('googlebot', 'google', 'msnbot', 'ia_archiver', 'lycos', 'jeeves', 'scooter', 'fast-webcrawler', 'slurp@inktomi', 'turnitinbot', 'technorati', 'yahoo', 'findexa', 'findlinks', 'gaisbo', 'zyborg', 'surveybot', 'bloglines', 'blogsearch', 'ubsub', 'syndic8', 'userland', 'gigabot', 'become.com');
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			foreach ($bots_useragent as $bot) {
				if (stristr($useragent, $bot) !== false) {
					return;
				}
			}
			header('Content-Type: text/html; charset='.get_option('blog_charset').'');
			$rated = check_rated($post_id);
			if(!$rated) {
				// Check Whether Is There A Valid Post
				$post = get_post($post_id);
				// If Valid Post Then We Rate It
				if($post && !wp_is_post_revision($post)) {


                                        $post_ratings = get_post_custom($post_id);
					$value=$post_ratings['loveit_loveitcount'][0];
					 $loveit_loveitcount = $value+1;

					if (!update_post_meta($post_id, 'loveit_loveitcount', $loveit_loveitcount)) {
						add_post_meta($post_id, 'loveit_loveitcount', $loveit_loveitcount, true);
					}

					echo $loveit_loveitcount;
					exit();
				} else {
					printf(__('Invalid Post ID. Post ID #%s.', 'betaout-loveit'), $post_id);
					exit();
				} // End if($post)
			} else {
				printf(__('You Had Already Rated This Post. Post ID #%s.', 'betaout-loveit'), $post_id);
				exit();
			}
		} // End if($rate && $post_id && check_allowtorate())
	} // End if(isset($_GET['action']) && $_GET['action'] == 'loveit')
}




### Function: Short Code For Inserting Ratings Into Posts
//if(has_shortcode ('loveit')){
//    add_shortcode('loveit', 'loveit_shortcode');
//    function loveit_shortcode($atts) {
//    	extract(shortcode_atts(array('id' => '0', 'results' => false), $atts));
//            if(!is_feed()) {
//                    $id = intval($id);
//                     return betaout_ratings('span', $id, false);
//
//            } else {
//                    return __('Note: There is a rating embedded within this post, please visit this post to rate it.', 'wp-postratings');
//            }
//    }
//}else{
//
//}
### Function: Short Code For Inserting Ratings Into Posts or add end of content
add_action('the_content', 'add_ratings_to_content');
    function add_ratings_to_content($content) {
        if(strpos($content, '[loveit]'))
        {
            $content= str_replace('[loveit]', betaout_ratings('div', $id, false),$content);
        }
       else if (!is_feed()) {
                    $content .= betaout_ratings('div', 0, false);
            }

            return $content;
    }



 ?>