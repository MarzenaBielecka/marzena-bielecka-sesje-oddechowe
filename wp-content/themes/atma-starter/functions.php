<?php

/* ==========================================
   STYLE
========================================== */

add_action('wp_enqueue_scripts', function () {

    wp_enqueue_style(
        'atma-starter-style',
        get_stylesheet_uri(),
        ['hello-elementor-theme-style'],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_style(
        'atma-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        ['atma-starter-style'],
        filemtime(get_stylesheet_directory() . '/assets/css/main.css')
    );

    wp_enqueue_script(
        'atma-navigation',
        get_stylesheet_directory_uri() . '/assets/js/navigation.js',
        [],
        filemtime(get_stylesheet_directory() . '/assets/js/navigation.js'),
        true
    );

    if (is_front_page()) {
        wp_enqueue_script(
            'atma-testimonials',
            get_stylesheet_directory_uri() . '/assets/js/testimonials.js',
            [],
            filemtime(get_stylesheet_directory() . '/assets/js/testimonials.js'),
            true
        );
    }

    if (is_page('faq')) {
        wp_enqueue_script(
            'atma-faq',
            get_stylesheet_directory_uri() . '/assets/js/faq.js',
            [],
            filemtime(get_stylesheet_directory() . '/assets/js/faq.js'),
            true
        );
    }

});

/* ==========================================
   THEME SETUP
========================================== */

add_action('after_setup_theme', function () {

    add_theme_support('post-thumbnails');

    register_nav_menus([
        'primary' => 'Menu główne',
    ]);

});

class ATMA_Nav_Walker extends Walker_Nav_Menu {

    private $submenu_ids = [];

    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
        $item_output = '';

        parent::start_el($item_output, $data_object, $depth, $args, $current_object_id);
        $output .= $item_output;

        if (isset($data_object->classes) && in_array('menu-item-has-children', $data_object->classes, true)) {
            $submenu_id = 'atma-submenu-' . absint($data_object->ID);
            $this->submenu_ids[$depth] = $submenu_id;
            $label = sprintf('Rozwiń podmenu: %s', wp_strip_all_tags($data_object->title));

            $output .= sprintf(
                '<button class="atma-submenu-toggle" type="button" aria-expanded="false" aria-controls="%1$s" aria-label="%2$s"><span aria-hidden="true"></span></button>',
                esc_attr($submenu_id),
                esc_attr($label)
            );
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $submenu_id = isset($this->submenu_ids[$depth]) ? $this->submenu_ids[$depth] : '';
        $classes = apply_filters('nav_menu_submenu_css_class', ['sub-menu'], $args, $depth);
        $atts = [
            'class' => implode(' ', $classes),
            'id'    => $submenu_id,
        ];
        $atts = apply_filters('nav_menu_submenu_attributes', $atts, $args, $depth);

        $output .= sprintf("\n%s<ul%s>\n", $indent, $this->build_atts($atts));
    }
}

/* ==========================================
   CUSTOMIZER
========================================== */

function atma_customize_register($wp_customize) {

    /* ===== HERO ===== */

    $wp_customize->add_section('atma_hero', [
        'title'    => 'Atma Starter – Hero',
        'priority' => 29,
    ]);

    $hero_settings = [
        'atma_hero_kicker' => [
            'label'   => 'Nadtytuł',
            'default' => 'MARZENA BIELECKA • SESJE ODDECHOWE',
            'type'    => 'text',
        ],
        'atma_hero_title' => [
            'label'   => 'Tytuł',
            'default' => 'Zapraszam Cię do świata oddechu',
            'type'    => 'textarea',
        ],
        'atma_hero_description' => [
            'label'   => 'Opis',
            'default' => "Zapraszam Cię do świata oddechu połączonego – niezwykle subtelnego, a jednocześnie mającego ogromną siłę oddziaływania.\n\nOddech może dotykać tego, co dzieje się w naszej psychice, ciele i emocjach, otwierając przestrzeń na zmianę, uwolnienie i nowe doświadczenia.\n\nJeśli czujesz, że potrzebujesz zmiany, zapraszam.\n\nPodążymy tą drogą razem.",
            'type'    => 'textarea',
        ],
        'atma_hero_button_primary_text' => [
            'label'   => 'Tekst głównego przycisku',
            'default' => 'Umów sesję',
            'type'    => 'text',
        ],
        'atma_hero_button_primary_url' => [
            'label'   => 'Adres głównego przycisku',
            'default' => '#kontakt',
            'type'    => 'url',
        ],
        'atma_hero_button_secondary_text' => [
            'label'   => 'Tekst drugiego przycisku',
            'default' => 'Poznaj metodę',
            'type'    => 'text',
        ],
        'atma_hero_button_secondary_url' => [
            'label'   => 'Adres drugiego przycisku',
            'default' => '#metoda',
            'type'    => 'url',
        ],
    ];

    foreach ($hero_settings as $setting_id => $setting) {
        $wp_customize->add_setting($setting_id, [
            'default'           => $setting['default'],
            'sanitize_callback' => $setting['type'] === 'url' ? 'esc_url_raw' : ($setting['type'] === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field'),
        ]);

        $wp_customize->add_control($setting_id, [
            'label'   => $setting['label'],
            'section' => 'atma_hero',
            'type'    => $setting['type'],
        ]);
    }

    /* ===== OFERTA ===== */

    $wp_customize->add_section('atma_offer', [
        'title'    => 'Atma Starter – Oferta',
        'priority' => 30,
    ]);

    $offer_settings = [
        'atma_offer_kicker' => [
            'label'   => 'Nadtytuł',
            'default' => 'INDYWIDUALNE SESJE ODDECHOWE',
            'type'    => 'text',
        ],
        'atma_offer_title' => [
            'label'   => 'Tytuł',
            'default' => 'Przestrzeń, w której ciało może naprawdę odetchnąć',
            'type'    => 'text',
        ],
        'atma_offer_description' => [
            'label'   => 'Opis',
            'default' => 'Każda sesja jest indywidualnym spotkaniem, podczas którego poprzez świadomy oddech, uważność i delikatną pracę z ciałem odnajdujesz więcej spokoju, lekkości i kontaktu ze sobą.',
            'type'    => 'textarea',
        ],
        'atma_offer_feature1_title' => [
            'label'   => 'Tytuł pierwszej korzyści',
            'default' => 'Sesja indywidualna',
            'type'    => 'text',
        ],
        'atma_offer_feature1_text' => [
            'label'   => 'Opis pierwszej korzyści',
            'default' => 'To spokojna, kameralna przestrzeń, w której możesz zatrzymać się, rozluźnić napięcia i odzyskać kontakt z własnym oddechem.',
            'type'    => 'textarea',
        ],
        'atma_offer_feature2_title' => [
            'label'   => 'Tytuł drugiej korzyści',
            'default' => 'Dla kogo?',
            'type'    => 'text',
        ],
        'atma_offer_feature2_text' => [
            'label'   => 'Opis drugiej korzyści',
            'default' => 'Dla osób odczuwających stres, przewlekłe napięcie, zmęczenie emocjonalne lub pragnących głębiej poznać siebie poprzez pracę z oddechem.',
            'type'    => 'textarea',
        ],
        'atma_offer_button_text' => [
            'label'   => 'Tekst przycisku',
            'default' => 'Umów pierwszą sesję',
            'type'    => 'text',
        ],
        'atma_offer_button_url' => [
            'label'   => 'Adres przycisku',
            'default' => '#kontakt',
            'type'    => 'url',
        ],
    ];

    foreach ($offer_settings as $setting_id => $setting) {
        $wp_customize->add_setting($setting_id, [
            'default'           => $setting['default'],
            'sanitize_callback' => $setting['type'] === 'url' ? 'esc_url_raw' : ($setting['type'] === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field'),
        ]);

        $wp_customize->add_control($setting_id, [
            'label'   => $setting['label'],
            'section' => 'atma_offer',
            'type'    => $setting['type'],
        ]);
    }

    $wp_customize->add_setting('atma_offer_image', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'atma_offer_image',
            [
                'label'     => 'Zdjęcie sekcji Oferta',
                'section'   => 'atma_offer',
                'mime_type' => 'image',
            ]
        )
    );

    /* ===== O MNIE ===== */

    $wp_customize->add_section('atma_about', [
        'title'    => 'Atma Starter – O mnie',
        'priority' => 31,
    ]);

    $wp_customize->add_setting('atma_about_image', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'atma_about_image',
            [
                'label'     => 'Zdjęcie Marzeny',
                'section'   => 'atma_about',
                'mime_type' => 'image',
            ]
        )
    );

    $about_settings = [
        'atma_about_kicker' => [
            'label'   => 'Nadtytuł',
            'default' => 'O MNIE',
            'type'    => 'text',
        ],
        'atma_about_title' => [
            'label'   => 'Tytuł',
            'default' => 'Poznaj Marzenę Bielecką',
            'type'    => 'text',
        ],
        'atma_about_paragraph1' => [
            'label'   => 'Akapit 1',
            'default' => 'Oddech jest dla mnie drogą do odzyskiwania kontaktu z ciałem, emocjami i wewnętrznym spokojem. Tworzę bezpieczną przestrzeń, w której możesz zatrzymać się i naprawdę usłyszeć siebie.',
            'type'    => 'textarea',
        ],
        'atma_about_paragraph2' => [
            'label'   => 'Akapit 2',
            'default' => 'Łączę świadomą pracę z oddechem, uważność oraz indywidualne podejście do każdej osoby. Każde spotkanie jest inne, ponieważ każda historia jest wyjątkowa.',
            'type'    => 'textarea',
        ],
        'atma_about_button_text' => [
            'label'   => 'Tekst przycisku',
            'default' => 'Poznaj moją historię',
            'type'    => 'text',
        ],
        'atma_about_button_url' => [
            'label'   => 'Link przycisku',
            'default' => home_url('/o-mnie/'),
            'type'    => 'url',
        ],
    ];

    foreach ($about_settings as $setting_id => $setting) {
        $wp_customize->add_setting($setting_id, [
            'default'           => $setting['default'],
            'sanitize_callback' => $setting['type'] === 'url' ? 'esc_url_raw' : ($setting['type'] === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field'),
        ]);

        $wp_customize->add_control($setting_id, [
            'label'   => $setting['label'],
            'section' => 'atma_about',
            'type'    => $setting['type'],
        ]);
    }

    /* ===== KONTAKT ===== */

    $wp_customize->add_section('atma_contact', [
        'title'    => 'Atma Starter – Kontakt',
        'priority' => 32,
    ]);

    $wp_customize->add_setting('atma_contact_image', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'atma_contact_image',
            [
                'label'   => 'Zdjęcie kontaktowe',
                'section' => 'atma_contact',
            ]
        )
    );

    $contact_settings = [
        'atma_contact_kicker' => [
            'label'   => 'Nadtytuł',
            'default' => 'KONTAKT',
            'type'    => 'text',
        ],
        'atma_contact_title' => [
            'label'   => 'Tytuł',
            'default' => 'Spotkajmy się online',
            'type'    => 'text',
        ],
        'atma_contact_description' => [
            'label'   => 'Opis',
            'default' => 'Zadzwoń lub wyślij SMS. Chętnie odpowiem na Twoje pytania i wspólnie znajdziemy najlepszy termin pierwszej sesji online.',
            'type'    => 'textarea',
        ],
        'atma_contact_phone' => [
            'label'   => 'Numer telefonu',
            'default' => '+48 506 853 033',
            'type'    => 'text',
        ],
        'atma_contact_button_text' => [
            'label'   => 'Tekst przycisku',
            'default' => 'Zadzwoń lub wyślij SMS',
            'type'    => 'text',
        ],
        'atma_contact_button_url' => [
            'label'   => 'Link przycisku',
            'default' => 'tel:+48506853033',
            'type'    => 'url',
        ],
    ];

    foreach ($contact_settings as $setting_id => $setting) {
        $sanitize_callback = $setting['type'] === 'url'
            ? function ($value) {
                return esc_url_raw($value, array_merge(wp_allowed_protocols(), ['tel']));
            }
            : ($setting['type'] === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field');

        $wp_customize->add_setting($setting_id, [
            'default'           => $setting['default'],
            'sanitize_callback' => $sanitize_callback,
        ]);

        $wp_customize->add_control($setting_id, [
            'label'   => $setting['label'],
            'section' => 'atma_contact',
            'type'    => $setting['type'],
        ]);
    }

    /* ===== STOPKA ===== */

    $wp_customize->add_section('atma_footer', [
        'title'    => 'Atma Starter – Stopka',
        'priority' => 34,
    ]);

    $wp_customize->add_setting('atma_footer_image', [
        'default'           => 73,
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'atma_footer_image',
            [
                'label'     => 'Zdjęcie w stopce',
                'section'   => 'atma_footer',
                'mime_type' => 'image',
            ]
        )
    );

    /* ===== OPINIE ===== */

    $wp_customize->add_section('atma_testimonials', [
        'title'    => 'Atma Starter – Opinie',
        'priority' => 33,
    ]);

    $testimonials_settings = [
        'atma_testimonials_kicker' => [
            'label'   => 'Nadtytuł',
            'default' => 'OPINIE KLIENTÓW',
            'type'    => 'text',
        ],
        'atma_testimonials_title' => [
            'label'   => 'Tytuł',
            'default' => 'Co mówią osoby po sesjach?',
            'type'    => 'text',
        ],
        'atma_testimonials_description' => [
            'label'   => 'Opis',
            'default' => 'Każda historia jest inna, ale wszystkie łączy jedno — większy spokój, lekkość i głębszy kontakt ze sobą.',
            'type'    => 'textarea',
        ],
    ];

    foreach ($testimonials_settings as $setting_id => $setting) {
        $wp_customize->add_setting($setting_id, [
            'default'           => $setting['default'],
            'sanitize_callback' => $setting['type'] === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field',
        ]);

        $wp_customize->add_control($setting_id, [
            'label'   => $setting['label'],
            'section' => 'atma_testimonials',
            'type'    => $setting['type'],
        ]);
    }

}

add_action('customize_register', 'atma_customize_register');

function atma_customize_stories_register($wp_customize) {
    $wp_customize->add_section('atma_stories', [
        'title'    => 'Historie sesji',
        'priority' => 31,
    ]);

    $wp_customize->add_setting('atma_stories_sidebar_image', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(new WP_Customize_Media_Control(
        $wp_customize,
        'atma_stories_sidebar_image',
        [
            'label'       => 'Zdjęcie na dole panelu bocznego',
            'description' => 'Wybierz zdjęcie z biblioteki mediów. Nie wybrano domyślnego zdjęcia.',
            'section'     => 'atma_stories',
            'mime_type'   => 'image',
        ]
    ));
}

add_action('customize_register', 'atma_customize_stories_register');

function atma_filter_stories_archive($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_home()) {
        return;
    }

    $stories_category = get_category_by_slug('historie-sesji');

    if ($stories_category) {
        $query->set('cat', (int) $stories_category->term_id);
    }

    $query->set('posts_per_page', 6);
}

add_action('pre_get_posts', 'atma_filter_stories_archive');

/* ==========================================
   CUSTOM POST TYPE — OPINIE
========================================== */

function atma_register_testimonials() {

    register_post_type('opinia', [

        'labels' => [
            'name'          => 'Opinie',
            'menu_name'     => 'Opinie',
            'singular_name' => 'Opinia',
            'all_items'     => 'Wszystkie opinie',
            'add_new'       => 'Dodaj opinię',
            'add_new_item'  => 'Dodaj opinię',
            'edit_item'     => 'Edytuj opinię',
            'view_item'     => 'Zobacz opinię',
            'search_items'  => 'Szukaj opinii',
            'not_found'     => 'Nie znaleziono opinii.',
        ],

        'public'        => true,
        'show_in_rest'  => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-format-quote',
        'supports'      => ['title', 'editor', 'revisions'],
        'has_archive'   => false,

    ]);

}

add_action('init', 'atma_register_testimonials');

/* ==========================================
   META BOX — OPINIA
========================================== */

function atma_add_testimonial_meta() {

    add_meta_box(
        'atma_testimonial',
        'Dane klienta',
        'atma_testimonial_callback',
        'opinia',
        'normal',
        'high'
    );

}

add_action('add_meta_boxes', 'atma_add_testimonial_meta');

function atma_testimonial_callback($post) {

    $name   = get_post_meta($post->ID, 'client_name', true);
    $city   = get_post_meta($post->ID, 'client_city', true);
    $rating = absint(get_post_meta($post->ID, 'client_rating', true));

    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }

    ?>

    <?php wp_nonce_field('atma_save_testimonial', 'atma_testimonial_nonce'); ?>

    <p>
        <label for="client_name">Imię i nazwisko</label><br>
        <input type="text" id="client_name" name="client_name" value="<?php echo esc_attr($name); ?>" class="widefat">
    </p>

    <p>
        <label for="client_city">Miejscowość</label><br>
        <input type="text" id="client_city" name="client_city" value="<?php echo esc_attr($city); ?>" class="widefat">
    </p>

    <p>
        <label for="client_rating">Liczba gwiazdek</label><br>
        <select id="client_rating" name="client_rating">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <option value="<?php echo esc_attr($i); ?>" <?php selected($rating, $i); ?>>
                    <?php echo esc_html($i . ' ★'); ?>
                </option>
            <?php endfor; ?>
        </select>
    </p>

    <?php
}

function atma_save_testimonial($post_id) {
    $post = get_post($post_id);

    if (
        ! $post
        || 'opinia' !== $post->post_type
        || wp_is_post_revision($post_id)
        || wp_is_post_autosave($post_id)
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || ! isset($_POST['atma_testimonial_nonce'])
        || ! is_string($_POST['atma_testimonial_nonce'])
        || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['atma_testimonial_nonce'])), 'atma_save_testimonial')
        || ! current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $rating = null;

    if (isset($_POST['client_rating'])) {
        $rating_value = wp_unslash($_POST['client_rating']);
        $rating       = is_scalar($rating_value) ? filter_var($rating_value, FILTER_VALIDATE_INT) : false;

        if (false === $rating || $rating < 1 || $rating > 5) {
            set_transient(
                'atma_testimonial_rating_error_' . get_current_user_id() . '_' . $post_id,
                'Nie zapisano pól opinii: liczba gwiazdek musi być liczbą całkowitą od 1 do 5.',
                MINUTE_IN_SECONDS
            );
            return;
        }
    }

    foreach (['client_name', 'client_city'] as $field) {
        if (isset($_POST[$field]) && is_scalar($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }

    if (null !== $rating) {
        update_post_meta($post_id, 'client_rating', $rating);
    }
}

add_action('save_post_opinia', 'atma_save_testimonial');

function atma_testimonial_save_error_notice() {
    if (!isset($_GET['post']) || !is_scalar($_GET['post'])) {
        return;
    }

    $post_id = absint(wp_unslash($_GET['post']));

    if ('opinia' !== get_post_type($post_id) || !current_user_can('edit_post', $post_id)) {
        return;
    }

    $transient_key = 'atma_testimonial_rating_error_' . get_current_user_id() . '_' . $post_id;
    $message       = get_transient($transient_key);

    if ($message) {
        delete_transient($transient_key);
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo esc_html($message); ?></p>
        </div>
        <?php
    }
}

add_action('admin_notices', 'atma_testimonial_save_error_notice');
