<?php

namespace FoozTheme\Rest\Routes;

use WP_REST_Server;
use FoozTheme\CustomPostTypes\BooksPostType;
use FoozTheme\CustomTaxonomies\BookGenreTaxonomy;

class LatestBooksRoute {
    public function register_route() {
        register_rest_route( 'fooz-theme/v1', '/latest-books', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_latest_books' ),
            'permission_callback' => '__return_true',
        ) );
    }

    public function get_latest_books( \WP_REST_Request $request ) {
        $exclude_id = (int) $request->get_param( 'exclude' );

        $args = array(
            'post_type'      => BooksPostType::POST_TYPE_SLUG,
            'posts_per_page' => 20,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ($exclude_id) {
            $args['post__not_in'] = array( $exclude_id );
        }

        $books = get_posts( $args );

        $data = array_map( function( $book ) {
            $genres = get_the_terms( $book->ID, BookGenreTaxonomy::TAXONOMY_NAME );
            $genre  = ( ! empty( $genres ) && ! is_wp_error( $genres ) )
                ? implode( ', ', wp_list_pluck( $genres, 'name' ) )
                : '';

            return array(
                'title'   => get_the_title( $book ),
                'date'    => get_the_date( 'F j, Y', $book ),
                'genre'   => $genre,
                'excerpt' => get_the_excerpt( $book ),
                'url'     => get_permalink( $book ),
            );
        }, $books );

        return rest_ensure_response( $data );
    }
}