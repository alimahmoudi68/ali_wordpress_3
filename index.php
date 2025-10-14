<?php get_header() ?>
    <div x-ref="home" class="w-[500px] flex items-center justify-around content">
        <button @click="goTo('/about/')" class="cursor-pointer hover:text-primary-100 px-3 py-1">درباره من</button>
        <img class="w-auto h-[350px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
        <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
        <img class="w-auto h-[350px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
        <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
        <img class="w-auto h-[350px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
        <button @click="goTo('/contact/')" class="cursor-pointer hover:text-primary-100 px-3 py-1">تماس با من</button>
    </div>
<?php get_footer() ?>



