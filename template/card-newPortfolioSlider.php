<?php 
    $lang = get_current_lang();
    $content = '';
    if ( $lang == 'en' ) {
        $content = get_post_meta( get_the_ID(), 'english_description', true);
    }else{
        $content = get_post_meta( get_the_ID(), 'persion_description', true);
    }
    $short_content = mb_substr(wp_strip_all_tags($content), 0, 50); 

    // دریافت دسته‌بندی‌ها
    $categories = get_the_terms(get_the_ID(), 'portfolio-cat');
    $category_names = array();

    if (!empty($categories) && !is_wp_error($categories)) {
         foreach ($categories as $category) {
             $category_names[] = $category->name; // نام دسته‌بندی
         }
     }


?>
    <div
        class='card-product flex flex-col hover:border-primary-100 dark:hover:border-primary-100 items-center h-full transition-all duration-300 rounded-lg border border-[3px] border-black-100 dark:border-textSecondaryDark-100 bg-white-100 dark:bg-dark-card-100 relative'>
        <div class='w-full overflow-hidden card-image-container rounded-lg'>
            <a href="<?php the_permalink() ?>">
                <?php the_post_thumbnail('post'); ?>
            </a>
        </div>
        <!-- <div class='w-full h-[230px]'>
        </div> -->
        <div class='flex flex-col w-full mt-2 p-3'>
            <div class='flex items-center text-sm font-semibold text-black-100 dark:text-white-100 mb-2'>
                <a href="<?php the_permalink() ?>">
                    <span>
                        <?php echo ( $lang == 'en' ) ? get_post_meta( get_the_ID(), 'english_title', true) : get_post_meta( get_the_ID(), 'persian_title', true) ?>
                    </span>
                </a>
            </div>
            <div
                class='w-full h-[50px] flex items-start flex-grow text-[0.8rem] font-light text-black-100 dark:text-white-100'>
                <a href="<?php the_permalink() ?>">
                <?php echo $short_content."..." ?>
                </a>
            </div>
            
        </div>
        <div class='w-full flex gap-x-2 justify-end text-sm font-light px-3 pb-3'>
            <?php 
                foreach ($category_names as $cat) { ?>
                        <span>   
                            <?php echo "#".$cat ?>
                        </span>
                <?php }
            ?>
         
        </div>
    </div>

