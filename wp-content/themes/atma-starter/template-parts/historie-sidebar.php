<?php
$story_sidebar_ids = get_posts([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'category_name'  => 'historie-sesji',
    'fields'         => 'ids',
]);
$story_sidebar_categories = $story_sidebar_ids
    ? get_categories([
        'taxonomy'   => 'category',
        'hide_empty' => true,
        'object_ids' => $story_sidebar_ids,
    ])
    : [];
$story_sidebar_excluded = is_singular('post') ? [get_queried_object_id()] : [];
$story_sidebar_recent = get_posts([
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 4,
    'category_name'       => 'historie-sesji',
    'post__not_in'         => $story_sidebar_excluded,
    'ignore_sticky_posts' => true,
]);
$story_sidebar_image_id = absint(get_theme_mod('atma_stories_sidebar_image'));
?>

<aside class="atma-stories-sidebar" aria-label="Historie sesji">
    <?php if ($story_sidebar_categories) : ?>
        <section class="atma-sidebar-section">
            <h2>Kategorie</h2>
            <ul class="atma-story-category-list">
                <?php foreach ($story_sidebar_categories as $story_sidebar_category) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_category_link($story_sidebar_category->term_id)); ?>">
                            <?php echo esc_html($story_sidebar_category->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if ($story_sidebar_recent) : ?>
        <section class="atma-sidebar-section">
            <h2>Ostatnie historie</h2>
            <ul class="atma-recent-stories">
                <?php foreach ($story_sidebar_recent as $story_sidebar_post) : ?>
                    <?php
                    $story_sidebar_post_categories = get_the_category($story_sidebar_post->ID);
                    $story_sidebar_category = $story_sidebar_post_categories ? $story_sidebar_post_categories[0] : null;
                    ?>
                    <li>
                        <a class="atma-recent-story-image" href="<?php echo esc_url(get_permalink($story_sidebar_post)); ?>" tabindex="-1" aria-hidden="true">
                            <?php if (has_post_thumbnail($story_sidebar_post)) : ?>
                                <?php echo get_the_post_thumbnail($story_sidebar_post, 'thumbnail', ['alt' => esc_attr(get_the_title($story_sidebar_post))]); ?>
                            <?php endif; ?>
                        </a>
                        <div class="atma-recent-story-content">
                            <?php if ($story_sidebar_category) : ?>
                                <a class="atma-recent-story-category" href="<?php echo esc_url(get_category_link($story_sidebar_category->term_id)); ?>">
                                    <?php echo esc_html($story_sidebar_category->name); ?>
                                </a>
                            <?php endif; ?>
                            <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C, $story_sidebar_post)); ?>">
                                <?php echo esc_html(get_the_date('', $story_sidebar_post)); ?>
                            </time>
                            <a class="atma-recent-story-title" href="<?php echo esc_url(get_permalink($story_sidebar_post)); ?>">
                                <?php echo esc_html(get_the_title($story_sidebar_post)); ?>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if ($story_sidebar_image_id && wp_attachment_is_image($story_sidebar_image_id)) : ?>
        <figure class="atma-stories-sidebar-image">
            <?php
            echo wp_get_attachment_image(
                $story_sidebar_image_id,
                'large',
                false,
                ['class' => 'atma-sidebar-feature-image']
            );
            ?>
        </figure>
    <?php endif; ?>
</aside>
