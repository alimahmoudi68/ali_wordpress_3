<?php 

add_filter('query_vars' , 'add_lang_query_handler');
function add_lang_query_handler($vars){
    $vars[]= 'lang';
    return $vars;
}