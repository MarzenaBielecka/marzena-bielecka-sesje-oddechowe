<?php
$testimonials = get_posts([
    'post_type'      => 'opinia',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC', 'ID' => 'DESC'],
]);

$opinions_page = get_page_by_path('opinie', OBJECT, 'page');
$opinions_url  = $opinions_page && 'publish' === $opinions_page->post_status
    ? get_permalink($opinions_page)
    : '';
$testimonial_count = count($testimonials);
$grid_modifier = 1 === $testimonial_count
    ? ' testimonials-grid--single'
    : (2 === $testimonial_count ? ' testimonials-grid--double' : '');
?>

<section class="atma-testimonials" id="opinie">
    <div class="atma-container">
        <div class="testimonials-heading">
            <span class="testimonials-kicker"><?php echo esc_html(get_theme_mod('atma_testimonials_kicker', 'OPINIE KLIENTÓW')); ?></span>
            <h2><?php echo esc_html(get_theme_mod('atma_testimonials_title', 'Co mówią osoby po sesjach?')); ?></h2>
            <p><?php echo esc_html(get_theme_mod('atma_testimonials_description', 'Każda historia jest inna, ale wszystkie łączy jedno — większy spokój, lekkość i głębszy kontakt ze sobą.')); ?></p>
        </div>

        <?php if ($testimonials) : ?>
            <div class="testimonials-carousel" data-testimonials-carousel>
                <div
                    class="testimonials-grid<?php echo esc_attr($grid_modifier); ?>"
                    id="atma-testimonials-track"
                    role="region"
                    aria-label="Opinie klientów"
                    tabindex="0"
                >
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <?php get_template_part('template-parts/testimonial-card', null, ['testimonial' => $testimonial, 'truncate' => true]); ?>
                    <?php endforeach; ?>
                </div>

                <?php if (count($testimonials) > 1) : ?>
                    <nav class="testimonials-controls" aria-label="Nawigacja opinii">
                        <button
                            class="testimonials-control"
                            type="button"
                            data-testimonials-previous
                            aria-controls="atma-testimonials-track"
                            aria-label="Poprzednie opinie"
                            disabled
                        >
                            <span aria-hidden="true">←</span>
                        </button>
                        <button
                            class="testimonials-control"
                            type="button"
                            data-testimonials-next
                            aria-controls="atma-testimonials-track"
                            aria-label="Następne opinie"
                        >
                            <span aria-hidden="true">→</span>
                        </button>
                    </nav>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($opinions_url) : ?>
            <div class="testimonials-actions">
                <a class="atma-btn" href="<?php echo esc_url($opinions_url); ?>">Zobacz wszystkie opinie</a>
            </div>
        <?php endif; ?>
    </div>
</section>
