<?php

/**
 * Retrive some specific data from menu
 */
function oc_get_nav_menu_array($menu_name){

    $menuLocations = get_nav_menu_locations();
    $menuID = $menuLocations[$menu_name];
    //Objects array which contains menu objects
    $array_menu = wp_get_nav_menu_items($menuID);

    $menu = array();
    foreach ($array_menu as $m) {
        if (empty($m->menu_item_parent)) {
            $menu[$m->ID] = array();
            $menu[$m->ID]['title']      =   $m->title;
            $menu[$m->ID]['url']        =   $m->url;
            $menu[$m->ID]['classes']    =   $m->classes;
            $menu[$m->ID]['children']   =   array();
        }
    }
    $submenu = array();
    foreach ($array_menu as $m) {
        if ($m->menu_item_parent) {
            $submenu[$m->ID] = array();
            $submenu[$m->ID]['title']   =   $m->title;
            $submenu[$m->ID]['url']     =   $m->url;
            $submenu[$m->ID]['classes'] =   $m->classes;
            $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
        }
    }
    return $menu;
}

function oc_api_top_menu() { return oc_get_nav_menu_array('top'); }

add_action('rest_api_init', function () {
    register_rest_route('menu/v1', 'top', array(
        'methods' => 'GET',
        'callback' => 'oc_api_top_menu'
    ));
});
