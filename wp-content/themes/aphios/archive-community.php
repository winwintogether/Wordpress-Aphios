<?php

/**
* Community Archive Template
**/


//* Add custom body class to the head
add_filter( 'body_class', 'custom_body_class' );
function custom_body_class( $classes ) {

	$classes[] = 'community-archive-template';

	return $classes;

}

//* Force full width content
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );


//* Reposition Archive Settings
remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );
add_action( 'genesis_after_header', 'genesis_do_cpt_archive_title_description' );



//* Remove genesis detault content loop
remove_action( 'genesis_loop', 'genesis_do_loop' );


// Add custom content loop
add_action( 'genesis_loop', 'taxonomy_loop' );
function taxonomy_loop() {

	$terms = get_terms( 'community_category' );

	if ( $terms ) {
		$i = - 1;
		foreach ( $terms as $term ) {
			$i ++;
			$term_link   = get_term_link( $term );
			$image       = get_field( 'community_category_featured_image', $term);
			$size        = 'feature';
			$cat_image   = wp_get_attachment_image( $image, $size );
			$extra_class = 'one-third';
			$extra_class .= 0 == $i % 3 ? ' first' : '';

			if ( empty( $cat_image ) ) {
				$cat_image = '<img src="' . get_stylesheet_directory_uri() . '/images/Urbanpreneur-DefaultCommunityCategoryImage.jpg" alt="Urbanpreneur - Community Category">';
			}

			echo '<div class="project-category-item ' . $extra_class . '">';
			echo '<a href="' . $term_link . '">';

			echo '<div class="item-image">' . $cat_image . '</div>';

			echo '<div class="item-info">';
			echo '<h3>' . $term->name . '</h3>';
			echo '<button>View Category</button>';
			//echo '< class="button" href="' . esc_url( $term_link ) . '">See Work</a>';
			echo '</div>';

			echo '</a>';
			echo '</div>';

		}


	} // end of if taxonomoy loop has terms

} // end of taxonomy loop


genesis();

