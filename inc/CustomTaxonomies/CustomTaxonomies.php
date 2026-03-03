<?php

namespace FoozTheme\CustomTaxonomies;

use FoozTheme\CustomTaxonomies\BookGenreTaxonomy;

class CustomTaxonomies {
    private $book_genre_taxonomy;

    public function __construct() {
        $this->book_genre_taxonomy = new BookGenreTaxonomy();
    }

    public function init() {
        add_action( 'init', array( $this, 'register_taxonomies' ) );
    }

    public function register_taxonomies() {
        $this->book_genre_taxonomy->register_taxonomy();
    }
}