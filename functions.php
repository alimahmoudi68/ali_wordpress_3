<?php

// cmb2
require_once dirname( __FILE__ ) . '/cmb2/init.php';

// metaData
require_once dirname( __FILE__ ) . '/functions/metaData/portfolio_metadata.php';


// rewrites
require_once dirname( __FILE__ ) . '/functions/rewrites/rewrites.php';


// api
require_once dirname( __FILE__ ) . '/functions/api/portfolio.php';



// incluse css and js
function add_theme_scripts(){
	// wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), null, true);
	// wp_enqueue_script('master' , get_template_directory_uri().'/js/alpine.js' , array() , false , true);
	wp_enqueue_style('fontiran' , get_template_directory_uri().'/css/fontiran.css' , array() , false , 'all');
	wp_enqueue_style('style2' ,  get_template_directory_uri().'/css/style2.css' , array() , false , 'all');
	wp_enqueue_style('style' , get_stylesheet_uri() , array() , false , 'all');
	wp_enqueue_script('master' , get_template_directory_uri().'/js/master.js' , array() , false , true);



	if ( is_home() ) {
		// wp_enqueue_script('typewriter', get_template_directory_uri() . '/js/typewriter.js' , array() , false , true );
		// wp_enqueue_script('swiper.min', get_template_directory_uri() . '/js/swiper.min.js' , array() , false , true );
		// wp_enqueue_style('swiper.min' , get_template_directory_uri().'/css/swiper.min.css' , array() , false , 'all');
		wp_enqueue_script('index', get_template_directory_uri() . '/js/index.js' , array('swiper.min') , false , true );
		// Pass basePath (works for subfolder installs like /portfolio)
		// wp_localize_script('master', 'themeGlobals', array(
		// 	'basePath' => parse_url(home_url(), PHP_URL_PATH),
		// ));
	}


	   if (is_singular('portfolio')) {
			wp_enqueue_script('swiper.min', get_template_directory_uri() . '/js/swiper.min.js' , array() , false , true );
			wp_enqueue_style('swiper.min' , get_template_directory_uri().'/css/swiper.min.css' , array() , false , 'all');
			wp_enqueue_script(
				'portfolio-single-script', // نام هندل
				get_template_directory_uri() . '/js/portfolio-single.js', // مسیر فایل JS
				array('swiper.min'), // وابستگی‌ها (در صورت نیاز می‌توانید jQuery یا ... اضافه کنید)
				null, // نسخه (می‌تونید از filemtime استفاده کنید برای کش)
				true // بارگذاری در footer
			);
			wp_enqueue_style('single-portfolio' , get_template_directory_uri().'/css/portfolio-single.css'  , array() , false , 'all');
    	}


	$colorPrimary = get_option('my_color');
	wp_add_inline_style('style' , 
	":root{--colorPrimary : $colorPrimary;}" 
	);
}
add_action('wp_enqueue_scripts' , 'add_theme_scripts');



// portfolio post type
function my_portfolio_post_type(){
	$labels1 =  array(
		'name' => 'نمونه کارها',
		'singular_name' => 'نمونه کار',
		'menu_name' => 'نمونه کارها',
		'name_admin_bar' => 'نمونه کارها',
		'add_new' => 'افزودن',
		'add_new_item' => 'افزودن نمونه کار',
		'new_item' => 'نمونه کار جدید',
		'edit_item' => 'ویرایش نمونه کار',
		'view_item' => 'مشاهده نمونه کار',
		'all_items' => 'تمام نمونه کارها',
		'search_items' => 'جستجوی نمونه کار', 
		'parent_item_colon' => 'مادر',
		'not_found' => 'نمونه کار ای پیدا نشد',
		'not_found_in_trash' => 'نمونه کاری در سطل زباله یافت نشد',
	);
	register_post_type('portfolio' , array(
		'public' => true ,
		'labels' => $labels1 , 
		'exclude_from_search' => true , 
		'menu_icon' => 'dashicons-editor-ul',
		'has_archive' => true,
		'rewrite'     => array( 'slug' => 'portfolio' ),
		'supports' => array( 'thumbnail' , 'title' , 'comments'),
	)
	);
}
add_action('init' , 'my_portfolio_post_type');


// Add new taxonomy for portfolio
function register_portfolio_cat_taxonomy(){

	$labels = array(
		'name' => _x( 'دسته‌بندی', 'دسته‌بندی' ),
		'singular_name' => _x( 'دسته بندی تی‌وی', 'دسته‌بندی' ),
		'search_items' =>  __( 'جستجوری دسته‌بندی‌ها' ),
		'all_items' => __( 'همه دسته‌بندی‌ها' ),
		'parent_item' => __( 'زیر دسته' ),
		'parent_item_colon' => __( 'دسته پدر:' ),
		'edit_item' => __( 'پیرایش دسته' ), 
		'update_item' => __( 'به روز رسانی دسته' ),
		'add_new_item' => __( 'افزودن دسته‌بندی جدید' ),
		'new_item_name' => __( 'عنوان دسته‌بندی جدید' ),
		'menu_name' => __( 'دسته‌بندی‌ها' ),
	);
	$args = array(
		'hierarchical' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'labels'                  => $labels,
		'public'         		  => true,
		'query_var '              => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'has_archive' => true,
	);
	register_taxonomy( 'portfolio-cat', 'portfolio' , $args );
}
add_action('init' , 'register_portfolio_cat_taxonomy');




function my_setup(){
	add_theme_support('title-tag');  
	add_theme_support('post-thumbnails'); 
	add_image_size('post' ,'768' , '432' , true ); 
}
add_action('after_setup_theme' , 'my_setup');




// lenth preview blog post
function custom_excerpt_length($excerpt) {
    if (has_excerpt()) {
        $excerpt = wp_trim_words(get_the_excerpt(), apply_filters("excerpt_length", 100));
    }
    return $excerpt;
}
add_filter("the_excerpt", "custom_excerpt_length", 999);




function title_filter( $where, $wp_query ){
    global $wpdb;
    if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $search_term ) ) . '%\'';
    }
    return $where;
}


// --- add dashboard widget ----
if(is_admin()){
	add_action('wp_dashboard_setup' , 'add_my_wifget');
	function add_my_wifget(){
		wp_add_dashboard_widget(
			'my_widget_about_id' ,
			'تماس با من' ,
			'my_widget_render_callback' , 
			null, null, 'normal' ,  
			'high'
		);

	}

	function my_widget_render_callback(){
		echo "<div>
		<span>
			برای سفارش طراحی سایت می‌توانید با من در تماس باشید 09158302458
		</span>
		</di>" ;
	}

}


// multi language
function custom_rewrite_rules() {
    // بازنویسی برای صفحه اصلی انگلیسی (با مسیر '/en')
    add_rewrite_rule('^en/?$', 'index.php', 'top');
    
    // بازنویسی برای صفحات دیگر انگلیسی (مانند '/en/about')
    add_rewrite_rule('^en/([^/]+)/?$', 'index.php?pagename=$matches[1]', 'top');
    
    // مسیرهای فارسی هم پیش‌فرض به همان فایل index.php هدایت می‌شوند

	// برای پست تایپ پورتفولیو است
	add_rewrite_rule(
	'^en/portfolio/([^/]+)/?$',
	'index.php?post_type=portfolio&name=$matches[1]&lang=fa',
	'top'
    );
}
add_action('init', 'custom_rewrite_rules');



function get_current_lang() {

	$home_path = parse_url(home_url(), PHP_URL_PATH);

    // بررسی می‌کنیم که آیا URL شامل '/en' است یا خیر
    if (strpos($_SERVER['REQUEST_URI'], $home_path . '/en') === 0) {
        return 'en'; // زبان انگلیسی
    } else {
        return 'fa'; // زبان فارسی
    }
}




function switch_language_url($target_lang) {
    // گرفتن URL فعلی
    $current_url = $_SERVER['REQUEST_URI'];

    // به دست آوردن مسیر نسبی home_url
    $home_path = parse_url(home_url(), PHP_URL_PATH);

    // بررسی اینکه آیا زبان فعلی انگلیسی است (وجود '/en' بعد از home_path)
    $is_english = strpos($current_url, $home_path . '/en') === 0;

    // حذف '/en' از URL اگر زبان فعلی انگلیسی است
    if ($is_english) {
        $new_url = str_replace($home_path . '/en', $home_path, $current_url);
    } else {
        $new_url = $current_url;
    }

    // اضافه کردن '/en' به URL اگر زبان هدف انگلیسی است
    if ($target_lang == 'en' && !$is_english) {
        $new_url = $home_path . '/en' . str_replace($home_path, '', $current_url);
    }

    return $new_url;
}


function make_url($lang, $address) {
    // دریافت دامنه و مسیر فعلی
    $home_path = parse_url(home_url(), PHP_URL_PATH);
    
    // ساخت URL بر اساس زبان و آدرس
    if ($lang == 'en') {
        $url =  "$home_path/en$address";
    } else {
        $url = "$home_path$address";
    }

    // حذف هرگونه اضافی '/' در ابتدای آدرس
    return str_replace('//', '/', $url);
}


?>