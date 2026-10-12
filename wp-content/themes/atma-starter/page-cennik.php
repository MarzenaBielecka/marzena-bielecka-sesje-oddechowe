<?php
get_header();

$pricing_image_id = absint(get_post_thumbnail_id(get_queried_object_id()));

if (!$pricing_image_id) {
    $pricing_images = get_posts([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'name'           => 'alpine-meadows-2',
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);
    $pricing_image_id = $pricing_images ? absint($pricing_images[0]) : 0;
}

$pricing_image_alt = $pricing_image_id
    ? get_post_meta($pricing_image_id, '_wp_attachment_image_alt', true)
    : '';
$pricing_image_caption = $pricing_image_id ? wp_get_attachment_caption($pricing_image_id) : '';

$pricing_cards = [
    [
        'title' => 'Sesje indywidualne online',
        'price' => '350 zł',
        'text'  => 'Spotykamy się online, dzięki czemu możesz wziąć udział w sesji w zaciszu własnego domu, w miejscu, w którym czujesz się bezpiecznie i komfortowo.',
    ],
    [
        'title' => 'Sesje indywidualne u Ciebie',
        'price' => '450 zł',
        'text'  => 'Prowadzę również sesje wyjazdowe na terenie Jeleniej Góry. Możemy spotkać się w Twojej przestrzeni.',
    ],
    [
        'title' => 'Warsztaty oddechowe',
        'price' => 'Wycena indywidualna',
        'text'  => 'Organizuję również warsztaty, których szczegóły i aktualną ofertę znajdziesz na tej stronie. Cena ustalana jest indywidualnie, w zależności od charakteru i zakresu spotkania.',
    ],
];
?>

<article class="atma-pricing-page">
    <section class="atma-pricing-hero">
        <div class="atma-container atma-pricing-hero-grid">
            <figure class="atma-pricing-hero-image">
                <?php if ($pricing_image_id) : ?>
                    <?php
                    echo wp_get_attachment_image(
                        $pricing_image_id,
                        'large',
                        false,
                        [
                            'class' => 'atma-pricing-image',
                            'alt'   => $pricing_image_alt ?: 'Spokojny, słoneczny krajobraz',
                        ]
                    );
                    ?>
                    <?php if ($pricing_image_caption) : ?>
                        <figcaption class="atma-pricing-image-credit"><?php echo wp_kses_post($pricing_image_caption); ?></figcaption>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="atma-pricing-image-placeholder" role="img" aria-label="Spokojny krajobraz"></div>
                <?php endif; ?>
            </figure>

            <div class="atma-pricing-hero-copy">
                <span class="atma-pricing-kicker">INDYWIDUALNE SESJE ODDECHOWE</span>
                <h1>Umów się na sesję</h1>
                <p>Jeśli czujesz, że chcesz rozpocząć swoją przygodę z oddechem, zapraszam Cię do kontaktu.</p>
                <p>Napisz do mnie, aby ustalić dogodny termin i wybrać formę spotkania, która będzie dla Ciebie odpowiednia. Jeśli masz pytania lub nie wiesz, od czego zacząć, chętnie porozmawiam z Tobą o możliwościach.</p>
            </div>
        </div>
    </section>

    <section class="atma-pricing-offer" aria-labelledby="atma-pricing-offer-title">
        <div class="atma-container">
            <div class="atma-pricing-offer-intro">
                <h2 id="atma-pricing-offer-title">Jak możemy się spotkać?</h2>
                <p>Każde spotkanie może mieć inną formę — wybierz tę, która jest dla Ciebie najbardziej odpowiednia.</p>
            </div>

            <div class="atma-pricing-cards">
                <?php foreach ($pricing_cards as $card) : ?>
                    <article class="atma-pricing-card">
                        <h3><?php echo esc_html($card['title']); ?></h3>
                        <p class="atma-pricing-price"><?php echo esc_html($card['price']); ?></p>
                        <p class="atma-pricing-card-text"><?php echo esc_html($card['text']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="atma-pricing-closing">
        <div class="atma-container">
            <p class="atma-pricing-closing-lead">Serdecznie zapraszam</p>
            <p class="atma-pricing-signature">Marzena Bielecka</p>
            <a class="atma-btn" href="<?php echo esc_url(home_url('/#kontakt')); ?>">Umów się</a>
        </div>
    </section>
</article>

<?php get_footer(); ?>
