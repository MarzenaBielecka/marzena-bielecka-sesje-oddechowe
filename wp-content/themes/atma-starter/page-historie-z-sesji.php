<?php
get_header();

$stories_query = new WP_Query([
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'category_name'       => 'historie-sesji',
    'posts_per_page'      => 6,
    'paged'               => max(1, (int) get_query_var('paged')),
    'ignore_sticky_posts' => true,
]);

get_template_part('template-parts/histories-archive', null, [
    'query' => $stories_query,
    'title' => 'Historie z sesji oddechowych',
]);

get_footer();
