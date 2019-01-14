<?php
/**
 * Plugin Name:		Taxonomy Image
 * Plugin URI:		https://github.com/themingisprose/taxonomy-image
 * Description:		A simple way to add images to categories, tags and custom taxonomies
 * Version:			1.0
 * Author:			Theming is Prose Team
 * Author URI:		https://themingisprose.com
 * Text Domain:		taxonomy_image
 * Domain Path:		/locale/
 * License:			GPLv2
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Get a list of taxonomies
 * @return array
 *
 * @since Taxonomy Image 1.0
 */
function taxonomy_image_get_taxonomies(){
	$args = array(
		'public'	=> true,
		'show_ui'	=> true,
	);
	return get_taxonomies( $args, 'objects' );
}

/**
 * Initialize hooks for every taxonomy
 *
 * @since Taxonomy Image 1.0
 */
function taxonomy_image_init_hooks(){
	$filters = taxonomy_image_get_taxonomies();
	foreach ( $filters as $filter => $value ) :
		add_action( $filter .'_add_form_fields', 'taxonomy_image_form_fields' );
		add_action( $filter .'_edit_form_fields', 'taxonomy_image_form_fields' );
		add_action( 'create_'. $filter, 'taxonomy_image_save_form_fields' );
		add_action( 'edited_'. $filter, 'taxonomy_image_save_form_fields' );
	endforeach;
}
add_action( 'admin_init', 'taxonomy_image_init_hooks' );

/**
 * Add the forms
 *
 * @since Taxonomy Image 1.0
 */
function taxonomy_image_form_fields( $term ){
	$filters = taxonomy_image_get_taxonomies();

	foreach ( $filters as $filter => $value ) :
		if ( current_filter() == $filter .'_add_form_fields' ) :
?>
	<div class="form-field term-taxonomy-image">
		<label for="term_taxonomy_image"><?php printf( __( '%s Image', 'taxonomy_image' ), $value->labels->singular_name ); ?></label>
		<input id="term_taxonomy_image" class="media-url" type="url" name="term_taxonomy_image" value="">
		<a href="#" class="button media-selector"><?php _e( 'Upload Image', 'taxonomy_image' ) ?></a>
		<p class="description"><?php _e( 'Add image here.','taxonomy_image' ); ?></p>
	</div>
<?php
		elseif ( current_filter() == $filter .'_edit_form_fields' ) :
			$data = get_term_meta( $term->term_id, 'taxonomy_image', true );
?>
	<tr class="form-field term-taxonomy-image">
		<th scope="row"><label for="term_taxonomy_image"><?php printf( __( '%s Image', 'taxonomy_image' ), $value->labels->singular_name ); ?></label></th>
		<td><input id="term_taxonomy_image" class="media-url" name="term_taxonomy_image" type="url" value="<?php echo esc_attr( $data ) ?>" size="40" />
		<a href="#" class="button media-selector"><?php _e( 'Upload Image', 'taxonomy_image' ) ?></a>
		<p class="description"><?php _e( 'Term Image.', 'taxonomy_image' ); ?></p></td>
	</tr>
<?php
		endif;
	endforeach;
}

/**
 * Save the data
 *
 * @since Taxonomy Image 1.0
 */
function taxonomy_image_save_form_fields( $term_id ){
	if ( ! isset( $_POST['term_taxonomy_image'] ) )
		return;

	if ( $_POST['term_taxonomy_image'] != '' ) :
		update_term_meta( $term_id, 'taxonomy_image', sanitize_text_field( $_POST['term_taxonomy_image'] ) );
	else :
		delete_term_meta( $term_id, 'taxonomy_image' );
	endif;
}

/**
 * Enqueue
 *
 * @since Taxonomy Image 1.0
 */
function taxonomy_image_enqueue(){
	wp_enqueue_media();
	wp_register_script( 'taxonomy-image', plugins_url( '/js/taxonomy-image.js', __FILE__ ), array( 'jquery' ) );
	$l10n = array(
		'upload_title'	=> __( 'Select Image', 'taxonomy_image' ),
		'upload_button'	=> __( 'Use selected image', 'taxonomy_image' ),
	);
	wp_localize_script( 'taxonomy-image', 'taxonomy_image_l10n', $l10n );
	wp_enqueue_script( 'taxonomy-image' );
}
add_action( 'admin_enqueue_scripts', 'taxonomy_image_enqueue' );
?>
