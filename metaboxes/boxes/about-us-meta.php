<?php
/**
	Metaboxes for About Us page
	----------------------------

	last_mod:    2016 12 09
	last_mod_by: gabriel
	
	----------------------------
	auto-included in: switch.php
*/


// Second Paragraph // ----
$box_id = 'our_team';

$page_set->add_metabox( array(
      'id' => $box_id ,
      'title' => 'Our Team'
      ));

$page_set->add_field($box_id, array(
       'type'  => 'text',
       'name'  => 'title',
       'label' => 'Title'
       ));

$page_set->add_field($box_id, array(
    'type'  => 'rich',
    'name'  => 'text',
    'label' => 'Content'
    ));

/* - * - * - * - * - * - * - * - * - * - * - * - * - */
