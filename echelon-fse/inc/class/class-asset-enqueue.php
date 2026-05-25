<?php
/**
 * Block Pattern Class
 *
 * @author Jegstudio
 * @package echelon-fse
 */
namespace Echelon_Fse;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init Class
 *
 * @package echelon-fse
 */
class Asset_Enqueue {
	/**
	 * Class constructor.
	 */
	public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
	}

    /**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_register_style(
			'echelon-fse-style',
			get_stylesheet_uri(),
			array(),
			ECHELON_FSE_VERSION
		);

		wp_style_add_data( 'echelon-fse-style', 'path', ECHELON_FSE_DIR );
		
		wp_enqueue_style( 'echelon-fse-style' );

				wp_register_style( 'echelon-fse-presset', trailingslashit( get_template_directory_uri() ) . 'assets/css/echelon-fse-presset.css', array(), ECHELON_FSE_VERSION );
		if ( file_exists( trailingslashit( get_template_directory() ) . 'assets/css/echelon-fse-presset.css' ) && filesize( trailingslashit( get_template_directory() ) . 'assets/css/echelon-fse-presset.css' ) < 51200 ) {
			wp_style_add_data( 'echelon-fse-presset', 'path', trailingslashit( get_template_directory() ) . 'assets/css/echelon-fse-presset.css' );
		}
		wp_enqueue_style( 'echelon-fse-presset' );
		wp_register_style( 'echelon-fse-custom-styling', trailingslashit( get_template_directory_uri() ) . 'assets/css/echelon-fse-custom-styling.css', array(), ECHELON_FSE_VERSION );
		if ( file_exists( trailingslashit( get_template_directory() ) . 'assets/css/echelon-fse-custom-styling.css' ) && filesize( trailingslashit( get_template_directory() ) . 'assets/css/echelon-fse-custom-styling.css' ) < 51200 ) {
			wp_style_add_data( 'echelon-fse-custom-styling', 'path', trailingslashit( get_template_directory() ) . 'assets/css/echelon-fse-custom-styling.css' );
		}
		wp_enqueue_style( 'echelon-fse-custom-styling' );
		wp_register_script( 'echelon-fse-animation-script', trailingslashit( get_template_directory_uri() ) . 'assets/js/echelon-fse-animation-script.js', array(), ECHELON_FSE_VERSION, true );
		wp_enqueue_script( 'echelon-fse-animation-script' );


        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
    }

	/**
	 * Enqueue admin scripts and styles.
	 */
	public function admin_scripts() {
		
    }
}
