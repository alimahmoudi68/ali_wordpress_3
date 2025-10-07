<?php 
add_action('rest_api_init', 'getProducts');
function getProducts(){
	register_rest_route('myapi/v1', '/portfolio' , array(
		'methods' => 'POST',
	    'callback' => 'get_products_function'
    ));


    function get_products_function($data){

        $page = sanitize_text_field($data['page']);
        $sort = sanitize_text_field($data['sort']);
        $cat = sanitize_text_field($data['cat']);


        if (!$page) {
            $page = 1;
        }


        if (!empty($q)) {
            $args['s'] = $q;
        }


        add_filter( 'posts_where', 'title_filter', 10, 2 );



        $args = array(
            'post_type' =>  'portfolio',
            'post_status' => 'publish',
            'no_found_rows' => true ,
            'posts_per_page' => 20 ,
            'paged' => $page  ,
        );



        if(  $sort  == 'expensive'  ){
            $args ['orderby'] = 'meta_value_num';
            $args ['meta_key'] ='_price';
            $args ['order'] = 'desc';
        }

        if(  $sort  == 'cheapest'  ){
            $args ['orderby'] = 'meta_value_num';
            $args ['meta_key'] ='_price';
            $args ['order'] = 'asc';
        }

        
	    $query = new WP_Query($args);
        
        $portfolio = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;

                $product_id = get_the_ID(); 
                $product_title = get_the_title(); 
                $product_permalink = get_permalink(); 
                // $product_img_thumbnail = get_the_post_thumbnail_url($product_id, 'thumbnail'); 
                // $product_img_medium = get_the_post_thumbnail_url($product_id, 'medium'); 
                // $product_img_450 = get_the_post_thumbnail_url($product_id, 'product-450'); 

            

                $portfolio[] = array(
                    'title' => $product_title,
                    'link'=> $product_permalink ,
                    // 'image_thumbnail' => $product_img_thumbnail,
                    // 'image_medium' => $product_img_medium,
                    // 'image_450' => $product_img_450,
                   
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
        $total_products = $total_query->found_posts; // تعداد کل محصولات با توجه به شرط‌ها
        //------- /pagination -------


        // بازگرداندن نتیجه
        return new WP_REST_Response(array(
            'current_page' => (int)$page,
            'total' =>  $total_products,
            'portfolio' => $portfolio,
        ), 200);
    }

}

?>