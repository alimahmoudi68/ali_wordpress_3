<?php
$teammates = new WP_Query(
    array(
        'post_type' => 'teammate',
        'posts_per_page' => 8 , 
    )
);
if($teammates->have_posts()){
?>


<div class='w-full flex flex-col items-center mb-[60px] md:mb-[80px]'>
    <span class='text-primary-100 text-[0.9rem] mb-3'>ما با چه کسانی همکاریم</span>
    <span class='text-white-100 text-[2rem] font-medium mb-10'>تیم ما</span>

    <div class="w-full flex flex-wrap" >
        <div class="w-full swiper-container swiper slider-teammates pb-30" dir="rtl">
            <div class="swiper-wrapper">

                <?php 
                    while($teammates->have_posts()):
                        $teammates->the_post();
                ?>
                <div class="swiper-slide">
                    <?php
                        get_template_part( 'template/card', 'teamSlider' );
                    ?>  
                </div>

                <?php endwhile; ?>
                <?php wp_reset_query() ?>
                        
            </div>

            <div class="swiper-pagination"></div>

        </div>  
    </div>


</div>

<?php } ?>