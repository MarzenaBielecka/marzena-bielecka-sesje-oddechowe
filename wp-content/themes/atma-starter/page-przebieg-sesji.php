<?php
get_header();
?>

<article class="atma-process-page">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>

        <?php
        $process_content = get_the_content();
        $has_process_content = trim(wp_strip_all_tags($process_content)) !== '';
        $process_intro = '';
        $process_summary = '';
        $process_content_html = '';

        if ($has_process_content) {
            $process_content_html = apply_filters('the_content', $process_content);

            if (preg_match('/<p\b[^>]*>.*?<\/p>/s', $process_content_html, $intro_match)) {
                $process_intro = $intro_match[0];
                $process_content_html = preg_replace('/<p\b[^>]*>.*?<\/p>/s', '', $process_content_html, 1);

                if (preg_match('/<p\b[^>]*>.*?<\/p>/s', $process_content_html, $summary_match)) {
                    $process_summary = $summary_match[0];
                    $process_content_html = preg_replace('/<p\b[^>]*>.*?<\/p>/s', '', $process_content_html, 1);
                }
            }
        }

        $process_image_id = absint(get_post_thumbnail_id(get_the_ID()));
        $process_image_alt = $process_image_id
            ? get_post_meta($process_image_id, '_wp_attachment_image_alt', true)
            : '';
        ?>

        <section class="atma-process-hero">
            <div class="atma-container atma-process-hero-grid">
                <div class="atma-process-hero-copy">
                    <span class="atma-process-kicker">KROK PO KROKU</span>
                    <h1><?php the_title(); ?></h1>
                    <?php if ($process_intro) : ?>
                        <div class="atma-process-intro"><?php echo $process_intro; ?></div>
                    <?php endif; ?>
                    <?php if ($process_summary) : ?>
                        <div class="atma-process-summary"><?php echo $process_summary; ?></div>
                    <?php endif; ?>
                </div>

                <figure class="atma-process-hero-image">
                    <?php if ($process_image_id) : ?>
                        <?php
                        echo wp_get_attachment_image(
                            $process_image_id,
                            'large',
                            false,
                            [
                                'class' => 'atma-process-image',
                                'alt'   => $process_image_alt ?: 'Spokojny krajobraz',
                            ]
                        );

                        $process_image_caption = wp_get_attachment_caption($process_image_id);
                        if ($process_image_caption) :
                        ?>
                            <figcaption class="atma-process-image-credit"><?php echo wp_kses_post($process_image_caption); ?></figcaption>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="atma-process-image-placeholder">
                            <span>Miejsce na zdjęcie natury</span>
                        </div>
                    <?php endif; ?>
                </figure>
            </div>
        </section>

        <?php if ($has_process_content) : ?>
            <section class="atma-process-content">
                <div class="atma-container atma-process-copy">
                    <?php echo $process_content_html; ?>
                </div>
            </section>

            <div class="atma-process-cta">
                <a class="atma-btn" href="<?php echo esc_url(home_url('/#kontakt')); ?>">Umów się</a>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
</article>

<?php get_footer(); ?>
