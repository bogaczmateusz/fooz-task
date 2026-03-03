<?php

namespace FoozTheme\CustomPostTypes;

use FoozTheme\CustomPostTypes\BooksPostType;

class CustomPostTypes {
    private $books_post_type;

    public function __construct() {
        $this->books_post_type = new BooksPostType();
    }

    public function init() {
        add_action( 'init', array( $this, 'register_post_types' ) );
    }

    public function register_post_types() {
        $this->books_post_type->register_post_type();
    }
}