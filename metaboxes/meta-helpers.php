<?php
defined('ABSPATH') or die('Access Denied!');

function bb_remove_content_editor($custom = '')
{
	global $_wp_post_type_features;

	//dump( $_wp_post_type_features);
	//die();

	if (isset($_wp_post_type_features['post']['editor']) && $_wp_post_type_features['post']['editor'])
	{
			unset($_wp_post_type_features['post']['editor']);
	}

	if (isset($_wp_post_type_features['page']['editor']) && $_wp_post_type_features['page']['editor'])
	{
	    unset($_wp_post_type_features['page']['editor']);
	}

	if(!empty($custom))
	{
		if (isset($_wp_post_type_features[$custom]['editor']) && $_wp_post_type_features[$custom]['editor'])
		{
				unset($_wp_post_type_features[$custom]['editor']);
		}
	}
}






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


function switch_section_metabox(&$page, $nr = null, $label = 'Section')
{
	if($nr !== null)
	{
		$id = 'switch_'.$nr;
	}
	else
	{
		$id = 'switch';
	}

	$page->add_metabox( array(
	'id' => $id ,
	'title' => $label . ' '.$nr,
	'priority' => 'high',
	'containers' => array('left','right')
	));

	$page->add_field(
		$id, array(
			'type'      => 'text',
			'label'     => 'Heading',
			'name'      => 'heading',
			'container' => 'left'
			)
		);

	$page->add_field(
		$id, array(
			'type'      => 'textarea',
			'label'     => 'Section Text',
			'name'      => 'text',
			'container' => 'left',
			'rows'		=> '5'
			)
		);

	$page->add_field($id, array(
					'type' => 'cta_btn',
					'label' => 'CTA Link',
					'container' => 'left'
					));

	$page->add_field($id, array(
					'type' => 'image',
					'label' => 'Section Image',
					'container' => 'right',
					'name' => 'image'
					));

}


function link_section_metabox(&$page, $title = 'CTA Section')
{
	$id = 'link_section';

	$page->add_metabox( array(
	'id' => $id ,
	'title' => $title,
	'priority' => 'high',
	'containers' => array('left','right')
	));

	$page->add_field(
		$id, array(
			'type'      => 'text',
			'label'     => 'Title',
			'name'      => 'title',
			'container' => 'left'
			)
		);

	$page->add_field(
		$id, array(
			'type'      => 'textarea',
			'label'     => 'Paragraph',
			'name'      => 'text',
			'container' => 'right',
			'rows' 		=> 5
			)
		);

	$page->add_field($id, array(
			'type' => 'cta_btn',
			'label' => 'CTA Link',
			'container' => 'right'
			));
}



function dual_section( &$page, $label = 'Dual Section')
{

	$page->add_metabox( array(
					'id' => 'underline' ,
					'title' => $label,
					'priority' => 'high',
					'containers' => array('left', 'right')
					));

		$page->add_field('underline', array(
							'type'  => 'textarea',
							'label' => 'left text (small)',
							'name'  => 'left',
							'container' => 'left',
							'rows' 		=> '2'
							)
						);
		$page->add_field('underline', array(
							'type'  => 'textarea',
							'label' => 'right text (big)',
							'name'  => 'right',
							'container' => 'right',
							'rows' => '3'
							)
						);
}


function video_section(&$page, $label = 'Video')
{
	$page->add_metabox( array(
					'id' => 'video' ,
					'title' => 'Video ',
					'priority' => 'high'
					));

		$page->add_field('video', array(
							'type'  => 'url',
							'label' => 'Youtube video url',
							'name'  => 'url'
							)
						);

		$page->add_field('video', array(
							'type'  => 'image',
							'label' => 'poster image (optional)',
							'name'  => 'poster'
							)
						);
}


function testimonial_section(&$page, $label = 'Testimonial')
{
	$page->add_metabox( array(
					'id' => 'testimonial' ,
					'title' =>  $label,
					'priority' => 'high'
					));

	$page->add_field('testimonial', array(
						'type'  => 'text',
						'label' => 'Heading',
						'name'  => 'title'
						)
					);
		$page->add_field('testimonial', array(
							'type'  => 'textarea',
							'label' => 'Content',
							'name'  => 'text'
							)
						);

		$page->add_field('testimonial', array(
							'type'  => 'text',
							'label' => 'Author First Name',
							'name'  => 'author_first_name'
							)
						);
		$page->add_field('testimonial', array(
							'type'  => 'text',
							'label' => 'Author Last Name',
							'name'  => 'author_last_name'
							)
						);

		$page->add_field('testimonial', array(
							'type'  => 'text',
							'label' => 'Author Position',
							'name'  => 'author_position'
							)
						);

		$page->add_field('testimonial', array(
							'type'  => 'image',
							'label' => 'Avatar',
							'name'  => 'image'
							)
						);
}


function section_cta_small(&$page, $label = 'Cta Section')
{
	$page->add_metabox( array(
					'id' => 'cta_section' ,
					'title' =>  $label,
					'priority' => 'high',
					'containers' => array('left','right')
					));

		$page->add_field('cta_section', array(
							'type'  => 'textarea',
							'label' => 'Content',
							'name'  => 'text',
							'container' => 'left',
							'rows'  => 5
							)
						);

			$page->add_field('cta_section', array(
						'type' => 'cta_btn',
						'label' => 'CTA link',
						'name' => 'cta',
						'container' => 'right'
							)
						);

}



function bb_leadin_image_metabox(&$page, $label = 'Lead in image')
{


			$page->add_metabox( array(
					'id' => 'lead_in_image' ,
					'title' => $label,
					'priority' => 'low',
					'context' => 'side'
					));

			$page->add_field( 'lead_in_image', array(
					'type'  => 'image',
					'label' => '',
					'name'  => 'img_id'
				));

}


function spec_section(&$page, $label = 'Spec Section'){

			$page->add_metabox( array(
					'id' => 'spec_section' ,
					'title' =>  $label,
					'priority' => 'high'
				));
			$page->add_field('spec_section', array(
					'type'  => 'text',
					'label' => 'Spec Title',
					'name'  => 'spec_title'
				));
			$page->add_field('spec_section', array(
					'type'  => 'text',
					'label' => 'Spec 1',
					'name'  => 'spec_1'
				));
			$page->add_field('spec_section', array(
					'type'  => 'text',
					'label' => 'Spec 2',
					'name'  => 'spec_2'
				));
			$page->add_field('spec_section', array(
					'type'  => 'text',
					'label' => 'Spec 3',
					'name'  => 'spec_3'
				));
			$page->add_field('spec_section', array(
					'type'  => 'text',
					'label' => 'Spec 4',
					'name'  => 'spec_4'
				));

}


function leadin_metabox(&$page)
{
		$page->add_metabox( array(
					'id' => 'leadin' ,
					'title' =>  'Lead in',
					'priority' => 'high'
				));

		$page->add_field('leadin', array(
					'type'  => 'text',
					'label' => 'Title',
					'name'  => 'title'
				));

		$page->add_field('leadin', array(
					'type'  => 'textarea',
					'label' => 'Text',
					'name'  => 'text',
					'rows' => 6
				));
		$page->add_field( 'leadin', array(
					'type'  => 'image',
					'label' => 'Image',
					'name'  => 'image'
				));

}

function job_spec_metabox(&$page)
{
		$page->add_metabox( array(
					'id' => 'job_spec' , // the id of the array
					'title' =>  'Job Spec',// the printed title in the backend
					'priority' => 'high' // where the metabox will be displayed
				));

		$page->add_field('job_spec', array(
					'type'  => 'text',// the type of meta box
					'label' => 'Role',// label printed in backend
					'name'  => 'role'//  the id used for data retrival
				));

		$page->add_field('job_spec', array(
					'type'  => 'text',
					'label' => 'Salary Range',
					'name'  => 'salary_range',
				));

		$page->add_field( 'job_spec', array(
					'type'  => 'text',
					'label' => 'Location',
					'name'  => 'location'
				));

		$page->add_field( 'job_spec', array(
					'type'  => 'text',
					'label' => 'Contact First Name',
					'name'  => 'contact_first_name'
				));

		$page->add_field( 'job_spec', array(
					'type'  => 'text',
					'label' => 'Contact Last Name',
					'name'  => 'contact_last_name'
				));
}

function check_list_section(&$page, $label = 'Check List')
{
	$page->add_metabox( array(
					'id' => 'check_list_section' ,
					'title' =>  $label,
					'priority' => 'high'
					));

	$page->add_field('check_list_section', array(
						'type'  => 'text',
						'label' => 'Heading',
						'name'  => 'title'
						)
					);
		$page->add_field('check_list_section', array(
							'type'  => 'textarea',
							'label' => 'Content',
							'name'  => 'text'
							)
						);

		$page->add_field('check_list_section', array(
							'type'  => 'rich',
							'label' => 'Check List',
							'name'  => 'check_list'
							)
						);
}

function four_part_section(&$page)
{
	$page->add_metabox( array(
				'id' => 'four_part_section' , // the id of the array
				'title' =>  'Four Part Section',// the printed title in the backend
				'priority' => 'high' // where the metabox will be displayed
			));

	$page->add_field('four_part_section', array(
				'type'  => 'text',// the type of meta box
				'label' => 'Heading 1',// label printed in backend
				'name'  => 'heading_1'//  the id used for data retrival
			));
	$page->add_field('four_part_section', array(
				'type'  => 'textarea',// the type of meta box
				'label' => 'Content 1',// label printed in backend
				'name'  => 'content_1'//  the id used for data retrival
			));

		$page->add_field('four_part_section', array(
					'type'  => 'text',// the type of meta box
					'label' => 'Heading 2',// label printed in backend
					'name'  => 'heading_2'//  the id used for data retrival
				));
		$page->add_field('four_part_section', array(
					'type'  => 'textarea',// the type of meta box
					'label' => 'Content 2',// label printed in backend
					'name'  => 'content_2'//  the id used for data retrival
				));

			$page->add_field('four_part_section', array(
						'type'  => 'text',// the type of meta box
						'label' => 'Heading 3',// label printed in backend
						'name'  => 'heading_3'//  the id used for data retrival
					));
			$page->add_field('four_part_section', array(
						'type'  => 'textarea',// the type of meta box
						'label' => 'Content 3',// label printed in backend
						'name'  => 'content_3'//  the id used for data retrival
					));

				$page->add_field('four_part_section', array(
							'type'  => 'text',// the type of meta box
							'label' => 'Heading 4',// label printed in backend
							'name'  => 'heading_4'//  the id used for data retrival
						));
				$page->add_field('four_part_section', array(
							'type'  => 'textarea',// the type of meta box
							'label' => 'Content 4',// label printed in backend
							'name'  => 'content_4'//  the id used for data retrival
						));

}
