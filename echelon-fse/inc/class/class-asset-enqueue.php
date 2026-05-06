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
		wp_enqueue_style( 'echelon-fse-style', get_stylesheet_uri(), array(), ECHELON_FSE_VERSION );

				wp_enqueue_style( 'presset', ECHELON_FSE_URI . '/assets/css/presset.css', array(), ECHELON_FSE_VERSION );
		wp_enqueue_style( 'custom-styling', ECHELON_FSE_URI . '/assets/css/custom-styling.css', array(), ECHELON_FSE_VERSION );
		wp_enqueue_script( 'animation-script', ECHELON_FSE_URI . '/assets/js/animation-script.js', array(), ECHELON_FSE_VERSION, true );


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
