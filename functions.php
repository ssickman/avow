<?php 
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: html5blank.com | @html5blank
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/
add_action('init', function(){ date_default_timezone_set('America/Los_Angeles'); }, 0);

$avow_events_table = $wpdb->prefix . "avow_events"; 
require_once('custom_post_type_package.php');
require_once('avow_events.php');
require_once('stripe_settings.php');
require_once('email_settings.php');

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/
class MissingPackageId extends Exception{}
class BadNonce extends Exception{}
class MismatchedChargeAmount extends Exception{}
class UnavailableDate extends Exception{}

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

$cookieData = getCookieData();
$stripeSettings  = get_option('stripe_settings'); 

$phone = '304.933.9016';
$email = 'info@avowpdx.com';
$tryAgain = "<br><br>Please try again or give us a call at {$phone}";

//$startingAction = 'package';

function get_stripe_key($stripeKeyIndex) {
	global $stripeSettings;
	return @$stripeSettings[$stripeKeyIndex];
}

function stepCompleted($step) {
	global $cookieData;
	
	if ($step == 'reserve') {
		$key = 'date';
	} elseif ($step == 'package') {
		$key = 'package_id';
	}
	
	return !empty($cookieData->$step->$key);
}

function completeStep($step) {
	$_SESSION['completed_steps'][$step] = true;
} 

function showStepBar() { 
	global $cookieData;

	return 
		!empty($cookieData->reserve) ||
		!empty($cookieData->package);
}

function getCookieData()
{ 
	if (isset($_COOKIE['avow-form-data'])) {
		return json_decode(stripslashes($_COOKIE['avow-form-data']));
	} else {
		return array();
	}
}

function reserveFormValue($key, $default)
{
	global $cookieData;
	return isset($cookieData->reserve->$key) ? $cookieData->reserve->$key : $default;
}

//add_action('init', function() { addFlash("You haven't selected a package"); }, 90);
function addFlash($message, $class = 'warning') 
{ 
	$_SESSION['flash'] = array(
		'message' => $message,
		'class'   => $class,
	);
}

add_action('init', 'showFlash', 100);
function showFlash()
{ 
	//if (!empty($_SESSION['flash']['message']) && !empty($_SESSION['flash']['class'])) {
		
		$m = @$_SESSION['flash']['message']; $c = @$_SESSION['flash']['class'];
		
		add_action('wp_footer', function() use($m, $c){ 

			echo "<div class='flash {$c}'><div>{$m}</div><a href='#'>ok</a></div>";
		}, 200);
		
		
	//}
	
	unset($_SESSION['flash']);
}

function package_format_features($string) {
	$features = explode('|', $string);
	$out = '';
	foreach ($features as $f) {
		$class = '';
		if (strpos($f, '^') === 0) {
			$class = 'extra';
			$f = str_replace('^', '', $f);
		}
		
		$out .= "<li class='{$class}'>$f</li>";
	}
	
	return $out;
}

function getDesiredDatesForRange($desiredDays, $start, $end)
{
	$dates = array();
    while ($start <= $end) {
    	if (in_array(date('D', strtotime($start)), $desiredDays)) {
    		$dates[] = $start;
    	}
    	
    	$start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
    }
    
    asort($dates);
	return array_slice($dates, 0);
}

function datesToEvents($dates, $json = false) 
{
	$bookedDates = getBookedDates();

	$events = array();
	foreach ($dates as $d) {
		for ($i = 10; $i <= 20; $i += 2) {
			$datetime = "{$d} {$i}:00:00";
			
			$events[] = array(
				'date'   => $datetime,
				'status' => in_array($datetime, $bookedDates) ? 'booked' : 'available',
				'day'    => date('D', strtotime($datetime)),
			);
		}
	}
	
	if ($json) {
		return json_encode($events);
	}
	
	return $events;
}

function eventsForRange($desiredDays, $start, $end, $json = true) 
{
	global $startMonth;
	global $minMonth;
	
	$dates = getDesiredDatesForRange($desiredDays, $start, $end);
	$startMonth =  date('Y-m-01', strtotime($dates[0]));
	$minMonth =  date('Y-m-01', strtotime('-1 month', strtotime($startMonth)));
	
	return datesToEvents($dates, $json);
} 

function getMailer()
{
	$emailSettings = get_option('email_settings', '');

	require_once(dirname(__FILE__).'/swift-5.1.0/swift_required.php');
	$transporter = Swift_SmtpTransport::newInstance($emailSettings['email_smtp_server'], $emailSettings['email_smtp_port'], strtolower($emailSettings['email_smtp_protocol']))
		->setUsername($emailSettings['email_from'])
		->setPassword($emailSettings['email_password']);
			
	$mailer = Swift_Mailer::newInstance($transporter);
	
	$message = Swift_Message::newInstance('')
		->setFrom(array($emailSettings['email_from'] => 'Avow'))
		->setBcc($emailSettings['email_replyto'])
		->setReplyTo($emailSettings['email_replyto'])
	;
	
	return array($mailer, $message,);
	
	//$mailer->send($message);
}


add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    } 
    
    //print_r($_SESSION); die();
	//$_SESSION = array();    
}

function myEndSession() {
    session_destroy ();
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

   // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}


// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
    	/*
		wp_register_script('conditionizr', 'http://cdnjs.cloudflare.com/ajax/libs/conditionizr.js/4.1.0/conditionizr.js', array(), '4.1.0'); // Conditionizr
        wp_enqueue_script('conditionizr');
		*/
		
		/*
        wp_register_script('modernizr', 'http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.7.1/modernizr.min.js', array(), '2.6.2'); // Modernizr
        wp_enqueue_script('modernizr');
		*/
		
		wp_deregister_script('jquery');
        wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', array(), null); // Custom scripts
        wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', '', '', true);
        
        wp_register_script('jquery-scrollTo', '//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/1.4.11/jquery.scrollTo.min.js', array('jquery'), null); // Custom scripts
        wp_enqueue_script('jquery-scrollTo', '//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/1.4.11/jquery.scrollTo.min.js', '', '', true);
        
        wp_deregister_script('underscore');
        wp_register_script('underscore', '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js', array('jquery'), null); // Custom scripts
        wp_enqueue_script('underscore', '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js', '', '', true);
        
		wp_register_script('moment-clndr', get_template_directory_uri() . '/js/moment-clndr.js', array('jquery', 'underscore',), null); // Custom scripts
        wp_enqueue_script('moment-clndr', get_template_directory_uri() . '/js/moment-clndr.js', '', '', true);
        
        wp_register_script('stripe', 'https://checkout.stripe.com/checkout.js', array()); // Custom scripts
        wp_enqueue_script('stripe', 'https://checkout.stripe.com/checkout.js', '', '', true);
        
        
        wp_deregister_script('comment-reply');

        wp_register_script('html5blankscripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), null); // Custom scripts
        wp_enqueue_script('html5blankscripts', get_template_directory_uri() . '/js/scripts.js', '', '', true);
    }
}

// Load HTML5 Blank styles
function html5blank_styles()
{	
	wp_register_style('fonts', '//fonts.googleapis.com/css?family=Oxygen:400,700,300|Trocchi', array(), null, 'all');
    wp_enqueue_style('fonts');
	
    wp_register_style('html5blank', get_template_directory_uri() . '/style.css', array(), null, 'all');
    wp_enqueue_style('html5blank');
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
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
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
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
function html5wp_pagination()
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
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
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
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
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
function html5blankgravatar ($avatar_defaults)
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
function html5blankcomments($comment, $args, $depth)
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

 add_action( 'template_redirect', 'relative_url' );

  function relative_url() {
    // Don't do anything if:
    // - In feed
    // - In sitemap by WordPress SEO plugin
    if ( is_feed() || get_query_var( 'sitemap' ) )
      return;
    $filters = array(
      'post_link',       // Normal post link
      'post_type_link',  // Custom post type link
      'page_link',       // Page link
      'attachment_link', // Attachment link
      'get_shortlink',   // Shortlink
      'post_type_archive_link',    // Post type archive link
      'get_pagenum_link',          // Paginated link
      'get_comments_pagenum_link', // Paginated comment link
      'term_link',   // Term link, including category, tag
      'search_link', // Search link
      'day_link',   // Date archive link
      'month_link',
      'year_link',

      // site location
      'option_siteurl',
      'blog_option_siteurl',
      'option_home',
      'admin_url',
      'home_url',
      'includes_url',
      'site_url',
      'site_option_siteurl',
      'network_home_url',
      'network_site_url',

      // debug only filters
      'get_the_author_url',
      'get_comment_link',
      'wp_get_attachment_image_src',
      'wp_get_attachment_thumb_url',
      'wp_get_attachment_url',
      'wp_login_url',
      'wp_logout_url',
      'wp_lostpassword_url',
      'get_stylesheet_uri',
      // 'get_stylesheet_directory_uri',
      // 'plugins_url',
      // 'plugin_dir_url',
      // 'stylesheet_directory_uri',
      // 'get_template_directory_uri',
      // 'template_directory_uri',
      'get_locale_stylesheet_uri',
      'script_loader_src', // plugin scripts url
      'style_loader_src', // plugin styles url
      'get_theme_root_uri'
      // 'home_url'
    );

    foreach ( $filters as $filter ) {
      //add_filter( $filter, 'wp_make_link_relative' );
      add_filter( $filter, 'conditionalRelativeUrl' );
    }
    home_url($path = '', $scheme = null);
  }
  
  function conditionalRelativeUrl($link) {
  	if (strpos($link, 'avow') !== false) {
  		return wp_make_link_relative($link);
  	} else {
  		return $link;
  	}
  }

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

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
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
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
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]


/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

?>
