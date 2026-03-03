<?php

namespace FoozTheme\Query;

use FoozTheme\CustomTaxonomies\BookGenreTaxonomy;

class QueryModifier {
	public function init() {
		add_action( 'pre_get_posts', array( $this, 'set_book_genre_posts_per_page' ) );
	}

	public function set_book_genre_posts_per_page( \WP_Query $query ) {
		if (
			!is_admin() &&
			$query->is_main_query() &&
			$query->is_tax( BookGenreTaxonomy::TAXONOMY_NAME )
		) {
			$query->set( 'posts_per_page', 5 );
		}
	}
}
