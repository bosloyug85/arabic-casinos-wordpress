<?php

register_nav_menus(['header' => __( 'Header Menu' )]);
register_nav_menus(['footer' => __( 'Footer Menu' )]);

function slugify($text, string $divider = '-')
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

function get_menu($data) {
//    # Change 'menu' to your own navigation slug.
//    return wp_get_nav_menu_items($data['menu']);
    $menu_items = wp_get_nav_menu_items($data['menu']);
    foreach($menu_items as $menu_item) {
        // ALTERNATIVE: $slug = get_post_field( 'post_name', $menu_item->object_id );
        $slug = basename( get_permalink($menu_item->object_id) );
        $menu_item->slug = slugify($menu_item->title);
    }
    return $menu_items;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'get-menus', '/menu/(?P<menu>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'get_menu',
    ) );
} );