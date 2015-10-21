<?php
defined('ABSPATH') or die('Access Denied!');

/*
*
* Example of helper function
*
*/



function hero_metabox(&$page)
{
	$id = 'hero';

	$page->add_metabox( array(
	'id' => $id ,
	'title' => 'Hero Section',
	'priority' => 'high',
	));

	$page->add_field(
		$id, array(
			'type' => 'text',
			'label' => 'Headline',
			'name' => 'heading'

			)
		);

	$page->add_field(
		$id, array(
			'type' => 'text',
			'label' => 'Sub Heading',
			'name' => 'sub-heading',
			)
		);
	$page->add_field(
		$id, array(
			'type' => 'cta_btn',
			'label' => 'CTA link',
			'name' => 'cta',
			)
		);
}

