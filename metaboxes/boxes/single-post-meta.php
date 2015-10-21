<?php
defined('ABSPATH') or die('Access Denied!');
/*
* 	Single Article specyfic metaboxes
*/


//-------- EVENT ------------------------------

$page_set->add_metabox( array(
		'id' 	   => 'event',
		'title'    => 'Event',
		'priority' => 'high'
		));

$page_set->add_field(
		'event', array(
			'type' => 'text',
			'label' => 'Name',
			'name' => 'name'
			));
$page_set->add_field(
		'event', array(
			'type' => 'date',
			'label' => 'Start Date',
			'name' => 'start_date'
			));
$page_set->add_field(
		'event', array(
			'type' => 'date',
			'label' => 'End Date',
			'name' => 'end_date'
			));

$page_set->add_field(
		'event', array(
			'type' => 'text',
			'label' => 'Venue Name',
			'name' => 'venue_name'
			));
$page_set->add_field(
		'event', array(
			'type' => 'text',
			'label' => 'Venue Address',
			'name' => 'venue_address'
			));


$page_set->add_field(
		'event', array(
			'type' => 'url',
			'label' => 'Tickets (url)',
			'name' => 'tickets'
			));

//-----------------------------------------------


video_section($page_set);



$page_set->add_metabox( array(
		'id' 	   => 'middle_content',
		'title'    => 'Middle Content',
		'priority' => 'high'
		));

$page_set->add_field(
		'middle_content', array(
			'type' => 'rich',
			'label' => '',
			'name' => 'text'
			));




//-----------------------------------------------

$page_set->add_metabox( array(
	'id' => 'gallery' ,
	'title' => 'Gallery',
	'priority' => 'high',
	));

$page_set->add_field(
		'gallery', array(
			'type' => 'gallery',
			'label' => '',
			'name' => 'images'

		));

//-----------------------------------------------

$page_set->add_metabox( array(
		'id' 	   => 'bottom_content',
		'title'    => 'Bottom Content',
		'priority' => 'high'
		));

$page_set->add_field(
		'bottom_content', array(
			'type' => 'rich',
			'label' => '',
			'name' => 'text'
			));





section_cta_small($page_set);

//-----------------------------------------------

$page_set->add_metabox( array(
				'id' 	   => 'related' ,
				'title'    => 'Related Articles',
				'priority' => 'high'
				));

$page_set->add_field(
				'related', array(
				'type' => 'text',
				'label' => 'Title',
				'name' => 'heading'
				));

$page_set->add_field(
			'related', array(
				'type' => 'number',
				'label' => 'Number of related articles',
				'name' => 'nr'
			));

			$related_selects = get_post_meta( $post_id, 'related' );
			$related_selects_nr = (!empty($related_selects[0]['nr'])) ? $related_selects[0]['nr'] : 3;

			for ($i=1; $i <= $related_selects_nr  ; $i++)
			{
				$page_set->add_field(
				'related', array(
				'type' => 'post_select',
				'label' => 'Related Article',
				'name' => 'article_'.$i
				)
				);
			}
