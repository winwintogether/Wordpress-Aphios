<?php
/**
 * Community Single Template
 */

//* Add custom body class to the head
add_filter( 'body_class', 'custom_body_class' );
function custom_body_class( $classes ) {
	$classes[] = 'community-single-template';

	return $classes;
}

//* Force full width content
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//* Remove the post info function
remove_action( 'genesis_before_post_content', 'genesis_post_info' );

//* Remove the post meta function
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

/* Previous and Next Post navigation
add_action( 'genesis_entry_content', 'sk_custom_post_nav', 20 );
function sk_custom_post_nav() {
	global $post;
	$terms     = array_shift( get_the_terms( $post->ID, 'community_category' ) );
	$args      = array(
		'posts_per_page'   => - 1,
		'post_type'        => $post->post_type,
		'project_category' => $terms->slug
	);
	$post_list = get_posts( $args );
	// get ids of posts retrieved from get_posts
	$ids = array();
	foreach ( $post_list as $post_one ) {
		$ids[] = $post_one->ID;
	}
	// get and echo previous and next post in the same taxonomy
	$index   = array_search( $post->ID, $ids );
	$prev_id = $ids[ $index - 1 ];
	$next_id = $ids[ $index + 1 ];

	echo '<div class="prev-next-post-links clear">';
	echo '<div class="previous-post-link" style="float:left">';
	if ( $index != 0 ):
		echo '<a href="' . get_permalink( $prev_id ) . '">&laquo; Previous</a>';		
	endif;
	echo '</div>';

	echo '<div class="next-post-link" style="float:right">';
	if ( $index != ( count( $ids ) - 1 ) ):
		echo '<a href="' . get_permalink( $next_id ) . '">Next &raquo;</a>';
	endif;
	echo '</div>';
	echo '</div>';
}
*/

genesis();
