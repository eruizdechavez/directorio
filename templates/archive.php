<?php get_header(); ?>

<article class="personas hentry entry">
    <header class="entry-header alignwide">
        <h2>
            Listado de Personas
        </h2>
        <form role="search" action="<?php echo site_url('/'); ?>" method="get" id="searchform">
            <input type="text" name="s" placeholder="Buscar personas">
            <input type="hidden" name="post_type" value="personas">
            <input type="submit" value="Buscar">
        </form>
    </header>

    <div class="entry-content">
        <?php echo do_shortcode('[tabla-de-personas]'); ?>
    </div>
</article>

<?php get_footer(); ?>