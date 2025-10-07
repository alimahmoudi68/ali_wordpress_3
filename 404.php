<?php get_header() ?>

<main class="flex-grow">
    <div class="container w-full h-full mx-auto flex flex-col justify-center items-center">
        <img src="<?php echo get_template_directory_uri(); ?>/images/error404.png">
        <h1>صفحه‌ای که دنبال آن بودید پیدا نشد!</h1>
        <button class="px-3 py-2 bg-primary-100 border border-primary-100 mt-2 rounded-md text-white-100 hover:bg-transparent hover:text-primary-100"><a href="<?php echo home_url(); ?>">برو به صفحه اصلی</a></button>
    </div>
</main>

<?php get_footer() ?>