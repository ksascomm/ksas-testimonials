<?php
/*
Plugin Name: KSAS Testimonials
Plugin URI: https://github.com/ksascomm/ksas_testimonials
Description: Creates a custom post type for alumni or internship testimonials.  
Version: 1.0
Author: Tim Gelles
Author URI: mailto:tgelles@jhu.edu
License: GPL2
*/
// registration code for testimonials post type
	function register_testimonials_posttype() {
		$labels = array(
			'name' 				=> _x( 'Testimonials', 'post type general name' ),
			'singular_name'		=> _x( 'Testimonial', 'post type singular name' ),
			'add_new' 			=> _x( 'Add New', 'Testimonial'),
			'add_new_item' 		=> __( 'Add New Testimonial '),
			'edit_item' 		=> __( 'Edit Testimonial '),
			'new_item' 			=> __( 'New Testimonial '),
			'view_item' 		=> __( 'View Testimonial '),
			'search_items' 		=> __( 'Search Testimonials '),
			'not_found' 		=>  __( 'No Testimonial found' ),
			'not_found_in_trash'=> __( 'No Testimonials found in Trash' ),
			'parent_item_colon' => ''
		);
		
		$taxonomies = array('testimonialtype');
		
		$supports = array('title','editor','revisions', 'thumbnail');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Testimonial'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type'   => 'testimonial',
			'capabilities' => array(
				'publish_posts' => 'publish_testimonials',
				'edit_posts' => 'edit_testimonials',
				'edit_others_posts' => 'edit_others_testimonials',
				'delete_posts' => 'delete_testimonials',
				'delete_others_posts' => 'delete_others_testimonials',
				'read_private_posts' => 'read_private_testimonials',
				'edit_post' => 'edit_testimonial',
				'delete_post' => 'delete_testimonial',
				'read_post' => 'read_testimonial',),			
			'has_archive' 		=> false,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'testimonial', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies
		 );
		 register_post_type('testimonial',$post_type_args);
	}
	add_action('init', 'register_testimonials_posttype');

// registration code for testimonialtype taxonomy
function register_testimonialtype_tax() {
	$labels = array(
		'name' 					=> _x( 'Testimonial Types', 'taxonomy general name' ),
		'singular_name' 		=> _x( 'Testimonial Type', 'taxonomy singular name' ),
		'add_new' 				=> _x( 'Add New Testimonial Type', 'Testimonial Type'),
		'add_new_item' 			=> __( 'Add New Testimonial Type' ),
		'edit_item' 			=> __( 'Edit Testimonial Type' ),
		'new_item' 				=> __( 'New Testimonial Type' ),
		'view_item' 			=> __( 'View Testimonial Type' ),
		'search_items' 			=> __( 'Search Testimonial Types' ),
		'not_found' 			=> __( 'No Testimonial Type found' ),
		'not_found_in_trash' 	=> __( 'No Testimonial Type found in Trash' ),
	);
	
	$pages = array('testimonial');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> __('Testimonial Type'),
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> false,
		'show_in_nav_menus' => false,
		'rewrite' 			=> array('slug' => 'testimonialtype', 'with_front' => false ),
	 );
	register_taxonomy('testimonialtype', $pages, $args);
}
add_action('init', 'register_testimonialtype_tax');

function check_testimonialtype_terms(){
 
        // see if we already have populated any terms
    $term = get_terms( 'testimonialtype', array( 'hide_empty' => false ) );
 
    // if no terms then lets add our terms
    if( empty( $term ) ){
        $terms = define_testimonialtype_terms();
        foreach( $terms as $term ){
            if( !term_exists( $term['name'], 'testimonialtype' ) ){
                wp_insert_term( $term['name'], 'testimonialtype', array( 'slug' => $term['slug'] ) );
            }
        }
    }
}

add_action( 'init', 'check_testimonialtype_terms' );

function define_testimonialtype_terms(){
 
$terms = array(
		'0' => array( 'name' => 'internship','slug' => 'internship-testimonial'),
		'1' => array( 'name' => 'alumni','slug' => 'alumni-testimonial'),
		);
 
    return $terms;
}
//Add testimonial details metabox
$testimonialdetails_1_metabox = array( 
	'id' => 'testimonialdetails',
	'title' => 'Testimonial Details',
	'page' => array('testimonial'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Class of',
					'desc' 			=> '',
					'id' 			=> 'ecpt_class',
					'class' 		=> 'ecpt_class',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std' => ''													
				),
															
				array(
					'name' 			=> 'Internship Location',
					'desc' 			=> '(if applicable)',
					'id' 			=> 'ecpt_internship',
					'class' 		=> 'ecpt_internship',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std' => ''												
				),

				array(
					'name' 			=> 'Current Job',
					'desc' 			=> '(if applicable)',
					'id' 			=> 'ecpt_job',
					'class' 		=> 'ecpt_job',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std' => ''													
				),
															
				array(
					'name' 			=> 'Excerpt/Pullquote',
					'desc' 			=> '',
					'id' 			=> 'ecpt_quote',
					'class' 		=> 'ecpt_quote',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std' => ''													
				),
												)
);			
			
add_action('admin_menu', 'ecpt_add_testimonialdetails_1_meta_box');
function ecpt_add_testimonialdetails_1_meta_box() {

	global $testimonialdetails_1_metabox;		

	foreach($testimonialdetails_1_metabox['page'] as $page) {
		add_meta_box($testimonialdetails_1_metabox['id'], $testimonialdetails_1_metabox['title'], 'ecpt_show_testimonialdetails_1_box', $page, 'normal', 'default', $testimonialdetails_1_metabox);
	}
}

// function to show meta boxes
function ecpt_show_testimonialdetails_1_box()	{
	global $post;
	global $testimonialdetails_1_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_testimonialdetails_1_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($testimonialdetails_1_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', $field['desc'];
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
					if($wp_version >= 3.3) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false));
					} else {
						// older versions of WP
						$editor = '';
						if(!post_type_supports($post->post_type, 'editor')) {
							$editor = wp_tiny_mce(true, array('editor_selector' => $field['class'], 'remove_linebreaks' => false) );
						}
						$field_html = '<div style="width: 97%; border: 1px solid #DFDFDF;"><textarea name="' . $field['id'] . '" class="' . $field['class'] . '" id="' . $field['id'] . '" cols="60" rows="8" style="width:100%">'. $meta . '</textarea></div><br/>' . __($field['desc']);
						echo $editor . $field_html;
					}
				} else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', $field['desc'];				
				}
				
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

add_action('save_post', 'ecpt_testimonialdetails_1_save');

// Save data from meta box
function ecpt_testimonialdetails_1_save($post_id) {
	global $post;
	global $testimonialdetails_1_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_testimonialdetails_1_meta_box_nonce']) ||!wp_verify_nonce($_POST['ecpt_testimonialdetails_1_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	foreach ($testimonialdetails_1_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
//Configuring Admin Columns - in View all testimonial
add_action("manage_posts_custom_column",  "testimonial_custom_columns");
add_filter("manage_edit-testimonial_columns", "testimonial_edit_columns");
 
function testimonial_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Name",
    "testimonialtype" => "Testimonial Type",
  );
 
  return $columns;
}
function testimonial_custom_columns($column){
  global $post;
 
  switch ($column) {
  
  case "testimonialtype":
      echo get_the_term_list($post->ID, 'testimonialtype', '', ', ','');
      break;
  }
}

?>