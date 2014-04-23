<?php
add_action('init', 'custom_post_type_package');
 
function custom_post_type_package() {
 
	$labels = array(
		'name' => _x('Packages', 'post type general name'),
		'singular_name' => _x('Package', 'post type singular name'),
		'add_new' => _x('Add New', 'package'),
		'add_new_item' => __('Add New Package'),
		'edit_item' => __('Edit Package'),
		'new_item' => __('New Package'),
		'view_item' => __('View Package'),
		'search_items' => __('Search Package'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => '',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail')
	  ); 
 
	register_post_type( 'package' , $args );	
}

$package_details = array(
	'price'    => 'text',
	'features' => 'text',
);

function add_package_meta() {
	 add_meta_box(
        'price-meta',
        __( 'Package Details', 'package_textdomain' ),
        'package_details',
        'package',
        'normal',
        'low'
    );
    
    }

function package_details() {
	global $post;
	global $package_details;
	$custom = get_post_custom($post->ID);
	
	foreach ($package_details as $field => $type) {
		$key = "package_{$field}";
		$label = ucfirst($field);
		
		$value = isset($custom[$key][0]) ? $custom[$key][0] : null;
		
		echo "
			<label style='width:75px; text-align:right; display:inline-block;'>{$label}</label>
			<input name='{$key}' value='{$value}' style='width:82%; display:inline-block;' />
			<br /><br />
		";
		
	}	
}

add_action( 'add_meta_boxes', 'add_package_meta' );


function save_package_details() {
	global $post;
	global $package_details;
	foreach ($package_details as $field => $type) {
		$key = "package_{$field}";
		
		if (is_object($post) && isset($post->ID)) {
			update_post_meta($post->ID, $key, isset($_POST[$key]) ? $_POST[$key] : null);
		}
	}
}

add_action('save_post', 'save_package_details');


function add_menu_icons_styles(){
	echo '<style>#adminmenu  .menu-icon-package div.wp-menu-image:before { content: "\f480"; }</style>'; 
}
