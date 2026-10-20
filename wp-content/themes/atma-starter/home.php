<?php
get_header();

get_template_part('template-parts/histories-archive', null, [
    'query' => $GLOBALS['wp_query'],
    'title' => 'Historie sesji',
]);

get_footer();
