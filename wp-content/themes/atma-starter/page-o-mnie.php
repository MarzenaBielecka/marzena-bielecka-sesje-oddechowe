<?php
get_header();

$about_image_id = absint(get_theme_mod('atma_about_image'));

if (!$about_image_id) {
    $about_image = get_page_by_path('marzena-bielecka-portret', OBJECT, 'attachment');
    $about_image_id = $about_image ? $about_image->ID : 0;
}

$about_image_alt = $about_image_id
    ? get_post_meta($about_image_id, '_wp_attachment_image_alt', true)
    : '';

$story_intro = '';
$story_content = '';

while (have_posts()) :
    the_post();
    $story_content = get_the_content();
    remove_filter('the_content', 'wptexturize');
    $story_content = apply_filters('the_content', $story_content);
    add_filter('the_content', 'wptexturize');

    $story_content = preg_replace_callback(
        '/(<span class="atma-story-turn">)(.*?)(<\/span>)/s',
        static function ($matches) {
            $sentences = preg_split('/(?<=[.!?])\s+(?=\p{Lu})/u', $matches[2]);

            if (!is_array($sentences) || count($sentences) !== 3) {
                return $matches[0];
            }

            $lines = array_map(
                static function ($sentence) {
                    return '<span class="atma-story-turn-line">' . $sentence . '</span>';
                },
                $sentences
            );

            return $matches[1] . implode(' ', $lines) . $matches[3];
        },
        $story_content
    );

    if (preg_match('/(<span class="atma-story-lead">.*?<\/span>)(\s*)(<span>.*?<\/span>)(\s*)/s', $story_content, $intro_matches)) {
        $story_intro = $intro_matches[1] . $intro_matches[2] . $intro_matches[3] . $intro_matches[4];
        $story_content = str_replace($intro_matches[0], '', $story_content);
    }
endwhile;
?>

<article class="atma-story-page">
    <section class="atma-story-hero">
        <div class="atma-container atma-story-hero-grid">
            <div class="atma-story-hero-content">
                <span class="atma-story-kicker">MARZENA BIELECKA</span>
                <h1><?php the_title(); ?></h1>
                <div class="atma-story-intro">
                    <?php echo $story_intro; ?>
                </div>
            </div>

            <div class="atma-story-hero-image">
                <?php if ($about_image_id) : ?>
                    <?php
                    echo wp_get_attachment_image(
                        $about_image_id,
                        'large',
                        false,
                        [
                            'class' => 'atma-story-image',
                            'alt'   => $about_image_alt ?: 'Marzena Bielecka',
                        ]
                    );
                    ?>
                <?php else : ?>
                    <div class="atma-story-image-placeholder" role="img" aria-label="Marzena Bielecka"></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="atma-story-content">
        <div class="atma-container">
            <div class="atma-story-copy">
                <?php echo $story_content; ?>
            </div>
        </div>
    </section>

    <div class="atma-story-cta">
        <a class="atma-btn" href="<?php echo esc_url(home_url('/#kontakt')); ?>">Umów sesję</a>
    </div>
</article>

<?php get_footer(); ?>
