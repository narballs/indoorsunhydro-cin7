<?php
/*
 *  Author: Ryan Dimaculangan
 *  URL: https://www.upwork.com/freelancers/~01ede5472b30508b53
 *  Custom functions,support,custom post types and more.
 */
/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/
require_once(get_stylesheet_directory().'/includes/product-markups/product-markups.php');
//require_once(get_stylesheet_directory().'/includes/bcc-email/bcc-email.php');
require_once(get_stylesheet_directory().'/includes/add-to-cart-ajax/add-to-cart-ajax.php');
/*------------------------------------*\
	Theme Support
\*------------------------------------*/
if (!isset($content_width))
{
	$content_width = 900;
}
if (function_exists('add_theme_support'))
{
	// Add Menu Support
	add_theme_support('menus');
	// Add Thumbnail Theme Support
	add_theme_support('post-thumbnails');
	add_image_size('large', 700, '', true); // Large Thumbnail
	add_image_size('medium', 250, '', true); // Medium Thumbnail
	add_image_size('small', 120, '', true); // Small Thumbnail
	add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');
	// Add Support for Custom Backgrounds - Uncomment below if you're going to use
	/*add_theme_support('custom-background', array(
	'default-color' => 'FFF',
	'default-image' => get_template_directory_uri() . '/img/bg.jpg'
	));*/
	// Add Support for Custom Header - Uncomment below if you're going to use
	/*add_theme_support('custom-header', array(
	'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
	'header-text'			=> false,
	'default-text-color'		=> '000',
	'width'				=> 1000,
	'height'			=> 198,
	'random-default'		=> false,
	'wp-head-callback'		=> $wphead_cb,
	'admin-head-callback'		=> $adminhead_cb,
	'admin-preview-callback'	=> $adminpreview_cb
	));*/
	// Enables post and comment RSS feed links to head
	add_theme_support('automatic-feed-links');
	// Localisation Support
	load_theme_textdomain('crazydev', get_template_directory() . '/languages');
}

function mytheme_add_woocommerce_support() { 
	global $wp;
	$url = add_query_arg( $wp->query_vars, home_url() );  

	if (strpos($url, 'weekly-specials') !== false) {
	    add_theme_support( 'woocommerce' );
	}     
}

add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

/*------------------------------------*\
	Functions
\*------------------------------------*/
// HTML5 Blank navigation
function crazydev_nav()
{
	wp_nav_menu(
	array(
		'theme_location'	=> 'header-menu',
		'menu'			=> '',
		'container'		=> 'div',
		'container_class'	=> 'menu-{menu slug}-container',
		'container_id'		=> '',
		'menu_class'		=> 'menu',
		'menu_id'		=> '',
		'echo'			=> true,
		'fallback_cb'		=> 'wp_page_menu',
		'before'		=> '',
		'after'			=> '',
		'link_before'		=> '',
		'link_after'		=> '',
		'items_wrap'		=> '<ul class="menu">%3$s</ul>',
		'depth'			=> 0,
		'walker'		=> ''
		)
	);
}
// Load HTML5 Blank scripts (header.php)
function crazydev_header_scripts()
{
	if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
		wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // Conditionizr
		wp_enqueue_script('conditionizr'); // Enqueue it!
		wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
		wp_enqueue_script('modernizr'); // Enqueue it!
		wp_register_script('crazydevscripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.0'); // Custom scripts
		wp_enqueue_script('crazydevscripts'); // Enqueue it!
        wp_register_script('landing-scripts', get_template_directory_uri() . '/js/landing-scripts.js', array('jquery'), '1.0.0'); // Landing scripts
		wp_enqueue_script('landing-scripts'); // Enqueue it!
		//wp_register_script('fixed-item', get_template_directory_uri() . '/js/fixed-item.js', array('jquery'), '1.0.0'); // Landing scripts
		//wp_enqueue_script('fixed-item'); // Enqueue it!
		wp_enqueue_style('google-fonts','//fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800|Open+Sans:400,700');
	}
    //wp_enqueue_style('wp-color-picker');
    //wp_enqueue_script('wp-color-picker');
    //wp_enqueue_script('wp-color-picker-script-handle', plugins_url('wp-color-picker-script.js',__FILE__ ), array('wp-color-picker'), false, true );
}
// Load HTML5 Blank conditional scripts
function crazydev_conditional_scripts()
{
	if (is_page('pagenamehere')) {
		wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
		wp_enqueue_script('scriptname'); // Enqueue it!
	}
}
// Load HTML5 Blank styles
function crazydev_styles()
{
	wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
	wp_enqueue_style('normalize'); // Enqueue it!
}
// Register HTML5 Blank Navigation
function register_crazydev_menu()
{
	register_nav_menus(array( // Using array to specify more menus if needed
		'header-menu' => __('Header Menu', 'crazydev'),
		'footer-menu' => __('Sidebar Menu', 'crazydev')
	));
}
// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
	$args['container'] = false;
	return $args;
}
// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
	return is_array($var) ? array() : '';
}
// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
	return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}
// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
	global $post;
	if (is_home()) {
		$key = array_search('blog', $classes);
		if ($key > -1) {
			unset($classes[$key]);
		}
	} elseif (is_page()) {
		$classes[] = sanitize_html_class($post->post_name);
	} elseif (is_singular()) {
		$classes[] = sanitize_html_class($post->post_name);
	}
	return $classes;
}
// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
	register_sidebar(array(
		'name' => __('Header Right', 'crazydev'),
		'id' => 'header-right',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __('Sidebar', 'crazydev'),
		'id' => 'widget-area-1',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __('Footer Widgets 1', 'crazydev'),
		'description' => __('', 'crazydev'),
		'id' => 'footer-widgets-1',
		'before_widget' => '<div id="%1$s" class="%2$s footer-widgets footer-widgets-1">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __('Footer Widgets 2', 'crazydev'),
		'description' => __('', 'crazydev'),
		'id' => 'footer-widgets-2',
		'before_widget' => '<div id="%1$s" class="%2$s footer-widgets footer-widgets-2">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __('Footer Widgets 3', 'crazydev'),
		'description' => __('', 'crazydev'),
		'id' => 'footer-widgets-3',
		'before_widget' => '<div id="%1$s" class="%2$s footer-widgets footer-widgets-3">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __('Footer Widgets 4', 'crazydev'),
		'description' => __('', 'crazydev'),
		'id' => 'footer-widgets-4',
		'before_widget' => '<div id="%1$s" class="%2$s footer-widgets footer-widgets-4">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
}
// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
	global $wp_widget_factory;
	remove_action('wp_head', array(
		$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
		'recent_comments_style'
	));
}
// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function crazydevwp_pagination()
{
	global $wp_query;
	$big = 999999999;
	echo paginate_links(array(
		'base' => str_replace($big, '%#%', get_pagenum_link($big)),
		'format' => '?paged=%#%',
		'current' => max(1, get_query_var('paged')),
		'total' => $wp_query->max_num_pages
	));
}
// Custom Excerpts
function crazydevwp_index($length) // Create 20 Word Callback for Index page Excerpts, call using crazydevwp_excerpt('crazydevwp_index');
{
	return 20;
}
// Create 40 Word Callback for Custom Post Excerpts, call using crazydevwp_excerpt('crazydevwp_custom_post');
function crazydevwp_custom_post($length)
{
	return 40;
}
// Create the Custom Excerpts callback
function crazydevwp_excerpt($length_callback = '', $more_callback = '')
{
	global $post;
	if (function_exists($length_callback)) {
		add_filter('excerpt_length', $length_callback);
	}
	if (function_exists($more_callback)) {
		add_filter('excerpt_more', $more_callback);
	}
	$output = get_the_excerpt();
	$output = apply_filters('wptexturize', $output);
	$output = apply_filters('convert_chars', $output);
	$output = '<p>' . $output . '</p>';
	echo $output;
}
// Custom View Article link to Post
function crazydev_blank_view_article($more)
{
	global $post;
	return '...';
}
// Remove 'text/css' from our enqueued stylesheet
function crazydev_style_remove($tag)
{
	return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}
// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
	$html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
	return $html;
}
// Custom Gravatar in Settings > Discussion
function crazydevgravatar ($avatar_defaults)
{
	$myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
	$avatar_defaults[$myavatar] = "Custom Gravatar";
	return $avatar_defaults;
}
// Threaded Comments
function enable_threaded_comments()
{
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
			wp_enqueue_script('comment-reply');
		}
	}
}
// Custom Comments Callback
function crazydevcomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<!-- heads up: starting < for the html tag (li or div) in the next line: -->
	<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>
	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>
	<?php comment_text() ?>
	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }
/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/
// Add Actions
add_action('init', 'crazydev_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'crazydev_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'crazydev_styles'); // Add Theme Stylesheet
add_action('init', 'register_crazydev_menu'); // Add HTML5 Blank Menu
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'crazydevwp_pagination'); // Add our HTML5 Pagination
// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
// Add Filters
add_filter('avatar_defaults', 'crazydevgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'crazydev_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('style_loader_tag', 'crazydev_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images
// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether
// Shortcode.php
// add_action( 'woocommerce_review_order_before_shipping', 'add_ship_info',5 );
 
//      function add_ship_info() {
//       echo '<span>HELLO</span>' 
//       ;
// }

function remove_extra_br_p($content) {
	$array = array(
		'<p>['    => '[',
		']</p>'   => ']',
		']<br />' => ']'
	);
	return strtr($content, $array);
}
add_filter('the_content','remove_extra_br_p');
add_image_size('sp-thumb',210,340,TRUE);
add_image_size('bs-thumb',370,300,TRUE);
add_image_size('na-thumb',350,360,TRUE);
function staff_picks_sc(){
	ob_start();
	query_posts(array(
		'posts_per_page' => 2,
        'post_type' => 'product_variation',
        'meta_key' => '_checkbox_staff-picks',
        'meta_value' => 'yes',
        'orderby' => 'rand'
	));
	echo '<div id="staff-picks-list" class="wrap clear">';
		while ( have_posts() ) { the_post();
			global $product;
			$id = get_the_ID();
			$price = get_post_meta( get_the_ID(), '_regular_price', true);
            $var_parent = wc_get_product( $product->get_parent_id() );
			$brand = $var_parent->get_attribute( 'pa_brand' );

			$size = 'full';
            $thumbnail = get_the_post_thumbnail( $product->get_id(), $size );

            if ( empty($thumbnail) || strpos($thumbnail, 'NoImage') ) {
                $thumbnail = get_the_post_thumbnail( $var_parent->get_id(), $size );
            }

			//$sold_in_quantities = get_post_meta( $id, '_variable_sold_in_quantities_of', true );
			$sold_in_quantities = 1;

            $title = get_post_meta($product->get_id(), '_variable_title');

			echo '<div class="sp-products"><a class="img-wrapper" href="'.get_the_permalink().'">';
                echo $thumbnail;
				echo '</a><div class="sp-products-text">';
					echo '<h4>'.wp_trim_words($title[0], 4, ' ...').'</h4>';
					echo '<p class="brand">'.$brand.'</p>';
					echo '<p class="price">$'.number_format($price, 2, '.', '').'</p>';
					 echo apply_filters( 'woocommerce_loop_add_to_cart_link',
					 	sprintf( '<a href="%s" data-quantity="%s" data-product_id="'.$id.'" class="%s c-add-button product_type_simple add_to_cart_button ajax_add_to_cart" %s>%s</a>',
					 	esc_url( $product->add_to_cart_url() ),
					 	esc_attr( $sold_in_quantities ),
					 	esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
					 	isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
					 esc_html( $product->add_to_cart_text())),$product,$args);
				echo '</div>';
			echo '</div>';
		}
	echo '</div>';
	wp_reset_query();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('staff-picks','staff_picks_sc');
function best_seller_sc(){
	ob_start();
	query_posts(array(
		'posts_per_page' => 3,
        'post_type' => 'product_variation',
        'meta_key' => '_checkbox_best-sellers',
        'meta_value' => 'yes',
        'orderby' => 'rand'
	));
	echo '<div id="best-seller-list" class="wrap clear">';
		while ( have_posts() ) { the_post();
			global $product;
			$id = get_the_ID();
			$price = get_post_meta( get_the_ID(), '_regular_price', true);
            $var_parent = wc_get_product( $product->get_parent_id() );

            $size = 'full';
            $thumbnail = get_the_post_thumbnail( $product->get_id(), $size );

            if ( empty($thumbnail) || strpos($thumbnail, 'NoImage') ) {
                $thumbnail = get_the_post_thumbnail( $var_parent->get_id(), $size );
            }

			$title = get_post_meta($product->get_id(), '_variable_title');

			echo '<div class="bs-products">';
				echo '<div class="ba-products-img"><a href="'.get_the_permalink().'">';
                echo $thumbnail;
				echo '</a></div>';
				echo '<div class="ba-products-text">';
					echo '<h4>'.wp_trim_words($title[0], 4, ' ...').'</h4>';
					echo '<p class="price">$'.number_format($price, 2, '.', '').'</p>';
				echo '</div>';
			echo '</div>';
		}
	echo '</div>';
	wp_reset_query();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('best-seller','best_seller_sc');
function new_arrivals_sc(){
	ob_start();
	query_posts(array(
		'posts_per_page' => 4,
        'post_type' => 'product_variation',
        'meta_key' => '_checkbox_new-arrivals',
        'meta_value' => 'yes',
        'orderby' => 'rand'
	));
	echo '<div id="new-arrivals-list" class="wrap clear">';
		while ( have_posts() ) { the_post();
			global $product;
			$id = get_the_ID();
			$price = get_post_meta( get_the_ID(), '_regular_price', true);
            $var_parent = wc_get_product( $product->get_parent_id() );

            $size = 'full';
            $thumbnail = get_the_post_thumbnail( $product->get_id(), $size );

            if ( empty($thumbnail) || strpos($thumbnail, 'NoImage') ) {
                $thumbnail = get_the_post_thumbnail( $var_parent->get_id(), $size );
            }

			//$sold_in_quantities = get_post_meta( $id, '_variable_sold_in_quantities_of', true );
			$sold_in_quantities = 1;

            $title = get_post_meta($product->get_id(), '_variable_title');

			echo '<div class="na-products">';
				echo '<div class="na-products-img"><a href="'.get_the_permalink().'">';
                echo $thumbnail;
				echo '</a></div>';
				echo '<div class="na-products-text">';
					echo apply_filters( 'woocommerce_loop_add_to_cart_link',
					sprintf( '<a href="%s" data-quantity="%s" data-product_id="'.$id.'" class="%s product_type_simple add_to_cart_button ajax_add_to_cart" %s>%s</a>',
					esc_url( $product->add_to_cart_url() ),
					esc_attr( $sold_in_quantities ),
					esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
					isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
					esc_html( $product->add_to_cart_text())),$product,$args);
					echo '<h4>'.wp_trim_words($title[0], 10, ' ...').'</h4>';
					echo '<p class="price">$'.number_format($price, 2, '.', '').'</p>';
				echo '</div>';
			echo '</div>';
		}
	echo '</div>';
	wp_reset_query();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('new-arrivals','new_arrivals_sc');

function product_description_heading() {
    return '';
}
add_filter('woocommerce_product_additional_information_heading','product_description_heading');
add_filter('woocommerce_product_description_heading','product_description_heading');
add_role('vip','VIP Members', array( 'read' => true, 'edit_posts' => true ) );
add_action( 'woocommerce_product_options_pricing', 'bbloomer_add_MSRP_to_products' );

function bbloomer_add_MSRP_to_products() {
    woocommerce_wp_text_input( array(
        'id' => 'msrp',
        'class' => 'short wc_input_price',
        'label' => __( 'MSRP', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
        'data_type' => 'price',
        )
    );
    woocommerce_wp_text_input( array(
        'id' => 'dprice',
        'class' => 'short wc_input_price',
        'label' => __( 'Display Price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
        'data_type' => 'price',
        )
    );
}
add_action( 'save_post', 'bbloomer_save_MSRP' );

function bbloomer_save_MSRP( $product_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;
    if ( isset( $_POST['msrp'] ) ) {
        if ( is_numeric( $_POST['msrp'] ) )
            update_post_meta( $product_id, 'msrp', $_POST['msrp'] );
    } else delete_post_meta( $product_id, 'msrp' );
    if ( isset( $_POST['dprice'] ) ) {
        if ( is_numeric( $_POST['dprice'] ) )
            update_post_meta( $product_id, 'dprice', $_POST['dprice'] );
    } else delete_post_meta( $product_id, 'dprice' );
}

add_action( 'woocommerce_single_product_summary', 'bbloomer_display_MSRP', 9 );

function bbloomer_display_MSRP() {
    global $product;
    if ( $product->get_type() <> 'variable' && $dprice = get_post_meta( $product->get_id(), 'dprice', true ) ) {
        echo '<div class="woocommerce_dprice">';
        _e( 'Cost: ', 'woocommerce' );
        echo '<span>' . wc_price( $dprice ) . '</span>';
        echo '</div>';
    }
}
add_filter('gettext', 'change_backend_product_regular_price', 100, 3 );
function change_backend_product_regular_price( $translated_text, $text, $domain ) {
    global $pagenow;
    if ( is_admin() && 'woocommerce' === $domain && 'post.php' === $pagenow && isset( $_GET['post'] )
        && 'product' === get_post_type( $_GET['post'] ) && 'Regular price' === $text  )
    {
        $translated_text =  __( 'Cost', $domain );
    }
    return $translated_text;
}

function archive_price() {
    global $product;
    if ( $product->get_type() <> 'variable' && $dprice = get_post_meta( $product->get_id(), 'dprice', true ) ) {
        echo '<span class="price"><span class="woocommerce-Price-amount amount">';
        echo wc_price( $dprice );
        echo '</span></span>';
    }
}

add_action('woocommerce_after_shop_loop_item_title','archive_price',10);

remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products',20);


/*
 * Order item title and price
 *
 * */

function product_title_price($woocommerce_product_description_tab) {
    global $product;
	$items = number_format($product->stock,0,'','');
    $price = get_post_meta( get_the_ID(), '_regular_price', true);

    if ( count( $product->get_children() ) > 1 ) {
        echo '<div class="product-title_price">';
            woocommerce_get_template('single-product/title.php');
        echo '</div>';
    } else {
        echo '<div class="product-title_price">';
            woocommerce_get_template('single-product/title.php');
            echo '<div class="product_price">'.wc_price( wc_get_price_excluding_tax( $product ) ).'</div>';
        echo '</div>';
        echo '<div class="product-stocks">';
        echo '<span>'.$items.'</span> In Stock';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary','product_title_price', 5);

add_action('woocommerce_single_product_summary','woocommerce_product_additional_information_tab',70);

add_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40);

remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_rating',10);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_sharing',50);
remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30);
remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);

add_filter('woocommerce_enable_order_notes_field','__return_false');
add_filter('woocommerce_checkout_fields','remove_order_notes');
add_filter('show_admin_bar','__return_false');

function remove_order_notes( $fields ) {
     unset($fields['order']['order_comments']);
     return $fields;
}

function woo_remove_product_tabs( $tabs ) {
	unset( $tabs['description'] ); // Remove the description tab
	unset( $tabs['reviews'] ); // Remove the reviews tab
	unset( $tabs['additional_information'] ); // Remove the additional information tab
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

/*
 * TODO: cart_function_head and my_header_add_to_cart_fragment fully remove no need code overprice to discount
 * */


function cart_function_head() {
	?>
	<?php
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	echo '<div class="header-cart">';
	global $adobo_options;
//	if( is_user_logged_in() ) {
//		if( current_user_can('administrator')) {
//			$rolename = 'administrator';
//		} elseif( current_user_can('editor')) {
//			$rolename = 'editor';
//		} elseif( current_user_can('author')) {
//			$rolename = 'author';
//		} elseif( current_user_can('contributor')) {
//			$rolename = 'contributor';
//		} elseif( current_user_can('subscriber')) {
//			$rolename = 'subscriber';
//		} elseif( current_user_can('customer')) {
//			$rolename = 'customer';
//		} elseif( current_user_can('shop_manager')) {
//			$rolename = 'shop_manager';
//		} elseif( current_user_can('vip_members')) {
//			$rolename = 'vip_members_';
//		} else {
//			$rolename = 'guest';
//		}
//		$firstmarkup = $rolename.'_first_markup';
//		$secondmarkup =  $rolename.'_second_markup';
//		$thirdmarkup =  $rolename.'_third_markup';
//		$fourthmarkup =  $rolename.'_fourth_markup';
//	} else {
		$firstmarkup = 'guest_first_markup';
		$secondmarkup =  'guest_second_markup';
		$thirdmarkup =  'guest_third_markup';
		$fourthmarkup =  'guest_fourth_markup';
//	}
		global $woocommerce;
		$totalprice = $woocommerce->cart->get_cart_total();
		$totalcartprice = WC()->cart->cart_contents_total;
		$count = WC()->cart->cart_contents_count;

		if ( $count > 0 ) {
			echo '<span class="count">'.$count.'</span> ';
			//echo $totalprice;
			echo '<span class="woocommerce-Price-currencySymbol">$</span>' . $totalcartprice;
			echo ' ('.$count.' items)';
		} else {
			echo '<span class="count">0</span> $0 (0 items)';	
			
		}

		if (is_plugin_active('woo-saved-carts/woo-saved-carts.php')) {
			do_action('wcsc_cart_widget');
		} 

		/* commented by payal */
		// if ( get_option('sales_enable') == 1 ) {
		// 	echo '<div class="cart-discounts">';
		// 		$totalcartprice = WC()->cart->cart_contents_total;
		// 		$freeShippingQualifyAmount = 5000;
		// 		if($totalcartprice < $freeShippingQualifyAmount ) {
		// 			$amountneeded = $freeShippingQualifyAmount - $totalcartprice;
		// 			$dcmarkup = $thirdmarkup;
		// 			echo 'add <strong>$'.$amountneeded.'</strong> more to your cart and get <strong  class="free-ship">FREE SHIPPING</strong> and <strong class="cd-discount">'.$adobo_options[$dcmarkup].'% OFF</strong> your entire cart';
		// 		} elseif( $totalcartprice >= 5000  && $totalcartprice < 10000 ) {
		// 			$amountneeded = 10000 - $totalcartprice;
		// 			$dcmarkup = $fourthmarkup;
		// 			echo 'add <strong>$'.$amountneeded.'</strong> more to your cart and get <strong  class="free-ship">FREE SHIPPING</strong> and <strong class="cd-discount">'.$adobo_options[$dcmarkup].'% OFF</strong> your entire cart';
		// 		} elseif( $totalcartprice >= 10000 ) {
		// 			$dcmarkup = $fourthmarkup;
		// 			echo 'Maximum Savings - You are getting <strong  class="free-ship">FREE SHIPPING</strong> and <strong class="cd-discount">'.$adobo_options[$dcmarkup].'% OFF</strong> your entire cart!';
		// 		} else {
		// 			$dcmarkup = 0;
		// 			$text = '';
		// 		}
		// 	echo '</div>';
		// }	



        echo '</div>';
	}
}

add_action('header_cart','cart_function_head' );

function my_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	echo '<div class="header-cart">';
	global $adobo_options;
//	if( is_user_logged_in() ) {
//		if( current_user_can('administrator')) {
//			$rolename = 'administrator';
//		} elseif( current_user_can('editor')) {
//			$rolename = 'editor';
//		} elseif( current_user_can('author')) {
//			$rolename = 'author';
//		} elseif( current_user_can('contributor')) {
//			$rolename = 'contributor';
//		} elseif( current_user_can('subscriber')) {
//			$rolename = 'subscriber';
//		} elseif( current_user_can('customer')) {
//			$rolename = 'customer';
//		} elseif( current_user_can('shop_manager')) {
//			$rolename = 'shop_manager';
//		} elseif( current_user_can('vip_members')) {
//			$rolename = 'vip_members_';
//		} else {
//			$rolename = 'guest';
//		}
//		$firstmarkup = $rolename.'_first_markup';
//		$secondmarkup =  $rolename.'_second_markup';
//		$thirdmarkup =  $rolename.'_third_markup';
//		$fourthmarkup =  $rolename.'_fourth_markup';
//	} else {
		$firstmarkup = 'guest_first_markup';
		$secondmarkup =  'guest_second_markup';
		$thirdmarkup =  'guest_third_markup';
		$fourthmarkup =  'guest_fourth_markup';
//	}

		global $woocommerce;
		$totalprice = $woocommerce->cart->get_cart_total();
		$totalcartprice = WC()->cart->cart_contents_total;
		$count = WC()->cart->cart_contents_count;

		echo '<a href="/cart">';
		if ( $count > 0 ) {
			echo '<div class="cart-counts"><span class="count">'.$count.'</span> ';
			//echo $totalprice;
			echo '<span class="woocommerce-Price-currencySymbol">$</span>' . $totalcartprice;
			echo ' ('.$count.' items)</div>';
		} else {
			echo '<span class="count">0</span> $0 (0 items)';
		}
		echo '</a>';
		
		if (is_plugin_active('woo-saved-carts/woo-saved-carts.php')) {
			do_action('wcsc_cart_widget');		
		} 
		/* commented by payal */
		// if ( get_option('sales_enable') == 1 ) {
		// 	echo '<div class="cart-discounts">';
		// 		$totalcartprice = WC()->cart->cart_contents_total;
		// 		$freeShippingQualifyAmount = 5000;
		// 		if($totalcartprice < $freeShippingQualifyAmount ) {
		// 			$amountneeded = $freeShippingQualifyAmount - $totalcartprice;
		// 			$dcmarkup = $thirdmarkup;
		// 			echo 'add <strong>$'.$amountneeded.'</strong> more to your cart and get <strong  class="free-ship">FREE SHIPPING</strong> and <strong class="cd-discount">'.$adobo_options[$dcmarkup].'% OFF</strong> your entire cart';
		// 		} elseif( $totalcartprice >= 5000  && $totalcartprice < 10000 ) {
		// 			$amountneeded = 10000 - $totalcartprice;
		// 			$dcmarkup = $fourthmarkup;
		// 			echo 'add <strong>$'.$amountneeded.'</strong> more to your cart and get <strong  class="free-ship">FREE SHIPPING</strong> and <strong class="cd-discount">'.$adobo_options[$dcmarkup].'% OFF</strong> your entire cart';
		// 		} elseif( $totalcartprice >= 10000 ) {
		// 			$dcmarkup = $fourthmarkup;
		// 			echo 'Maximum Savings - You are getting <strong  class="free-ship">FREE SHIPPING</strong> and <strong class="cd-discount">'.$adobo_options[$dcmarkup].'% OFF</strong> your entire cart!';
		// 		} else {
		// 			$dcmarkup = 0;
		// 			$text = '';
		// 		}
		// 	echo '</div>';
		// }

	echo '</div>';

	$fragments['.header-cart'] = ob_get_clean();
    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments','my_header_add_to_cart_fragment');

function login_form() {
	ob_start();
			echo '<h3>Login</h3>';
			echo '<div class="wp_login_error">';
			if( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
				echo '<p>The password you entered is incorrect, Please try again.</p>';
			} elseif( isset( $_GET['login'] ) && $_GET['login'] == 'empty' ) {
				echo '<p>Please enter both username and password.</p>';
			}
			echo '</div>';
			$args = array(
				'redirect' => site_url('/my-account/'),
			);
			wp_login_form($args);
			echo '<div class="login-links clear">';
				echo '<label class="cb-container"><input type="checkbox" name="remember" value="Remember" id="remember-me"> <span class="checkmark"></span>Remember<label>';
				echo '<a href="'.wp_lostpassword_url().'" title="Lost Password">Lost Password</a>';
			echo '</div><div class="login-required">';
				echo '<span>*</span> Required field';
			echo '</div>';
			?>
				<script type='text/javascript'>
				jQuery('#remember-me').on('click',function(){
					if(jQuery(this).is(':checked') === true){
						jQuery('.login-remember #rememberme').prop('checked',true);
					} else {
						jQuery('.login-remember #rememberme').prop('checked',false);
					}
				});
				jQuery('#user_login').attr('placeholder','Email *');
				jQuery('#user_pass').attr( 'placeholder','Password *');
				</script>
			<?php
	$output = ob_get_clean();
	return $output;
}
add_shortcode('login-form','login_form');

function front_end_login_fail($username) {
	$referrer = $_SERVER['HTTP_REFERER'];
	if( !empty( $referrer ) && !strstr( $referrer,'wp-login' ) && !strstr( $referrer,'wp-admin' ) ) {
		wp_redirect(home_url('login/?login=failed'));
		exit;
	}
}
add_action( 'wp_login_failed', 'front_end_login_fail' );
function check_username_password( $login, $username, $password ) {
	$referrer = $_SERVER['HTTP_REFERER'];
	if( !empty( $referrer ) && !strstr( $referrer,'wp-login' ) && !strstr( $referrer,'wp-admin' ) ) {
		if( $username == "" || $password == "" ){
			wp_redirect(home_url('login/?login=empty'));
			exit;
		}
	}
}
add_action( 'authenticate', 'check_username_password', 1, 3);

function my_logged_in_redirect() {
	if (is_user_logged_in() && is_page(array(1716,2024))) {
		wp_redirect('/my-account/');
		die;
	}

	/* added by pooja */
    if( is_checkout() && !is_user_logged_in() ) {
        // wp_redirect( wc_get_cart_url() ); 
        // exit;
    }
}
add_action('template_redirect','my_logged_in_redirect');

function registration_form() {
	ob_start();
		echo '<h3>Sign Up</h3>';
		echo do_shortcode('[woocommerce_my_account]');
		echo '<div class="login-required">';
			echo '<span>*</span> Required field';
		echo '</div>';
		?>
		<script type='text/javascript'>
			jQuery('#reg_username').attr('placeholder', 'Name*');
			jQuery('#reg_email').attr('placeholder', 'Email*');
			jQuery('#reg_password').attr('placeholder', 'Password*');
			jQuery('.woocommerce-Button').prop('value','Sign Up');
		</script>
		<?php
	$output = ob_get_clean();
	return $output;
}
add_shortcode('registration-form','registration_form');

function elementor_content($atts,$content = null) {
	extract(shortcode_atts(array(
		'id' => '',
	), $atts));
		ob_start();
	$args = array( 'post_type' => 'elementor_library','post_id' => $id);
	$loop = new WP_Query( $args );
	echo '<div id="latest-news">';
		while ( $loop->have_posts() ) : $loop->the_post();
			the_content();
		endwhile;
		wp_reset_query();
	echo '</div>';
	$html = ob_get_contents();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('elementor-content', 'elementor_content');




//add_filter( 'woocommerce_get_catalog_ordering_args', 'wcs_get_catalog_ordering_args' );
//function wcs_get_catalog_ordering_args( $args ) {
//    $orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
//
//if ( 'on_sale' == $orderby_value ) {
////        $args['posts_per_page'] = 3;
//        $args['orderby'] = 'date';
//        $args['post_type'] = 'product_variation';
//        $args['order'] = 'DESC';
////                $args['meta_key'] = '_checkbox_best-sellers';
////                $args['meta_value'] = 'yes';
//        $args['post_status'] = 'publish';
//
//        $meta_query   = WC()->query->get_meta_query();
//        $meta_query[] = array(
//            'key'   => '_checkbox_best-sellers',
//            'value' => 'yes'
//        );
//        $args['meta_query'] = $meta_query;
//
//
////        $args['meta_query'] = [[
////            'key'     => '_checkbox_best-sellers',
////            'value'   => 'yes',
////            'compare' => '=',
////        ]];
//    }
//    elseif ( 'date' == $orderby_value ) {
//        $args['orderby'] = 'date';
//        $args['post_type'] = 'product_variation';
//        $args['order'] = 'DESC';
//                $args['meta_key'] = '_checkbox_new-arrivals';
//                $args['meta_value'] = 'yes';
//        $args['post_status'] = 'publish';
////        $args['meta_query'] = [[
////            'key'     => '_checkbox_new-arrivals',
////            'value'   => 'yes',
////            'compare' => '=',
////        ]];
//    }
//    return $args;
//}

function filter_taxonomy() {
	ob_start();
	?>
	<div class="product-filters pf-taxonomy">
	<select name="event-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
		<option value=""><?php echo esc_attr(__('Select Category')); ?></option>
		<?php
			$option = '<option value="' . get_option('home') . '/category/">All Categories</option>'; // change category to your custom page slug
			$categories = get_categories(array('taxonomy' => 'product_cat'));
			foreach ($categories as $category) {
				$option .= '<option value="'.get_option('home').'/product-category/'.$category->slug.'">';
				$option .= $category->cat_name;
				$option .= ' ('.$category->category_count.')';
				$option .= '</option>';
			}
        echo $option;
	echo '</select>';
	echo '</div>';
	$html = ob_get_contents();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('filter-taxonomy', 'filter_taxonomy');

function filter_brand() {
	ob_start();
	?>
	<div class="product-filters pf-taxonomy">
	<select name="event-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
		<option value=""><?php echo esc_attr(__('Select Brands')); ?></option>
		<?php
			$option = '<option value="' . get_option('home') . '/products/">All Brands</option>'; // change category to your custom page slug
			$categories = get_categories(array('taxonomy' => 'pa_brand'));
			foreach ($categories as $category) {
				$option .= '<option value="'.get_option('home').'/products/?filter_brand='.$category->slug.'">';
				$option .= $category->cat_name;
				$option .= ' ('.$category->category_count.')';
				$option .= '</option>';
			}
        echo $option;
	echo '</select>';
	echo '</div>';
	$html = ob_get_contents();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('filter-brand', 'filter_brand');

function filter_show_only() {
	ob_start();
	echo '<div class="product-filters pf-show-only">';
		echo '<ul class="show-only-wrap">';
			echo '<a class="so-new" href="/products/?orderby=date">New</a>';
			echo '<a class="so-discount" href="/products/?orderby=on_sale">Discount</a>';
		echo '</ul>';
	echo '</div>';
	$html = ob_get_contents();
	$output = ob_get_clean();
	return $output;
}

add_shortcode('filter-show-only', 'filter_show_only');

function filter_default() {
	ob_start();
		?>
		<div class="product-filters pf-default">
		<form class="product-filters pf-default" method="get">
			<select name="orderby" class="orderby">
				<option value="date">Latest</option>
				<option value="popularity">Popularity</option>
				<option value="rating">Ratings</option>
				<option value="price">Sort by price: low to high</option>
				<option value="price-desc">Sort by price: high to low</option>
			</select>
			<input type="hidden" name="paged" value="1">
		</form>
		</div>
		<?php
	$html = ob_get_contents();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('filter-default', 'filter_default');


function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function lags_builder($id = '', $field = 'page_fields', $foldername = 'landing-builder') {
  global $post;

  $gtdu = get_template_directory_uri();
  $post_id = (!empty($id)) ? $id : $post->ID;

  $fields = get_field($field, $post_id);

  if (empty($fields)) return;

  foreach ($fields as $key => $f) {
    $context['f'] = $f;

    $context['button_title'] = get_field('button_title', $post_id);

    $context['gtdu'] = get_template_directory_uri();
    if ( file_exists(get_template_directory().'/'.$foldername.'/'.$f['acf_fc_layout'].'.php') ) {
      include($foldername.'/'.$f['acf_fc_layout'].'.php');
    }
  }
}


add_action('woocommerce_check_cart_items', 'validate_all_cart_contents');
function validate_all_cart_contents(){
	/*
    foreach ( WC()->cart->cart_contents as $cart_content_product ) {

        $sold_in_quantities = get_post_meta( $cart_content_product['variation_id'], '_variable_sold_in_quantities_of', true );

        if ($cart_content_product['quantity'] < $sold_in_quantities || ($cart_content_product['quantity'] % $sold_in_quantities) != 0) {
            wc_add_notice( sprintf( 'Quantity is uncorrect' ), 'error' );
        }
        else {
            return true;
        }
	}
	*/
}

function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

add_filter( 'body_class','my_body_classes' );
function my_body_classes( $classes ) {
    if ( is_home() ) {
        $classes[] = 'blog';
    } elseif ( is_page_template('template-landingpage.php') ) {		
        $classes[] = 'landing-page';		
    }

    return $classes;
}

		
function promotions_post() {		
	$labels = array(		
		'name'					=>	'Promotions',		
		'singular_name'			=>	'Promotions',		
		'menu_name'				=>	'Promotions',		
		'name_admin_bar'		=>	'Promotions',		
		'add_new'				=>	'Add New',		
		'add_new_item'			=>	'Add New',		
		'new_item'				=>	'New Promotions',		
		'edit_item'				=>	'Edit Promotions',		
		'view_item'				=>	'View Promotions',		
		'all_items'				=>	'All Promotions',		
		'search_items'			=>	'Search Promotions',		
		'parent_item_colon'		=>	'Parent Promotions:',		
		'not_found'				=>	'No Promotions found.',		
		'not_found_in_trash'	=>	'No Promotions found in Trash.',		
	);		
	$args = array(		
		'labels'				=>	$labels,		
		'public'				=>	true,		
		'publicly_queryable'	=>	true,		
		'show_ui'				=>	true,		
		'show_in_menu'			=>	true,		
		'menu_icon'				=>	'dashicons-megaphone',		
		'query_var'				=>	true,		
		'rewrite'				=>	true,		
		'rewrite'				=>	array( 'slug'	=>	'promotions' ),		
		'capability_type'		=>	'post',		
		'has_archive'			=>	true,		
		'hierarchical'			=>	false,		
		'menu_position'			=>	5,		
		'supports'				=>	array('title','thumbnail')		
	);		
	register_post_type('promotions',$args);		
}		
add_action('init','promotions_post');		
function promotions_flush() {		
	promotions_post();		
	flush_rewrite_rules();		
}		
register_activation_hook(__FILE__,'promotions_flush');

// Update CSS within in Admin
function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');

//Remove account display name error from customer account page
add_filter('woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields' );
function wc_save_account_details_required_fields( $required_fields ){
    unset( $required_fields['account_display_name'] );
    return $required_fields;
}

// Set order status to 'will call processing' if customer picks local pickup
function check_order_shipping( $order_id ){
	$order = wc_get_order( $order_id );

	$chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');

	$method = $chosen_shipping_methods[0];
	
	if( preg_match( '/^local_pickup/', $method ) ) {
		$update_status = array(
			'ID'          => $order_id,
			'post_status' => 'wc-will-call-proce'
		);

		wp_update_post( $update_status );
	}

}

add_action( 'woocommerce_payment_complete', 'check_order_shipping' );

function reset_default_shipping_method( $method, $available_methods ) {

	//Set last shipping method as default (which is never will call)
    $methods = array_keys( $available_methods );

	return end($methods);

}
add_filter('woocommerce_shipping_chosen_method', 'reset_default_shipping_method', 10, 2);


function lw_woocommerce_gpf_title( $title, $product_id ) {

	return preg_replace('/Title\: /', '', $title);
}
add_filter( 'woocommerce_gpf_title', 'lw_woocommerce_gpf_title', 10, 2 );

function product_listing_badges() {
	global $product;
	
	$pid = $product->get_id();

	$variations = $product->get_available_variations();

	$has_sp = false;
	$has_bs = false;
	$has_na = false;
	$has_fs = false;

	foreach ( $variations as $variation ) {
		$badge_sp = get_post_meta( $variation['variation_id'], '_checkbox_staff-picks', true );
		if ( $badge_sp == 'yes' ) {
			$has_sp = true;
			break;
		}
	}

	foreach ( $variations as $variation ) {
		$badge_bs = get_post_meta( $variation['variation_id'], '_checkbox_best-sellers', true );
		if ( $badge_bs == 'yes' ) {
			$has_bs = true;
			break;
		}
	}	

	foreach ( $variations as $variation ) {
		$badge_na = get_post_meta( $variation['variation_id'], '_checkbox_new-arrivals', true );
		if ( $badge_na == 'yes' ) {
			$has_na = true;
			break;
		}
	}	

	foreach ( $variations as $variation ) {
		$badge_fs = get_post_meta( $variation['variation_id'], '_checkbox_free-shipping', true );
		if ( $badge_fs == 'yes' ) {
			$has_fs = true;
			break;
		}
	}	

	$html = '<div class="' . $pid . ' product-badges listings-page-badges">';		
		
		if ($has_sp) {
			$html .= '<img class="product-badge product-grid staff-picks" src="/wp-content/uploads/2018/12/badge-staff-pick.png">';
		}

		if ($has_bs) {
			$html .= '<img class="product-badge product-grid  best-sellers" src="/wp-content/uploads/2019/07/icon-big-sale-shadow.png">';
		}

		if ($has_na) {
			$html .= '<img class="product-badge product-grid  new-arrivals" src="/wp-content/uploads/2018/12/badge-new-product.png">';
		}

		if ($has_fs) {
			$html .= '<img class="product-badge product-grid  free-shipping" src="/wp-content/uploads/2020/02/free-shipping-badge.png">';
		}

	$html .= '</div>';

	echo $html;
}

add_action('woocommerce_before_shop_loop_item_title','product_listing_badges',10);

/* 23-1-2021 = added by pooja */
// remove_action( 'woocommerce_proceed_to_checkout','woocommerce_button_proceed_to_checkout', 20);


remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price',10 );

/* 27-1-2021 = Added by pooja */


/* 27-1-2021 = Added by Pooja */
add_filter( 'woocommerce_product_single_add_to_cart_text','woocommerce_custom_single_add_to_cart_text' ); 
function woocommerce_custom_single_add_to_cart_text() {
	global $post;
	$product = wc_get_product( $post->ID );
	$product_id = $product->get_id(); 
	$terms = wp_get_post_terms( $post->ID, 'product_cat' );
?>
		<script type="text/javascript">	
		 //console.log(jQuery('.woocommerce .single-product .product .single_variation_wrap .woocommerce-variation-add-to-cart').find('.js-wsb-add-to-cart').text());
		jQuery('.woocommerce .single-product .product .single_variation_wrap .woocommerce-variation-add-to-cart').find('.js-wsb-add-to-cart').hide(); 
		</script>
	<?php
	foreach($terms as $term) $categories[] = $term->slug; 

	if(in_array('weekly-specials',$categories)) { 		
		return __('Buy Now', 'woocommerce'); 
	}  else{	
		return __('Add To Cart', 'woocommerce'); 
	}
} 

function woocommerce_expired_shortcode() { 
 	?>
 	<div class="woocommerce"> 
	<h3>Expired Deals</h3>
	<ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">
    <?php
        $args = array( 'post_type' => 'product','product_cat' => 'weekly-specials', 'orderby' => 'rand','post_status' => 'publish' );
        $loop = new WP_Query( $args );
        // echo '<pre>';
        // print_r($loop);
        // die();
        while ( $loop->have_posts() ) : $loop->the_post(); 
        	global $product; 

        	$expire_start_date = get_field('expire_start_date');
		    $expire_start_time = get_field('expire_start_time');
		    $expire_end_date = get_field('expire_end_date');
		    $expire_end_time = get_field('expire_end_time');
		    date_default_timezone_set("UTC");
		    $merge_start_date = $expire_start_date.' '.$expire_start_time;
		    $merge_end_date = $expire_end_date.' '.$expire_end_time;
		    
		    // $dateTime = new DateTime('now',new DateTimeZone('Asia/Kolkata')); 
		    // $currentTime = $dateTime->format("Y-m-d h:i a");  
		    $currentTime = date('Y-m-d h:i a');

		    $star_time = $currentTime;
		    $end_time  = $merge_end_date;
		    $end_time1  = $merge_start_date; 

		    $start = strtotime($currentTime);
		    $end = strtotime($merge_end_date);
		    $end1 = strtotime($merge_start_date); 					
    		//echo $loop->post->post_title;

		    if($end1 <= $start && $end >= $start)
		    {   
		    } else { 
        	?> 
                <li <?php wc_product_class( '', $product ); ?>>    
                    <a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">

                        <?php woocommerce_show_product_sale_flash( $post, $product ); ?>

                        <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" width="300px" height="300px" />';  

                        echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h2>';
                        ?>
                        <!-- <span class="price"><?php echo $product->get_price_html(); ?></span>  -->       
                    </a>
                    <?php woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?> 
                </li>

    <?php  } endwhile; ?>
    <?php wp_reset_query(); ?>
</ul><!--/.products-->
</div>
<?php  
} 
// woocommerce_expired_shortcode shortcode
add_shortcode('expired_products', 'woocommerce_expired_shortcode');

add_filter('woocommerce_product_add_to_cart_text', 'wh_archive_custom_cart_button_text');   // 2.1 +

function wh_archive_custom_cart_button_text()
{
	global $post;
	$product = wc_get_product( $post->ID );
	$product_id = $product->get_id(); 
	$terms = wp_get_post_terms( $post->ID, 'product_cat' );

	foreach($terms as $term) $categories[] = $term->slug; 

	if(in_array('weekly-specials',$categories)) { 
		return __('View Details', 'woocommerce');
	}else{
		return __('Select Option', 'woocommerce');
	}    
}

/* 3-2-2021 added by Pooja */

// add_action('wp_ajax_wdm_add_user_custom_data_options', 'wdm_add_user_custom_data_options_callback');
// add_action('wp_ajax_nopriv_wdm_add_user_custom_data_options', 'wdm_add_user_custom_data_options_callback');

// function wdm_add_user_custom_data_options_callback()
// {
//       //Custom data - Sent Via AJAX post method
//       $product_id = $_POST['id']; //This is product ID
//       $user_custom_data_values =  $_POST['user_data']; //This is User custom value sent via AJAX
//       session_start();
//       $_SESSION['wdm_user_custom_data'] = $user_custom_data_values;
//       die();
// }

add_action('woocommerce_before_checkout_form', 'displays_cart_products_feature_image');
function displays_cart_products_feature_image() {
 
    $customSession = array();
    $product_ids = array();
    // print_r(WC()->cart->get_cart());
    // exit;
   // print_r($_GET);
    foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];        
        if(!empty($product)){
        	$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key ); 

			$product_id = apply_filters('woocommerce_cart_item_product_id',$cart_item['product_id'], $cart_item, $cart_item_key);
			
			$data = array();
			if($product_id != $_GET['product_id']){
				$data['product_id'] = $product_id;
				$data['qty'] =  $cart_item['quantity'];
				$product_ids[] = $data;
				if(is_checkout()) {
					//WC()->cart->remove_cart_item($cart_item_key);
				}
			}
			 
        }
    }
    session_start();
	$_SESSION['cart_array'] = $product_ids; 
}

add_action( 'woocommerce_cart_calculate_fees', 'discount_based_on_cart_total', 10, 1 );
	function discount_based_on_cart_total( $cart_object ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
    		$cart_total = $cart_object->cart_contents_total; 

    		//$fees = WC()->cart->get_fees();
    		// var_dump($fees);exit;
    		// Cart total
    	if ( $cart_total > 4999 && $cart_total <= 9999) {
        	$percent = 15; // 15%
    	}
    	elseif ( $cart_total > 9999 && $cart_total < 15000) {
        	$percent = 10; // 10%
    	}
    	else{
        	$percent = 0;
    	}

    	if ( $percent != 0 ) {
        	$discount =  $cart_total * $percent / 100;
        	$cart_object->add_fee( "Discount ($percent%)", -$discount, true );
    	}
	}

	function filter_woocommerce_calculated_total( $total, $cart ) {
	$fees = WC()->cart->get_fees();
	//echo '<pre>';var_dump($fees['discount']->amount);exit; 
    // Get subtotal
    $subtotal = $cart->get_subtotal();
    
    return $total - $fees['discount']->amount;
}
//add_filter( 'woocommerce_calculated_total', 'filter_woocommerce_calculated_total', 10, 2 );
	add_action( 'woocommerce_before_calculate_totals', 'custom_fees' );
	function custom_fees() {
		$fees = WC()->cart->get_fees();
		//var_dump($fees);exit;
		foreach ($fees as $key => $fee) {
        if($fees[$key]->name === __( "Discount")) {
            unset($fees[$key]);
        }
        WC()->cart->fees_api()->set_fees($fees);
    }
    WC()->cart->fees_api()->set_fees($fees);
}

function lags_recalc_price( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;


    $subtotal = 0;

    // Loop through cart items
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
    	$quantity = $cart_item['quantity'];
        
        $product = $cart_item['data'];
        $price = $product->get_price();

        $price = $price / 1.2;

        $product_sub_total = $price * $quantity;

        $subtotal += $product_sub_total;
    }

    $price_formula_value = 1;

   	if ( $subtotal > 4999 && $subtotal <= 9999) {
   		$price_formula_value = 1.15;
   	}
   	elseif ( $subtotal > 9999) {
   		$price_formula_value = 1.10;
   	}

    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
    	$product = $cart_item['data'];
        $price = $product->get_price();

        $price = $price / 1.2;

        $new_price = $price * $price_formula_value;

        $product->set_price( $new_price );
    }

}

//add_filter('woocommerce_product_variation_get_price', 'lags_custom_price' , 99, 2 );

function lags_custom_price( $price, $product ) {
    // Delete product cached price  (if needed)
    wc_delete_product_transients($product->get_id());

    return $price * 1.20;
}

function lags_recalc_price_backup( $cart_object ) {
	$cart_total = $cart_object->cart_contents_total;

	echo 'cart_total => ' . $cart_total . '<br />';

	$price_formula_value = 1;

	if ( $cart_total > 0 && $cart_total <= 4999 )  {
        $price_formula_value = 1.20; // 20%
   	}
   	elseif ( $cart_total > 4999 && $cart_total <= 9999) {
   		$price_formula_value = 1.15;
   	}
   	elseif ( $cart_total > 9999) {
   		$price_formula_value = 1.10;
   	}

   	$subtotal = 0;

	foreach ( $cart_object->get_cart() as $hash => $value ) {
		$price = $value['data']->get_price();
		
		$new_price = $price * $price_formula_value;

		$value['data']->set_price((double)$new_price);
	}
}

function before_cart() {
	session_start();
	//print_r(count($_SESSION['cart_array']));
	//$session_array = $_SESSION['cart_array'];
	global $woocommerce;
	foreach($_SESSION['cart_array'] as $key => $value) {
			# code...
		//echo $value['product_id'];
		WC()->cart->add_to_cart($value['product_id'],$value['qty']);
	}	//WC()->cart->add_to_cart( $product_id, $quantity );
     
	unset($_SESSION['cart_array']);

	foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];        
        if(!empty($product)){
        	$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key ); 

			$product_id = apply_filters('woocommerce_cart_item_product_id',$cart_item['product_id'], $cart_item, $cart_item_key);
			
			$data = array(); 
			$terms = get_the_terms($product_id,'product_cat');
			$product_cat = array();

			foreach($terms as $term) {
			   $product_cat[] = $term->slug;
			}			

			if(in_array('weekly-specials',$product_cat)) { 
				//WC()->cart->remove_cart_item($cart_item_key);  
			} 
        }
    }
}

add_filter( 'woocommerce_before_subcategory', 'woocommerce_shortcode_product_categories_orderby' );

function woocommerce_shortcode_product_categories_orderby($args) { 
	 
	if($args->slug == 'weekly-specials'){
		
		$args1 = array( 'post_type' => 'product','product_cat' => 'weekly-specials', 'orderby' => 'rand','post_status' => 'publish' );
        $loop = new WP_Query( $args1 );
       
        $i = $j = 1;
        while ( $loop->have_posts() ) : $loop->the_post(); 
        	global $product; 

        	$expire_start_date = get_field('expire_start_date');
		    $expire_start_time = get_field('expire_start_time');
		    $expire_end_date = get_field('expire_end_date');
		    $expire_end_time = get_field('expire_end_time');
		    date_default_timezone_set("UTC");
		    $merge_start_date = $expire_start_date.' '.$expire_start_time;
		    $merge_end_date = $expire_end_date.' '.$expire_end_time;
		    
		    // $dateTime = new DateTime('now',new DateTimeZone('Asia/Kolkata')); 
		    // $currentTime = $dateTime->format("Y-m-d h:i a");  
		    $currentTime = date('Y-m-d h:i a');

		    $star_time = $currentTime;
		    $end_time  = $merge_end_date;
		    $end_time1  = $merge_start_date; 

		    $start = strtotime($currentTime);
		    $end = strtotime($merge_end_date);
		    $end1 = strtotime($merge_start_date); 					
    		//echo $loop->post->post_title;
		    
		    if($end1 <= $start && $end >= $start)
		    {  
		    	$j++; 
		    } else {  
		    }
		$i++;
	    endwhile;  
	    $args->count  = ($j-1);  
	    return $args;
	} 
}

// add_action('template_redirect', 'woocommerce_custom_redirections');
// function woocommerce_custom_redirections() {
//     // Case1: Non logged user on checkout page (cart empty or not empty)
//     if ( !is_user_logged_in() && is_checkout() ){
//         // session_start();
// 	   // $_SESSION['after_checkou_url'] = $_SERVER['REQUEST_URI']; 
// 	    wp_redirect( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
//     }
//     // Case2: Logged user on my account page with something in cart
//     // if( is_user_logged_in() && ! WC()->cart->is_empty() && is_account_page() ){
//         // wp_redirect( $_SESSION['after_checkou_url'] );
//         // wp_redirect( get_permalink( get_option('woocommerce_checkout_page_id') ) );
//     // }
// }