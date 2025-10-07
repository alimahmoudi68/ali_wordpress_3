<?php
$logoes = new WP_Query(
    array(
        'post_type' => 'logo',
        'posts_per_page' => 8 , 
    )
);
?>
<div class='flex flex-col md:flex-row md:justify-between items-center gap-x-[60px] mb-[60px] md:mb-[80px]'>
    <div class='w-full md:w-[calc(40%-30px)] flex flex-col items-center mb-8 md:mb-0'>
        <span class='text-primary-100 text-[0.9rem] mb-3'>درباره لیلا احمدی بیشتر بدانید</span>
        <span class='text-white-100 text-[2rem] font-medium mb-10'>درباره ما</span>
        <p class='text-white-100 text-[0.9rem] text-justify font-light mb-[16px]'>
            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی
        </p>
        <p class='text-white-100 text-[0.9rem] text-justify font-light mb-[16px]'>
            لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی
        </p>
        <div class="w-full flex flex-wrap border border-primary-100 rounded-lg p-5">
            <div class="w-full swiper-container swiper slider-logoes" dir="rtl">
                <div class="swiper-wrapper">

                    <?php 
                        while($logoes->have_posts()):
                            $logoes->the_post();
                    ?>
                    <div class="swiper-slide">
                        <?php
                            get_template_part( 'template/card', 'serviceSlider' );
                        ?>  
                    </div>

                    <?php endwhile; ?>
                    <?php wp_reset_query() ?>
                            
                </div>

                <div class="swiper-pagination"></div>

            </div>  
        </div>
    </div>
    <div class='w-full md:w-[calc(50%-30px)] flex justify-center md:justify-end items-center h-full'>
        <img src='<?php echo get_template_directory_uri().'/images/about.jpg' ?>' class="rounded-full p-[20px] md:p-[40px] border border-primary-100 w-[70%] h-full"/>
    </div>    
</div>
