<?php

namespace FoozTheme\Rest;

use FoozTheme\Rest\Routes\LatestBooksRoute;

class RestRoutes {
    public function __construct() {
        $this->latest_books_route = new LatestBooksRoute();
    }

    public function init() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {
        $this->latest_books_route->register_route();
    }
}