<?php
defined('ABSPATH') or die('Access Denied!');


//---------------------------------------------------------------





  $box_id = 'trio';
  $page_set->add_metabox( array(
        'id' => $box_id ,
        'title' => 'Trio Section',
        'containers' => array('left one-third', 'mid one-third','right one-third')
        ));
        image_box($page_set, $box_id, 'box_1', 'left one-third');
        image_box($page_set, $box_id, 'box_2', 'mid one-third');
        image_box($page_set, $box_id, 'box_3', 'right one-third');
  



$box_id = 'accreditations';
  $page_set->add_metabox( array(
        'id' => $box_id ,
        'title' => 'Accreditations',
        'containers' => array('left', 'right')
        ));


  for ($i=1; $i <= 4; $i++)
  { 
     $page_set->add_field( $box_id, array(
    'type'  => 'image',
    'name'  => 'logo_' . $i,
    'container' => 'left',
    'label' => 'Logo ' . $i
    ));
  }

    for ($i=5; $i <= 8; $i++)
  { 
     $page_set->add_field( $box_id, array(
    'type'  => 'image',
    'name'  => 'logo_' . $i,
    'container' => 'right',
    'label' => 'Logo ' . $i
     ));
  }


//------------------------------------------------------------------


  $box_id = 'philosophy';

  $page_set->add_metabox( array(
        'id' => $box_id ,
        'title' => 'Philosophy',
        'containers' => array('left', 'right')
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


      $page_set->add_field($box_id, array(
          'type'  => 'checkbox',
          'name'  => 'list',
          'label' => 'Add Philosophies List',
          'value' => '1'
          ));








