<?php
get_header();
?>

<article class="atma-topics-page">
    <?php while (have_posts()) : ?>
        <?php
        the_post();

        $topic_blocks = parse_blocks(get_the_content());
        $topic_intro = '';
        $topic_sections = [];
        $topic_closing = '';
        $current_topic = null;
        $topic_closing_block_index = null;

        for ($block_index = count($topic_blocks) - 1; $block_index >= 0; $block_index--) {
            if (isset($topic_blocks[$block_index]['blockName']) && 'core/paragraph' === $topic_blocks[$block_index]['blockName']) {
                $topic_closing_block_index = $block_index;
                break;
            }
        }

        foreach ($topic_blocks as $block_index => $topic_block) {
            $block_name = isset($topic_block['blockName']) ? $topic_block['blockName'] : '';
            $heading_level = isset($topic_block['attrs']['level'])
                ? (int) $topic_block['attrs']['level']
                : 2;

            if ($block_index === $topic_closing_block_index) {
                if (null !== $current_topic) {
                    $topic_sections[] = $current_topic;
                    $current_topic = null;
                }

                $topic_closing = render_block($topic_block);
                continue;
            }

            if (
                'core/heading' === $block_name
                && 2 === $heading_level
            ) {
                if (null !== $current_topic) {
                    $topic_sections[] = $current_topic;
                }

                $current_topic = [
                    'title'   => wp_strip_all_tags(render_block($topic_block)),
                    'content' => '',
                ];
                continue;
            }

            if (null === $current_topic) {
                if (!$topic_intro && 'core/paragraph' === $block_name) {
                    $topic_intro = render_block($topic_block);
                }
                continue;
            }

            $current_topic['content'] .= render_block($topic_block);
        }

        if (null !== $current_topic) {
            $topic_sections[] = $current_topic;
        }
        ?>

        <section class="atma-topics-hero">
            <div class="atma-container">
                <span class="atma-topics-kicker">SESJE ODDECHOWE</span>
                <h1><?php the_title(); ?></h1>
                <?php if ($topic_intro) : ?>
                    <div class="atma-topics-intro"><?php echo $topic_intro; ?></div>
                <?php endif; ?>
            </div>
        </section>

        <?php if ($topic_sections) : ?>
            <section class="atma-topics-section" aria-label="Tematy, z którymi możesz przyjść na sesję">
                <div class="atma-container">
                    <div class="atma-topics-grid">
                        <?php foreach ($topic_sections as $topic_section) : ?>
                            <section class="atma-topics-card">
                                <h2><?php echo esc_html($topic_section['title']); ?></h2>
                                <div class="atma-topics-card-content">
                                    <?php echo $topic_section['content']; ?>
                                </div>
                            </section>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($topic_closing) : ?>
            <section class="atma-topics-closing-section">
                <div class="atma-container">
                    <div class="atma-topics-closing"><?php echo $topic_closing; ?></div>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
</article>

<?php get_footer(); ?>
