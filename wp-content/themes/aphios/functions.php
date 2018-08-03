<?php
/**
 * @package Aphios
 * @author  Adaptive Web Studio
 * @link    http://www.adaptivewebstudio.com/
 */
//* Start the engine
include_once(get_template_directory() . '/lib/init.php');
//* Setup Theme
include_once(get_stylesheet_directory() . '/lib/theme-defaults.php');
//* Set Localization (do not remove)
load_child_theme_textdomain('genesis-sample', apply_filters('child_theme_textdomain', get_stylesheet_directory() . '/languages', 'genesis-sample'));
//* Add Image upload and Color select to WordPress Theme Customizer
require_once(get_stylesheet_directory() . '/lib/customize.php');
//* Include Customizer CSS
include_once(get_stylesheet_directory() . '/lib/output.php');

//* Child theme (do not remove)
define('CHILD_THEME_NAME', 'Aphios');
define('CHILD_THEME_URL', 'https://www.aphios.com/');
define('CHILD_THEME_VERSION', '1.0');
//* Enqueue Scripts and Styles
add_action('wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles');
function genesis_sample_enqueue_scripts_styles()
{
    //Google Fonts
    wp_enqueue_style('genesis-fonts', 'https://fonts.googleapis.com/css?family=Nunito+Sans:400,600,700|Oswald:200,300,400', array(), CHILD_THEME_VERSION);
    // Dashicons
    wp_enqueue_style('dashicons');
    // Font Awesome
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css');
    // Accordion Content
    wp_enqueue_script('accordion-content', get_stylesheet_directory_uri() . '/js/accordion-content.js', array('jquery'), '1.0.0', true);
}

// Header & Navs
///////////////////////////////////////////////////////////////////////////////////////////////////
//* Add support for custom header
add_theme_support('custom-header', array(
    'width' => 140,
    'height' => 40,
    'header-selector' => '.site-title a',
    'header-text' => false,
    'flex-height' => true,
));
//* Unregister primary/secondary navigation menus
remove_theme_support('genesis-menus');
/* Add responsive menu
add_action( 'genesis_header_right', 'responsive_menu_pro', 9 );
function responsive_menu_pro() {

	echo do_shortcode('[responsive_menu_pro]'); 
}*/


// Imagery
///////////////////////////////////////////////////////////////////////////////////////////////////
// Add Theme Support for Thumbnails
add_theme_support('post-thumbnails');
//* Add Custom Image Sizes
add_image_size('slide', 1600, 600, true);
add_image_size('banner', 1600, 500, array('left', 'top')); // Hard crop left top
add_image_size('feature', 800, 600, true);
add_image_size('featured-content', 600, 380, array('center', 'center'));
add_image_size('large', 1024, 9999, false);
add_image_size('medium-large', 800, 9999, false);
add_image_size('medium', 600, 9999, false);
add_image_size('small-large', 400, 9999, false);
add_image_size('small', 200, 9999, false);

// Register the three useful image sizes for use in Add Media modal
add_filter('image_size_names_choose', 'add_custom_image_sizes');
function add_custom_image_sizes($sizes)
{
    return array_merge($sizes, array(
        'small' => __('Small'),
        'small-large' => __('Small Large'),
        'medium-large' => __('Medium Large'),
        'featured-content' => __('Featured Content'),
    ));
}

//* Modify size of the Gravatar in the author box
add_filter('genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar');
function genesis_sample_author_box_gravatar($size)
{
    return 90;
}

//* Modify size of the Gravatar in the entry comments
add_filter('genesis_comment_list_args', 'genesis_sample_comments_gravatar');
function genesis_sample_comments_gravatar($args)
{
    $args['avatar_size'] = 60;
    return $args;
}

// Custom Post Types
///////////////////////////////////////////////////////////////////////////////////////////////////
//* Add Archive Settings option to Projects CPT
add_post_type_support('projects', 'genesis-cpt-archives-settings');
if (function_exists('acf_add_options_page')) {
    acf_add_options_sub_page(array(
        'page_title' => 'Archive Projects Banner',
        'menu_title' => 'Archive Projects Banner',
        'parent_slug' => 'edit.php?post_type=projects',
    ));
    acf_add_options_sub_page(array(
        'page_title' => 'Archive Team Banner',
        'menu_title' => 'Archive Team Banner',
        'parent_slug' => 'edit.php?post_type=team',
    ));
}

// Flexible Content Areas
///////////////////////////////////////////////////////////////////////////////////////////////////
//* Add flexible content
add_action('genesis_entry_content', 'aphios_partials');
function aphios_partials()
{
    if (is_page() || is_single()):
        if (have_rows('flexible_content')):
            while (have_rows('flexible_content')) : the_row();
                switch (get_row_layout()) {
                    case 'header_style_1':
                        echo '<div class="header_style_1 clear"><h3>';
                        the_sub_field('header_1');
                        echo '</h3></div>';
                        break;
                    case 'header_style_2':
                        echo '<div class="header_style_2 clear"><h3><span>';
                        the_sub_field('header_2');
                        echo '</span></h3></div>';
                        break;
                    case 'sub_header':
                        echo '<div class="section-sub-header clear"><h6>';
                        the_sub_field('sub_header');
                        echo '</h6></div>';
                        break;
                    case 'full_width':
                        get_template_part('partials/full-width', 'full-width');
                        break;
                    case '2_column':
                        get_template_part('partials/2-column', '2-column');
                        break;
                    case '2_column_reverse':
                        get_template_part('partials/2-column-reverse', '2-column-reverse');
                        break;
                    case '3_column':
                        get_template_part('partials/2-column', '3-column');
                        break;
                    case '4_column':
                        get_template_part('partials/2-column', '4-column');
                        break;
                    case 'section_divider':
                        switch (get_sub_field('divider_type')) {
                            case 'small-space':
                                echo '<span class="small-space"></span>';
                                break;
                            case 'medium-space':
                                echo '<span class="medium-space"></span>';
                                break;
                            case 'large-space':
                                echo '<span class="medium-space"></span>';
                                break;
                            case 'line':
                                echo '<span class="divider"><hr></span>';
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'accordion_content':
                        get_template_part('partials/accordion-content', 'accordion-content');
                        break;
                    case 'single_testimonial':
                        get_template_part('partials/single-testimonial', 'single-testimonial');
                        break;
                    case 'multiple_testimonials':
                        get_template_part('partials/multiple-testimonials', 'multiple-testimonials');
                        break;
                    case 'message':
                        get_template_part('partials/message', 'message');
                        break;
                    case 'content_sidebar':
                        get_template_part('partials/content-sidebar', 'content-sidebar');
                        break;
                    case 'sidebar_content':
                        get_template_part('partials/sidebar-content', 'sidebar-content');
                        break;
                    case 'featured_snippets_2_column':
                        get_template_part('partials/featured-snippets-2-column', 'featured-snippets-2-column');
                        break;
                    case 'featured_content':
                        get_template_part('partials/featured-content', 'featured-content');
                        break;
                    default:
                        break;
                }
            endwhile;
        else :
        endif;
    endif;
}

/* Add specialty content
add_action ( 'genesis_before_footer', 'aphios_specialty_content', 6);
function aphios_specialty_content(){
if ( is_page() ):
	if( have_rows('specialty_content') ):
		while ( have_rows('specialty_content') ) : the_row();
			if( get_row_layout() == 'specialty_content_type' ):
				if( get_sub_field('choices') == 'call_to_action' ):
					genesis_widget_area( 'cta-widget', array(
					'before' => '<div class="cta-widget">',
					'after'  => '</div>',
					) );
				endif;
				if( get_sub_field('choices') == 'newsletter_signup' ):

					genesis_widget_area( 'newsletter-widget', array(
					'before' => '<div class="newsletter-widget">',
					'after'  => '</div>',
					) );
				endif;
				if( get_sub_field('choices') == 'affiliates' ):

					genesis_widget_area( 'affiliate-widget', array(
					'before' => '<div class="affiliate-widget">',
					'after'  => '</div>',
					) );
				endif;
		endif;
		if( get_row_layout() == 'testimonial_section' ):
					echo '<div class="site-testimonial">';
						echo '<div class="wrap">';
						echo '<h3>What Our Clients Say</h3>';
						echo '<p>'.get_sub_field('testimonial_section_quote').'</p>';
						echo '<h6>'.get_sub_field('testimonial_section_name').'</h6>';
						echo '</div>';
					echo '</div>';
					//get_template_part('partials/testimonial-section', 'testimonial-section');
		endif;
	endwhile;
	else :
	// no layouts found
	endif;
endif;
}
*/
/* Add call to action
add_action ( 'genesis_before_footer', 'aphios_cta', 6);
function aphios_cta(){
	genesis_widget_area( 'cta-widget', array(
	'before' => '<div class="cta-widget">',
	'after'  => '</div>',
	) );
}
*/
// Add Call To Action Area
add_action('genesis_before_footer', 'aphios_call_to_action', 6);
function aphios_call_to_action()
{
    if (get_field('enable_call_to_action_area') == true):
        if (get_field('call_to_action_area') == 'call_to_action_1'):
            genesis_widget_area('cta-widget', array(
                'before' => '<div class="cta-widget">',
                'after' => '</div>',
            ));
        endif;
        if (get_field('call_to_action_area') == 'call_to_action_2'):
            genesis_widget_area('cta-widget-2', array(
                'before' => '<div class="cta-widget">',
                'after' => '</div>',
            ));
        endif;
    endif;
}

// Widgets
///////////////////////////////////////////////////////////////////////////////////////////////////
//* Register Footer Widgets
genesis_register_sidebar(array(
    'id' => 'footer-widget-area-1',
    'name' => __('Footer Widget Area 1'),
    'description' => __('This is a widget area for the footer area.'),
));
genesis_register_sidebar(array(
    'id' => 'footer-widget-area-2',
    'name' => __('Footer Widget Area 2', 'base'),
    'description' => __('This is a widget area for the footer area.'),
));
/*
genesis_register_sidebar( array(
	'id'          => 'footer-widget-area-3',
	'name'        => __( 'Footer Widget Area 3' ),
	'description' => __( 'This is a widget area for the footer area.' ),
) );
genesis_register_sidebar( array(
	'id'          => 'footer-widget-area-4',
	'name'        => __( 'Footer Widget Area 4' ),
	'description' => __( 'This is a widget area for the footer area.' ),
) );
genesis_register_sidebar( array(
	'id'          => 'footer-widget-area-5',
	'name'        => __( 'Footer Widget Area 5' ),
	'description' => __( 'This is a widget area for the footer area.' ),
) );
*/
//* Register Call To Action Widget 1
genesis_register_sidebar(array(
    'id' => 'cta-widget',
    'name' => __('Call To Action Widget'),
    'description' => __('This is the widget area for the first call to action area.'),
));
//* Register Call To Action Widget 2
genesis_register_sidebar(array(
    'id' => 'cta-widget-2',
    'name' => __('Call To Action Widget 2'),
    'description' => __('This is the widget area for the second call to action.'),
));
//* Register Call To Action Widget 2
genesis_register_sidebar(array(
    'id' => 'cta-widget-2',
    'name' => __('Call To Action Widget 2'),
    'description' => __('This is the widget area for the second call to action.'),
));
//* Register Newsletter Widget
genesis_register_sidebar(array(
    'id' => 'newsletter-widget',
    'name' => __('Newsletter Widget'),
    'description' => __('This is the widget area for the newsletter area.'),
));


// WP Editor
///////////////////////////////////////////////////////////////////////////////////////////////////
//* * Customize TinyMCE's configuration
add_filter('tiny_mce_before_init', 'configure_tinymce');
function configure_tinymce($in)
{
    $in['paste_preprocess'] = "function(plugin, args){
    // Strip all HTML tags except those we have whitelisted
    var whitelist = 'p,span,b,strong,i,em,h3,h4,h5,h6,ul,li,ol';
    var stripped = jQuery('<div>' + args.content + '</div>');
    var els = stripped.find('*').not(whitelist);
    for (var i = els.length - 1; i >= 0; i--) {
      var e = els[i];
      jQuery(e).replaceWith(e.innerHTML);
    }
    // Strip all class and id attributes
    stripped.find('*').removeAttr('id').removeAttr('class');
    // Return the clean HTML
    args.content = stripped.html();
  }";
    return $in;
}


// Footer
///////////////////////////////////////////////////////////////////////////////////////////////////
//* Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter($creds)
{
    $creds = '[footer_copyright] Aphios';
    return $creds;
}

//* Add Footer Widget Areas (3 colums)
add_action('genesis_before_footer', 'footer_widget_areas', 6);
function footer_widget_areas()
{
    echo '<div class="site-footer-widgets"><div class="wrap">';
    echo '<div class="one-fourth first">';
    dynamic_sidebar('footer-widget-area-1');
    echo '</div>';
    echo '<div class="three-fourths">';
    dynamic_sidebar('footer-widget-area-2');
    echo '</div>';
    echo '<div class="clear"></div>';
    echo '</div>'; //end of section
}

/* Add Footer Widget Areas (5 columns)
add_action( 'genesis_before_footer', 'footer_widget_areas', 12 );
function footer_widget_areas() {
echo '<div class="site-footer-widgets">';
echo '<div class="one-fifth first">';
dynamic_sidebar( 'footer-widget-area-1');
echo '</div>';
echo '<div class="one-fifth">';
dynamic_sidebar( 'footer-widget-area-2');
echo '</div>';
echo '<div class="one-fifth">';
dynamic_sidebar( 'footer-widget-area-3');
echo '</div>';
echo '<div class="one-fifth">';
dynamic_sidebar( 'footer-widget-area-4');
echo '</div>';
echo '<div class="one-fifth">';
dynamic_sidebar( 'footer-widget-area-5');
echo '</div>';
echo '<div class="clear"></div>';
echo '</div>'; //end of section

}
*/

// Soliloquy
///////////////////////////////////////////////////////////////////////////////////////////////////
//* Add white label to Soliloquy Slider
add_filter('gettext', 'tgm_soliloquy_whitelabel', 10, 3);
function tgm_soliloquy_whitelabel($translated_text, $source_text, $domain)
{
    // If not in the admin, return the default string.
    if (!is_admin()) {
        return $translated_text;
    }
    if (strpos($source_text, 'Soliloquy Slider') !== false) {
        return str_replace('Soliloquy Slider', 'Slider', $translated_text);
    }
    if (strpos($source_text, 'Soliloquy Sliders') !== false) {
        return str_replace('Soliloquy Sliders', 'Sliders', $translated_text);
    }
    if (strpos($source_text, 'Soliloquy slider') !== false) {
        return str_replace('Soliloquy slider', 'slider', $translated_text);
    }
    if (strpos($source_text, 'Soliloquy') !== false) {
        return str_replace('Soliloquy', 'Slider', $translated_text);
    }
    return $translated_text;
}

// Breadcrumb Modifications
///////////////////////////////////////////////////////////////////////////////////////////////////
//* Modify breadcrumb arguments.
add_filter('genesis_breadcrumb_args', 'sp_breadcrumb_args');
function sp_breadcrumb_args($args)
{
    $args['home'] = 'Home'; //<i class="fa fa-home fa-lg"></i></a>
    $args['sep'] = ' / ';
    $args['list_sep'] = ', '; // Genesis 1.5 and later
    $args['prefix'] = '<div class="breadcrumb">';
    $args['suffix'] = '</div>';
    $args['heirarchial_attachments'] = true; // Genesis 1.5 and later
    $args['heirarchial_categories'] = true; // Genesis 1.5 and later
    $args['display'] = true;
    $args['labels']['prefix'] = '';
    $args['labels']['author'] = 'Archives for ';
    $args['labels']['category'] = ''; // Genesis 1.6 and later
    $args['labels']['tag'] = '';
    $args['labels']['date'] = '';
    $args['labels']['search'] = 'Search for ';
    $args['labels']['tax'] = '';
    $args['labels']['post_type'] = '';
    $args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later
    return $args;
}

//* Rewriting community permalink structure to include taxonomy
add_filter('post_type_link', 'wpa_show_permalinks', 1, 2);
function wpa_show_permalinks($post_link, $post)
{
    if (is_object($post) && $post->post_type == 'community') {
        $terms = wp_get_object_terms($post->ID, 'community_category');
        if ($terms) {
            return str_replace('%community_category%', $terms[0]->slug, $post_link);
        }
    }
    return $post_link;
}

//* Add CPT Archive Link to its Taxonomy Page
add_filter('genesis_tax_crumb', 'tax_community_breadcrumb', 10, 2);
function tax_community_breadcrumb($crumb, $args)
{
    if (is_tax())
        return '<a href="' . get_post_type_archive_link('community') . '">Community</a>' . $args['sep'] . ' ' . $crumb;
    else
        return $crumb;
}

//* Add Category Breadcrumb to Single Projects
add_filter('genesis_single_crumb', 'single_community_breadcrumb', 10, 2);
function single_community_breadcrumb($crumb, $args)
{
    // Only modify the breadcrumb if in the 'projects' post type
    if ('community' !== get_post_type())
        return $crumb;
    // Grab terms
    $terms = get_the_terms(get_the_ID(), 'community_category');
    if (empty($terms) || is_wp_error($terms))
        return $crumb;
    // Only use one term
    $term = array_shift($terms);
    // Build the breadcrumb
    $crumb = '<a href="' . get_post_type_archive_link('community') . '">Community</a>' . $args['sep'] . '<a href="' . get_term_link($term, 'community_category') . '">' . $term->name . '</a>' . $args['sep'] . get_the_title();
    return $crumb;
}

// Removing Elements
///////////////////////////////////////////////////////////////////////////////////////////////////
// Removing admin tabs
function remove_menus()
{
    remove_menu_page('edit.php');                   //Posts
    remove_menu_page('edit-comments.php');          //Comments
    remove_menu_page('admin.php?page=responsive-menu-pro');        // Responsive Mennu Pro
    //remove_menu_page( 'themes.php' );                 //Appearance
    //remove_menu_page( 'plugins.php' );                //Plugins
    //remove_menu_page( 'users.php' );                  //Users
    //remove_menu_page( 'tools.php' );                  //Tools
    //remove_menu_page( 'options-general.php' );        //Settings
}

add_action('admin_menu', 'remove_menus');

/* Hide ACF WP Menu Item
add_filter('acf/settings/show_admin', '__return_false');
*/
/* Unregister content/sidebar/sidebar layout setting
genesis_unregister_layout( 'content-sidebar-sidebar' );
*/
//* Unregister sidebar/sidebar/content layout setting
genesis_unregister_layout('sidebar-sidebar-content');
//* Unregister sidebar/content/sidebar layout setting
genesis_unregister_layout('sidebar-content-sidebar');
//* Unregister secondary sidebar
unregister_sidebar('sidebar-alt');

// Misc
///////////////////////////////////////////////////////////////////////////////////////////////////
//* Add HTML5 markup structure
add_theme_support('html5', array('caption', 'comment-form', 'comment-list', 'gallery', 'search-form'));
//* Add Accessibility support
add_theme_support('genesis-accessibility', array('404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links'));
//* Add viewport meta tag for mobile browsers
add_theme_support('genesis-responsive-viewport');
/** Remove Edit Link */
add_filter('edit_post_link', '__return_false');
//* Remove unused genesis templates
function be_remove_genesis_page_templates($page_templates)
{
    unset($page_templates['page_archive.php']);
    unset($page_templates['page_blog.php']);
    return $page_templates;
}

add_filter('theme_page_templates', 'be_remove_genesis_page_templates');
//* Remove the post info function
remove_action('genesis_entry_header', 'genesis_post_info', 12);
//* Remove the post meta function
remove_action('genesis_entry_footer', 'genesis_post_meta');
//* Remove the post entry footer
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_open', 5);
remove_action('genesis_entry_footer', 'genesis_entry_footer_markup_close', 15);




