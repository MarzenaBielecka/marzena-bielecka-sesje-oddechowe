<?php
$story_post_ids = get_posts([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'category_name'  => 'historie-sesji',
    'fields'         => 'ids',
]);
$story_categories = $story_post_ids
    ? get_categories([
        'taxonomy'   => 'category',
        'hide_empty' => true,
        'object_ids' => $story_post_ids,
    ])
    : [];
$story_category_ids = wp_list_pluck($story_categories, 'term_id');

if (!in_array(get_queried_object_id(), array_map('absint', $story_category_ids), true)) {
    get_template_part('index');
    return;
}

get_header();

get_template_part('template-parts/histories-archive', null, [
    'query'       => $GLOBALS['wp_query'],
    'title'       => single_cat_title('', false),
    'description' => get_the_archive_description(),
]);

get_footer();
