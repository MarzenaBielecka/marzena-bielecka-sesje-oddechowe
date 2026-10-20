<?php
$stories_query = isset($args['query']) && $args['query'] instanceof WP_Query
    ? $args['query']
    : $GLOBALS['wp_query'];
$stories_title = isset($args['title']) ? $args['title'] : 'Historie sesji';
$stories_description = isset($args['description']) ? $args['description'] : '';
?>

<section class="atma-stories-page">
    <div class="atma-container">
        <header class="atma-stories-intro">
            <p class="atma-stories-kicker">Historie sesji</p>
            <h1><?php echo esc_html($stories_title); ?></h1>
            <?php if ($stories_description) : ?>
                <div class="atma-stories-description">
                    <?php echo wp_kses_post($stories_description); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="atma-stories-layout">
            <div class="atma-stories-list">
                <?php if ($stories_query->have_posts()) : ?>
                    <?php while ($stories_query->have_posts()) : ?>
                        <?php $stories_query->the_post(); ?>
                        <article <?php post_class('atma-story-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a class="atma-story-card-image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                    <?php the_post_thumbnail('large', ['alt' => the_title_attribute(['echo' => false])]); ?>
                                </a>
                            <?php endif; ?>

                            <div class="atma-story-card-body">
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

                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                                <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 36, '…')); ?></p>
                                <a class="atma-story-read-more" href="<?php the_permalink(); ?>">Czytaj całą historię</a>
                            </div>
                        </article>
                    <?php endwhile; ?>

                    <?php
                    $pagination = paginate_links([
                        'total'     => (int) $stories_query->max_num_pages,
                        'current'   => max(1, (int) get_query_var('paged')),
                        'type'      => 'list',
                        'prev_text' => 'Poprzednia',
                        'next_text' => 'Następna',
                    ]);

                    if ($pagination) :
                    ?>
                        <nav class="atma-stories-pagination" aria-label="Paginacja historii">
                            <?php echo wp_kses_post($pagination); ?>
                        </nav>
                    <?php endif; ?>
                <?php else : ?>
                    <p class="atma-stories-empty">Nie opublikowano jeszcze żadnych historii.</p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>

            <?php get_template_part('template-parts/historie-sidebar'); ?>
        </div>
    </div>
</section>
