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
            'default' => 'Oddech, który prowadzi do wewnętrznego spokoju',
            'type'    => 'textarea',
        ],
        'atma_hero_description' => [
            'label'   => 'Opis',
            'default' => 'Indywidualne sesje oddechowe, medytacja i świadoma praca z energią. Odkryj moc głębokiego oddechu i odzyskaj równowagę.',
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
        'atma_testimonial_1_text' => [
            'label'   => 'Opinia 1 — Treść opinii',
            'default' => 'To było naprawdę niesamowite doświadczenie.',
            'type'    => 'textarea',
        ],
        'atma_testimonial_1_name' => [
            'label'   => 'Opinia 1 — Imię i nazwisko',
            'default' => 'Agata Nowak',
            'type'    => 'text',
        ],
        'atma_testimonial_1_city' => [
            'label'   => 'Opinia 1 — Miejscowość',
            'default' => 'Jelenia Góra',
            'type'    => 'text',
        ],
        'atma_testimonial_1_rating' => [
            'label'   => 'Opinia 1 — Liczba gwiazdek',
            'default' => 5,
            'type'    => 'number',
        ],
        'atma_testimonial_2_text' => [
            'label'   => 'Opinia 2 — Treść opinii',
            'default' => 'Marzena to wspaniała terapeutka. Sesja była naprawdę mocna.',
            'type'    => 'textarea',
        ],
        'atma_testimonial_2_name' => [
            'label'   => 'Opinia 2 — Imię i nazwisko',
            'default' => 'Stefan Koziński',
            'type'    => 'text',
        ],
        'atma_testimonial_2_city' => [
            'label'   => 'Opinia 2 — Miejscowość',
            'default' => 'Golub-Dobrzyń',
            'type'    => 'text',
        ],
        'atma_testimonial_2_rating' => [
            'label'   => 'Opinia 2 — Liczba gwiazdek',
            'default' => 5,
            'type'    => 'number',
        ],
        'atma_testimonial_3_text' => [
            'label'   => 'Opinia 3 — Treść opinii',
            'default' => 'To była super sesja.',
            'type'    => 'textarea',
        ],
        'atma_testimonial_3_name' => [
            'label'   => 'Opinia 3 — Imię i nazwisko',
            'default' => 'Anna Kowalska',
            'type'    => 'text',
        ],
        'atma_testimonial_3_city' => [
            'label'   => 'Opinia 3 — Miejscowość',
            'default' => 'Warszawa',
            'type'    => 'text',
        ],
        'atma_testimonial_3_rating' => [
            'label'   => 'Opinia 3 — Liczba gwiazdek',
            'default' => 5,
            'type'    => 'number',
        ],
    ];

    foreach ($testimonials_settings as $setting_id => $setting) {
        $sanitize_callback = $setting['type'] === 'number'
            ? function ($value) {
                return min(5, max(1, absint($value)));
            }
            : ($setting['type'] === 'textarea' ? 'sanitize_textarea_field' : 'sanitize_text_field');

        $wp_customize->add_setting($setting_id, [
            'default'           => $setting['default'],
            'sanitize_callback' => $sanitize_callback,
        ]);

        $control_args = [
            'label'   => $setting['label'],
            'section' => 'atma_testimonials',
            'type'    => $setting['type'],
        ];

        if ($setting['type'] === 'number') {
            $control_args['input_attrs'] = [
                'min'  => 1,
                'max'  => 5,
                'step' => 1,
            ];
        }

        $wp_customize->add_control($setting_id, $control_args);
    }

}

add_action('customize_register', 'atma_customize_register');

/* ==========================================
   CUSTOM POST TYPE — OPINIE
========================================== */

function atma_register_testimonials() {

    register_post_type('opinia', [

        'labels' => [
            'name'          => 'Opinie',
            'singular_name' => 'Opinia',
            'add_new_item'  => 'Dodaj opinię',
            'edit_item'     => 'Edytuj opinię',
        ],

        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-format-quote',
'supports' => ['title', 'editor'],
        'has_archive'  => false,

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
        'opinia'
    );

}

add_action('add_meta_boxes', 'atma_add_testimonial_meta');

function atma_testimonial_callback($post) {

    $name   = get_post_meta($post->ID, 'client_name', true);
    $city   = get_post_meta($post->ID, 'client_city', true);
    $rating = get_post_meta($post->ID, 'client_rating', true);

    ?>

    <p>
        <label>Imię i nazwisko</label><br>
        <input type="text" name="client_name" value="<?php echo esc_attr($name); ?>" style="width:100%;">
    </p>

    <p>
        <label>Miasto</label><br>
        <input type="text" name="client_city" value="<?php echo esc_attr($city); ?>" style="width:100%;">
    </p>

    <p>
        <label>Ocena</label><br>
        <select name="client_rating">
            <?php for($i=5;$i>=1;$i--) : ?>
                <option value="<?php echo $i; ?>" <?php selected($rating,$i); ?>>
                    <?php echo $i; ?> ★
                </option>
            <?php endfor; ?>
        </select>
    </p>

    <?php
}

function atma_save_testimonial($post_id) {

    if(isset($_POST['client_name']))
        update_post_meta($post_id,'client_name',sanitize_text_field($_POST['client_name']));

    if(isset($_POST['client_city']))
        update_post_meta($post_id,'client_city',sanitize_text_field($_POST['client_city']));

    if(isset($_POST['client_rating']))
        update_post_meta($post_id,'client_rating',intval($_POST['client_rating']));
}

add_action('save_post_opinia','atma_save_testimonial');