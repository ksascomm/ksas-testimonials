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
		
		$taxonomies = array('category');
		
		$supports = array('title','editor','revisions', 'thumbnail');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Testimonial'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type'   => 'post',			
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
					'name' 			=> 'Last Name (For Indexing)',
					'desc' 			=> '',
					'id' 			=> 'ecpt_testimonial_alpha',
					'class' 		=> 'ecpt_testimonial_alpha',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
				
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

add_filter( 'manage_edit-testimonial_columns', 'my_edit_testimonial_columns' ) ;
 
function my_edit_testimonial_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
	'title' => __( 'Name' ),
	'testimonialtype' => __( 'Type' ),
	'quote' => __('Excerpt'),
	'thumbnail' => __('Thumbnail'),
	'date' => __( 'Date' ),
  );
 
  return $columns;
}


add_action( 'manage_testimonial_posts_custom_column', 'my_manage_testimonial_columns', 10, 2 );
function my_manage_testimonial_columns($column){
	global $post;

	switch( $column ) {

		/* If displaying the 'role' column. */
		case 'testimonialtype' :

			/* Get the roles for the post. */
			$terms = get_the_terms( $post_id, 'testimonialtype' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'testimonialtype' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'testimonialtype', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'No Type Assigned' );
			}

			break;
		case 'quote' :
			if (get_post_meta($post->ID, 'ecpt_quote', true)) {
					echo get_post_meta($post->ID, 'ecpt_quote', true);
			} else {
				the_excerpt();
			}
		break;
		case 'thumbnail' :
			if ( has_post_thumbnail()) { 
				the_post_thumbnail('directory');
			} else {
				echo __( 'No Photo' );
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

function testimonial_add_taxonomy_filters() {
	global $typenow;

	// An array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array('testimonialtype', 'filter');
 
	// must set this to the post type you want the filter(s) displayed on
	if ( $typenow == 'testimonial' ) {
 
		foreach ( $taxonomies as $tax_slug ) {
			$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			$tax_obj = get_taxonomy( $tax_slug );
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if ( count( $terms ) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>$tax_name</option>";
				foreach ( $terms as $term ) {
					echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";
			}
		}
	}
}

add_action( 'restrict_manage_posts', 'testimonial_add_taxonomy_filters' );

/*************Testimonial Widget*****************/
// Define Testimonial widget
class Testimonial_Widget extends WP_Widget {
	public function __construct() {
		$widget_options = array( 'classname' => 'ksas_testimonial', 'description' => __('Displays a random testimonial', 'ksas_testimonial') );
		$control_options = array( 'width' => 300, 'height' => 350, 'id_base' => 'ksas_testimonial-widget' );
		parent::__construct( 'ksas_testimonial-widget', __('Testimonial', 'ksas_testimonial'), $widget_options, $control_options );
	}


	/* Update/Save the widget settings. */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title']	= isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['category_choice']   = isset( $new_instance['category_choice'] ) ? wp_strip_all_tags( $new_instance['category_choice'] ) : '';
		$instance['random']	= isset( $new_instance['random'] ) ? wp_strip_all_tags( $new_instance['random'] ) : '';
		$instance['age']	= isset( $new_instance['age'] ) ? wp_strip_all_tags( $new_instance['age'] ) : '';
		$instance['link']	= isset( $new_instance['link'] ) ? wp_strip_all_tags( $new_instance['link'] ) : '';

		return $instance;
	}

	/* Widget Options */
	public function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Testimonial', 'ksas_testimonial'), 'category_choice' => '1', 'random' => 'rand', 'link' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<!-- Choose Testimonial Type: Select Box -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category_choice' ) ); ?>"><?php _e('Choose Testimonial Type:', 'ksas_testimonial'); ?></label> 
			<select id="<?php echo esc_attr( $this->get_field_id( 'category_choice' ) ); ?>" name="<?php echo esc_attr($this->get_field_name( 'category_choice' )); ?>" class="widefat" style="width:100%;">
			<?php global $wpdb;
				$categories = get_categories(array(
								'orderby'                  => 'name',
								'order'                    => 'ASC',
								'hide_empty'               => 1,
								'taxonomy' => 'testimonialtype'));
		    foreach($categories as $category) {
		    	$category_choice = $category->slug;
		        $category_title = $category->name; ?>
		       <option value="<?php echo $category_choice; ?>"<?php if ( $category_choice == $instance['category_choice'] ) echo 'selected="selected"'; ?>>
		       		<?php echo $category_title; ?>
				</option>
		    <?php } ?>
			</select>
		</p>

		<!-- Widget Link: Archive Link -->
		<p>
			<label for="<?php echo esc_attr ( $this->get_field_id( 'link' ) ); ?>"><?php _e('Link to Testimonial Type Archive:', 'hybrid'); ?></label>
			<input id="<?php echo esc_attr ( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" value="<?php echo $instance['link']; ?>" style="width:100%;" />
		</p>
		
		<!-- Order: Latest or Random -->
		<p>
			<label for="<?php echo esc_attr ( $this->get_field_id( 'random' ) ); ?>"><?php _e('Order (Latest or Random)', 'ksas_testimonial'); ?></label>
			<select id="<?php echo esc_attr ( $this->get_field_id( 'random' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'random' ) ); ?>" class="widefat" style="width:100%;">
			<option value="date" <?php if ( 'date' === $instance['random'] ) echo 'selected="selected"'; ?>>Latest Only</option>
			<option value="rand" <?php if ( 'rand' === $instance['random'] ) echo 'selected="selected"'; ?>>Random</option>
			</select>
		</p>

	<?php }

	/* Widget Display */
	public function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$category_choice = isset( $instance['category_choice'] ) ? $instance['category_choice'] : '';
		$random = isset( $instance['random'] ) ? $instance['random'] : '';
		$age = isset( $instance['age'] ) ? $instance['age'] : '';
		$archive_link = isset( $instance['link'] ) ? $instance['link'] : '';
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		// Create a new filtering function that will add our where clause to the query
		global $post;
		$testimonial_widget_query = new WP_Query(array(
					'post_type' => 'testimonial',
					'testimonialtype' => $category_choice,
					'orderby' => $random,
					'year' => $age,
					'posts_per_page' => 1));
					
		if ( $testimonial_widget_query->have_posts() ) :  while ($testimonial_widget_query->have_posts()) : $testimonial_widget_query->the_post(); ?>
				<article aria-labelledby="widget-title-<?php the_ID(); ?>">							
						<?php if ( has_post_thumbnail()) { the_post_thumbnail('large',  array('class' => "aligncenter", 'alt' => get_the_title())); } ?>
						<?php if( ! empty( $post->post_title ) ) : ?>
							<h5 class="testimonial-bio-details"><a href="<?php the_permalink(); ?>" id="widget-title-<?php the_ID(); ?>"><?php the_title(); ?><span class="link"></span></a></h5>
						<?php endif;?>

						<?php if ( get_post_meta($post->ID, 'ecpt_job', true) ) : ?>
							<p class="testimonial-job-details"><strong><?php echo get_post_meta($post->ID, 'ecpt_job', true); ?></strong></p>
						<?php endif; ?>

						<?php if ( get_post_meta($post->ID, 'ecpt_internship', true) ) : ?>
							<p class="testimonial-internship-details"><strong><?php echo get_post_meta($post->ID, 'ecpt_internship', true); ?></strong></p>
						<?php endif; ?>

						<p class="testimonial-quote"><?php if(get_post_meta($post->ID, 'ecpt_quote', true)) { echo get_post_meta($post->ID, 'ecpt_quote', true); } else { echo get_the_excerpt(); } ?>
						</p>
				</article>
	<?php endwhile; ?>
		<article aria-label="<?php echo $category_choice ;?> archives">
			<p class="view-more-link"><a href="<?php echo $archive_link;?>">View more Testimonials <span class="fa fa-chevron-circle-right" aria-hidden="true"></span></a></p>
		</article>
	<?php endif; echo $after_widget;
	}

}

//register widgets
add_action('widgets_init', 'ksas_register_testimonial_widgets');
	function ksas_register_testimonial_widgets() {
		register_widget('Testimonial_Widget');
	}

?>