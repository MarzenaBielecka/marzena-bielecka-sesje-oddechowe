<?php
if (!has_category('historie-sesji')) {
    get_template_part('index');
    return;
}

get_header();
?>

<?php while (have_posts()) : ?>
    <?php the_post(); ?>
    <section class="atma-stories-page atma-story-single">
        <div class="atma-container">
            <div class="atma-stories-layout">
                <article class="atma-story-article">
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="atma-story-hero">
                            <?php the_post_thumbnail('large', ['alt' => the_title_attribute(['echo' => false])]); ?>
                        </figure>
                    <?php endif; ?>

                    <header class="atma-story-heading">
                        <?php
                        $story_categories = get_the_category();
                        if ($story_categories) :
                        ?>
                            <div class="atma-story-categories">
                                <?php foreach ($story_categories as $story_category) : ?>
                                    <a href="<?php echo esc_url(get_category_link($story_category->term_id)); ?>">
                                        <?php echo esc_html($story_category->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <h1><?php the_title(); ?></h1>
                        <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </header>

                    <div class="atma-story-content">
                        <?php
                        the_content();
                        wp_link_pages([
                            'before' => '<nav class="page-links" aria-label="Strony wpisu">',
                            'after'  => '</nav>',
                        ]);
                        ?>
                    </div>
                </article>

                <?php get_template_part('template-parts/historie-sidebar'); ?>
            </div>
        </div>
    </section>
<?php endwhile; ?>

<?php get_footer(); ?>
