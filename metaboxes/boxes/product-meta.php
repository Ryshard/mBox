<?php

$box_id = 'acet_course';

if(!empty($post_id))
{
    $linked_acet_course_ids = acet_get_linked_course($post_id); 

    if(!empty($linked_acet_course_ids))
    {
      $page_set->add_metabox( array(
            'id' => $box_id ,
            'title' => 'Linked ACET Course',
            'context' => 'side',
            'priority' => 'low'
             ));
      foreach ($linked_acet_course_ids as $linked_acet_course_id) 
      {
         $page_set->add_field( $box_id, array(
          'type'  => 'info',
          'name'  => 'id',
          'info' => '<a href="' . WP_HOME . '/wp-admin/post.php?post='.$linked_acet_course_id . '&action=edit">Edit Course (id:'.$linked_acet_course_id.')</a>'
       ));
      }
    }

}



    function acet_get_linked_course($post_id)
    {

      $acet_curses = get_posts( array( 'posts_per_page' => -1, 'post_type' => 'acet_courses', 'fields' => 'ids'));

      // error_log(__FUNCTION__ . '  $acet_curses: ' . print_r( $acet_curses, 1));

      $l_courses = array(); 
      foreach ($acet_curses as $acet_course_id)
      {
        $c_page = new bbPage($acet_course_id);
         $linked_product_id = $c_page->get_field('product', 'id');

         // $page_meta = get_post_meta($acet_course_id, 'product');
         // error_log( __FUNCTION__ . '  $page_meta: ' . print_r( $page_meta, 1));

        if(!empty( $linked_product_id ) AND $linked_product_id == $post_id)
        {
            $l_courses[] = $acet_course_id;
        }

        unset($c_page);
      }

      return $l_courses; 


    }