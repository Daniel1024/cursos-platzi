<?php
/*
Plugin Name: Simple  Testimonials
Plugin URI: http://1stwebdesigner.com/
Description: Slider Component for WordPress
Version: 1.0
Author: Tacumasoft
Author URI: http://1stwebdesigner
*/

include( dirname( __FILE__ ) . '/widgets/widget-testimonials.php' );

add_action('wp_enqueue_scripts', 'stp_enqueue_scripts');
function stp_enqueue_scripts() {
	wp_register_script('owl-carousel', plugins_url('js/owl.carousel.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('owl-carousel');

	//wp_register_script('testimonials-js', plugins_url('js/simple-testimonials.js', __FILE__), array('jquery'));
	wp_enqueue_script('testimonials-js', plugins_url('js/simple-testimonials.js', __FILE__), array('jquery'), false, true);
	//wp_enqueue_script('testimonials-js');
}

add_action('wp_enqueue_scripts', 'stp_enqueue_styles');
function stp_enqueue_styles(){
	wp_register_style('owl-carousel-style', plugins_url('css/owl.carousel.css', __FILE__));
	wp_enqueue_style('owl-carousel-style');

	wp_register_style('testimonials-style', plugins_url('css/simple-testimonials.css', __FILE__));
	wp_enqueue_style('testimonials-style');

	wp_register_style('animate-style-test', plugins_url('css/animate.css', __FILE__));
	wp_enqueue_style('animate-style-test');
}

add_action('init', 'create_custom_post_type');
function create_custom_post_type(){
	$labels = array(
        'name' => 'Testimonials',
        'singular_name' => 'Testimonial',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Testimonial',
        'edit_item' => 'Edit Testimonial',
        'new_item' => 'New Testimonial',
        'view_item' => 'View Testimonial',
        'search_items' => 'Search Testimonials',
        'not_found' =>  'No Testimonials found',
        'not_found_in_trash' => 'No Testimonials in the trash',
        'parent_item_colon' => '',
    );

	register_post_type('testimonials', array(
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
	    'supports' => array('editor', 'thumbnail'),	 
	    'register_meta_box_cb' => 'testimonials_meta_boxes',   
	));
}

function testimonials_meta_boxes() {
	add_meta_box( 'testimonials_form', 'Testimonial Details', 'testimonials_form', 'testimonials', 'normal', 'high' );
}

function testimonials_form() {
	$post_id = get_the_ID();
	$testimonial_data = get_post_meta( $post_id, '_testimonial', true );
	$client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
	$client_job = ( empty( $testimonial_data['client_job'] ) ) ? '' : $testimonial_data['client_job'];
	$source = ( empty( $testimonial_data['source'] ) ) ? '' : $testimonial_data['source'];
	$link = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];

	wp_nonce_field( 'testimonials', 'testimonials' );
	?>
	<p>
		<label>Client's Name (optional)</label><br />
		<input type="text" value="<?php echo $client_name; ?>" name="testimonial[client_name]" size="40" />
	</p>
	<p>
		<label>Client's Job (optional)</label><br />
		<input type="text" value="<?php echo $client_job; ?>" name="testimonial[client_job]" size="40" />
	</p>
	<p>
		<label>Business/Site Name (optional)</label><br />
		<input type="text" value="<?php echo $source; ?>" name="testimonial[source]" size="40" />
	</p>
	<p>
		<label>Link (optional)</label><br />
		<input type="text" value="<?php echo $link; ?>" name="testimonial[link]" size="40" />
	</p>
	<?php
}

add_action( 'save_post', 'testimonials_save_post' );
/**
 * Data validation and saving
 *
 * This functions is attached to the 'save_post' action hook.
 */
function testimonials_save_post( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( ! empty( $_POST['testimonials'] ) && ! wp_verify_nonce( $_POST['testimonials'], 'testimonials' ) )
		return;

	if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return;
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
	}

	if ( ! wp_is_post_revision( $post_id ) && 'testimonials' == get_post_type( $post_id ) ) {
		remove_action( 'save_post', 'testimonials_save_post' );

		wp_update_post( array(
			'ID' => $post_id,
			'post_title' => 'Testimonial - ' . $post_id
		) );

		add_action( 'save_post', 'testimonials_save_post' );
	}

	if ( ! empty( $_POST['testimonial'] ) ) {
		$testimonial_data['client_name'] = ( empty( $_POST['testimonial']['client_name'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['client_name'] );
		$testimonial_data['client_job'] = ( empty( $_POST['testimonial']['client_job'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['client_job'] );
		$testimonial_data['source'] = ( empty( $_POST['testimonial']['source'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['source'] );
		$testimonial_data['link'] = ( empty( $_POST['testimonial']['link'] ) ) ? '' : esc_url( $_POST['testimonial']['link'] );

		update_post_meta( $post_id, '_testimonial', $testimonial_data );
	} else {
		delete_post_meta( $post_id, '_testimonial' );
	}
}

add_filter( 'manage_edit-testimonials_columns', 'testimonials_edit_columns' );
/**
 * Modifying the list view columns
 *
 * This functions is attached to the 'manage_edit-testimonials_columns' filter hook.
 */
function testimonials_edit_columns($columns) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Title',
		'testimonial' => 'Testimonial Deployment',
		'testimonial-client-name' => 'Client\'s Name',
		'testimonial-client-job' => 'Client\'s Job',
		'testimonial-source' => 'Business/Site',
		'testimonial-link' => 'Link',
		'author' => 'Posted by',
		'date' => 'Date'
	);
	return $columns;
}


add_action( 'manage_posts_custom_column', 'testimonials_columns', 10, 2 );
/**
 * Customizing the list view columns
 *
 * This functions is attached to the 'manage_posts_custom_column' action hook.
 */
function testimonials_columns( $column, $post_id ) {
	$testimonial_data = get_post_meta( $post_id, '_testimonial', true );
	switch ( $column ) {
		case 'testimonial':
			the_excerpt();
			break;
		case 'testimonial-client-name':
			if ( ! empty( $testimonial_data['client_name'] ) )
				echo $testimonial_data['client_name'];
			break;
		case 'testimonial-client-job':
			if ( ! empty( $testimonial_data['client_job'] ) )
				echo $testimonial_data['client_job'];
			break;
		case 'testimonial-source':
			if ( ! empty( $testimonial_data['source'] ) )
				echo $testimonial_data['source'];
			break;
		case 'testimonial-link':
			if ( ! empty( $testimonial_data['link'] ) )
				echo $testimonial_data['link'];
			break;
	}
}

/**
 * Display a testimonial
 *
 * @param	int $post_per_page  The number of testimonials you want to display
 * @param	string $orderby  The order by setting  https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
 * @param	array $testimonial_id  The ID or IDs of the testimonial(s), comma separated
 *
 * @return	string  Formatted HTML
 */

function get_testimonial( $posts_per_page = 1, $orderby = 'none', $testimonial_id = null, $owlc_opts=null) {
	$args = array(
		'posts_per_page' => (int) $posts_per_page,
		'post_type' => 'testimonials',
		'orderby' => $orderby,
		'no_found_rows' => true,
	);
	if ( $testimonial_id )
		$args['post__in'] = array( $testimonial_id );

	if ($owlc_opts){
		
		
		$string = explode(",",$owlc_opts);
		$array = array();
		foreach ($string as $key => $value) {
			list($prop, $val) = explode(":", $value);
			$array[trim($prop)] = trim($val);
		}
		$owlc_args = json_encode($array);
		wp_localize_script('testimonials-js', 'owlc_opts', $array);
	}

	$query = new WP_Query( $args  );

	$testimonials = '<div class="owl-carousel simple-testimonials">';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) : $query->the_post();
			$post_id = get_the_ID();
			$testimonial_data = get_post_meta( $post_id, '_testimonial', true );
			$client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
			$client_job = ( empty( $testimonial_data['client_job'] ) ) ? '' : $testimonial_data['client_job'];
			$source = ( empty( $testimonial_data['source'] ) ) ? '' : ' at '.$testimonial_data['source'];
			$link = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];
			$cite = ( $link ) ? '<a href="' . esc_url( $link ) . '" target="_blank">' . $client_name . $source . '</a>' : $client_name . $source;
			$url_thumb = wp_get_attachment_thumb_url(get_post_thumbnail_id($post_id));
			$testimonials .= '<div class="item testimonial">';
			$testimonials .= '<div class="text"><span>'.get_the_content().'</span></div>';
			$testimonials .= '<div class="meta-info">';
			$testimonials .= '<img class="thumbnail" src="'.$url_thumb.'" />';
			$testimonials .= '<p class="name">'.$client_name.'</p>';
			$testimonials .= '<p class="job">'.$client_job.''.$source.'</p>';
			$testimonials .= '</div>';
			$testimonials .= '</div>';
		endwhile;
		$testimonials .= "</div>";
		wp_reset_postdata();
	}

	return $testimonials;
}

add_shortcode( 'simple-testimonials', 'testimonial_shortcode' );
/**
 * Shortcode to display testimonials
 *
 * This functions is attached to the 'testimonial' action hook.
 *
 * [testimonial posts_per_page="1" orderby="none" testimonial_id=""]
 */
function testimonial_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'posts_per_page' => '1',
		'orderby' => 'none',
		'testimonial_id' => '',
		'owlc_opts' => '',
	), $atts ) );

	return get_testimonial( $posts_per_page, $orderby, $testimonial_id, $owlc_opts);
}



?>