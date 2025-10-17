<?php get_header() ?>
    <div class='content'>
        <div x-ref="home" class="h-full flex items-center justify-around">
            <button @click="goTo('/about/')" class="h-full cursor-pointer hover:text-primary-100 px-3 hover:px-8 duration-300 py-1">درباره من</button>
            <img class="w-auto h-[550px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
            <button @click="goTo('/portfolio/')" class="h-full cursor-pointer hover:text-primary-100 px-3 hover:px-8 duration-300 py-1">نمونه کارها</button>
            <img class="w-auto h-[550px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
            <button @click="goTo('page3')" class="h-full cursor-pointer hover:text-primary-100 px-3 hover:px-8 duration-300 py-1">صفحه سوم</button>
            <img class="w-auto h-[550px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
            <button @click="goTo('/contact/')" class="h-full cursor-pointer hover:text-primary-100 px-3 hover:px-8 duration-300 py-1">تماس با من</button>
        </div>
    </div>
<?php get_footer() ?>



