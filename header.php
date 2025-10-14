<?php
    $langs = array('fa' , 'en');
    $lang = get_current_lang();
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
    <meta name="theme-color" content="<?php echo get_option('my_color'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="description" content="<?php bloginfo('description') ?>">
    <meta name="keywords" content="<?php bloginfo('description') ?>">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri().'/images/favicon.png' ?>" title="Favicon" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <?php wp_head(); ?>
</head>

<body dir="<?php echo ( $lang == 'en' ) ? 'ltr' : 'rtl' ?>" class='h-screen bg-cream-100 font-pinar dark:bg-dark-100 flex flex-col items-center justify-center relative overflow-hidden'> 

<main class="w-full h-full flex flex-wrap items-around">

    <div x-data="pageController()" class="container mx-auto my-auto px-5" x-ref="container">
        <div class="w-full flex flex-wrap items-center justify-center space-x-6 relative">
        <!-- افکت نوری -->
        <div x-ref="flash" class="flash"></div>

          <!-- تاپ منو -->
          <template x-if="showTopMenu">
            <div class="w-full h-fit flex items-center justify-center">
            <button @click="loadPage('/')" 
                      :class="currentPage === '/' || initUrl === '/' ? 'border-r-2 border-l-2 border-primary-100' : ''"
                      class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه اصلی</button>
              <button @click="loadPage('/about/')" 
                      :class="currentPage === '/about/' || initUrl === '/about/' ? 'border-r-2 border-l-2 border-primary-100' : ''"
                      class="cursor-pointer hover:text-primary-100 px-3 py-1">درباره من</button>
              <button @click="loadPage('page3')" 
                      :class="currentPage === 'page3' ? 'border-r-2 border-l-2 border-primary-100' : ''"
                      class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
              <button @click="loadPage('page3')" 
                      :class="currentPage === 'page3' ? 'border-r-2 border-l-2 border-primary-100' : ''"
                      class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
              <button @click="loadPage('/contact/')" 
                      :class="currentPage === '/contact/' || initUrl === '/contact/' ? 'border-r-2 border-l-2 border-primary-100' : ''"
                      class="cursor-pointer hover:text-primary-100 px-3 py-1">تماس با من</button>
            </div>
          </template>

        <div class="w-fit flex items-center contentContainer transition-all duration-700 relative">
          <img class="w-auto h-[350px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>

          <div class="w-full flex justify-center h-[400px] overflow-y-auto overflow-x-hidden scroll-pl-6">
            <!-- صفحه اصلی (منو) -->
            <template x-if="currentPage === '/'">
              <div x-ref="home" class="w-[500px] flex items-center justify-around">
                <button @click="goTo('/about/')" class="cursor-pointer hover:text-primary-100 px-3 py-1">درباره من</button>
                <img class="w-auto h-[350px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
                <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
                <img class="w-auto h-[350px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
                <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
                <img class="w-auto h-[350px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
                <button @click="goTo('/contact/')" class="cursor-pointer hover:text-primary-100 px-3 py-1">تماس با من</button>
              </div>
            </template>
            <!-- صفحه لودینگ -->
            <template x-if="loading">
              <div x-ref="loading" class="absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] flex items-center justify-center text-gray-600">
                <span>در حال بارگذاری...</span>
              </div>
            </template>
            <!-- about -->
            <template x-if="currentPage === '/about/'">
              <div x-ref="/about/" class="w-full h-full">
                <div x-html="pageContent"></div>
              </div>
            </template>

            <!-- contact -->
            <template x-if="currentPage === '/contact/'">
              <div x-ref="/contact/" class="w-full h-full">
                <div x-html="pageContent"></div>
              </div>
            </template>


      

      