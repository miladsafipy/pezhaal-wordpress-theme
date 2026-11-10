<?php
defined('ABSPATH') || exit;

/**
 * Register Golden Packages Custom Post Type.
 */

function pezhaal_register_golden_package_cpt(){
    $labels = array(
        'name'                   =>     'پکیج های طلایی',
        'singular_name'          =>     'پکیج طلایی',
        'menu_name'              =>     'پکیج های طلایی',
        'add_new'                =>     'افزودن پکیج',
        'add_new_item'           =>     'افزودن پکیج جدید',
        'edit_item'              =>     'ویرایش پکیج',
        'new_item'               =>     'پکیج جدید',
        'view_item'              =>     'مشاهده پکیج',
        'search_items'           =>     'جستجوی پکیج ها',
        'not_found'              =>     'پکیجی پیدا نشد',
        'not_found_in_trash'     =>     'پکیجی در زباله دان وجود ندارد',
        'all_items'              =>     'تمام پکیج ها',
        'featured_image'         =>     'تصویر پکیج',
        'set_featured_image'     =>     'انتخاب تصویر پکیج',
        'remove_featured_image'  =>     'حذف تصویر پکیج',
    );

    $args = array(
        'labels'  =>  $labels,
        'public'  =>  true,
        'show_ui'  =>  true,
        'show_in_menu'  =>  true,
        'show_in_rest'  =>  true,
        'menu_position'  =>  25,
        'menu_icon'  =>  'dashicons-awards',
        'supports'  =>  array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive'  =>  'golden-packages',
        'rewrite'  =>  array(
            'slug'  =>  'golden-packages',
            'with_front'    =>  false,
        ),
        'publicly_queryable'  =>  true,
        'exclude_from_search'  =>  false,
        
    );

    register_post_type('golden_package', $args);
}

add_action('init', 'pezhaal_register_golden_package_cpt');