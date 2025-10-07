<?php 
    $lang = get_current_lang();
    $portfolio = new WP_Query(
        array(
            'post_type' => 'portfolio',
            'posts_per_page' => 4 , 
        )
    );
    if($portfolio->have_posts()){
?>
<div class='w-full flex flex-col items-start mb-[60px] md:mb-[80px]'>
 
    <div class='w-full flex justify-between items-center mb-10'>
        <div class='flex items-center gap-x-[10px]'>
            <div class='w-[5px] h-[50px] bg-black-100 dark:bg-textSecondaryDark-100'></div>
            <span class='flex flex-col text-primary-100 text-[2rem] font-bold '>
                <?php echo ( $lang == 'en' ) ? 'My Latest Portfolio' : 'جدیدترین نمونه کارهای من' ?>
            </span>
        </div>
        <a href='#' class='dark:text-textPrimaryDark-100 text-textPrimaryDark-100 dark:hover:text-primary-100 hover:text-primary-100 hover:underline'>
            مشاهده همه
        </a>
    </div>


    <div class="w-full swiper-container swiper slider-portfolio pb-30" >
        <div class="swiper-wrapper">

            <?php 
                while($portfolio->have_posts()):
                    $portfolio->the_post();
            ?>
            <div class="swiper-slide">
                <?php
                    get_template_part( 'template/card', 'newPortfolioSlider' );
                ?>  
            </div>

            <?php endwhile; ?>
            <?php wp_reset_query() ?>
                    
        </div>

        <div class="swiper-pagination"></div>

    </div>  


</div>



<?php } ?>



