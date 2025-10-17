<?php
add_action('init', 'my_custom_rewrite');
function my_custom_rewrite() {
    // add_rewrite_rule(
    //     '^portfolio/',
    //     'index.php?portfolio=yes',
    //     'top'
    // );
}

add_filter('query_vars', 'add_lang_query_handler');
function add_lang_query_handler($vars) {
    $vars[] = 'lang';
    //$vars[] = 'portfolio';
    return $vars;
}

// add_filter('template_include', 'template_path_handler_customer');
// function template_path_handler_customer($template_path) {
//     $portfolio_page = get_query_var('portfolio');
//     if ($portfolio_page) {
//         return get_template_directory() . '/template/portfolio.php';
//     }
//     return $template_path;
// }