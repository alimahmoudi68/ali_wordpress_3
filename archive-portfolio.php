<?php 
defined( 'ABSPATH' ) || exit;

$cat_url = home_url('/wp-json/myapi/v1/portfolios/cats');
$response_cats = wp_remote_get($cat_url);
$cats = array();
if (is_wp_error($response_cats)) {
    error_log('portfolio.php cats fetch error: ' . $response_cats->get_error_message());
} else {
    $body_cats = wp_remote_retrieve_body($response_cats);
    // Normalize payload: remove UTF-8 BOM, ZWNBSP, zero-width chars, and trim
    $body_cats = (string) $body_cats;
    $body_cats = preg_replace("/^\xEF\xBB\xBF+/", '', $body_cats); // BOM
    $body_cats = preg_replace("/^[\x{FEFF}\x{200B}\x{200C}\x{200D}\x{2060}\x{00A0}]+/u", '', $body_cats); // ZW* and NBSP
    $body_cats = ltrim($body_cats); // leading whitespace
    try {
        $data_cats = json_decode($body_cats, true, 512, JSON_THROW_ON_ERROR);
        if (is_array($data_cats) && isset($data_cats['cats']) && is_array($data_cats['cats'])) {
            $cats = $data_cats['cats'];
        } else {
            error_log('portfolio.php cats decode: missing cats key or invalid structure');
        }
    } catch (JsonException $e) {
        // Log first 200 chars of body for debugging
        error_log('portfolio.php cats JSON error: ' . $e->getMessage() . ' body=' . substr($body_cats, 0, 200));
    }
}


$attributes = []; 

$catTitle = "دسته بندی";
$catSlug = "cat";
$catItems = [];

foreach ($cats as $cat) {
    $catItems[] = (object)[
        "title" => $cat['title'], // مقدار 'title' به عنوان 'key'
        "slug" => $cat['slug'], // مقدار 'slug' به عنوان 'value'
        "count" =>$cat['count']
    ];
}


$attributes[] = (object)[
    "title" => $catTitle,
    "slug" => $catSlug ,
    "items" => $catItems
];


// var_dump( $attributes[0]->items[0]->title );
// wp_die();

$filterObj = new stdClass();
$filterElKeyObj = new stdClass();
$product_attributes = array(); // Store attributes and terms here

//-------- get init posts --------
$limit = 20;
$page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '1';
$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : '';
$cat = isset($_GET['cat']) ? sanitize_text_field($_GET['cat']) : '';


$post_url = home_url('/wp-json/myapi/v1/portfolios');
// Build initial query from current URL params so first render is filtered
$query_params = array();
if (!empty($page)) { $query_params['page'] = intval($page); }
if (!empty($sort)) { $query_params['sort'] = $sort; }
if (!empty($cat))  { $query_params['cat']  = $cat; }
$post_url = !empty($query_params) ? $post_url . '?' . http_build_query($query_params) : $post_url;
$response_posts = wp_remote_get($post_url);
//error_log('portfolio.php $response_posts=' . print_r($response_posts, true));

$posts = array();
$total_posts = 0;
if (is_wp_error($response_posts)) {
    error_log('portfolio.php posts fetch error: ' . $response_posts->get_error_message());
} else {
    $body_portfolio_req = (string) wp_remote_retrieve_body($response_posts);
    $body_portfolio_req = preg_replace("/^\xEF\xBB\xBF+/", '', $body_portfolio_req); // BOM
    $body_portfolio_req = preg_replace("/^[\x{FEFF}\x{200B}\x{200C}\x{200D}\x{2060}\x{00A0}]+/u", '', $body_portfolio_req); // ZW* and NBSP
    $body_portfolio_req = ltrim($body_portfolio_req);
    try {
        $data_portfolio_posts = json_decode($body_portfolio_req, true, 512, JSON_THROW_ON_ERROR);
        if (is_array($data_portfolio_posts)) {
            $posts = isset($data_portfolio_posts['portfolios']) && is_array($data_portfolio_posts['portfolios']) ? $data_portfolio_posts['portfolios'] : array();
            $total_posts = isset($data_portfolio_posts['total']) ? intval($data_portfolio_posts['total']) : 0;
        } else {
            error_log('portfolio.php posts decode: invalid root structure');
        }
    } catch (JsonException $e) {
        error_log('portfolio.php posts JSON error: ' . $e->getMessage() . ' body=' . substr($body_portfolio_req, 0, 200));
    }
}

//-------- /get init posts --------

get_header();
?>
<div class='content'>

    <span class="text-[1.2rem] dark:text-white-100 font-bold block mb-3" >
        ت
    </span>

    <div x-data="allPost()" class='w-full flex flex-col'> 

        <div class='w-full flex gap-x-3 mb-4 pb-2 overflow-x-auto md:overflow-x-visible scroll-smooth'>

            <!-- <?php
            foreach ( $attributes as $attribute ) {

                $attributeName = $attribute->title;
                $attributeSlug = $attribute->slug;
                $filterElKeyObj->$attributeSlug="";
                $filterObj->$attributeSlug=array();  
            ?> -->

            <div x-data="dropdown" class="w-fit flex flex-col bg-white-100 dark:bg-darkCard-100 rounded-lg relative">
                <div :class="{'md:border-gray-800' : open}" class='flex items-center gap-x-2 cursor-pointer px-4 py-2 border border-white-100 dark:border-darkCard-100 dark:text-white-100 md:hover:border-gray-800 rounded-md' @click="toggle()">
                    <h4><?php echo $attributeName ?></h4>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" :class="{'rotate-[180deg]': open}" class="w-[20px] h-[20px] stroke-gray-700 dark:stroke-white-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div x-show="open" x-cloak class='bg-darkTransparent-100 fixed right-0 left-0 bottom-0 top-0 z-[999999999999999999999999] md:hidden'></div>
                <div x-show="open" x-cloak x-collapse.duration.100ms  @click.outside="close" class='flex flex-col bg-white-100 dark:bg-darkCard-100 md:border md:border-gray-800 fixed left-0 bottom-0 z-[9999999999999999999999999] md:absolute gap-y-3 p-3 rounded-md md:top-[50px] md:bottom-auto md:left-auto right-0 md:z-100000000000000'>
                    <div class='flex items-center gap-x-1'>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-[16px] h-[16px] stroke-gray-700 dark:stroke-white-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input x-model="filterElementKey['<?php echo $attributeSlug; ?>']" class='outline-hidden pr-2 rounded-lg dark:text-white-100 dark:bg-darkBack-100 text-[0.9rem] text-[0.9rem] font-light placeholder:text-[0.9rem] placeholder:font-light placeholder:text-gray-600 dark:placeholder:text-white-100' placeholder='جستجو'>
                    </div>
                    <?php
                    $term_data = array(); 
                    foreach ( $attribute->items as $item ) {
                        // Count the number of products for each term
                    
                    ?>
                        <div x-show="isShowFilterElement('<?php echo $attributeSlug; ?>' , '<?php echo $item->title ?>')" class="w-full flex items-center gap-x-2 cursor-pointer" data-id="<?php echo $item->slug ?>" @click="updateFilters('<?php echo $item->slug ?>' , '<?php echo $attributeSlug ?>')">
                            <div class='w-[16px] h-[16px] border border-gray-600' :class="{'bg-primary-100 border-primary-100': filtersTemp['<?php echo $attributeSlug ?>'].includes('<?php echo $item->slug ?>')}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" :class="{'hidden': !filtersTemp['<?php echo $attributeSlug ?>'].includes('<?php echo $item->slug ?>')}" class="w-[16px] h-[18px] stroke-gray-700 dark:stroke-white-100">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </div>
                            <span class='dark:text-white-100'><?php echo $item->title ?> (<?php echo $item->count ?>)</span>
                        </div>
                    <?php 

                    $term_data[] = array(
                        'key' => $item->slug,    // Term slug (e.g., 'blue')
                        'value' => $item->title   // Term name (e.g., 'آبی')
                    );


                    $product_attributes[$attributeSlug] = $term_data; // Add this attribute's terms to the result
                    }
                    ?> 
                    <div class='flex items-center justify-center gap-x-2'>
                        <div class='border px-3 py-1 cursor-pointer rounded-md' @click="clearFilter('<?php echo $attributeSlug ?>')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-[24px] h-[24px] stroke-gray-700 dark:stroke-white-100">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>
                        <div class='px-3 py-1 bg-primary-100 text-white-100 cursor-pointer rounded-md' @click="makeFilterUrl(1)">
                            اعمال
                        </div>
                    </div> 

                </div>

            </div>

            <?php } ?>
        </div>


        <div class='flex items-center mb-4 md:mb-8 gap-x-3'>
            <template x-for="item in filterToList(filters)">
                <div class='flex items-center gap-x-3 px-3 py-1 rounded-md bg-gray-500'>
                    <span x-text="getAttribiteName(item.key , item.value)" class='text-white-100'></span>
                    <svg @click="updateFilters(item.value , item.key , true)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-[16px] h-[16px] stroke-white-100 cursor-pointer hover:opacity-80">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            </template>
        </div> 

        <div class='w-full flex gap-[10px] md:gap-[30px] flex-wrap relative'>

            <?php if (!empty($posts)) : ?>
            <div id="ssr-list" class='contents' x-show="!loaded" x-cloak >
                <?php foreach ($posts as $p) : ?>
                    <div class='w-[calc(50%-5px)] md:w-[calc(33%-10px)] lg:w-[calc(25%-30px)] card-product flex flex-col items-center bg-white-100 transition-all duration-300 rounded-lg dark:bg-darkCard-100 relative'>
                        <img src='<?php echo esc_url($p['image_medium'] ?? ''); ?>'
                        srcset="<?php echo esc_attr(($p['image_thumbnail'] ?? '') . ' 150w, ' . ($p['image_medium'] ?? '') . ' 450w'); ?>"
                        sizes="50vw , (min-width: 768px) 33vw  , (min-width: 1024px) 25vw" 
                        loading="lazy" 
                        decoding="async"
                        class='rounded-lg' 
                        width="450" 
                        height="400"/>
                        <div class='w-full flex flex-col p-3'>
                            <span class='w-full h-[50px] text-[0.9rem] md:text-[1rem] text-gray-700 dark:text-white-100'>
                                <?php echo esc_html($p['title'] ?? ''); ?>
                            </span>
                            <div class='w-full flex flex-col justify-end items-end text-sm font-light h-[80px]'>
                                <p class='w-full mb-3 dark:text-white-100 text-[0.8rem] md:text-[0.9rem]'><?php echo esc_html($p['body'] ?? ''); ?></p>
                                <div class='w-full flex justify-between items-center'>
                                    <div>
                                        <?php if (!empty($p['categories']) && is_array($p['categories'])) : ?>
                                            <?php foreach ($p['categories'] as $cat_name) : ?>
                                                <span class='text-[0.8] font-light text-gray-600 dark:text-white-100 px-2 py-1'># <?php echo esc_html($cat_name); ?></span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class='cursor-default flex items-center justify-center px-3 py-2 dark:text-white-100 rounded-lg bg-gray-200 dark:bg-darkBack-100'>
                                        مشاهده
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <span x-show="!loading && posts.length==0" x-cloak class='mx-auto my-[60px]'>
            موردی پیدا نشد :(
            </span>


            <template x-for="post in posts">
                <div class='w-[calc(50%-5px)] md:w-[calc(33%-10px)] lg:w-[calc(25%-30px)] card-product flex flex-col items-center bg-white-100 transition-all duration-300 rounded-lg dark:bg-darkCard-100 relative'>
                    <img :src='post.image_medium'
                    :srcset="post.image_thumbnail + ' 150w, '  + post.image_medium + ' 450w'"
                    sizes="50vw , (min-width: 768px) 33vw  , (min-width: 1024px) 25vw" 
                    loading="lazy" 
                    decoding="async"
                    class='rounded-lg' 
                    width="450" 
                    height="400"/>
                    <div class='w-full flex flex-col p-3'>
                        <span x-text='post.title' class='w-full h-[50px] text-[0.9rem] md:text-[1rem] text-gray-700 dark:text-white-100'>
                        </span>
                        <div class='w-full flex flex-col justify-end items-end text-sm font-light h-[80px]'>
                            <p x-text='post.body' class='w-full mb-3 dark:text-white-100 text-[0.8rem] md:text-[0.9rem]'></p>
                            <div class='w-full flex justify-between items-center'>
                                <template x-for="cat in post.categories">
                                    <span x-text="'# ' + cat" class='text-[0.8] font-light text-gray-600 dark:text-white-100 px-2 py-1'></span>
                                </template>
                                <div class='cursor-pointer flex items-center justify-center px-3 py-2 dark:text-white-100 rounded-lg bg-gray-200 dark:bg-darkBack-100' @click='showDetailHandler(post)'>
                                    مشاهده
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <noscript>
            <?php if (!empty($posts)) : ?>
            <div class='w-full flex gap-[10px] md:gap-[30px] flex-wrap relative'>
                <?php foreach ($posts as $p) : ?>
                    <div class='w-[calc(50%-5px)] md:w-[calc(33%-10px)] lg:w-[calc(25%-30px)] card-product flex flex-col items-center bg-white-100 transition-all duration-300 rounded-lg dark:bg-darkCard-100 relative'>
                        <img src='<?php echo esc_url($p['image_medium'] ?? ''); ?>'
                        srcset="<?php echo esc_attr(($p['image_thumbnail'] ?? '') . ' 150w, ' . ($p['image_medium'] ?? '') . ' 450w'); ?>"
                        sizes="50vw , (min-width: 768px) 33vw  , (min-width: 1024px) 25vw" 
                        class='rounded-lg' 
                        width="450" 
                        height="400"/>
                        <div class='w-full flex flex-col p-3'>
                            <span class='w-full h-[50px] text-[0.9rem] md:text-[1rem] text-gray-700 dark:text-white-100'>
                                <?php echo esc_html($p['title'] ?? ''); ?>
                            </span>
                            <div class='w-full flex flex-col justify-end items-end text-sm font-light h-[80px]'>
                                <p class='w-full mb-3 dark:text-white-100 text-[0.8rem] md:text-[0.9rem]'><?php echo esc_html($p['body'] ?? ''); ?></p>
                                <div class='w-full flex justify-between items-center'>
                                    <div>
                                        <?php if (!empty($p['categories']) && is_array($p['categories'])) : ?>
                                            <?php foreach ($p['categories'] as $cat_name) : ?>
                                                <span class='text-[0.8] font-light text-gray-600 px-2 py-1'># <?php echo esc_html($cat_name); ?></span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </noscript>

        <div x-show="loading" x-cloak class='mt-[10px] w-full flex flex-wrap gap-[10px]'>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
                <div class='w-[calc(50%-10px)] md:w-[calc(33%-20px)] lg:w-[calc(25%-30px)] h-[250px] bg-skeleton'></div>
        </div> 


        <<div x-show="showDetail" x-cloak class='bg-black-70 fixed top-0 bottom-0 left-0 right-0 md:pr-[290px] flex flex-col items-center justify-center'>
            
            <div class='w-full max-w-[80%] md:max-w-[60%] flex justify-end mb-3 ml-[0px]'>
                <div class='flex gap-x-1 p-1 border border-white-100 text-white-100 rounded-lg cursor-pointer' @click='closeDetailHandler()'>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[20px] h-[20px]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <div class="relative w-full max-w-[80%] md:max-w-[50%] rounded-lg overflow-hidden bg-black">
                <!-- استفاده از نسبت تصویر 9 به 16 -->
                <div class="relative aspect-16/9 w-full rounded-lg overflow-hidden">
                    <video controls class="absolute top-0 left-0 w-full h-full object-contain rounded-lg">
                        <source :src="detailData.video" type="video/mp4">
                        مرورگر شما از ویدیو پشتیبانی نمی‌کند.
                    </video>
                </div>
            </div>
            <div class='w-full max-w-[80%] md:max-w-[60%] bg-white-100 dark:bg-darkCard-100 p-5'>
                <span x-text='detailData.title' class='dark:text-white-100 font-bold mb-3'>
                </span>
                <span x-text='detailData.body' class='block dark:text-white-100 mb-3'>
                </span>
                <template x-if="detailData.files.length !== 0">
                    <div class='flex flex-col gap-y-2'>
                        <template x-for="file in detailData.files">
                            <div class='w-full flex justify-between py-1 px-3 rounded-lg bg-gray-200 dark:bg-darkBack-100'>
                                <div class='flex items-center justify-center'>
                                    <span x-text="file.post_file_title" class='text-[0.8rem] font-light text-gray-600 dark:text-white-100 px-2 py-1'></span>
                                    <template x-if="file.post_file_type == 'music'">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-[16px] h-[16px] stroke-gray-700 dark:stroke-white-100">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 0 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z" />
                                        </svg>
                                    </template>
                                    <template x-if="file.post_file_type == 'video'">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-[16px] h-[16px] stroke-gray-700 dark:stroke-white-100">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                        </svg>
                                    </template>
                                </div>
                                <div class='flex items-center justify-center'>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-[16px] h-[16px] stroke-gray-700 dark:stroke-white-100 cursor-pointer">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                    </svg>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

            </div>
        </div> 
    </div>


    <?php 
    foreach ($attributes as $attribute) {
        $attributeName = $attribute->title; // ? مثل color
        if ( isset($_GET[$attributeName]) ) { // اگر ویژگی‌ای از درخواست ارسال شده باشد
            $terms = explode(',', sanitize_text_field($_GET[$attributeName]));
            $filterObj->$attributeName = $terms;
        }
    }
    ?>

    <?php
    // داده‌های مورد نیاز برای js
    $wpPortfolioData = [
        'url' =>  home_url('/wp-json/myapi/v1/portfolios'),
        'filterElementKey' => $filterElKeyObj,
        'filtersTemp' => $filterObj,
        'filters' => $filterObj,
        'allAttributes' => $product_attributes,
        'limit' => intval($limit),
        'totalPosts' => intval($total_posts),
        'posts' => $posts,
    ];

    // برای حالت نرمال (نه AJAX) همچنان localize اجرا شود
    wp_localize_script('archive-portfolio', 'wpPortfolio', $wpPortfolioData);
    ?>

    <!-- 🔥 این بخش جدید است: داده را مستقیماً در HTML می‌ریزیم تا با fetch هم برگردد -->
    <script id="wpPortfolio-data">
    window.wpPortfolio = <?php echo wp_json_encode($wpPortfolioData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>;
    </script>

</div>    




<?php get_footer() ?>