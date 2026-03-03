<?php

namespace FoozTheme\Blocks;

use FoozTheme\Constants\Constants;

class Blocks {
	public function init(): void {
		add_action( 'init', [ $this, 'register_blocks' ] );
	}

	public function register_blocks(): void {
		$blocks_dir = Constants::theme_dir() . '/build/blocks';

		register_block_type( $blocks_dir . '/faq-accordion' );
		register_block_type( $blocks_dir . '/faq-item' );
	}
}
