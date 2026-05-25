<?php
/**
 * Theme Functions
 *
 * @author Jegstudio
 * @package echelon-fse
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

defined( 'ECHELON_FSE_VERSION' ) || define( 'ECHELON_FSE_VERSION', '1.2.0' );
defined( 'ECHELON_FSE_DIR' ) || define( 'ECHELON_FSE_DIR', trailingslashit( get_template_directory() ) );

defined( 'GUTENVERSE_COMPANION_REQUIRED_VERSION' ) || define( 'GUTENVERSE_COMPANION_REQUIRED_VERSION', '2.4.0' );
defined( 'GUTENVERSE_LIBRARY_SERVER' ) || define( 'GUTENVERSE_LIBRARY_SERVER', 'https://gutenverse.com' );

require get_parent_theme_file_path( 'inc/autoload.php' );

Echelon_Fse\Init::instance();
