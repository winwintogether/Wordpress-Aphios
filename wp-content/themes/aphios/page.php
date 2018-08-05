<?php
/**
 * Default Page Template
 **/
//* Add custom body class to the head
add_filter('body_class', 'custom_body_class');
function custom_body_class($classes)
{
    if (get_field('enable_slider_area') == true):
        $classes[] = 'slider-template';
        return $classes;
    else:
        $classes[] = 'default-template';
        return $classes;
    endif;
}

//* Force sidebar-content layout setting
add_filter('genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content');

//* Add Slider
add_action('genesis_after_header', 'slider_template_after_header', 6);
function slider_template_after_header()
{
    if (get_field('enable_slider_area') == true):
        if (function_exists('soliloquy')):
            echo '<div class="site-slider">';
            soliloquy('slider', 'slug');
            echo '</div>';
        endif;
    else:
    endif;
}

//* Add Start of Site Background Wrapper
add_action('genesis_after_header', 'start_site_background_wrapper', 8);
function start_site_background_wrapper()
{
    if (get_field('enable_background_image') == true):
        $site_background = get_field('background_image');
        $site_background_size = 'slide';
        $site_background_final = wp_get_attachment_image($site_background, $site_background_size);
        echo '<div class="site-background-image" style="background: url(' . $site_background . ') no-repeat center center fixed;">';
        echo '<div class="site-background-color">';
    endif;
}

//* Add End of Site Background Wrapper
add_action('genesis_after_footer', 'end_site_background_wrapper', 14);
function end_site_background_wrapper()
{
    if (get_field('enable_background_image') == true):
        echo '</div>';
        echo '</div>';
    endif;
}

//* Add Site Section Header
add_action('genesis_after_header', 'default_template_page_header', 12);
function default_template_page_header()
{
    global $post;
    if (get_field('enable_slider_area') == false):
        if (is_front_page()):
            echo '<div class="site-section-header"><div class="entry-title"><h1>Welcome to Aphios</h1><h2>' . $banner_headline . '</h2></div></div>';
        elseif ($post->post_parent):
            $parent_title = get_the_title($post->post_parent);
            echo '<div class="site-section-header"><div class="entry-title"><h1>' . $parent_title . '</h1></div></div>';
        else:
            $page_title = get_the_title($posts_page_id);
            echo '<div class="site-section-header"><div class="entry-title"><h1>' . $page_title . '</h1></div></div>';
        endif;
    endif;
}

//* Update Entry Header
remove_action('genesis_entry_header', 'genesis_do_post_title');
add_action('genesis_entry_header', 'default_template_secondary_page_header', 6);
function default_template_secondary_page_header()
{
    $page_title = get_the_title($posts_page_id);
    if (is_front_page()):
        echo '<div class="entry-title" itemprop="headline"><h1>Welcome to Aphios</h1><h2>' . $banner_headline . '</h2></div>';
    else:
        echo '<div class="entry-title" itemprop="headline"><h1>' . $page_title . '</h1></div>';
    endif;
}

genesis();

