<?php
defined('ABSPATH') or die('Access Denied!');
/*
*	Class for adding metaboxes into Wordpress pages and posts
*	author: Gabriel Garus
*   gabriel.garus@gmail.com
*/

class mBoxSet
{

	public $metaboxes = array();
	public $custom_post_types = array();
	public $meta = array();
	
	
	public $cached_taxonomies = array();
	public $cached_media = array();
	public $cached_posts = array();
	public $cached_pages = array();

	private $folder_url = '';

	private $all_img_srcs = '';

	public function __construct()
	{
		$this->custom_post_types = get_post_types( array( '_builtin' => false ));
		$this->meta = $this->extract( get_post_meta( POST_ID) );
		$this->folder_url = get_stylesheet_directory_uri() . '/metaboxes/';

		//$this->cache = $this->get_cache();
	}
	//----//----//----//----//----//----//----//----//----//----//----//


	private function get_cache()
	{
		if(isset($_SESSION['mbox']))
		{
			$cached = unserialize($_SESSION['mbox']);
			return $cached;
		}
		else 
		{
			return array();
		}

	}


	private function add_to_cache($name, $data)
	{
		$current_cache = $this->get_cache();

		$current_cache[$name] = $data;

		//$new_cache = serialize($current_cache);
		$this->cache = $current_cache;
		
		//$_SESSION['mbox'] = $new_cache;

	}






	public function activate()
	{
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function add_metabox($box)
	{
		$id = $box['id'];
		$this->metaboxes[$id] = $box;
	}
	//----//----//----//----//----//----//----//----//----//----//----//


	private function extract($meta)
	{
		if( !is_array($meta) )
        return array('error' => 'Meta is not an array');

    	if( empty($meta['box_index'][0]) )
        return array('error' => 'Please re-save the post.');

	    $index = @unserialize($meta['box_index'][0]);

	    $deserialised = array();

	    foreach ($index as $key )
	    {
	        $string = $meta[$key][0];
	        $arra = @unserialize($string);
	        $deserialised[$key] = $arra;

	    }

    	return $deserialised;
	}
	//----//----//----//----//----//----//----//----//----//----//----//


	public function add_field($id, $field)
	{
		if(isset($this->metaboxes[$id]))
		{
			$this->metaboxes[$id]['fields'][] = $field;
		}
		else
		{
			die('Metabox not set: ' . $id);
		}

	}
	//----//----//----//----//----//----//----//----//----//----//----//


	public function add_meta_boxes()
	{
		//dump($this->metaboxes);
		//die();
		foreach ($this->metaboxes as $box)
		{
			$id = $box['id'];
			$title = (!empty($box['title'])) ? $box['title'] : 'Metabox';
			$priority = (isset($box['priority'])) ? $box['priority'] : 'high';
			$context = (isset($box['context'])) ? $box['context'] : 'normal';
			$fields = (!empty($box['fields'])) ? $box['fields'] : null;

			$cb_Function = (!empty($box['function'])) ? $box['function'] : array( $this, 'meta_box_html' );
			$containers = (!empty($box['containers'])) ? $box['containers'] : null;

			$callback_args = array('fields'=> $fields, 'id' => $id, 'containers' => $containers, 'box' => $box );

		//	error_log('$callback_args: ' . print_r($callback_args, true ));
					add_meta_box(
						$id ,    // Unique ID
						$title,     // Title
						$cb_Function, // Callback function
						null , 	           // Admin page (or post type)
						$context,              // Context
						$priority,             // Priority
						$callback_args  // callback args (array)
		  			);
		}


	}
	//----//----//----//----//----//----//----//----//----//----//----//





	public function save( $post_id )
	{
		// Check if our nonce is set.
		if ( ! isset( $_POST['bigbang_2015_mbox_nonce'] ) )
		{
			//die('no nonce in POST');
			return $post_id;
		}

		$nonce = $_POST['bigbang_2015_mbox_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'bigbang_2015_mbox' ) )
		{
			//die('nonce not verified');
			return $post_id;
		}


		// If this is an autosave, our form has not been submitted,
	    // so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return $post_id;
		}
		//-----------------


		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] )
		{
			if ( ! current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		}
		else
		{
			if ( ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
		}
		//-----------------


		/* OK, its safe for us to save the data now. */

		$box_index = array();

		foreach ($this->metaboxes as $box)
		{

			$key = $box['id'];

			$box_index[] = $box['id'];

			$mydata =  $_POST[$key];

			update_post_meta( $post_id, $key, $mydata );
			// data saved


			// WPML fields update
			foreach($box['fields'] as $field)
			{

				if($field['type'] == 'text' OR $field['type'] == 'textarea' OR $field['type'] == 'rich' )
				{
					$field_name = $field['name'];
					$string_name = $key . '-' . $field_name . '-' . $post_id;
					$data = $mydata[$field_name];

					do_action( 'wpml_register_single_string', 'bigbang',  $string_name  , $data  );
				}
				elseif($field['type'] == 'cta_btn' )
				{
					$string_name = $key . '-cta_label-' . $post_id;
					$data = (!empty($mydata['cta_label'])) ? $mydata['cta_label'] : (!empty($mydata['label'])) ? $mydata['label'] : 'cta_label';

					do_action( 'wpml_register_single_string', 'bigbang',  $string_name , $data  );
				}
			}

		}


		// -- update box index
		update_post_meta( $post_id, 'box_index', $box_index );


	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function meta_box_html($post, $args)
	{
		wp_nonce_field( 'bigbang_2015_mbox', 'bigbang_2015_mbox_nonce' );
		//dump($this->metaboxes);
		$fields = $args['args']['fields'];
		$id = $args['args']['id'];
		$containers =  $args['args']['containers'];

		$box = $args['args']['box']; 

		echo '<div class="metabox '.$id.'">'.PHP_EOL;

		if($fields !== null)
		{
			$this->display_fields($fields, $id);
		}

		if(!empty($containers))
		{
			foreach ($containers as $container)
			{
				echo '<div class="container '.$container.'">'.PHP_EOL;
				$this->display_fields($fields, $id, $container);
				echo '</div>' . PHP_EOL;
			}

			echo '<div style="clear:both;"></div>' .PHP_EOL;

		}

		echo '</div>'.PHP_EOL;

		if( !isset( $box['back-to-top'] ) ) 
		{
			$box['back-to-top'] = true;
		}

		if( $box['back-to-top'] )
		{
			echo '<center><a class="back-to-top button-primary" href="#post">Back To Top</a></center>';	
		}
		
	}
	//----//----//----//----//----//----//----//----//----//----//----//


	// Displays all fields in the metabox
	// $fields - array of fields and its parameters
	// $id - metabox id

	public function display_fields($fields, $id, $container = null)
	{

		foreach ($fields as $field)
		{

			$field_container = (!empty($field['container'])) ? $field['container'] : null;


			if($field_container == $container )
			{

				$field_name = (!empty($field['name'])) ? $field['name'] : null;

				$class = (!empty($field['class'])) ? $field['class'] : $field_name;
				$type  = (!empty($field['type']))  ? $field['type'] : 'text';
				$label = (!empty($field['label'])) ? $field['label'] : null;
				$rows  = (!empty($field['rows']))  ? $field['rows'] : null;
				$info  = (!empty($field['info']))  ? $field['info'] : null;
				$js = (!empty($field['script']))  ? $field['script'] : null;
				$video_display = (!empty($field['video_display']))  ? $field['video_display'] : false;
				$meta = $this->meta;

				echo '<div class="field ' . $class .'">'.PHP_EOL;

				if($type == 'text')
				{
					echo $this->input_field_html($id, $field_name, $label, 'text');
				}
				elseif($type == 'url')
				{
					echo $this->input_field_html($id, $field_name, $label, 'url');
				}
				elseif($type == 'email')
				{
					echo $this->input_field_html($id, $field_name, $label, 'email');
				}
				elseif($type == 'date')
				{
					echo $this->input_field_html($id, $field_name, $label, 'date');
				}
				elseif($type == 'number')
				{
					$min = (!empty($field['min'])) ? $field['min'] : null;
					$max = (!empty($field['max'])) ? $field['max'] : null;
					$step = (!empty($field['step'])) ? $field['step'] : null;
					$default = (!empty($field['default'])) ? $field['default'] : null;

					$args = array( 'min' => $min, 'max' => $max, 'step' =>$step, 'default' => $default	);

					echo $this->input_field_html($id, $field_name, $label, 'number', $args);
				}
				elseif($type == 'rich')
				{
					$rich_opts = array();

					if(!empty($field['rows']))
						$rich_opts['rows'] = $field['rows'];

					if(!empty($field['html']))
						$rich_opts['html'] = $field['html'];

					if(!empty($field['media']))
						$rich_opts['media'] = $field['media'];

					$this->richText_html($id, $field_name, $rich_opts);
				}
				elseif($type == 'cta_btn')
				{
					echo $this->cta_button_hmtl($id, $field_name, $label);
				}

				elseif ($type == 'textarea')
				{

					echo $this->textarea_html($id, $field_name,$label,$rows);
				}
				elseif ($type == 'image' OR $type == 'img' OR $type == 'media_select')
				{
					$media_type = (!empty($field['mime']))  ? $field['mime'] : 'image';
					$show = (!empty($field['show']))  ? $field['show'] : true;
		    		echo $this->mediaSelect( $id, $field_name, $media_type,$label, $show );
				}
				elseif ($type == 'user')
				{
					echo $this->user_select($id, $field_name );
				}
				elseif ($type == 'info')
				{
					$hr = (isset($field['hr'])) ? $field['hr'] : true;
					echo '<div class="info">'.PHP_EOL;
					echo '<p>'. $info .'</p>'.PHP_EOL;
					if($hr != false) {	echo '<hr>'; }
					echo '</div>'.PHP_EOL;
				}
				elseif ($type == 'form')
				{
					echo $this->select_form($id, $field_name);
				}
				elseif($type == 'script')
				{
					echo '<script>'.PHP_EOL;
					echo $js;
					echo '</script>'.PHP_EOL;
				}
				elseif($type == 'hidden')
				{
					$val = (!empty($field['value']))  ? $field['value'] : null;
					echo '<input type="hidden" name="'.$id.'['.$field_name.']'.'" value="'.$val.'" />'.PHP_EOL;
				}
				elseif($type == 'content_select')
				{
					$post_type = (!empty($field['post_type']))  ? $field['post_type'] : 'all';
					echo $this->content_selector_html($id, $field_name,$label,$post_type);
				}
				elseif($type == 'custom_select')
				{
					$select_options = (!empty($field['select_options']))  ? $field['select_options'] : null;

					if( $select_options !== null OR !is_array($select_option))
					{
						echo $this->custom_content_selector($id, $field_name,$label,$select_options);
					}
					else
					{
						echo '<p>select_options is empty</p>';
					}
				}
				elseif($type == 'gallery')
				{
					$this->the_gallery($id, $field_name, $label);
				}
				elseif($type == 'map')
				{
					
					echo '<fieldset class="cta_button">';
					echo '<legend><h4> Map  </h4></legend>';
					echo $this->input_field_html($id, 'latitude', 'Latitude: ');
					echo $this->input_field_html($id, 'longitude', 'Longitude: ');
					$args = array( 'min' => 1, 'max' => 19, 'step' =>1, 'default' => 9	);
					echo $this->input_field_html($id, 'zoom', 'Zoom Level:', 'number', $args);

					echo '</fieldset>';

					$longitude = (!empty($meta[$id]['longitude'])) ? $meta[$id]['longitude'] : null;
					$latitude  = (!empty($meta[$id]['latitude'])) ? $meta[$id]['latitude'] : null;
					$zoom      = (!empty($meta[$id]['zoom'])) ? $meta[$id]['zoom'] : 7;

					if($longitude !== null AND $latitude !== null )
					{
						echo '<img src="//maps.googleapis.com/maps/api/staticmap?center=';
						echo $latitude . ',' .$longitude;
						echo '&amp;zoom=' . $zoom . '&amp;size=400x400">' ;
					}
					else
					{
						echo 'Map Error: longitiude or latitude NOT found';
					}

				}
				elseif($type == 'image_upload')
				{
					$theName = $id.'['.$field_name.']';
					$current = (!empty($meta[$id][$field_name])) ? $meta[$id][$field_name] : '';
					echo '<div class="img-upload-field">';
					echo '<input class="upload_image" type="text" size="36" name="'. $theName .'" value="'.$current.'" />';
					echo '<input class="upload_image_button" type="button" value="Upload Image" />';
					echo '</div>';
				}
				elseif($type == 'heading')
				{
					$heading = (!empty($field['heading']))  ? $field['heading'] : null;
					$heading_type = (!empty($field['heading_type']))  ? $field['heading_type'] : 'h3';

					echo '<'.$heading_type . ' style="margin-top: 20px;">'. $heading . '</'. $heading_type . '>' . PHP_EOL;

				}
				elseif($type == 'radio')
				{
					$options = (!empty($field['options']))  ? $field['options'] : null;
					$theName = $id.'['.$field_name.']';
					$current =  (!empty($meta[$id][$field_name])) ? $meta[$id][$field_name] : '';
					
					echo '<h3>' . $label . '</h3><br>';
					foreach ($options as $key => $value) 
					{
						if($current == $key)
						{
							$chk = 'checked';
						}
						else
						{
							$chk = null;
						}

						echo '<label><input type="radio" name="'.$theName.'" value="' . $key .'" '.$chk.'>'.$value .'</label><br>';
					}
				//	echo '<hr>';

				}
				elseif($type == 'checkbox')
				{
					$theName = $id.'['.$field_name.']';
					$current =  (!empty($meta[$id][$field_name])) ? $meta[$id][$field_name] : '';
					$key = (!empty($field['value']))  ? $field['value'] : null;
					if($current == $key)
						{
							$chk = 'checked';
						}
						else
						{
							$chk = null;
						}

					//error_log('$theName: ' . $theName);
					//error_log('$current: ' . $current);
					//error_log('$key: ' . $key);

					echo '<label><input type="checkbox" name="'.$theName.'" value="' . $key .'" '.$chk.'>'.$label .'</label><br>';
				}
				elseif($type == 'navi')
				{
					echo '<ul>';
					foreach ($this->metaboxes as $box)
					{
						$id = $box['id'];
						if($id != 'navigation')	
						{
							echo '<li><a href="#'.$id.'">'.$box['title'].'</a></li>';
						}
						
					}
					echo '</ul>';
				}
				elseif($type == 'custom' AND !empty($field['function']))
				{
					if(!function_exists($field['function']))
					{
						echo 'Function ' . $field['function'] . ' does not exist. '; 
					}
					else
					{
						call_user_func($field['function'], POST_ID);
					}
					

				}	
				echo '</div>'.PHP_EOL;
			} // end If - container check


		} // end - foreach loop

	}
	//----//----//----//----//----//----//----//----//----//----//----//


	public function input_field_html($name, $arr , $label = 'Enter Text', $type = 'text', $args = array())
	{
		$html = '';

		$meta = $this->meta;
		$current = (!empty($meta[$name][$arr])) ? $meta[$name][$arr] : '';


		if(empty($current) AND !empty($args['default']))
		{
			$current = $args['default'];
		}

		$mn =  	(!empty($args['min'])) ? ' min="'.$args['min'].'" ' : null;
		$mx =  	(!empty($args['max']))? ' max="'. $args['max'].'" ' : null;
		$st =  	(!empty($args['step']))? ' step="'.$args['step'].'" ' : null;

		$theName = $name.'['.$arr.']';

		$html .=  '<label for="'.$theName.'">'.$label.'</label>' . PHP_EOL;
		$html .=  '<input type="' . $type . '" id="'.$theName.'" name="' . $theName . '" ';
		$html .=  'value="'. $current .'"'. $mn.$mx.$st. ' />' . PHP_EOL;
		$html .=  '<br>'.PHP_EOL;

		return $html;
	}
	//----//----//----//----//----//----//----//----//----//----//----//



	function textarea_html($name, $arr = null, $label = 'Enter Text', $rows = 2)
	{

		$html = '';

		$theName = $name.'['.$arr.']';

		$meta = $this->meta;
		$current = (!empty($meta[$name][$arr])) ? $meta[$name][$arr] : '';

		$html .= '<label for="'.$theName.'">'.$label.'</label>' . PHP_EOL;
		$html .= '<textarea id="'.$theName.'" name="' . $theName . '" rows="'.$rows.'">';
		$html .= $current .'</textarea>' . PHP_EOL;
		$html .= '<br>'.PHP_EOL;

		return $html;
	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function richText_html($name, $arr = 'text', $args = array())
	{
		$arr = ($arr != null) ?  $arr : 'text';

		$rows = (!empty($args['rows'])) ? $args['rows'] : 10;
		$media_btn = (!empty($args['media'])) ? $args['media'] : false;
		$quicktags = (!empty($args['html'])) ? $args['html'] : true;


		$theName = $name.'['.$arr.']';

		$meta = $this->meta;
		$current = (!empty($meta[$name][$arr])) ? $meta[$name][$arr] : '';

	    $settings = array(
                'textarea_rows' => $rows,
                'textarea_name' => $theName,
                'media_buttons' => $media_btn,
                'quicktags' => $quicktags,
                'editor_height' => $rows * 10
	            );

	    if(empty($current))
	    {
	    	 $settings['textarea_rows'] = 1;
	    }

	    wp_editor( $current, $name . '_' . $arr .'_editor', $settings );
	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function cta_button_hmtl($name, $arr=null, $label = 'Button:')
	{
		$html = '';

		if($arr != null)
		{
			$arr = $arr .'_';
		}

		$html .= '<fieldset class="cta_button"><br /><br />';
		$html .= '<legend><h4> '.$label.' </h4></legend>';
		$html .= '<div class="link">'.PHP_EOL;
		$html .= $this->content_selector_html($name, $arr.'link','Link: ');
		$html .= '</div>'.PHP_EOL;
		$html .= '<div class="custom-link">'.PHP_EOL;
		$html .= $this->input_field_html($name, $arr.'custom_link', 'Custom Link (optional):','text');
		$html .= '</div>'.PHP_EOL;
		$html .= '<div class="label">'.PHP_EOL;
		$html .= $this->input_field_html($name, $arr.'label', 'Button label:');
		$html .= '</div>'.PHP_EOL;

		$html .= '</fieldset>';

		return $html;
	}
	//----//----//----//----//----//----//----//----//----//----//----//


	public function custom_content_selector($name, $arr = null, $label = 'Link' ,$select_options)
	{
		$html = '';

		$custom_post_types = $this->custom_post_types;

		$meta = $this->meta;
		$current_page = (!empty($meta[$name][$arr])) ? $meta[$name][$arr] : '';

		$theName = $name.'['.$arr.']';

		$html .= '<label for="'. $theName .'">'. $label . '</label>'.PHP_EOL;
		$html .= '<select id="' . $theName . '" name="' . $theName . '" class="chosen-select">'.PHP_EOL;

		$sel = ( $current_page == '0' ) ? 'selected' : '';
		$html .= '<option value="0" ' . $sel . ' > -- Select -- </option>'.PHP_EOL;

		foreach($select_options as $id => $name)
		{
			$sel = ($current_page == $id ) ? 'selected' : '';
			$html .= '<option value="' . $id .  '" ' . $sel .'>' . $name . '</option>';
		}


		$html .= '</select>'.PHP_EOL;
		$html .= '<br />';

		return $html; 
	}


	public function content_selector_html($name,$arr = null, $label = 'Link', $post_type = 'all', $show = false )
	{
		$html = '';

		$custom_post_types = $this->custom_post_types;

		$meta = $this->meta;
		$current_page = (!empty($meta[$name][$arr])) ? $meta[$name][$arr] : '';

		$theName = $name.'['.$arr.']';

		$html .= '<label for="'. $theName .'">'. $label . '</label>'.PHP_EOL;
		$html .= '<select id="' . $theName . '" name="' . $theName . '" class="chosen-select">'.PHP_EOL;

		$sel = ( $current_page == '0' ) ? 'selected' : '';
		$html .= '<option value="0" ' . $sel . ' > -- Select -- </option>'.PHP_EOL;



		if($post_type == 'all')
		{

			$html .= $this->select_options_pages($current_page);

			$html .= $this->select_options_posts($current_page);

			foreach ($custom_post_types as $type)
			{
				$html .= $this->select_options_posts($current_page, $type);
			}
		}
		elseif($post_type == 'page')
		{
			$html .= $this->select_options_pages($current_page);
		}
		elseif(is_array($post_type))
		{
			foreach ($post_type as $type)
			{	
				if(taxonomy_exists($type))
				{
					$html .= $this->select_options_taxonomy($current_page, $type);
				}
				elseif( in_array($type, $custom_post_types))
				{
					$html .= $this->select_options_posts($current_page, $type);
				}
				elseif( $type == 'page')
				{
					$html .= $this->select_options_pages($current_page);
				}
				elseif( $type == 'post')
				{
					$html .= $this->select_options_posts($current_page, $type);
				}
				
			}
		}
		elseif( in_array( $post_type, $custom_post_types ) OR $post_type == 'post')
		{
			$html .= $this->select_options_posts($current_page, $post_type);
		}
		elseif($post_type == 'cpt' || $post_type == 'post-types' || $post_type == 'custom-post-types')
		{
			foreach($custom_post_types as $type)
			{
				$sel = ($current_page == $type ) ? 'selected' : '';
				$html .= '<option value="' . $type . '" ' . $sel . ' >' . $type . '</option>'.PHP_EOL;
			}
		}
		else
		{
			$html .= '<option style="color: red;">Error: post type not recognised.</option>';
		}



		$html .= '</select>'.PHP_EOL;
		$html .= '<br />';


		if($show != false)
		{
			$selected_page = get_post($current_page, 'ARRAY_A');
			$html .= '<h3>' . $selected_page['post_title'] . '</h3>';
			$html .= '<p>' .get_post_meta($selected_page['ID'], '_yoast_wpseo_metadesc', true) . '</p>';
			$html .= '<p>' . $selected_page['post_excerpt']. '</p>';
		}


		return $html;
	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function select_options_pages($current_id)
	{
		if(!empty($this->cached_pages))
		{
			$pages = $this->cached_pages;
		}
		else 
		{
			$pages = get_pages(array('post_status' => 'publish'));
			$this->cached_pages = $pages;
		}
		

		if(empty($pages) OR !is_array($pages))
		{
			return false;
		}

		$html = '';

		$html .= '<optgroup label="Pages">'.PHP_EOL;

		foreach ($pages as $page)
		{
			$sel = ($current_id == $page->ID ) ? 'selected' : '';
			$html .= '<option value="' . $page->ID . '" ' . $sel . ' >' . $page->post_title . '</option>'.PHP_EOL;
		}

		$html .= '</optgroup>'.PHP_EOL;

		return $html;

	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function select_options_posts($current_id, $cpt = 'post')
	{
		
		if(!empty($this->cached_posts[$cpt]))
		{
			$posts = $this->cached_posts[$cpt];
			error_log('Posts from CACHE : posts_' . $cpt);
		}
		else 
		{
			$posts = get_posts(array('post_type'=> $cpt, 'posts_per_page' => -1));
			$this->cached_posts[$cpt] = $posts;
			error_log('Posts from DB : posts_' . $cpt);
			
		}
		

		//error_log('found posts: ' . sizeof($posts));

		if(empty($posts) OR !is_array($posts))
		{
			return false;
		}

		$html = '';

		$html .= '<optgroup label="' . $cpt . '">'.PHP_EOL;

		foreach ($posts as $page)
		{
			$sel = ($current_id == $page->ID ) ? 'selected' : '';
			$html .= '<option value="' . $page->ID . '" ' . $sel . ' >' . $page->post_title . '</option>'.PHP_EOL;
		}

		 $html .= '</optgroup>'.PHP_EOL;

		return $html;



	}
	//----//----//----//----//----//----//----//----//----//----//----//


	public function select_options_taxonomy($current_id, $taxonomy)
	{	

		

		if(!empty($this->cached_taxonomies))
		{
			$tax = $this->cached_taxonomies;
			error_log('cats from cache');
		}
		else
		{
			error_log('cats NOT cache');
			$tax = get_terms( array(
						'taxonomy' => $taxonomy,
						'hide_empty' => true
				));
			$this->cached_taxonomies = $tax; 
		}
			
			//error_log('tax: ' . print_r($tax, true));

			$html = '';
			$html .= '<optgroup label="' . 'Category' . '">'.PHP_EOL;

			foreach($tax as $term)
			{
				$sel = ($current_id == $term->term_id ) ? 'selected' : '';
				$html .= '<option value="' . $term->term_id .  '" ' . $sel .'>' . $term->name . '</option>';
			}
				
			$html .= '</optgroup>'.PHP_EOL;
		


		return $html;
	}


	public function mediaSelect( $id, $name, $mimetype = '', $label = '', $show = true)
	{
	

		$meta = $this->meta;
		$current_sel = (!empty($meta[$id][$name])) ? $meta[$id][$name] : 0;


		$field_name = $id.'['.$name.']';

		$mime = array();

		if($mimetype == 'pdf')
		{
			$mime[] = 'application/pdf';
			$show = false;
		}
		elseif($mimetype == 'image' OR $mimetype == 'img')
		{
			$mime[] = 'image/png';
			$mime[] = 'image/jpeg';
			$mime[] = 'image/svg+xml';
			$mime[] = 'image/gif';
		}
		elseif($mimetype == 'jpg' )
		{
			$mime[] = 'image/jpeg';
		}
		elseif($mimetype == 'png' )
		{
			$mime[] = 'image/png';
		}
		elseif($mimetype == 'svg' )
		{
			$mime[] = 'image/svg+xml';
		}
		elseif(is_array($mimetype))
		{
			foreach ($mimetype as $mtype)
			{
				if($mtype == 'pdf')
				{
					$mime[] = 'application/pdf';
					$show = false;
				}
				elseif($mtype == 'image' OR $mtype == 'img')
				{
					$mime[] = 'image/png';
					$mime[] = 'image/jpeg';
					$mime[] = 'image/svg+xml';
					$mime[] = 'image/gif';
				}
				elseif($mtype == 'jpg' )
				{
					$mime[] = 'image/jpeg';
				}
				elseif($mtype == 'png' )
				{
					$mime[] = 'image/png';
				}
				elseif($mtype == 'svg' )
				{
					$mime[] = 'image/svg+xml';
				}
			}
		}
		else
		{
			$mime = '';
		}


		$html = '';

		$html .= '<label for="' . $name . '">'. $label . '</label>';
		$html .= '<select id="'.  $id . '-'. $name . '" name="'.  $field_name . '" class="chosen-select img-select">'.PHP_EOL;

		$html .= $this->img_select_options($current_sel, $mime);

		$html .= '</select>'.PHP_EOL;
		$html .=  '<br /><br />';

		if($show == true)
		{
			$the_image = wp_get_attachment_image_src($current_sel, 'switch-home');
			$src = $the_image[0];

			if($current_sel != 0)
			{

				$html .= '<img id="'. $id . '-'. $name .'-image" class="sel-img small"  width="200" src="'. $src .'" />';

			}
			else
			{
				$html .= '<img id="'. $id . '-'. $name .'-image" class="sel-img small"  width="200" src="' . $this->folder_url . 'default.png" />';
			}

			$html .= '<script>' . PHP_EOL;
			$html .= 'var img_url = "";' . PHP_EOL;
			$html .= ' '; 
			$html .= ' jQuery("#'.$id . '-'. $name.'").change( function(){ '  . PHP_EOL;
			$html .= '     var optionSelected = jQuery("option:selected", this); ' . PHP_EOL;
			$html .= '     var newSrc = optionSelected.attr("src");' . PHP_EOL;
			$html .= '     jQuery("#'. $id . '-'. $name .'-image").attr("src", newSrc ).css("opacity", "0.7");' . PHP_EOL;
			$html .= ' }); ' . PHP_EOL;
			$html .= '</script>' . PHP_EOL;
		}


		return $html;


	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function img_select_options($selected, $mime = array('image/png', 'image/jpeg', 'image/svg+xml' ))
	{

	    $Qargs = array ( 'post_type' => 'attachment', 'posts_per_page' => -1, 'orderby' => 'title');

	    if(!empty($mime))
	    {
	       $Qargs['post_mime_type'] = $mime;
	    }
	    
	   
	    
	    if($this->cached_media)
	    {
	    	$media = $this->cached_media;
	    	// error_log('Media from CACHE');
	    }
	    else 
	    {
	    	$media = get_posts( $Qargs );
	    	$this->cached_media = $media; 
	    	// error_log('Media from DB');
	    	//error_log('Cache: ' . print_r($cache, true));
	    }
	    

	  	$html = '';

	    if ( !empty($media) )
	    {

	        $selZ = ($selected == '0' ) ? 'selected' : '';

	        $html .= '<option value="0" '. $selZ .' src="'.$this->folder_url .'default.png" > -- select -- </option>' . PHP_EOL;


	        // -- PNG -----------------------
	        if(in_array('image/png', $mime))
	        {
	            //error_log('adding png');
	            $set = array(
	                'media' =>  $media ,
	                'mime' => 'image/png',
	                'label' => 'png',
	                'current' => $selected
	                );
	        	$html .= $this->img_mime_selects( $set , array('mime' => 'png') ) ;
	        }
	        //-----------------------------------------


	        // -- JPG -----------------------
	        if(in_array('image/jpeg', $mime))
	        {
	            $set = array(
	                'media' =>  $media ,
	                'mime' => 'image/jpeg',
	                'label' => 'jpg',
	                'current' => $selected
	                );
	        	$html .= $this->img_mime_selects( $set,  array('mime' => 'jpeg')  ) ;
	        }
	         //-----------------------------------------


	           // -- JPG -----------------------
	        if(in_array('image/gif', $mime))
	        {
	            $set = array(
	                'media' =>  $media ,
	                'mime' => 'image/gif',
	                'label' => 'gif',
	                'current' => $selected
	                );
	        	$html .= $this->img_mime_selects( $set,  array('mime' => 'gif')  ) ;
	        }
	         //-----------------------------------------


	        // -- SVG -----------------------
	        if(in_array('image/svg+xml', $mime))
	        {
	            $set = array(
	                'media' =>  $media ,
	                'mime' => 'image/svg+xml',
	                'label' => 'svg',
	                'current' => $selected
	                );
	   			$html .= $this->img_mime_selects( $set,  array('mime' => 'svg')  ) ;
	        }
	         //-----------------------------------------


	        // -- PDF -----------------------
	        if(in_array('application/pdf', $mime))
	        {
	            $set = array(
	                'media' =>  $media ,
	                'mime' => 'application/pdf',
	                'label' => 'pdf',
	                'current' => $selected
	                );
	     		$html .= $this->img_mime_selects( $set, array('mime' => 'pdf')  ) ;
	        }
	         //-----------------------------------------
	    }
	    else
	    {
	        $html .= ' No Posts';
	    }
	    // endof if ( !empty($media) )


	    return $html;
	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function img_mime_selects($args = array() )
	{
	    $media   = (!empty($args['media'])) ? $args['media'] : null;
	    $mime    = (!empty($args['mime'])) ? $args['mime'] : null;
	    $label   = (!empty($args['label'])) ? $args['label'] : null;
	    $current = (!empty($args['current'])) ? $args['current'] : null;

	    $html = '';

	    if($mime == 'application/pdf')
	    {
	        $ext = '.pdf';
	    }
	    else
	    {
	        $ext = null;
	    }

	    $options = bb_get_options();
	   

	    if(empty($this->all_img_srcs))
	    {
	    	foreach ($media as $image)
		    {
		    	$id = $image->ID;
		    	
		    	$img_src = wp_get_attachment_image_src($id, 'switch-home');
		    	$img_src = $img_src[0];

		    	$this->all_img_srcs[$id] = $img_src;
		    }
	    }

	    $img_srcs = $this->all_img_srcs;
	    
	    	$bb_img_selects = '';
		    foreach ($media as $image)
		    {
		    	$id = $image->ID;
		    	
		       	$img_src = $img_srcs[$id];
		    
	            $sel = ($current == $id ) ? 'selected' : '';

	            if($image->post_mime_type == $mime)
	            {
	               $bb_img_selects .= '<option value="' . $id . '" ' . $sel . ' src="'.$img_src . '" >' . $image->post_name . $ext . ' | '.$mime.'</option>';
	            }
		    }

		   


	    $html .= '<optgroup label="'.$label.'">'.PHP_EOL;
		$html .= $bb_img_selects;
	    $html .= '</optgroup>'.PHP_EOL;

	    return $html;
	}
	//----//----//----//----//----//----//----//----//----//----//----//



	public function the_gallery($name, $arr, $label)
	{

		$theName = $name.'['.$arr.']';

		$meta = $this->meta;
		$gallery_value = (!empty($meta[$name][$arr])) ? $meta[$name][$arr] : null;

	    $gallery_value = ( !empty($gallery_value ) ) ? $gallery_value : '[gallery ids="0"]';

	    $settings = array(
	                'textarea_rows' => 1,
	                'textarea_name' => $theName,
	                'media_buttons' => false,
	                'quicktags' => false
	            );

	    echo '<label>' . $label . '</label>';
	    echo '<p> Select images to include into the gallery </p>';
	    echo '<div class="bb-gallery mce-toolbar-grp">';
	    wp_editor( $gallery_value, 'the_gallery_editor', $settings );
	    echo '</div>';

	}
	//----//----//----//----//----//----//----//----//----//----//----//




	public function select_form($name,$arr = null, $label = 'Form:')
	{
		$forms = get_posts( array( 'post_type'  => 'wpcf7_contact_form' ) );

		if(!empty($forms))
		{
			$meta = $this->meta;
			$current = (!empty($meta[$name][$arr])) ? $meta[$name][$arr] : 0;

			$theName = $name . '['.$arr .']';


			$html = '';
			$inputId = $name;
			$html .= '<label for="'. $inputId .'">'.$label.'</label>'.PHP_EOL;
			$html .=  '<select id="'. $inputId .'" name="'.$theName.'">'.PHP_EOL;

			$zerosel = ($current == '0' OR empty($current) ) ? 'selected' : '';
			$html .=  '<option value="" ' . $zerosel . ' > -- select a form  -- </option>'.PHP_EOL;


			foreach ($forms as $form)
				{


					$sel = ($current == $form->ID OR empty($current)) ? 'selected' : '';
					$html .=  '<option value="' . $form->ID . '" ' . $sel . ' >';
					$html .=  $form->post_title;
					$html .=  '</option>'.PHP_EOL;

				}
				$html .=  '</select>';

			return $html;
		}
		else
		{
			return '';
		}
	}
	//----//----//----//----//----//----//----//----//----//----//----//





	public function get_the_image($img_id, $opts = array())
	{
		if(empty($img_id))
		{
			return null;
		}

		$width = (!empty($opts['width'])) ? $opts['width'] : null;
		$height = (!empty($opts['height'])) ? $opts['height'] : null;
		$class = (!empty($opts['class'])) ? 'class="' . $opts['class'] . '" ' : null;
		$the_id = (!empty($opts['id'])) ? 'id="' . $opts['id'] . '" ' : null;
		$itemprop = (!empty($opts['itemprop']))? ' itemprop="'. $opts['itemprop'] . '" ' : null;
		$size = (!empty($opts['size']))?  $opts['size'] : null;


		$options = get_option('bigbang');

		$bb_image = array();



		if(!empty($img_id))
		{
			$image_post = get_post($img_id);
			$image_meta = wp_get_attachment_metadata($img_id);
			$the_image = wp_get_attachment_image_src( $img_id, $size );

			if(!empty($image_post->post_mime_type) AND $image_post->post_mime_type == 'image/svg+xml')
			{
				//die('bombasvg');
				$alt = (!empty($image_post->post_title)) ? $image_post->post_title : null;
				//dump($image_post);
				$img_url = $image_post->guid;
				$e = explode('/', $img_url);
				$img_file = end($e);

				if(!empty($options['s3bucket']))
				{
					$img_src = rtrim($options['s3bucket'], '/') . '/' . $img_file;
				}
				else
				{
					$img_src = WP_HOME . '/' . UPLOADS . '/' . $img_file;
				}


				$img_html = '<img ';
				$img_html .= $itemprop;
				$img_html .= 'src="'. $img_src  .'" ';
				$img_html .= 'alt="'. $alt . '" ';

				if($width !== null)
				{
					$img_html .= 'width="'.$width .'" ';
				}

				if($height !== null)
				{
					$img_html .= 'height="'. $height. '" ';
				}

				if(!empty($class))
				{
					$img_html .=  $class;
				}


				$img_html .= ' />';

				$bb_image['html'] = $img_html;
				$bb_image['mime'] = $image_post->post_mime_type;

				return $bb_image;

			}

			if(empty($image_meta) OR !is_array($image_meta) )
			{

				return;
			}



			// ---- get url --------------------------------
			if($size !== null)
			{
				if(!empty($image_meta['sizes'][$size]))
				{
					$file = $image_meta['sizes'][$size]['file'];
				}
				else
				{
					$file = $image_meta['file'];
				}
			}
			else
			{
				$file = $image_meta['file'];
			}


			if(!empty($options['s3bucket']))
			{
				$img_url = rtrim($options['s3bucket'], '/') . '/' . $file;
			}
			else
			{
				$img_url = $the_image[0];
			}

			$bb_image['url'] = $img_url;
			//-----------------------------------------

			$image_sizes = array();

			if(!empty($image_meta['sizes'] ))
			{
				foreach ($image_meta['sizes'] as $key => $value)
				{
					$image_sizes[] = $key;
				}
			}






			// --- get Caption --------

			if(!empty($image_meta["image_meta"]['caption']))
			{
				$image_caption = $image_meta["image_meta"]['caption'];
			}
			elseif(!empty($image_post->post_excerpt))
			{
				$image_caption = $image_post->post_excerpt;
			}
			elseif(!empty($image_post->post_content))
			{
				$image_caption = bb_get_excerpt($img_id);
			}
			else
			{
				$image_caption = null;
			}

			$bb_image['caption'] = $image_caption;
			//----------------------------


			// --- get ALT --------

			$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
			$title = get_the_title($img_id);

			$bb_image['alt'] = (!empty($alt))? $alt : $title;

			if(!empty($opts['alt']))
			{
				$bb_image['alt'] = $opts['alt'];
			}

			//----------------------------------------------




			// Sizes

			// - width provided and height provided
			if($width != null AND $height != null)
			{
					$bb_image['width'] = $width;
					$bb_image['height'] = $height;
			} // - width provided
			elseif($width != null )
			{
				$ratio = $width /  $image_meta['width'];
				$height = round($image_meta['height'] * $ratio);
				$bb_image['width'] = $width;
				$bb_image['height'] = $height;
			}// - height provided
			elseif($height != null )
			{
				$ratio = $height /  $image_meta['height'];
				$width = round($image_meta['width'] * $ratio);
				$bb_image['width'] = $width;
				$bb_image['height'] = $height;
			}
			else
			{
				$bb_image['width'] = $the_image[1];
				$bb_image['height'] = $the_image[2];
			}

			if($width != null AND empty($image_meta['width']) )
			{
				$bb_image['width'] = $width;
			}


			if($height != null AND empty($image_meta['height']))
			{
				$bb_image['height'] = $height;
			}



					//----------------------------------------------


			$img_html = '<img ';
			$img_html .= $the_id;
			$img_html .= $itemprop;
			$img_html .= 'src="'. $img_url  .'" ';
			$img_html .= 'alt="'.$bb_image['alt'] . '" ';
			$img_html .= 'width="'.$bb_image['width'] .'" ';
			$img_html .= 'height="'. $bb_image['height']. '" ';
			$img_html .= $class;

			$img_html .=' />';


			$bb_image['html'] = $img_html;
		}
		else
		{
			$bb_image = array();
		}

		$bb_image['mime'] = $image_post->post_mime_type;
		$bb_image['meta'] = $image_meta;

		$bb_image['sizes'] = $image_sizes;

		return $bb_image;
	}
	//----//----//----//----//----//----//----//----//----//----//----//




	public function user_select($id, $name)
	{
		$theName = $id . '['.$name .']';
		$meta = $this->meta;
		$current = (!empty($meta[$id][$name])) ? $meta[$id][$name] : 0;

		$html = '';
		$html .= 'Current: ' . $current;
		$html .=  '<select id="'. $name .'" name="'.$theName.'">'.PHP_EOL;

		$zerosel = ($current == '0' OR empty($current) ) ? 'selected' : '';
		$html .=  '<option value="0" ' . $zerosel . ' > -- select a user  -- </option>'.PHP_EOL;

		$users = get_users();

		foreach ($users as $user)
		{
			//dump($user);
			$sel = ($current == $user->ID ) ? 'selected="selected"' : '';
			$html .=  '<option value="' . $user->ID . '" ' . $sel . ' >';
			$html .=  $user->display_name;

			if(!empty( $user->user_email ))
			{
				$html .= ' (' . $user->user_email . ')';
			}
		
			$html .=  '</option>'.PHP_EOL;
		}

		$html .=  '</select>';

		return $html;
	}

}
//----//----//----//----//----//----//----//----//----//----//----//
 /*  END of CLASS */
//----//----//----//----//----//----//----//----//----//----//----//
