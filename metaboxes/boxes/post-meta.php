<?php
/**
 Metaboxes for Single Post
 ----------------------------

 last_mod:    2016 12 15
 last_mod_by: gabriel

 ----------------------------
 included in: switch.php
 */


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


$box_id = 'related_articles';
$page_set->add_metabox( array( 'id' => $box_id,   'title' => 'Related Articles'   ));


for( $ai=1;$ai<=4;$ai++)
{
	$page_set->add_field($box_id, array(
			'type'  => 'content_select',
			'name'  => 'related_'.$ai,
			'label' => 'Article '.$ai,
			'post_type' => 'post'
	));
}


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


$box_id = 'custom_author';

$page_set->add_metabox( array(
		'id'       => $box_id ,
		'title'    => 'Custom Author',
		'priority' => 'low' ));
		
$page_set->add_field($box_id, array(
		'type'  => 'text',
		'label' => 'First Name',
		'name'  => 'first_name'
));

$page_set->add_field($box_id, array(
		'type'  => 'text',
		'label' => 'Last Name',
		'name'  => 'last_name'
));
