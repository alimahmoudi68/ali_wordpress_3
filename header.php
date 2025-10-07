<?php
    $langs = array('fa' , 'en');
    $lang = get_current_lang();
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0" />
    <meta name="theme-color" content="<?php get_option('my_color'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="description" content="<?php bloginfo('description') ?>">
    <meta name="keywords" content="<?php bloginfo('description') ?>">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri().'/images/favicon.png' ?>" title="Favicon" />
    <?php wp_head(); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body dir="<?php echo ( $lang == 'en' ) ? 'ltr' : 'rtl' ?>" class='h-screen bg-cream-100 font-pinar dark:bg-dark-100 flex flex-col items-center justify-center relative'> 

<main x-data="dataViewer()" class='w-full h-full flex flex-col justify-center grow'>
    <div  class="container mx-auto px-5">

    <div class="flex justify-between items-center">
        <div class="flex items-center gap-x-2">
            <!-- <img src="<?php echo get_template_directory_uri().'/images/logo.png'?>" alt="logo" class="w-10 h-10"> -->
            <span class="text-2xl font-bold">LLLL</span>
        </div>

    </div>


    <template x-if="showMenu">
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center text-white">
        <a href="<?php echo home_url('/about'); ?>"  @click.prevent="fetchLink($event.target.href)" class="home-menu-item">درباره من</a>
        </div>
    </template>
        

    <div class="flex justify-center items-center min-h-[calc(100vh-80px)] md:min-h-[calc(100vh-180px)]">
        <!-- <img class="w-auto h-[400px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/> -->
         <span>0</span>

         <template x-if="loading">
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center text-white">
            در حال بارگذاری...
            </div>
        </template>

