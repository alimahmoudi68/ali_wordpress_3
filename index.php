<?php get_header() ?>

    <template x-if="showIndexMenu">
        <div class="content">
            <div class="flex items-center justify-center">

            <a href="<?php echo home_url('/about'); ?>"  @click.prevent="fetchLink($event.target.href)" class="home-menu-item">درباره من</a>
            <span>0</span>
            <a href="<?php echo make_url(get_current_lang(), '/about'); ?>" class="home-menu-item">تست ۲</a>
            <span>0</span>
            <a href="<?php echo make_url(get_current_lang(), '/about'); ?>" class="home-menu-item">تست ۳</a>
            <span>0</span>
            <a href="<?php echo home_url('/contact'); ?>"  @click.prevent="fetchLink($event.target.href)" class="home-menu-item">تماس با من</a>
              
            </div>
        </div>
    </template>


    <!-- محتوای جدید -->
    <template x-if="pageContent && !loading">
        <div class="content p-6" x-html="pageContent"></div>
    </template>

<?php get_footer() ?>



