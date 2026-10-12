<?php
get_header();

$after_image_id = absint(get_post_thumbnail_id(get_queried_object_id()));

if (!$after_image_id) {
    $after_images = get_posts([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'name'           => 'alpine-meadows-2',
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);
    $after_image_id = $after_images ? absint($after_images[0]) : 0;
}

$after_image_alt = $after_image_id
    ? get_post_meta($after_image_id, '_wp_attachment_image_alt', true)
    : '';
$after_image_caption = $after_image_id ? wp_get_attachment_caption($after_image_id) : '';

$after_items = [
    'Odprężona(y), zrelaksowana(y), często spokojna(y).',
    'Czujesz więcej energii w ciele i większą świadomość swojego ciała.',
    'Energia, która wcześniej była wykorzystywana na tłumienie trudnych emocji, staje się dostępna dla Ciebie.',
    'Wzrasta poziom akceptacji życia takim, jakie jest.',
    'Doświadczasz wyciszenia i większego spokoju.',
    'Wzrasta poziom kreatywności i lekkości.',
    'Możesz zacząć postrzegać świat i siebie inaczej – prościej, pełniej, spokojniej.',
    'W ciągu kolejnych dni po sesji mogą pojawiać się zmiany związane z tematem, z którym pracowałaś(eś). Możesz zauważyć zewnętrzne zmiany związane z tym obszarem.',
    'Pojawia się w Tobie przestrzeń na wybieranie innych zachowań i reakcji niż te, które pojawiały się dotychczas.',
];
?>

<article class="atma-after-page">
    <section class="atma-after-hero">
        <div class="atma-container atma-after-hero-grid">
            <div class="atma-after-hero-copy">
                <span class="atma-after-kicker">PO SESJI ODDECHOWEJ</span>
                <h1>Jak możesz czuć się po sesji oddechowej?</h1>
            </div>

            <figure class="atma-after-hero-image">
                <?php if ($after_image_id) : ?>
                    <?php
                    echo wp_get_attachment_image(
                        $after_image_id,
                        'large',
                        false,
                        [
                            'class' => 'atma-after-image',
                            'alt'   => $after_image_alt ?: 'Spokojny, słoneczny krajobraz',
                        ]
                    );
                    ?>
                    <?php if ($after_image_caption) : ?>
                        <figcaption class="atma-after-image-credit"><?php echo wp_kses_post($after_image_caption); ?></figcaption>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="atma-after-image-placeholder" role="img" aria-label="Spokojny krajobraz"></div>
                <?php endif; ?>
            </figure>
        </div>
    </section>

    <section class="atma-after-list-section" aria-label="Możliwe odczucia po sesji">
        <div class="atma-container">
            <ol class="atma-after-list">
                <?php foreach ($after_items as $index => $item) : ?>
                    <li class="atma-after-card">
                        <span class="atma-after-number" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                        <p><?php echo esc_html($item); ?></p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>
</article>

<?php get_footer(); ?>
