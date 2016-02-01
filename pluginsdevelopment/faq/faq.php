<?php
/* Plugin Name: FAQ Plugin
URI: http://1stwebdesigner.com/
Description: Slider Component for WordPress Version: 1.0
Author: Tacumasoft Author
URI: http://1stwebdesigner */

add_action('wp_enqueue_scripts', 'faq_enqueue_styles');
function faq_enqueue_styles(){
    wp_register_style('faq-style', plugins_url('css/faq-style.css', __FILE__));
    wp_enqueue_style('faq-style');
}

add_action('init', 'create_custom_post_type_faq');
function create_custom_post_type_faq(){
    $labels = array(
        'name' => 'FAQs',
        'singular_name' => 'FAQ',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New FAQ',
        'edit_item' => 'Edit FAQ',
        'new_item' => 'New FAQ',
        'view_item' => 'View FAQ',
        'search_items' => 'Search FAQs',
        'not_found' =>  'No FAQs found',
        'not_found_in_trash' => 'No FAQs in the trash',
        'parent_item_colon' => '',
    );

    register_post_type('FAQs', array(
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
        'supports' => array('title','editor', 'thumbnail'),
//        'register_meta_box_cb' => 'faqs_meta_boxes',
    ));
}

function faqs_meta_boxes() {
    add_meta_box( 'faqs_form', 'FAQ Details', 'faqs_form', 'faqs', 'normal', 'high' );
}



function faqs_form() {
    $post_id = get_the_ID();
    $faq_data = get_post_meta( $post_id, '_faq', true );

    $faq_question = ( empty( $faq_data['question'] ) ) ? '' : $faq_data['question'];
    $faq_answer = ( empty( $faq_data['answer'] ) ) ? '' : $faq_data['answer'];

    wp_nonce_field( 'faqs', 'faqs' );
    ?>
    <p>
        <label>Question (No optional)</label><br />
        <input type="text" value="<?php echo $faq_question; ?>" name="faq_data['question'] " size="120" />
    </p>
    <p>
        <label>Answer (No optional)</label><br />

        <textarea name="faq_data['answer']" cols="120" rows="6" ><?php echo $faq_answer; ?></textarea>
    </p>

    <?php
}


add_filter( 'manage_edit-faqs_columns', 'faqs_edit_columns' );
/**
 * Modifying the list view columns
 *
 * This functions is attached to the 'manage_edit-faqs_columns' filter hook.
 */
function faqs_edit_columns($columns) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'Question',
        'answer' => 'Answer',
        'author' => 'Posted by',
        'date' => 'Date'
    );
    return $columns;
}


add_action( 'manage_posts_custom_column', 'faqs_columns', 10, 2 );
/**
 * Customizing the list view columns
 *
 * This functions is attached to the 'manage_posts_custom_column' action hook.
 */
function faqs_columns( $column, $post_id ) {
    $faq_data = get_post_meta( $post_id, '_faq', true );
    switch ( $column ) {
        case 'Answer':
            the_excerpt();
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

function get_faq( $posts_per_page = 1, $orderby = 'none', $faq_id = null ) {
    $args = array(
        'posts_per_page' => (int) $posts_per_page,
        'post_type' => 'FAQs',
        'orderby' => $orderby,
        'no_found_rows' => true,
    );
    if ( $faq_id )
        $args['post__in'] = array( $faq_id );

    $query = new WP_Query( $args  );

    $faqs = '<div class="wrap-faq">';
    if ( $query->have_posts() ) {
        $faqs .= '<h1 class="FAQ-title">Frequent Questions</h1>';
        /*Block Question*/
        $faqs .= '<div class="block-question">';
        $faqs .= '<ul class="FAQ-list">';

        while ( $query->have_posts() ) : $query->the_post();
            $post_id = get_the_ID();
            $faq_data = get_post_meta( $post_id, '_faq', true );
            $faq_question = ( empty( get_the_title($post_id) ) ) ? '' : get_the_title($post_id);
            //$faq_answer = ( empty( $faq_data['faq_answer'] ) ) ? '' : $faq_data['faq_answer'];


//            $faqs .= '<div class="text"><span>'.get_the_content().'</span></div>';
//            $faqs .= '<div class="meta-info">';
//            $faqs .= '<img class="thumbnail" src="'.$url_thumb.'" />';
            $faqs .= '<li><a href="'."#".$post_id.'">'.$faq_question.'</a></li>';

//            $faqs .= '</div>';
//            $faqs .= '</div>';
        endwhile;
        $faqs .= "</div>";


        /*Block Answer*/
        $faqs .= '<div class="block-answer">';


        while ( $query->have_posts() ) : $query->the_post();
            $post_id = get_the_ID();
            //$faq_data = get_post_meta( $post_id, '_faq', true );
            $faq_question = ( empty( get_the_title($post_id) ) ) ? '' : get_the_title($post_id);
            //$faq_answer = ( empty( $faq_data['faq_answer'] ) ) ? '' : $faq_data['faq_answer'];
            $faq_answer = ( empty( get_the_content() ) ) ? '' : get_the_content();


            $faqs .= '<div class="FAQ-answer" id="'.$post_id.'">';

            $faqs .= '<h1>'.$faq_question.'</h1>';
            $faqs .= '<p>'.$faq_answer.'</p>';
            $faqs .= "</div>";

        endwhile;
        $faqs .= "</div>";
        $faqs .= "</div>";



        wp_reset_postdata();
    }

    return $faqs;
}

add_shortcode( 'faqs', 'faq_shortcode' );
/**
 * Shortcode to display FAQs
 *
 * This functions is attached to the 'FAQ' action hook.
 *
 * [faqs posts_per_page="1" orderby="none" faq_id=""]
 */
function faq_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'posts_per_page' => '1',
        'orderby' => 'none',
        'faq_id' => '',
    ), $atts ) );

    return get_faq( $posts_per_page, $orderby, $faq_id );
}

?>