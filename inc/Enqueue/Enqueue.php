<?php

namespace FoozTheme\Enqueue;

class Enqueue {
	private $theme_uri;
	private $theme_dir;
	private $home_url;

	public function __construct( $theme_uri = null, $theme_dir = null, $home_url = null ) {
		$this->theme_uri = $theme_uri ?: get_stylesheet_directory_uri();
		$this->theme_dir = $theme_dir ?: get_stylesheet_directory();
		$this->home_url = $home_url ?: home_url();
	}

	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function enqueue_assets() {
		$css_rel = 'build/assets/css/styles.css';
		$js_rel  = 'build/assets/js/scripts.js';

		$css_path = trailingslashit( $this->theme_dir ) . $css_rel;
		$js_path  = trailingslashit( $this->theme_dir ) . $js_rel;

		$css_ver = file_exists( $css_path ) ? filemtime( $css_path ) : null;
		$js_ver  = file_exists( $js_path ) ? filemtime( $js_path ) : null;

		wp_enqueue_style(
			'fooz-theme-style',
			trailingslashit( $this->theme_uri ) . $css_rel,
			array(),
			$css_ver
		);

		wp_enqueue_script(
			'fooz-theme-js',
			trailingslashit( $this->theme_uri ) . $js_rel,
			array(),
			$js_ver,
			true
		);

		wp_localize_script(
			'fooz-theme-js',
			'foozThemeVars',
			array(
				'homeUrl' => $this->home_url,
				'postId'  => get_the_ID() ?: 0,
			)
		);
	}
}
