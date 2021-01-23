<?php get_header(); ?>

<article class="personas hentry entry">
    <header class="entry-header alignwide">
        <h2>
            Resultados de búsqueda
        </h2>
        <form role="search" action="<?php echo site_url('/'); ?>" method="get" id="searchform">
            <input type="text" name="s" placeholder="Buscar personas" value="<?php echo $s ?>">
            <input type="hidden" name="post_type" value="personas">
            <input type="submit" value="Buscar">
        </form>

    </header>

    <div class="entry-content">

        <?php
        $personas = array();

        if (have_posts()) :
            while (have_posts()) :
                the_post();
                $personas[] = $post;
            endwhile;
        endif;

        echo tabla_personas($personas);
        ?>

    </div>
</article>

<?php get_footer(); ?>