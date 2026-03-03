<?php

namespace FoozTheme\CustomPostTypes;

class BooksPostType {
    public const POST_TYPE_SLUG = 'library';

    public function register_post_type() {
        $labels = array(
            'name' => __('Books', 'fooz-theme'),
            'singular_name' => __('Book', 'fooz-theme'),
            'menu_name' => __('Books', 'fooz-theme'),
            'name_admin_bar' => __('Book', 'fooz-theme'),
            'add_new' => __('Add New', 'fooz-theme'),
            'add_new_item' => __('Add New Book', 'fooz-theme'),
            'new_item' => __('New Book', 'fooz-theme'),
            'edit_item' => __('Edit Book', 'fooz-theme'),
            'view_item' => __('View Book', 'fooz-theme'),
            'all_items' => __('All Books', 'fooz-theme'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => self::POST_TYPE_SLUG,
            ),
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
            ),
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-book',
        );
        
        register_post_type( self::POST_TYPE_SLUG, $args );
    }
}