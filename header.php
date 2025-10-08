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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <?php wp_head(); ?>
</head>

<body dir="<?php echo ( $lang == 'en' ) ? 'ltr' : 'rtl' ?>" class='h-screen bg-cream-100 font-pinar dark:bg-dark-100 flex flex-col items-center justify-center relative'> 

<main class="w-full grow">
    <div  class="container h-full mx-auto px-5" x-ref="container">
        <div x-data="pageController()" class="w-full h-full flex flex-wrap items-center justify-center space-x-6 relative">
        <!-- افکت نوری -->
        <div x-ref="flash" class="flash"></div>

        <!-- تاپ منو -->
        <template x-if="showTopMenu">
          <div x-ref="home" class="fixed top-[200px] w-full flex items-center justify-center">
            <button @click="goTo('page2')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه دوم</button>
            <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
            <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
            <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
          </div>
        </template>

        <div class="flex items-center content bg-red-500 transition-all duration-700 relative">
            <img class="absolute top-0 right-0" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>

              <div class="flex-1 flex justify-center">
                <!-- صفحه اصلی (منو) -->
                <template x-if="currentPage === 'home'">
                  <div x-ref="home" class="flex items-center justify-center">
                    <button @click="goTo('page2')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه دوم</button>
                    <img class="" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
                    <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
                    <img class="" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
                    <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
                    <img class="" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
                    <button @click="goTo('page3')" class="cursor-pointer hover:text-primary-100 px-3 py-1">صفحه سوم</button>
                  </div>
                </template>
                <!-- صفحه لودینگ -->
                <template x-if="loading">
                  <div x-ref="loading" class="absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] flex items-center justify-center bg-gray-100 text-gray-600">
                    <span>در حال بارگذاری...</span>
                  </div>
                </template>
                <!-- صفحه ۲ -->
                <template x-if="currentPage === 'page2'">
                  <div x-ref="page2" class="w-full overflow-auto">
                    <h2 class="text-lg font-bold mb-2">صفحه دوم 📄</h2>
                    <template x-for="item in data" :key="item.id">
                      <div class="border-b border-indigo-400 py-1" x-text="item.title"></div>
                    </template>
                    <button @click="backToHome()" class="mt-4 bg-white text-indigo-700 px-3 py-1 rounded">بازگشت</button>
                  </div>
                </template>
                <!-- صفحه ۳ -->
                <template x-if="currentPage === 'page3'">
                  <div x-ref="page3" class="absolute inset-0 bg-green-600 text-white p-4 rounded-xl overflow-auto">
                    <h2 class="text-lg font-bold mb-2">صفحه سوم 📊</h2>
                    <template x-for="item in data" :key="item.id">
                      <div class="border-b border-green-400 py-1" x-text="item.name"></div>
                    </template>
                    <button @click="backToHome()" class="mt-4 bg-white text-green-700 px-3 py-1 rounded">بازگشت</button>
                  </div>
                </template>
              </div>
              <img class="absolute top-0 left-0" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
            </div>

        </div>