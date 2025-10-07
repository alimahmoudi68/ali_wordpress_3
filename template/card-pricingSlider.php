<?php 
    $priceItem = get_post_meta( get_the_ID() , 'pricing_group' , true); 
?>
<div
    class='w-full card-services flex flex-col items-start h-full transition-all duration-300 rounded-lg bg-[#1d1f21] p-5 relative'>
    <div class='w-full flex items-center justify-end mb-8'>
        <div class='text-[0.8rem] border border-primary-100 rounded-full p-3 text-white-100'>
            <?php the_title(); ?>
        </div>
    </div>
    <div class='w-full flex flex-col lg:flex-row'>
        <span class='w-full lg:w-[40%] text-[1.2rem] font-bold text-white-100 mb-5'>
            <?php echo get_post_meta( get_the_ID() , 'pricing_price' , true) ?> هزار تومان
        </span>
        <div class='w-full lg:w-[60%] flex flex-col'>
            <?php 
                foreach($priceItem as $item) {
            ?>
                <div class='flex items-center gap-x-2'>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} class="w-[16px] h-[16px] stroke-primary-100">
                    <path strokeLinecap="round" strokeLinejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class='text-[0.9rem] text-white-100'>
                        <?php echo $item['pricing_group_item']                                                                                                                                                                       ?>
                    </span>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
