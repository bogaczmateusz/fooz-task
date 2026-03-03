<?php

use FoozTheme\Main\Main;

spl_autoload_register(
	function ( $class ) {
		$prefix   = 'FoozTheme\\';
		$base_dir = trailingslashit( get_stylesheet_directory() ) . 'inc/';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$file = $base_dir . str_replace( '\\', '/', $relative ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);

$fooz_theme = new Main();
$fooz_theme->init();
