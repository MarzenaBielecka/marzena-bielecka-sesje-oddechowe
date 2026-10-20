<?php
if (!has_category('historie-sesji')) {
    get_template_part('index');
    return;
}

get_header();
?>

<?php while (have_posts()) : ?>
    <?php the_post(); ?>
    <section class="atma-stories-page atma-story-single">
        <div class="atma-container">
            <div class="atma-stories-layout">
                <article class="atma-story-article">
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="atma-story-hero">
                            <?php the_post_thumbnail('large', ['alt' => the_title_attribute(['echo' => false])]); ?>
                        </figure>
                    <?php endif; ?>

                    <header class="atma-story-heading">
                        <?php
                        $story_categories = get_the_category();
                        if ($story_categories) :
                        ?>
                            <div class="atma-story-categories">
                                <?php foreach ($story_categories as $story_category) : ?>
                                    <a href="<?php echo esc_url(get_category_link($story_category->term_id)); ?>">
                                        <?php echo esc_html($story_category->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <h1><?php the_title(); ?></h1>
                        <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </header>

                    <div class="atma-story-content">
                        <?php
                        the_content();
                        wp_link_pages([
                            'before' => '<nav class="page-links" aria-label="Strony wpisu">',
                            'after'  => '</nav>',
                        ]);
                        ?>
                    </div>

                    <?php
                    $comment_form_defaults_filter = function ($defaults) {
                        $defaults['comment_field'] = str_replace(
                            '<label for="comment">' . _x('Comment', 'noun'),
                            '<label for="comment">Treść komentarza',
                            $defaults['comment_field']
                        );
                        $defaults['title_reply'] = 'Dodaj komentarz';
                        $defaults['title_reply_to'] = 'Odpowiedz na %s';
                        $defaults['cancel_reply_link'] = 'Anuluj odpowiedź';
                        $defaults['label_submit'] = 'Dodaj komentarz';
                        $defaults['class_container'] = 'comment-respond atma-story-comments';
                        $defaults['class_form'] = 'comment-form atma-story-comments-form';

                        return $defaults;
                    };
                    $comment_form_fields_filter = function ($fields) {
                        if (isset($fields['author'])) {
                            $fields['author'] = str_replace(
                                '<label for="author">' . __('Name'),
                                '<label for="author">Imię',
                                $fields['author']
                            );
                        }

                        if (isset($fields['email'])) {
                            $fields['email'] = str_replace(
                                '<label for="email">' . __('Email'),
                                '<label for="email">Adres e-mail',
                                $fields['email']
                            );
                        }

                        unset($fields['url']);

                        return $fields;
                    };

                    add_filter('comment_form_defaults', $comment_form_defaults_filter);
                    add_filter('comment_form_fields', $comment_form_fields_filter);
                    comments_template();
                    remove_filter('comment_form_defaults', $comment_form_defaults_filter);
                    remove_filter('comment_form_fields', $comment_form_fields_filter);
                    ?>
                </article>

                <?php get_template_part('template-parts/historie-sidebar'); ?>
            </div>
        </div>
    </section>
<?php endwhile; ?>

<?php get_footer(); ?>
