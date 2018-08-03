<?php
/**
 * Template Name: Banner Template
 * Description: Template for Banner
 **/

//* Add custom body class to the head
add_filter('body_class', 'custom_body_class');
function custom_body_class($classes)
{
    if (is_page('client-testimonials')) {
        $classes[] = 'banner-template';
        return $classes;
    } else {
        $classes[] = 'banner-template';
        return $classes;
    }
}

//* Force full width content
//add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/* Add Banner
add_action( 'genesis_after_header', 'banner_template_page_header', 11 );
function banner_template_page_header() {
		$image = get_field('banner_image');
		$size = 'banner'; 
		$banner_image = wp_get_attachment_image( $image, $size );
		if ( empty( $banner_img ) ) {
			$banner_img = '<img src="' . get_stylesheet_directory_uri() . '/images/Urbanpreneur-DefaultBanner.jpg" alt="Urbanpreneur Banner">';
		}
		echo $banner_image;
}
*/

// Add Banner
add_action('genesis_after_header', 'banner_template_page_header', 11);
function banner_template_page_header()
{
    if (have_rows('banner_area')):
        while (have_rows('banner_area')): the_row();
            $image = get_sub_field('banner_image');
            $size = 'banner';
            $banner_image = wp_get_attachment_image($image, $size);
            if (get_field('enable_banner_area') == true):
                if (empty($banner_image)):
                    $banner_image = '<img src="' . get_stylesheet_directory_uri() . '/images/Urbanpreneur-DefaultBanner.jpg" alt="Urbanpreneur Banner">';
                    echo $banner_image;
                else:
                    echo $banner_image;
                endif;
            else:
                $banner_image = '<img src="' . get_stylesheet_directory_uri() . '/images/Urbanpreneur-DefaultBanner.jpg" alt="Urbanpreneur Banner">';
                echo $banner_image;
            endif;
        endwhile;
    endif;
}

/* Add Banner Headline
add_action( 'genesis_after_header', 'banner_template_banner_headline', 12 );
function banner_template_banner_headline() {
	$page_title = get_the_title($posts_page_id);
	$banner_headline = get_field('banner_headline');
	if ( is_front_page() ) {
			echo '<div class="entry-title"><h1>Welcome to Urbanpreneur</h1><h2>' . $banner_headline . '</h2></div>';
	}
	else {
		echo '<div class="entry-title"><h1>' . $page_title . '</h1><h2>' . $banner_headline . '</h2></div>';
	}
}
*/

//* Add Banner Headline
add_action('genesis_after_header', 'banner_template_banner_headline', 12);
function banner_template_banner_headline()
{
    if (have_rows('banner_area')):
        while (have_rows('banner_area')): the_row();
            $banner_headline = get_sub_field('banner_headline');
            $page_title = get_the_title($posts_page_id);
            if (get_field('enable_banner_area') == true) {
                if (is_front_page()) {
                    if (get_sub_field('banner_headline')) {
                        echo '<div class="entry-title"><h1>Welcome to Urbanpreneur</h1><h2>' . $banner_headline . '</h2></div>';
                    } else {
                        echo '<div class="entry-title"><h1 class="style-2">Welcome to Urbanpreneur</h1></div>';
                    }
                } else {
                    if (get_sub_field('banner_headline')) {
                        echo '<div class="entry-title"><h1>' . $page_title . '</h1><h2>' . $banner_headline . '</h2></div>';
                    } else {
                        echo '<div class="entry-title"><h1 class="style-2">' . $page_title . '</h1></div>';
                    }
                }
            } else {
                if (is_front_page()) {
                    echo '<div class="entry-title"><h1 class="style-2">Welcome to Urbanpreneur</h1></div>';
                } else {
                    echo '<div class="entry-title"><h1 class="style-2">' . $page_title . '</h1></div>';
                }
            }
        endwhile;
    endif;
}

//* Remove entry header markup & title
remove_action('genesis_entry_header', 'genesis_entry_header_markup_open', 5);
remove_action('genesis_entry_header', 'genesis_do_post_title');
remove_action('genesis_entry_header', 'genesis_entry_header_markup_close', 15);

//* Add entry header markup & title
add_action('genesis_after_header', 'genesis_entry_header_markup_open', 10);
//add_action( 'genesis_after_header', 'genesis_do_post_title', 11 );
add_action('genesis_after_header', 'genesis_entry_header_markup_close', 13);

/* Add Introductory Box
add_action( 'genesis_entry_header', 'banner_template_introductory_box', 4 );
function banner_template_introductory_box() {
		$intro_headline = get_sub_field('introductory_headline');
		$intro_paragraph = get_sub_field('introductory_paragraph');
		//$intro_separator = '<img src="' . get_stylesheet_directory_uri() . '/images/UP-Graphic-Diamonds.jpg" alt=">';	
		$intro_separator = '<img src="http://urbanpreneur.local/wp-content/uploads/UP-Graphic-Diamonds.png" alt="">';	

		echo '<div class="entry-introduction"><h2>' . $intro_headline . '</h2><p>'. $intro_paragraph . ' </p><span class="separator">'. $intro_separator .'</span></div>';
}
*/

// Add Introductory Box
add_action('genesis_entry_header', 'banner_template_introductory_box', 4);
function banner_template_introductory_box()
{
    if (have_rows('introductory_area')):
        while (have_rows('introductory_area')): the_row();
            $intro_headline = get_sub_field('introductory_headline');
            $intro_paragraph = get_sub_field('introductory_paragraph');
            $intro_separator = '<img src="' . get_stylesheet_directory_uri() . '/images/UP-Graphic-Diamonds.png" alt="">';
            if (get_field('enable_introductory_area') == true):
                if (get_sub_field('introductory_headline') and get_sub_field('introductory_paragraph')):
                    echo '<div class="entry-introduction"><h2>' . $intro_headline . '</h2><p>' . $intro_paragraph . ' </p><span class="separator">' . $intro_separator . '</span></div>';
                endif;
            endif;
        endwhile;
    endif;
}

genesis();
	
