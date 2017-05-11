<?php
defined('ABSPATH') or die('Access Denied!');

add_action( 'load-post.php', 'bb_metaboxes' );
add_action( 'load-post-new.php', 'bb_metaboxes' );


session_start();



function bb_metaboxes()
{
   
	//bb_remove_content_editor();

	// ---- get post ID ----------------------------------------

	$post_id = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post_ID']) ? $_POST['post_ID'] : '') ;


	if(!defined('POST_ID'))
		{
			define('POST_ID', $post_id);
		}
	//----------------------------------------------

	$post = get_post( $post_id );

	if(!empty($post_id ))
	{
		$bbPage = new bbPage($post_id);
	}
	

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



	// get blog page ID
	$blog_page_id = (defined('BLOG_ID')) ? BLOG_ID : get_option( 'page_for_posts' );

	// get front Page ID
	$front_page_id = (defined('HOME_ID')) ? HOME_ID : get_option( 'page_on_front' );

	define('BOXES', THEME_DIR . 'metaboxes/boxes/');

	$meta_data = bb_extract( get_post_meta($post_id) );

//--------------------------------------------------------------------------------------------
//  											THE SWITCH
//--------------------------------------------------------------------------------------------



//----------------------------------------------
//  				POSTS
//----------------------------------------------

$page_set = new mBoxSet();

/*
$page_set->add_metabox( array(
		'id'       => 'post_type' ,
		'title'    => 'Post Type',
		'priority' => 'low',
		'context' => 'side'
));

$page_set->add_field('post_type', array(
		'type' => 'info',
		'name' => 'pti',
		'info' => $post_type
));
*/

$simple_cpts = array(
		'attachment',
		'team',
		'faq',
		'philosophy',
		'product',
		'course',
		'wc_booking',
		'lesson',
		'post',
		'shop_order',
		'bookable_resource'
	);

if( !in_array( $post_type, $simple_cpts ) )
{
	$page_set->add_metabox( array(
						'id'       => 'headline' ,
						'title'    => 'Headline',
						'priority' => 'high'
						));

	$page_set->add_field('headline', 
							array( 
								'type' => 'text',
								'name' => 'title',
								'label' => 'Title'
								));

	$page_set->add_field('headline', 
							array( 
								'type' => 'text',
								'name' => 'subtitle',
								'label' => 'Sub Title'
								));


	$page_set->add_metabox( array(
						'id' => 'leadin' ,
						'title' =>  'Lead in',
						'priority' => 'low',
						'context' => 'side'
					));

	$page_set->add_field('leadin', array(
						'type'  => 'text',
						'label' => 'Title',
						'name'  => 'title'
					));



	$page_set->add_field( 'leadin', array(
						'type'  => 'image',
						'label' => '',
						'name'  => 'image'
					));


	$page_set->add_metabox( array(
							'id'       => 'testimonials' ,
							'title'    => 'Testimonials (Bottom)',
							'priority' => 'low',
							'context'  => 'side'
							));

				for ($i=1; $i < 6; $i++) 
				{ 
					$page_set->add_field('testimonials', 
						array( 
							'type'  => 'content_select',
							'name' => 'testimony_' . $i,
							'label'=> 'Testimony ' . $i,
							'post_type' => 'testimony'
						));
				}


}




//----------------------------------------------
//  				POSTS
//----------------------------------------------
if($post_type === 'wc_booking')
{
	$page_set->add_metabox( array(
		'id'       => 'customer_info' ,
		'title'    => 'Additional Customer Information',
		'priority' => 'low',
		'context' => 'side'
));

$page_set->add_field('customer_info', array(
		'type' => 'custom',
		'name' => 'pti',
		'function' => 'booking_customer_info_box'
));
}



if($post_type === 'post')
{
	include_once BOXES . 'post-meta.php';
}
elseif($post_type === 'page')
{

		//----------------------------------------------
		//  				PAGES
		//----------------------------------------------
		// ---- get page -> get template name ----------------------------------------
		if(!empty($post_id))
		{
			$template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
		}
		else
		{
			$template_file = 'default';
		}



	  	if( $post_id  == $front_page_id OR $template_file == 'template-homepage.php' )
	  	{
	  		include_once BOXES . 'home-meta.php';
	  	}
	  	elseif( $template_file == 'templates/course-type.php' )
	  	{
	  		include_once BOXES . 'courses-type-meta.php';
	  	}
		elseif( $template_file == 'templates/our-courses.php' )
	  	{
	  		include_once BOXES . 'our-courses-meta.php';
	  	}
	  	elseif( $template_file == 'template-fullwidth.php' )
	  	{	
	  			$page_set->add_metabox( array(
						'id'       => 'content_section' ,
						'title'    => 'Custom Content Section',
						'priority' => 'high'
						));
	  		    $page_set->add_field('content_section', array(
		          'type'  => 'rich',
		          'name'  => 'text',
		          'label' => 'Content',
		          'rows'  => '60'
		          ));
	  	}
	  	else
	  	{
	  		$tpl_parts = explode('/', $template_file);
	  		$tpl_file = end($tpl_parts);
	  		$tpl_file = rtrim($tpl_file, '.php');

	  		$meta_file = $tpl_file . '-meta.php';
	  		if( is_file( BOXES . $meta_file  ) )
	  		{
	  			include_once BOXES . $meta_file;
	  		}
	  		else
	  		{
	  			error_log( 'switch.php -> : meta file NOT found: ' . $meta_file ); 
	  		}
	  	}
	  
}
elseif($post_type === 'shop_order')
{
	//include_once BOXES . 'order-meta.php';
}
elseif($post_type === 'product')
{
	include_once BOXES . 'product-meta.php';
}
else
{
	$custom_post_types = get_post_types( array( '_builtin' => false ));

	foreach ($custom_post_types as $type) 
	{
		if($post_type == $type)
		{
			$filename = $type .'-meta.php';
			if(is_file(BOXES.$filename))
			{
				include_once BOXES.$filename;
			}

		}
	}
}



global $shortcode_tags;
  //error_log('  global $shortcode_tags: ' . print_r( $shortcode_tags, 1));
if(!empty($shortcode_tags) AND WP_DEBUG)
{
	$page_set->add_metabox( array(
		'id'       => 'shortcodes' ,
		'title'    => 'Available shortcodes',
		'priority' => 'low',
		'context' => 'side'
	));


$scs = '';

	$scs .= '';
	foreach ($shortcode_tags as $key => $value) 
	{
		$scs .= '[' . $key . ']';
		$scs .= '<br />';
	}
	

	$page_set->add_field('shortcodes', array(
		'type' => 'info',
		'name' => 'sc',
		'info' => $scs
	));
		
}


		
				


	
	$page_set->activate();
}



require 'mBox_class.php';

require 'meta-helpers.php';

include_once INC_DIR . 'bb-page-class.php';
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

if(empty($dontResetMeta)) :
update_user_meta( $user_ID, 'meta-box-order_post', '' );
update_user_meta( $user_ID, 'meta-box-order_page', '' );
endif;
//----------------------------------------


// Remove Post Tags And Categories 
/*
function wpse120418_unregister_categories() 
{
    register_taxonomy( 'category', array() );
    register_taxonomy( 'post_tag', array() );
}
add_action( 'init', 'wpse120418_unregister_categories' );
*/



/*
add_action( 'add_meta_boxes', 'hide_categories_metabox' ); 
function hide_categories_metabox() 
{ 
	remove_meta_box('categorydiv','post','side');
	remove_meta_box('tagsdiv-post_tag','post','side');


}
*/


function include_box($id)
{
	$file_path = BOXES . $id . '.php';

	if(is_file($file_path))
	{
		include_once( $file_path );
	}
	else
	{
		error_log('include_box failed: ' . $file_path . ' is not a file');
	}
	
}