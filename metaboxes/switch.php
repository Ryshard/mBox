<?php
defined('ABSPATH') or die('Access Denied!');

function bb_metaboxes()
{

	$post_id = bb_get_post_id();
	$post_type = bb_get_post_type($post_id);
	$meta_data = bb_extract( get_post_meta($post_id) );

	define('BOXES', THEME_DIR . 'metaboxes/boxes/');

	remove_meta_box( 'postcustom', $post_type, 'normal');
	remove_meta_box( 'slugdiv', $post_type, 'normal');
	remove_meta_box( 'commentsdiv', 'page', 'normal');
	remove_meta_box( 'commentstatusdiv', 'page', 'normal');
	remove_meta_box( 'trackbacksdiv', 'post', 'normal');


	//--------------------------------------------------------------------------------------------
	//  											THE SWITCH
	//--------------------------------------------------------------------------------------------


	// Initiate Object
	$page_set = new mBoxSet();



	// General Rules - apply to all pages / posts
	$page_set->add_metabox( array(
				'id' => 'headline' ,
			//	'title' => 'Headline',
			//	'priority' => 'high',
			//	'context' => 'side'
				));
	$page_set->add_field('headline', array(
						'type'  => 'text',
						'label' => '',
						'name'  => 'title'
						));


	//----------------------------------------------
	//  				POSTS
	//----------------------------------------------

    // *** based on post type ***/
	if($post_type === 'post') // dafault post type
	{
		include_once BOXES . 'single-post-meta.php';
	}
	elseif($post_type === 'yyy')
	{
		// include_once BOXES . 'careers-single-meta.php';	
	}
	elseif($post_type === 'page')
	{
		$template_file = bb_get_post_template($post_id);
		$front_page_id = get_option( 'page_on_front' );
		$blog_page_id = get_option( 'page_for_posts' );
		//----------------------------------------------
		//  				PAGES
		//----------------------------------------------
	  	if($post_id  == $front_page_id) // HomePage 
	  	{
	  		include_once BOXES . 'home-meta.php';	
	  	}
	  	elseif($post_id == $blog_page_id) // Blog Index
	  	{
	  		include_once BOXES . 'news-meta.php';
	  	}
	  	elseif ($template_file ==='templates/xxx.php') // *** based on page template ***/
		{
			// include_once BOXES . 'xxx-meta.php';
		}
		else // page default metaboxes
		{
			
		}
	}

	//---------------------------------

	if($post_id  != $front_page_id)
	{
		leadin_metabox($page_set);
	}

	// Activate metaboxes
	$page_set->activate();

} // bb_metaboxes function END
//----//----//----//----//----//----//----//----//----//----//----//
//----//----//----//----//----//----//----//----//----//----//----//
//----//----//----//----//----//----//----//----//----//----//----//



add_action( 'load-post.php', 'bb_metaboxes' );
add_action( 'load-post-new.php', 'bb_metaboxes' );

//----//----//----//----//----//----//----//----//----//----//----////----//----//----//----//----//----//----//----//----//----//----//
//----//----//----//----//----//----//----//----//----//----//----////----//----//----//----//----//----//----//----//----//----//----//

require_once 'mBox_class.php';

require_once 'meta-helpers.php';




//----------------------------------------------
// move Yoast SEO to the bottom
//----------------------------------------------

add_filter( 'wpseo_metabox_prio', function() { return 'low';});

//----------------------------------------


//----------------------------------------------
// Reset metabox positions
//----------------------------------------------
$user_ID = get_current_user_id();
$dontResetMeta = get_user_meta($user_ID, 'noBoxReset');

if(empty($dontResetMeta)) 
{
	update_user_meta( $user_ID, 'meta-box-order_post', '' );
	update_user_meta( $user_ID, 'meta-box-order_page', '' );
}
//----------------------------------------






function bb_get_post_id()
{
	// ---- get current post ID ----------------------------------------

	$post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : '') ;

	if(!defined('POST_ID'))
		{
			define('POST_ID', $post_id);
		}
	//----------------------------------------------

	return $post_id;
}



function bb_get_post_type($post_id)
{
		// ---- get post type ----------------------------------------
	if(!empty($post_id))
	{
		$post_type = get_post_type($post_id);
	}
	elseif(isset($_GET['post_type']) && !empty($_GET['post_type']))
	{
		$post_type = $_GET['post_type'];
	}
	else
	{
		$post_type = 'post';
	}

	return $post_type;
}



function bb_get_post_template($post_id)
{
	// ---- get page -> get template name ----------------------------------------
	$post_type = bb_get_post_type($post_id);
	if($post_type === 'page')
	{
		if(!empty($post_id))
		{
			$template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
		}
		else
		{
			$template_file = 'default';
		}
	}
	else
	{
		$template_file = null;
	}


	return $template_file;

}