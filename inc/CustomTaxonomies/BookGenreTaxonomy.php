<?php

namespace FoozTheme\CustomTaxonomies;

use FoozTheme\CustomPostTypes\BooksPostType;

class BookGenreTaxonomy {
    public const TAXONOMY_NAME = 'book-genre';
    public const POST_TYPE = BooksPostType::POST_TYPE_SLUG;
    
    public function register_taxonomy() {
        $labels = array(
            'name' => __('Genres', 'fooz-theme'),
            'singular_name' => __('Genre', 'fooz-theme'),
            'menu_name' => __('Genres', 'fooz-theme'),
            'name_admin_bar' => __('Genre', 'fooz-theme'),
            'add_new' => __('Add New', 'fooz-theme'),
            'add_new_item' => __('Add New Genre', 'fooz-theme'),
            'new_item' => __('New Genre', 'fooz-theme'),
            'edit_item' => __('Edit Genre', 'fooz-theme'),
            'view_item' => __('View Genre', 'fooz-theme'),
            'all_items' => __('All Genres', 'fooz-theme'),
            'parent_item' => __('Parent Genre', 'fooz-theme'),
            'parent_item_colon' => __('Parent Genre:', 'fooz-theme'),
            'search_items' => __('Search Genres', 'fooz-theme'),
            'popular_items' => __('Popular Genres', 'fooz-theme'),
            'separate_items_with_commas' => __('Separate genres with commas', 'fooz-theme'),
            'add_or_remove_items' => __('Add or remove genres', 'fooz-theme'),
            'choose_from_most_used' => __('Choose from the most used genres', 'fooz-theme'),
            'not_found' => __('No genres found.', 'fooz-theme'),
            'no_terms' => __('No genres', 'fooz-theme'),
            'items_list_navigation' => __('Genres list navigation', 'fooz-theme'),
            'items_list' => __('Genres list', 'fooz-theme'),
            'back_to_items' => __('Back to genres', 'fooz-theme'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
        );

        register_taxonomy( self::TAXONOMY_NAME, self::POST_TYPE, $args );
    }
}