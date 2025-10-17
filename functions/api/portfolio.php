<?php
/**
 * Portfolio REST API Endpoints
 */

add_action('rest_api_init', 'register_portfolio_routes');

/**
 * Register REST API routes
 */
function register_portfolio_routes() {
	register_rest_route('myapi/v1', '/portfolios', [
		'methods'             => 'GET',
		'callback'            => 'get_portfolio_list',
		'permission_callback' => '__return_true', // Public access
	]);

	register_rest_route('myapi/v1', '/portfolios/cats', [
		'methods'             => 'GET',
		'callback'            => 'get_portfolio_categories',
		'permission_callback' => '__return_true',
	]);
}


/**
 * Get portfolio list
 */
if (!function_exists('get_portfolio_list')) {
	function get_portfolio_list(WP_REST_Request $request) {

        $limit = 20;
        $page = (int) sanitize_text_field($request->get_param('page'));
        $sort = sanitize_text_field($request->get_param('sort'));
        $cat = sanitize_text_field($request->get_param('cat'));


        if (!$page) {
            $page = 1;
        }


        $args = array(
            'post_type' =>  'portfolio',
            'post_status' => 'publish',
            'no_found_rows' => true ,
            'posts_per_page' => $limit ,
            'paged' => $page  ,
        );



        if ($sort == 'new') {
            $args['orderby'] = 'ID'; // مرتب‌سازی بر اساس ID
            $args['order'] = 'DESC'; // از جدید به قدیم
        }
        
        if ($sort == 'old') {
            $args['orderby'] = 'ID'; // مرتب‌سازی بر اساس ID
            $args['order'] = 'ASC'; // از قدیم به جدید
        }


        // اگر مقدار $cat وجود دارد
        if (!empty($cat)) {
            // جدا کردن مقادیر بر اساس کاما و تبدیل به آرایه
            $cat_slugs = array_map('trim', explode(',', $cat));

            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'portfolio-cat', // نوع taxonomy
                    'field' => 'slug', // جستجو بر اساس slug
                    'terms' => $cat_slugs, // آرایه‌ای از اسلاگ‌ها
                    'operator' => 'IN' // جستجو برای هرکدام از اسلاگ‌ها
                )
            );
        }

        // اجرای کوئری
	    $query = new WP_Query($args);
        
        // آماده‌سازی نتیجه
        $posts = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $content = get_the_content(); 
                $short_content = mb_substr(wp_strip_all_tags($content), 0, 20); // حذف تگ‌های HTML و نمایش 20 کاراکتر اول


                $post_id = get_the_ID(); // دریافت ID محصول
                $post_title = get_the_title(); // دریافت عنوان محصول
                $post_content = esc_html($short_content).'...';
                $post_thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                $post_img_450 = get_the_post_thumbnail_url($post_id, 'product-450'); 
                $video =  get_post_meta( get_the_ID(), 'video_post', true);
                $files =  get_post_meta( get_the_ID(), 'files_group', true);

        // دریافت دسته‌بندی‌های نمونه‌کار
        $categories = get_the_terms($post_id, 'portfolio-cat');
                $category_names = array();

                if (!empty($categories) && !is_wp_error($categories)) {
                    foreach ($categories as $category) {
                        $category_names[] = $category->name; // نام دسته‌بندی
                    }
                }

           
        
                $posts[] = array(
                    'id' => $post_id ,
                    'title' => $post_title,
                    'body' => $post_content ,
                    'video' =>  $video ,
                    'files' => $files ,
                    'image_thumbnail' => $post_thumbnail,
                    'image_medium' => $post_img_450,
                    'categories' => $category_names, // اضافه کردن دسته‌بندی‌ها

                );
            }
            wp_reset_postdata();
        }


        //------- pagination -------
        $total_args = $args;
        unset($total_args['posts_per_page']);
        unset($total_args['paged']);
        unset($total_args['no_found_rows']); // برای محاسبه تعداد کل محصولات، باید این را حذف کنیم

        $total_query = new WP_Query($total_args);
        $total_posts = $total_query->found_posts; // تعداد کل محصولات با توجه به شرط‌ها
        //------- /pagination -------


        // بازگرداندن نتیجه
        return new WP_REST_Response(array(
            'current_page' => (int)$page,
            'total' =>  $total_posts,
            'portfolios' => $posts,
        ), 200);
	}
}


/**
 * Get portfolio categories
 */
if (!function_exists('get_portfolio_categories')) {
	function get_portfolio_categories() {
		$terms = get_terms([
			'taxonomy'   => 'portfolio-cat',
			'hide_empty' => false,
		]);

		if (is_wp_error($terms)) {
			return new WP_REST_Response(['cats' => []], 200);
		}

		$cats = array_map(static function($term) {
			return [
				'id'    => (int) $term->term_id,
				'title' => $term->name,
				'slug'  => $term->slug,
				'count' => (int) $term->count,
			];
		}, $terms);

		return new WP_REST_Response(['cats' => $cats], 200);
	}
}
