<div
    class='w-full card-services flex flex-col items-center h-full transition-all duration-300 rounded-lg dark:bg-dark-card-100 relative group'>
    <div class='w-full overflow-hidden card-image-container rounded-lg'>
        <?php the_post_thumbnail('teammates'); ?>
    </div>
    <div class='absolute left-[20px] right-[20px] bottom-[-30px] group-hover:bg-primary-100 bg-[#1d1f21] flex flex-col gap-y-[8px] py-4 px-3 items-center rounded-lg duration-1000 justify-center'>
        <span class='text-[1.2rem] text-white-100'>
            <?php the_title(); ?>
        </span>
        <span class='text-[0.9rem] text-white-100'>
            <?php  echo get_post_meta( get_the_ID() , 'teammate_position' , true); ?>
        </span>
    </div>
</div>
