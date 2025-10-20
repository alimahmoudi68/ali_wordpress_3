<?php
/**
 * Theme Functions File
 * ---------------------
 * Includes scripts, registers post types, taxonomies, API, and multilingual rewrites.
 */

/*------------------------------------*\
  # Includes
\*------------------------------------*/

require_once dirname(__FILE__) . '/cmb2/init.php';
require_once dirname(__FILE__) . '/functions/metaData/portfolio_metadata.php';
require_once dirname(__FILE__) . '/functions/rewrites/rewrites.php';


/*------------------------------------*\
  # Enqueue Styles & Scripts
\*------------------------------------*/

function theme_enqueue_assets() {

	wp_enqueue_style('fontiran', get_template_directory_uri() . '/css/fontiran.css', [], false, 'all');
	wp_enqueue_style('style2', get_template_directory_uri() . '/css/style2.css', [], false, 'all');
	wp_enqueue_style('main-style', get_stylesheet_uri(), [], false, 'all');
	wp_enqueue_script('master', get_template_directory_uri() . '/js/master.js', [], false, true);

	// صفحه خانه
	if (is_home()) {
		wp_enqueue_script('index', get_template_directory_uri() . '/js/index.js', [], false, true);
	}

	// صفحه آرشیو نمونه‌کارها
	// if (is_post_type_archive('portfolio')) {
	// 	wp_enqueue_script('archive-portfolio', get_template_directory_uri() . '/js/archive-portfolio.js', [], '1.0.0', true);
	// }
	if (true) {
		wp_enqueue_script('archive-portfolio', get_template_directory_uri() . '/js/archive-portfolio.js', [], '1.0.0', true);
	}

	// صفحه تکی نمونه‌کار
	if (is_singular('portfolio')) {
		wp_enqueue_script('swiper', get_template_directory_uri() . '/js/swiper.min.js', [], false, true);
		wp_enqueue_style('swiper', get_template_directory_uri() . '/css/swiper.min.css', [], false, 'all');
		wp_enqueue_script('portfolio-single', get_template_directory_uri() . '/js/portfolio-single.js', ['swiper'], null, true);
		wp_enqueue_style('portfolio-single', get_template_directory_uri() . '/css/portfolio-single.css', [], false, 'all');
	}

	// رنگ اصلی پوسته
	$colorPrimary = get_option('my_color', '#0088ff');
	wp_add_inline_style('main-style', ":root{--colorPrimary: {$colorPrimary};}");
}
add_action('wp_enqueue_scripts', 'theme_enqueue_assets');


/*------------------------------------*\
  # Portfolio Post Type
\*------------------------------------*/

function register_portfolio_post_type() {
	$labels = [
		'name'               => 'نمونه کارها',
		'singular_name'      => 'نمونه کار',
		'menu_name'          => 'نمونه کارها',
		'add_new'            => 'افزودن',
		'add_new_item'       => 'افزودن نمونه کار جدید',
		'edit_item'          => 'ویرایش نمونه کار',
		'new_item'           => 'نمونه کار جدید',
		'view_item'          => 'مشاهده نمونه کار',
		'all_items'          => 'تمام نمونه کارها',
		'search_items'       => 'جستجوی نمونه کار',
		'not_found'          => 'نمونه‌کاری یافت نشد',
		'not_found_in_trash' => 'نمونه‌کاری در زباله‌دان یافت نشد',
	];

	register_post_type('portfolio', [
		'public'       => true,
		'labels'       => $labels,
		'menu_icon'    => 'dashicons-portfolio',
		'has_archive'  => true,
		'rewrite'      => ['slug' => 'portfolio'],
		'supports'     => ['thumbnail', 'title', 'comments'],
		'show_in_rest' => true,
	]);
}
add_action('init', 'register_portfolio_post_type');


/*------------------------------------*\
  # Portfolio Categories
\*------------------------------------*/

function register_portfolio_category() {
	$labels = [
		'name'              => 'دسته‌بندی‌ها',
		'singular_name'     => 'دسته‌بندی',
		'search_items'      => 'جستجوی دسته‌بندی',
		'all_items'         => 'همه دسته‌بندی‌ها',
		'parent_item'       => 'دسته والد',
		'edit_item'         => 'ویرایش دسته',
		'update_item'       => 'به‌روزرسانی دسته',
		'add_new_item'      => 'افزودن دسته جدید',
		'new_item_name'     => 'نام دسته جدید',
		'menu_name'         => 'دسته‌بندی‌ها',
	];

	register_taxonomy('portfolio-cat', 'portfolio', [
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'public'            => true,
		'rewrite'           => ['slug' => 'portfolio-cat'],
	]);
}
add_action('init', 'register_portfolio_category');


/*------------------------------------*\
  # Theme Setup
\*------------------------------------*/

function theme_setup() {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('post', 768, 432, true);
}
add_action('after_setup_theme', 'theme_setup');


/*------------------------------------*\
  # Custom Excerpt Length
\*------------------------------------*/

function custom_excerpt_length($excerpt) {
	if (has_excerpt()) {
		$excerpt = wp_trim_words(get_the_excerpt(), 100);
	}
	return $excerpt;
}
add_filter('the_excerpt', 'custom_excerpt_length', 999);



/*------------------------------------*\
  # Admin Dashboard Widget
\*------------------------------------*/

if (is_admin()) {
	add_action('wp_dashboard_setup', function() {
		wp_add_dashboard_widget(
			'my_widget_about_id',
			'تماس با من',
			function() {
				echo '<div><span>برای سفارش طراحی سایت با من تماس بگیرید: <strong>09158302458</strong></span></div>';
			}
		);
	});
}


/*------------------------------------*\
  # Multilingual URL Rewrite
\*------------------------------------*/

function custom_rewrite_rules() {
	add_rewrite_rule('^en/?$', 'index.php', 'top');
	add_rewrite_rule('^en/([^/]+)/?$', 'index.php?pagename=$matches[1]', 'top');
	add_rewrite_rule('^en/portfolio/([^/]+)/?$', 'index.php?post_type=portfolio&name=$matches[1]&lang=fa', 'top');
}
add_action('init', 'custom_rewrite_rules');


function get_current_lang() {
	$home_path = parse_url(home_url(), PHP_URL_PATH);
	return (strpos($_SERVER['REQUEST_URI'], $home_path . '/en') === 0) ? 'en' : 'fa';
}


function switch_language_url($target_lang) {
	$current_url = $_SERVER['REQUEST_URI'];
	$home_path = parse_url(home_url(), PHP_URL_PATH);
	$is_english = strpos($current_url, $home_path . '/en') === 0;

	$new_url = $is_english
		? str_replace($home_path . '/en', $home_path, $current_url)
		: $current_url;

	if ($target_lang === 'en' && !$is_english) {
		$new_url = $home_path . '/en' . str_replace($home_path, '', $current_url);
	}

	return $new_url;
}


function make_url($lang, $address) {
	$home_path = parse_url(home_url(), PHP_URL_PATH);
	$url = ($lang === 'en') ? "$home_path/en$address" : "$home_path$address";
	return str_replace('//', '/', $url);
}


/*------------------------------------*\
  # API
\*------------------------------------*/

require_once dirname(__FILE__) . '/functions/api/portfolio.php';
