<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="atma-header">

    <div class="atma-container atma-nav">

        <a href="<?php echo esc_url(home_url('/')); ?>" class="atma-logo">
            <?php $site_name = preg_split('/\s+/', trim(get_bloginfo('name')), 3, PREG_SPLIT_NO_EMPTY); ?>
            <span><?php echo esc_html(implode(' ', array_slice($site_name, 0, 2))); ?></span>
            <span><?php echo esc_html(implode(' ', array_slice($site_name, 2))); ?></span>
        </a>

        <button class="atma-menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" aria-label="Otwórz menu">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </button>

        <nav class="atma-menu" id="primary-navigation" aria-label="Nawigacja główna">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'menu',
                'walker'         => new ATMA_Nav_Walker(),
            ]);
            ?>
        </nav>

        <a href="<?php echo esc_url(home_url('/#kontakt')); ?>" class="atma-btn">
            Umów się
        </a>

    </div>

</header>

<main>