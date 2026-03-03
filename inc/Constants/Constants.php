<?php

namespace FoozTheme\Constants;

class Constants {
	public static function theme_dir() {
		return get_stylesheet_directory();
	}

	public static function theme_uri() {
		return get_stylesheet_directory_uri();
	}

	public static function home_url() {
		return home_url();
	}
}
