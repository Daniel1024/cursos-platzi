<?php
/*
Plugin Name: Simple Slider
Plugin URI: http://1stwebdesigner.com/
Description: Slider Component for WordPress
Version: 1.0
Author: Tacumasoft
Author URI: http://1stwebdesigner
*/

//include( dirname( __FILE__ ) . '/widgets/widget-testimonials.php' );

add_action('wp_enqueue_scripts', 'ss_enqueue_scripts');
function ss_enqueue_scripts() {
	wp_register_script('owl-carousel', plugins_url('js/owl.carousel.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('owl-carousel');

	wp_enqueue_script('simple-slider-js', plugins_url('js/simple-slider.js', __FILE__), array('jquery'), false, true);
}



add_action('wp_enqueue_scripts', 'ss_enqueue_styles');
function ss_enqueue_styles(){
	
	wp_register_style('owl-carousel-style', plugins_url('css/owl.carousel.css', __FILE__));
	wp_enqueue_style('owl-carousel-style');

	wp_register_style('simple-slider-style', plugins_url('css/simple-slider.css', __FILE__));
	wp_enqueue_style('simple-slider-style');

	wp_register_style('animate-style', plugins_url('css/animate.css', __FILE__));
	wp_enqueue_style('animate-style');
	
}

/*
add_action('init', 'ss_create_custom_post_type');
function ss_create_custom_post_type(){
	$labels = array(
        'name' => 'Slides',
        'singular_name' => 'Slider',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Slider',
        'edit_item' => 'Edit Slider',
        'new_item' => 'New Slider',
        'view_item' => 'View Slider',
        'search_items' => 'Search Slider',
        'not_found' =>  'No Slider found',
        'not_found_in_trash' => 'No Slider in the trash',
        'parent_item_colon' => '',
    );

	register_post_type('slides', array(
		'labels' => $labels,		
		'public' => true,
		'publicly_queryable' => true,
	    'show_ui' => true,
	    'exclude_from_search' => true,
	    'query_var' => true,
	    'rewrite' => true,
	    'capability_type' => 'post',
	    'has_archive' => true,
	    'hierarchical' => false,
	    'menu_position' => 10,
	    'supports' => array('title', 'editor', 'thumbnail'),	 
	    'register_meta_box_cb' => 'ss_meta_boxes',   
	));
}*/

/*function ss_meta_boxes() {
	add_meta_box( 'ss_form', 'Extra Info', 'ss_form', 'slides', 'normal', 'high' );
}

function ss_form() {
	$post_id = get_the_ID();
	$slide_data = get_post_meta( $post_id, '_slide', true );
	$link = ( empty( $slide_data['link'] ) ) ? '' : $slide_data['link'];

	wp_nonce_field( 'slides', 'slides' );
	?>
	<p>
		<label>Link (optional)</label><br />
		<input type="text" value="<?php echo $link; ?>" name="slide[link]" size="110" />
	</p>
	<?php
}*/

//add_action( 'save_post', 'ss_save_post' );
/**
 * Data validation and saving
 *
 * This functions is attached to the 'save_post' action hook.
 */
/*function ss_save_post( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( ! empty( $_POST['slides'] ) && ! wp_verify_nonce( $_POST['slides'], 'slides' ) )
		return;

	if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return;
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
	}

	if ( ! wp_is_post_revision( $post_id ) && 'slides' == get_post_type( $post_id ) ) {
		remove_action( 'save_post', 'ss_save_post' );

		wp_update_post( array(
			'ID' => $post_id			
		) );

		add_action( 'save_post', 'ss_save_post' );
	}

	if ( ! empty( $_POST['slide'] ) ) {		
		$slide_data['link'] = ( empty( $_POST['slide']['link'] ) ) ? '' : esc_url( $_POST['slide']['link'] );

		update_post_meta( $post_id, '_slide', $slide_data );
	} else {
		delete_post_meta( $post_id, '_slide' );
	}
}*/

//add_filter( 'manage_edit-slides_columns', 'ss_edit_columns' );
/**
 * Modifying the list view columns
 *
 * This functions is attached to the 'manage_edit-testimonials_columns' filter hook.
 */
/*function ss_edit_columns($columns) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Title',
		'text' => 'Text',
		'link' => 'Link',
		'date' => 'Date'
	);
	return $columns;
}*/


//add_action( 'manage_posts_custom_column', 'ss_columns', 10, 2 );
/**
 * Customizing the list view columns
 *
 * This functions is attached to the 'manage_posts_custom_column' action hook.
 */
/*function ss_columns( $column, $post_id ) {	
	$slide_data = get_post_meta( $post_id, '_slide', true );
	switch ( $column ) {
		case 'text':
			the_excerpt();
			break;
		case 'link':
			if ( ! empty( $slide_data['link'] ) )
				echo $slide_data['link'];
			break;
	}
}*/

/**
 * Display a Slide
 *
 * @param	int $post_per_page  The number of slides you want to display
 * @param	string $orderby  The order by setting  https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
 * @param	array $slide_id  The ID or IDs of the testimonial(s), comma separated
 *
 * @return	string  Formatted HTML
 */

function ss_get_slide( $posts_per_page = 1, $orderby = 'none', $post_id = null, $owlc_opts=null) {
	$args = array(
		'posts_per_page' => (int) $posts_per_page,
		'post_type' => 'post',
		'orderby' => $orderby,
		'no_found_rows' => true,
	);

	if ( $post_id )
		$args['post__in'] = array( $post_id );

	if ($owlc_opts){		
		$owlc_args = json_decode($owlc_opts, true);
		wp_localize_script('simple-slider-js', 'owlc_opts', $owlc_args);
	}

	$query = new WP_Query( $args  );

	$slides = '<div class="owl-carousel simple-slider">';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) : $query->the_post();
			$post_id = get_the_ID();
			$link = get_permalink($post_id);			
			$url_thumb = wp_get_attachment_thumb_url(get_post_thumbnail_id($post_id));
			$slides .= '<div class="item slide"><div class="content-text">';
			$slides .= '<h2 class="slide-title">'.get_the_title().'</h2>';
			$slides .= '<div class="slide-desc">'.get_the_excerpt().'<a class="read-more" href="'.$link.'">Leer Mas</a></div>';
			$slides .= '</div></div>';			
		endwhile;
		$slides .= "</div>";
		wp_reset_postdata();
	}	
	return $slides;
}

add_shortcode( 'simple-slider', 'ss_shortcode' );
/**
 * Shortcode to display slides
 *
 * This functions is attached to the 'slide' action hook.
 *
 * [simple-slider posts_per_page="1" orderby="none" slide_id=""]
 */
function ss_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'posts_per_page' => '1',
		'orderby' => 'none',
		'post_id' => '',
		'owlc_opts' => '',
	), $atts ) );

	return ss_get_slide($posts_per_page, $orderby, $post_id, $owlc_opts);
}

?>