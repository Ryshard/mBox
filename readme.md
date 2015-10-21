**********************************
Mbox Class - Instructions
=============================================


Installation
----------------------------

1. Copy 'metaboxes' folder to the root of the theme
2. Include 'switch.php' in main functions.php



The Switch
----------------------------

Use switch.php to control metaboxes sets depending on different pages.
You can differentiate meta box sets and have specyfic set for:
	- single post (default)
	- single custom post (custom post type)
	- homepage
	- blog index page
	- normal page (using default template)
	- page with custom template

When certain criteria have been met switch with include partial php file - depending of what page is being edited.
Page specyfic templates should be created inside 'boxes' folder.
You can name them as you want - you need to include them manually in proper place of switch.php

Switch.php also contains some general metaboxes definitions for every page. 

Mbox Class has been initialised in the switch.php as:

	$page_set = new mBoxSet();




Adding Metaboxes
----------------------------

Using $page_set object you can add as many metaboxes as you want. 

First you add a metabox itself:
	
	$page_set->add_metabox( $args );

	$args = array(
			'id' => 'headline' ,
			'title' => 'Headline',
			'priority' => 'high', 
			'context' => 'side'
			);

'id'       - unique id of the metabox - mandatory
'title'    - Metabox Title - optional (Default 'Metabox')
'priority' - Metabox position in relation to toher metaboxes - optional (high|low)
'context'  - side - in the sidebar below 'save button' 
		   - normal - in the main content area (default)



Adding Fields
----------------------------

Once added metabox, you can add fields to it.

	$page_set->add_field( $metabox_id, $args);

	$args = array(
				'type'  => 'text',
				'name'  => 'heading'
				'label' => '',
				));

$metabox_id - unique id of the metabox you wish to add this field to. 
'type' - field type (available types below)
'name' - unique id of that field
'label' - optional label for the field


Field Types
----------------------------


 * Text

Standard text field

 	$args = array(
		'type'  => 'text',
		'name'  => 'unique_name'
		'label' => '',
		));

----------------------------

 * email 

Standard text field with validation type of 'email'

 	$args = array(
		'type'  => 'email',
		'name'  => 'unique_name'
		'label' => '',
		));

----------------------------



 * url

Standard text field with validation type of 'url'

 	$args = array(
		'type'  => 'email',
		'name'  => 'unique_name'
		'label' => '',
		));

----------------------------


 * date

Standard text field with validation type of 'date'

 	$args = array(
		'type'  => 'date',
		'name'  => 'unique_name'
		'label' => '',
		));

----------------------------

 * number

Standard text field with validation type of 'number'

 	$args = array(
		'type'  => 'number',
		'name'  => 'unique_name'
		'label' => '',
		'min' => 1,
		'max' => 60,
		'step' => 1,
		'default' => 1
		));

----------------------------

 * rich

Generates Wordpress Rich text editor (TinyMCE)

 	$args = array(
		'type'  => 'rich',
		'name'  => 'unique_name'
		'label' => '',
		'rows' => 8,
		'html' => 60,
		'media' => 1
		));

		'html' - true or false - if code view should be enabled
		'media' - true or false - if media add button should be enabled

----------------------------


 * textarea

Standard Textarea field with specified number of rows

    $args = array(
		'type'  => 'textarea',
		'name'  => 'unique_name'
		'label' => '',
		'rows' => 8
		));

----------------------------


 * image

Select Box - Select media
Seves media ID in DB.

    $args = array(
		'type'  => 'image',
		'name'  => 'unique_name'
		'label' => '',
		'mime' => 'jpg'
		'show' => true
		));

'mime' - optional - by default this fields let you choose from all the available media.
		you can limit that to specyfic mime types
		'jpg'|'png'|'svg'|'pdf'
		it can also be an array of mime types
		array('jpg','svg')

'show' - true or false (default true)
Either display selected image or not.

----------------------------


 * user

Select box - registered users

 	$args = array(
		'type'  => 'user',
		'name'  => 'unique_name'
		'label' => '',
		));

----------------------------


 * cta_btn

Displays set of fields for button
- content selector - for link
- text field - for custom link
- text field - for button Label
		
		$args = array(
		'type'  => 'cta_btn',
		'name'  => 'unique_name'
		));
----------------------------

 * info

Displays static information text

 	$args = array(
		'type'  => 'cta_btn',
		'name'  => 'unique_name',
		'info' => 'This is sample descriptive Text',
		'hr' => true
		));

'info' - text to display
'hr'  - true / false  - either to dirplay <hr> aftrt info text (default: true)

----------------------------

 * form

Works with Contact Form 7
Select box - list of available CF7 forms
Saves form ID in the DB

 	$args = array(
		'type'  => 'form',
		'name'  => 'unique_name'
		'label' => '',
		));

----------------------------


 * script

Inserts custom script into html

    <script>{your-script}</script>

 	$args = array(
		'type'  => 'script',
		'name'  => 'unique_name'
		'script' => '$("#button").click(function(){ .....',

		));

----------------------------

 * hidden

Inserts hidden field into the metabox

    $args = array(
		'type'  => 'hidden',
		'name'  => 'unique_name'
		'value' => 'special-value',

		));

----------------------------


 * content_select

Select Box - list of all available pages and posts
Saves ID in Database.

$args = array(
		'type'  => 'content_select',
		'name'  => 'unique_name'
		'label' => '',
		'post_type' => 'post'
		));

'post_type' - by default displays all pages and posts

can be limited to specyfic types:
    'all'(default) | 'page' | 'post' | 'custom-post-type'

can be an array of posts
	array('page', 'custom-post-type')

----------------------------



 * gallery

Displays gallery selector/editor. Saves an array of id's

$args = array(
		'type'  => 'gallery',
		'name'  => 'unique_name'
		'label' => '',
		));

----------------------------



 * map

Displays fields necessary for displaying google static map
- text field 'longitiude'
- text field 'latitude' 
- number field 'zoom'

- google maps static image based on above

        $args = array(
            'type'  => 'map',
            'name'  => 'unique_name'
            'label' => '',
            ));

----------------------------


