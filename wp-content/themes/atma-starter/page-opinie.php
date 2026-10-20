<?php get_header(); ?>

<?php
$testimonials = get_posts([
    'post_type'      => 'opinia',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC', 'ID' => 'DESC'],
]);
?>

<section class="atma-testimonials atma-testimonials-page" id="opinie">
    <div class="atma-container">
        <div class="testimonials-heading">
            <span class="testimonials-kicker"><?php echo esc_html(get_theme_mod('atma_testimonials_kicker', 'OPINIE KLIENTÓW')); ?></span>
            <h1><?php the_title(); ?></h1>
            <p><?php echo esc_html(get_theme_mod('atma_testimonials_description', 'Każda historia jest inna, ale wszystkie łączy jedno — większy spokój, lekkość i głębszy kontakt ze sobą.')); ?></p>
        </div>

        <?php if ($testimonials) : ?>
            <div class="testimonials-all-grid">
                <?php foreach ($testimonials as $testimonial) : ?>
                    <?php get_template_part('template-parts/testimonial-card', null, ['testimonial' => $testimonial]); ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="testimonials-empty">Brak opublikowanych opinii.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
