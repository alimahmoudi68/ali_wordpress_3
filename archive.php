<?php get_header() ?>
6666
<main>
    <div class="container" id="main-container">
        <?php while(have_posts()) : the_post(); ?>
        22

        <h1>
            <?php the_title(); ?>
        </h1>


        <?php endwhile; ?>




    </div>
</main>


<?php get_footer() ?>