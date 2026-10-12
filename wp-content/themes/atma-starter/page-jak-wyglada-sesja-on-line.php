<?php
get_header();

$session_image_id = absint(get_post_thumbnail_id(get_queried_object_id()));

if (!$session_image_id) {
    $session_image = get_page_by_path('sesja-oddechowa-oferta', OBJECT, 'attachment');
    $session_image_id = $session_image ? absint($session_image->ID) : 0;
}

$session_image_alt = $session_image_id
    ? get_post_meta($session_image_id, '_wp_attachment_image_alt', true)
    : '';
?>

<article class="atma-session-page">
    <?php while (have_posts()) : ?>
        <?php
        the_post();
        $session_content = get_the_content();
        $has_session_content = trim(wp_strip_all_tags($session_content)) !== '';
        $session_lead = '';
        $session_content_html = '';

        if ($has_session_content) {
            $session_content_html = apply_filters('the_content', $session_content);

            if (preg_match('/<p\b[^>]*>.*?<\/p>/s', $session_content_html, $lead_match)) {
                $session_lead = $lead_match[0];
                $session_content_html = preg_replace('/<p\b[^>]*>.*?<\/p>/s', '', $session_content_html, 1);
            }
        }
        ?>

        <section class="atma-session-hero">
            <div class="atma-container atma-session-hero-grid">
                <div class="atma-session-hero-copy">
                    <span class="atma-session-kicker">SESJA ODDECHOWA ONLINE</span>
                    <h1>Jak wygląda sesja oddechowa online?</h1>
                    <?php if ($has_session_content) : ?>
                        <?php if ($session_lead) : ?>
                            <div class="atma-session-lead"><?php echo $session_lead; ?></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <p class="atma-session-lead">Prowadzę sesje w spokojnej, kameralnej atmosferze. Obecnie odbywają się one online, dzięki czemu możesz pracować ze mną w swoim domu – siedząc w ulubionym fotelu albo leżąc w łóżku, pod ciepłym i miękkim kocykiem. Podczas sesji temperatura ciała może się obniżyć, dlatego warto zadbać o komfort i ciepło.</p>
                    <?php endif; ?>
                </div>

                <div class="atma-session-hero-image">
                    <?php if ($session_image_id) : ?>
                        <?php
                        echo wp_get_attachment_image(
                            $session_image_id,
                            'large',
                            false,
                            [
                                'class' => 'atma-session-image',
                                'alt'   => $session_image_alt ?: 'Osoba odpoczywająca w przytulnej przestrzeni domowej',
                            ]
                        );
                        ?>
                    <?php else : ?>
                        <div class="atma-session-image-placeholder" role="img" aria-label="Spokojna przestrzeń do sesji oddechowej"></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="atma-session-content">
            <div class="atma-container atma-session-copy">
                <?php if ($has_session_content) : ?>
                    <?php echo $session_content_html; ?>
                <?php else : ?>
                    <p>Podstawą sesji jest oddech i jego połączony rytm, którego uczę Cię na początku spotkania.</p>

                    <p class="atma-session-emphasis">Reszta dzieje się sama.</p>

                    <p>Możesz przyjść z konkretnym tematem, który chcesz poruszyć, albo po prostu zaufać oddechowi i położyć się, pozwalając ciału prowadzić Cię przez doświadczenie.</p>

                    <p class="atma-session-wisdom">I to jest bardzo ciekawe, ponieważ nasza głowa może mieć wiele pomysłów na to, czym powinniśmy się zająć. Tymczasem oddech i ciało mają swoją własną mądrość.</p>

                    <p>W tym procesie istnieje pewien „święty porządek” – pojawia się to, co w danym momencie najbardziej potrzebuje naszej uwagi, doświadczenia i integracji.</p>
                <?php endif; ?>
            </div>
        </section>

        <div class="atma-session-cta">
            <a class="atma-btn" href="<?php echo esc_url(home_url('/#kontakt')); ?>">Umów sesję</a>
        </div>
    <?php endwhile; ?>
</article>

<?php get_footer(); ?>
