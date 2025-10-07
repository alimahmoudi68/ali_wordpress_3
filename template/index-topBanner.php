<?php 
    $lang = get_current_lang();
?>
<div class='w-full flex flex-col justify-center items-center mt-[30px] mb-[60px] md:mb-[80px] xl:mb-[100] md:flex-row md:justify-between relative'>
    <img class='top-img md:w-[40%] px-5 md:order-1 mb-8 md:mb-0 z-3' src='<?php echo get_template_directory_uri().'/images/top.jpg' ?>'></img>

    <div class='w-[90%] h-[90%] opacity-80 absolute left-[50%] top-[50%] translate-x-[-50%] translate-y-[-50%] bg-dots-fade masked-background z-2'>
    </div>

    <div class='w-full h-full flex items-center md:items-start md:justify-center flex-col gap-y-2 z-3'>
        <span class='text-primary-100 text-[1.1rem] md:text-[3rem] font-black md:mb-1'>
            <?php echo ( $lang == 'en' ) ? '#Ali Mahmoudi' : '#علی محمودی' ?>
        </span>
        <div class='flex gap-x-2'>
            <span class='font-medium text-slate-700 dark:text-white-100 text-center md:text-start text-[1rem] md:[1.5rem]'>
                <?php echo ( $lang == 'en' ) ? 'I am' : 'من' ?>
                <span id='index-typewriter' data-lang='<?php echo $lang ?>' class='font-medium text-primary-100 text-center md:text-start text-[1rem] md:[1.5rem]'>
                </span>
                <span class='font-medium text-slate-700 dark:text-white-100 text-center md:text-start text-[1rem] md:[1.5rem]'>
                    <?php echo ( $lang == 'en' ) ? '' : 'هستم' ?>
                </span>
            </span>
        </div>
    </div>
</div> 