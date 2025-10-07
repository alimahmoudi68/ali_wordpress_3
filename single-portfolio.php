<?php 
$lang = get_current_lang();
get_header();

$galleries = get_post_meta( get_the_ID() , 'gallery_group' , true);
?>
<?php while(have_posts()) : the_post(); ?>

<main class='w-full flex-grow'>
    <div class="container mx-auto px-2 pt-[70px] md:pt-[150px]">
           <h1 class="font-bold text-[1.2rem] text-textPrimaryLight-100 dark:text-textPrimaryDark-100 mb-5">
                <?php echo ( $lang == 'en' ) ?  get_post_meta( get_the_ID(), 'english_title', true) :  get_post_meta( get_the_ID(), 'persian_title', true) ?>
            </h1>
        <div class='flex gap-[32px] items-center'>
            <div class='w-full xl:w-[calc(50%_-_16px)]'>
                <p class="text-textPrimaryLight-100 dark:text-textPrimaryDark-100 mb-5">
                <?php echo ( $lang == 'en' ) ?  get_post_meta( get_the_ID(), 'english_description', true) :  get_post_meta( get_the_ID(), 'persion_description', true) ?>
                </p>
            </div>
            <div class="w-full xl:w-[calc(50%_-_16px)] flex flex-wrap justify-center">
                <div class="w-full swiper slider-single-portfolio">
                    <div class="swiper-wrapper">
                        <?php 
                            foreach($galleries as $gallery) {
                                ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo $gallery['gallery_group_image'];  ?>" class='rounded-lg'/>
                                </div>
                            <?php } ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>

             </div>

        </div>

        

        <?php endwhile; ?>

    </div>
</main>


<?php get_footer() ?>