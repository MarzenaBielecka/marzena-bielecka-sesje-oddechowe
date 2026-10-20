<?php
$testimonial = isset($args['testimonial']) && $args['testimonial'] instanceof WP_Post
    ? $args['testimonial']
    : null;

if (!$testimonial) {
    return;
}

$rating = absint(get_post_meta($testimonial->ID, 'client_rating', true));

if ($rating < 1 || $rating > 5) {
    $rating = 5;
}

$name = get_post_meta($testimonial->ID, 'client_name', true);
$city = get_post_meta($testimonial->ID, 'client_city', true);

if (!$name) {
    $name = get_the_title($testimonial);
}
?>

<article class="testimonial-card">
    <div class="testimonial-stars" role="img" aria-label="<?php echo esc_attr(sprintf('Ocena: %d na 5 gwiazdek', $rating)); ?>">
        <?php echo esc_html(str_repeat('★', $rating)); ?>
    </div>

    <div class="testimonial-text<?php echo !empty($args['truncate']) ? ' testimonial-text--clamped' : ''; ?>">
        <?php echo wp_kses_post(apply_filters('the_content', $testimonial->post_content)); ?>
    </div>

    <div class="testimonial-footer">
        <div class="testimonial-author"><?php echo esc_html($name); ?></div>
        <?php if ($city) : ?>
            <div class="testimonial-city"><?php echo esc_html($city); ?></div>
        <?php endif; ?>
    </div>
</article>
