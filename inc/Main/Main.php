<?php

namespace FoozTheme\Main;

use FoozTheme\Constants\Constants;
use FoozTheme\Enqueue\Enqueue;
use FoozTheme\CustomPostTypes\CustomPostTypes;
use FoozTheme\CustomTaxonomies\CustomTaxonomies;
use FoozTheme\Rest\RestRoutes;
use FoozTheme\Query\QueryModifier;
use FoozTheme\Blocks\Blocks;

class Main {
	public function __construct() {
		$this->theme_dir = Constants::theme_dir();
		$this->theme_uri = Constants::theme_uri();
		$this->home_url = Constants::home_url();
		
		$this->enqueue = new Enqueue( $this->theme_uri, $this->theme_dir, $this->home_url );
		$this->custom_post_types = new CustomPostTypes();
		$this->custom_taxonomies = new CustomTaxonomies();
		$this->rest_routes = new RestRoutes();
		$this->query_modifier = new QueryModifier();
		$this->blocks = new Blocks();
	}

	private function load_textdomain() {
		load_theme_textdomain( 'fooz-theme', trailingslashit( $this->theme_dir ) . 'languages' );
	}

	public function init() {
		$this->load_textdomain();
		
		$this->enqueue->init();
		$this->custom_post_types->init();
		$this->custom_taxonomies->init();
		$this->rest_routes->init();
		$this->query_modifier->init();
		$this->blocks->init();
	}
}
