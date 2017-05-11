<?php

for ($i=1; $i <=5 ; $i++) 
{ 
	 $box_id = 'section_' . $i;

	 $page_set->add_metabox( array(
        'id' => $box_id ,
        'title' => 'Section ' . $i,
    
        ));

    $page_set->add_field($box_id, array(
          'type'  => 'rich',
          'name'  => 'text',
          'label' => 'Content',
          'rows' => 37,
    		'media' => 'true'
          ));


    $box_id = 'section_' . $i . '_images';
    $page_set->add_metabox( array(
        'id' => $box_id ,
        'title' => 'Section ' . $i .' images',
        'containers' => array('left one-third', 'mid one-third','right one-third')
        ));

  	$c=0;
	for ($a=1; $a <=9; $a++)
	{ 
		$c++;
	  		
	  		if( $c === 1 OR $c === 4 OR $c === 7 ) { $con = 'left one-third'; }
	  		elseif( $c === 2 OR $c === 5 OR $c === 8 ) { $con = 'mid one-third'; }
			elseif( $c === 3 OR $c === 6 OR $c === 9 ) { $con = 'right one-third'; $c = 0; }  	
	  		

		$page_set->add_field($box_id, array(
				'type' => 'image',
				'label' => 'Image '. $a,
				'name' => 'image_' . $a,
				'container' => $con 
				));

				$page_set->add_field($box_id, array(
				'type' => 'info',
				'label' => '',
				'info' => '<br>',
				'name' => 'info_' . $a,
				'container' => $con 
				));
	}
}


$box_id = 'bottom_cta';

	 $page_set->add_metabox( array(
        'id' => $box_id ,
        'title' => 'Bottom Call to Action',
        ));
	 
	 $page_set->add_field($box_id, array(
	 		'type'  => 'text',
	 		'name'  => 'title',
	 		'label' => 'Title'
	 ));

    $page_set->add_field($box_id, array(
          'type'  => 'rich',
          'name'  => 'text',
          'label' => 'Content',
          'rows' => 15
          ));
    
    $page_set->add_field($box_id, array(
    		'type' => 'cta_btn',
    		'label' => '',
    		'name' => 'cta'
    ));
    
    $page_set->add_field($box_id, array(
    		'type' => 'image',
    		'label' => 'Background Image ',
    		'name' => 'image'
    ));
    
    
 $box_id = 'recognition_section';
	 $page_set->add_metabox( array(
        'id' => $box_id ,
        'title' => 'Add Recognition Section',
	 	'context' => 'side',
	 	'priority' => 'low'
        ));

	 $page_set->add_field($box_id, array(
	 		'type' => 'checkbox',
	 		'label' => '',
	 		'name' => 'check',
	 		'value' => '1'
	 ));
 

	if(!empty($meta_data['recognition_section']['check']) AND $meta_data['recognition_section']['check'] == 1)
	{
		$box_id = 'recognition';
		$page_set->add_metabox( array(
				'id' => $box_id ,
				'title' => 'Recognition Section'
		));
		
		$page_set->add_field($box_id, array(
				'type'  => 'text',
				'name'  => 'title',
				'label' => 'Heading'
		));
		
		for ($e = 1; $e <=10; $e++)
		{
			$page_set->add_field($box_id, array(
					'type'  => 'text',
					'name'  => 'title_'.$e,
					'label' => 'Title ' . $e
			));
			
			$page_set->add_field($box_id, array(
					'type'  => 'rich',
					'name'  => 'text_'.$e,
					'label' => 'Content ' . $e,
					'rows' => 15
			));
			
			$page_set->add_field($box_id, array(
					'type'  => 'image',
					'name'  => 'image_'.$e,
					'label' => 'Logo ' . $e
			));
			
			$page_set->add_field($box_id, array(
					'type'  => 'info',
					'name'  => 'info_'.$e,
					'info' => '<br><br>',
					'label' => ''
			));
		}
		
	}
	
	 
	 