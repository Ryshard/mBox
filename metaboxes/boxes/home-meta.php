<?php
defined('ABSPATH') or die('Access Denied!');
/*
*  HomePage Metaboxes
*
*
*/

$page_set->add_field('headline', array(
          'type'  => 'text',
          'label' => 'Subtitle',
          'name'  => 'subtitle'
          )
        );


$page_set->add_metabox( array(
			'id' => 'cta_hero' ,
			'title' => 'CTA Hero',
			'priority' => 'high',
		//	'context' => 'side'
			));
$page_set->add_field('cta_hero', array(
					'type'  => 'cta_btn',
					'label' => '',
					'name'  => 'cta_hero'
			));

$page_set->add_field('cta_hero', array(
          'type'  => 'map',
          'label' => '',
          'name'  => 'cta_map'
      ));



//-----------added by gavin--------------
link_section_metabox($page_set);


//---------------------------------------------------------------

$page_set ->add_metabox( array(
  'id' => 'bloglist' ,
  'title' => 'Blog Posts',
  'priority' => 'high'
  ));

$page_set->add_field(
  'bloglist', array(
    'type' => 'text',
    'label' => 'Heading',
    'name' => 'title'
    ));

$page_set->add_field(
  'bloglist', array(
    'type' => 'rich',
    'label' => 'Number of Posts',
    'name' => 'number'
    ));




$page_set->add_field(
  'bloglist', array(
    'type' => 'content_select',
    'label' => 'Selector',
    'name' => 'test_selector',
    'post_type'=> array('services','page')
    ));

$page_set->add_field(
  'bloglist', array(
    'type' => 'image',
    'mime' => array('svg', 'png', 'jpg'),
    'label' => 'Img',
    'name' => 'imagee',
    'post_type'=> array('services','page')
    ));

