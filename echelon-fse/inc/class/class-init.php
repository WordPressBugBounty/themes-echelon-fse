<?php
/**
 * Init Configuration
 *
 * @author Jegstudio
 * @package echelon-fse
 */

namespace Echelon_Fse;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Init Class
 *
 * @package echelon-fse
 */
class Init {

	/**
	 * Instance variable
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Class instance.
	 *
	 * @return Init
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Class constructor.
	 */
	private function __construct() {
		$this->init_instance();
		$this->load_hooks();
	}

	/**
	 * Load initial hooks.
	 */
	private function load_hooks() {
		add_action( 'init', array( $this, 'register_block_patterns' ), 9 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'dashboard_scripts' ) );

		add_action( 'wp_ajax_echelon-fse_set_admin_notice_viewed', array( $this, 'notice_closed' ) );

		add_action( 'after_switch_theme', array( $this, 'update_global_styles_after_theme_switch' ) );
		add_filter( 'gutenverse_template_path', array( $this, 'template_path' ), null, 3 );
		add_filter( 'gutenverse_themes_template', array( $this, 'add_template' ), 10, 2 );
		add_filter( 'gutenverse_block_config', array( $this, 'default_font' ), 10 );
		add_filter( 'gutenverse_font_header', array( $this, 'default_header_font' ) );
		add_filter( 'gutenverse_global_css', array( $this, 'global_header_style' ) );

		add_filter( 'gutenverse_stylesheet_directory', array( $this, 'change_stylesheet_directory' ) );
		add_filter( 'gutenverse_themes_override_mechanism', '__return_true' );

		
	}

	/**
	 * Update Global Styles After Theme Switch
	 */
	public function update_global_styles_after_theme_switch() {
		// Get the path to the current theme's theme.json file
		$theme_json_path = get_template_directory() . '/theme.json';
		$theme_slug      = get_option( 'stylesheet' ); // Get the current theme's slug
		$args            = array(
			'post_type'      => 'wp_global_styles',
			'post_status'    => 'publish',
			'name'           => 'wp-global-styles-' . $theme_slug,
			'posts_per_page' => 1,
		);

		$global_styles_query = new WP_Query( $args );
		// Check if the theme.json file exists
		if ( file_exists( $theme_json_path ) && $global_styles_query->have_posts() ) {
			$global_styles_query->the_post();
			$global_styles_post_id = get_the_ID();
			// Step 2: Get the existing global styles (color palette)
			$global_styles_content = json_decode( get_post_field( 'post_content', $global_styles_post_id ), true );
			if ( isset( $global_styles_content['settings']['color']['palette']['theme'] ) ) {
				$existing_colors = $global_styles_content['settings']['color']['palette']['theme'];
			} else {
				$existing_colors = array();
			}

			// Step 3: Extract slugs from the existing colors
			$existing_slugs = array_column( $existing_colors, 'slug' );
			// Step 4:Read the contents of the theme.json file

			$theme_json_content = file_get_contents( $theme_json_path );
			$theme_json_data    = json_decode( $theme_json_content, true );

			// Access the color palette from the theme.json file
			if ( isset( $theme_json_data['settings']['color']['palette'] ) ) {

				$theme_colors = $theme_json_data['settings']['color']['palette'];

				// Step 5: Loop through theme.json colors and add them if they don't exist
				foreach ( $theme_colors as $theme_color ) {
					if ( ! in_array( $theme_color['slug'], $existing_slugs ) ) {
						$existing_colors[] = $theme_color; // Add new color to the existing palette
					}
				}
				foreach ( $theme_colors as $theme_color ) {
					$theme_slug = $theme_color['slug'];

					// Step 6: Use in_array to check if the slug already exists in the global palette
					if ( ! in_array( $theme_slug, $existing_slugs ) ) {
						// If the slug does not exist, add the theme color to the global palette
						$global_colors[] = $theme_color;
					}
				}
				// Step 6: Update the global styles content with the new colors
				$global_styles_content['settings']['color']['palette']['theme'] = $existing_colors;

				// Step 7: Save the updated global styles back to the post
				wp_update_post(
					array(
						'ID'           => $global_styles_post_id,
						'post_content' => wp_json_encode( $global_styles_content ),
					)
				);

			}
			wp_reset_postdata(); // Reset the query
		}
	}

	/**
	 * Change Stylesheet Directory.
	 *
	 * @return string
	 */
	public function change_stylesheet_directory() {
		return ECHELON_FSE_DIR . 'gutenverse-files';
	}

	/**
	 * Initialize Instance.
	 */
	public function init_instance() {
		new Asset_Enqueue();
		new Plugin_Notice();
	}

	/**
	 * Notice Closed
	 */
	public function notice_closed() {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'echelon-fse_admin_notice' ) ) {
			update_user_meta( get_current_user_id(), 'gutenverse_install_notice', 'true' );
		}
		die;
	}

	/**
	 * Generate Global Font
	 *
	 * @param string $value  Value of the option.
	 *
	 * @return string
	 */
	public function global_header_style( $value ) {
		$theme_name      = get_stylesheet();
		$global_variable = get_option( 'gutenverse-global-variable-font-' . $theme_name );

		if ( empty( $global_variable ) && function_exists( 'gutenverse_global_font_style_generator' ) ) {
			$font_variable = $this->default_font_variable();
			$value        .= \gutenverse_global_font_style_generator( $font_variable );
		}

		return $value;
	}

	/**
	 * Header Font.
	 *
	 * @param mixed $value  Value of the option.
	 *
	 * @return mixed Value of the option.
	 */
	public function default_header_font( $value ) {
		if ( ! $value ) {
			$value = array(
				array(
					'value'  => 'Alfa Slab One',
					'type'   => 'google',
					'weight' => 'bold',
				),
			);
		}

		return $value;
	}

	/**
	 * Alter Default Font.
	 *
	 * @param array $config Array of Config.
	 *
	 * @return array
	 */
	public function default_font( $config ) {
		if ( empty( $config['globalVariable']['fonts'] ) ) {
			$config['globalVariable']['fonts'] = $this->default_font_variable();

			return $config;
		}

		if ( ! empty( $config['globalVariable']['fonts'] ) ) {
			// Handle existing fonts.
			$theme_name   = get_stylesheet();
			$initial_font = get_option( 'gutenverse-font-init-' . $theme_name );

			if ( ! $initial_font ) {
				$result = array();
				$array1 = $config['globalVariable']['fonts'];
				$array2 = $this->default_font_variable();
				foreach ( $array1 as $item ) {
					$result[ $item['id'] ] = $item;
				}
				foreach ( $array2 as $item ) {
					$result[ $item['id'] ] = $item;
				}
				$fonts = array();
				foreach ( $result as $key => $font ) {
					$fonts[] = $font;
				}
				$config['globalVariable']['fonts'] = $fonts;

				update_option( 'gutenverse-font-init-' . $theme_name, true );
			}
		}

		return $config;
	}

	/**
	 * Default Font Variable.
	 *
	 * @return array
	 */
	public function default_font_variable() {
		return array(
            array (
  'id' => 'h1-font',
  'name' => 'H1 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '68',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '55',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '48',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.1',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'h2-font',
  'name' => 'H2 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '46',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '42',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '32',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'h3-font',
  'name' => 'H3 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '26',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '24',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '24',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'h4-font',
  'name' => 'H4 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '25',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '25',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '24',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'h5-font',
  'name' => 'H5 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '24',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '22',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '21',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'h5-alt-font',
  'name' => 'H5 Alt Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '24',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '20',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '21',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'h6-font',
  'name' => 'H6 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '24',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '18',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '20',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'h7-font',
  'name' => 'H7 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '22',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '22',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '21',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'h8-font',
  'name' => 'H8 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '20',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '17',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '17',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'button-hero-font',
  'name' => 'Button Hero Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '18',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '16',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '16',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1',
      ),
    ),
    'weight' => '500',
  ),
),array (
  'id' => 'button-font',
  'name' => 'Button Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '15',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1',
      ),
    ),
    'weight' => '500',
  ),
),array (
  'id' => 'subtitle-font',
  'name' => 'Subtitle Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '15',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '400',
    'spacing' => 
    array (
      'Desktop' => '0.3',
    ),
    'transform' => 'uppercase',
  ),
),array (
  'id' => 'text-font',
  'name' => 'Text Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Open Sans',
      'value' => 'Open Sans',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '15',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '14',
      ),
    ),
    'weight' => '400',
  ),
),array (
  'id' => 'text-hero-font',
  'name' => 'Text Hero Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Open Sans',
      'value' => 'Open Sans',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '18',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '16',
      ),
    ),
    'weight' => '400',
  ),
),array (
  'id' => 'text-14-font',
  'name' => 'Text 14px Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Open Sans',
      'value' => 'Open Sans',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '14',
      ),
    ),
    'weight' => '400',
  ),
),array (
  'id' => 'text-13-font',
  'name' => 'Text 13px Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Open Sans',
      'value' => 'Open Sans',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '13',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '12',
      ),
    ),
    'weight' => '400',
  ),
),array (
  'id' => '404-font',
  'name' => '404 Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '150',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '140',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '120',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.1',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'price-font',
  'name' => 'Price Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '50',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '48',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'comment-font',
  'name' => 'Post Comment Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '36',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '34',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'fun-fact-number-font',
  'name' => 'Fun Fact Number Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '42',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '40',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '36',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'fun-fact-super-font',
  'name' => 'Fun Fact Super Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '30',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '28',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'fun-fact-title-font',
  'name' => 'Fun Fact Title Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '13',
      ),
      'Tablet' => 
      array (
        'unit' => 'px',
        'point' => '12',
      ),
      'Mobile' => 
      array (
        'unit' => 'px',
        'point' => '11',
      ),
    ),
    'spacing' => 
    array (
      'Desktop' => '0.3',
    ),
    'transform' => 'uppercase',
    'weight' => '400',
  ),
),array (
  'id' => 'title-18-font',
  'name' => 'Title 18px Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '18',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.5',
      ),
    ),
    'weight' => '700',
  ),
),array (
  'id' => 'title-16-font',
  'name' => 'Title 16px Font',
  'font' => 
  array (
    'font' => 
    array (
      'label' => 'Lato',
      'value' => 'Lato',
      'type' => 'google',
    ),
    'size' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'px',
        'point' => '16',
      ),
    ),
    'lineHeight' => 
    array (
      'Desktop' => 
      array (
        'unit' => 'em',
        'point' => '1.2',
      ),
    ),
    'weight' => '700',
  ),
),
		);
	}



	/**
	 * Add Template to Editor.
	 *
	 * @param array $template_files Path to Template File.
	 * @param array $template_type Template Type.
	 *
	 * @return array
	 */
	public function add_template( $template_files, $template_type ) {
		if ( 'wp_template' === $template_type ) {
			$new_templates = array(
				'404',
				'about',
				'archive',
				'blank-canvas',
				'blog',
				'contact',
				'faq',
				'front-page',
				'index',
				'page',
				'search',
				'services',
				'single',
				'template-basic'
			);

			foreach ( $new_templates as $template ) {
				$template_files[] = array(
					'slug'  => $template,
					'path'  => $this->change_stylesheet_directory() . "/templates/{$template}.html",
					'theme' => get_template(),
					'type'  => 'wp_template',
				);
			}
		}

		return $template_files;
	}

	/**
	 * Use gutenverse template file instead.
	 *
	 * @param string $template_file Path to Template File.
	 * @param string $theme_slug Theme Slug.
	 * @param string $template_slug Template Slug.
	 *
	 * @return string
	 */
	public function template_path( $template_file, $theme_slug, $template_slug ) {
		switch ( $template_slug ) {
            case 'footer':
					return $this->change_stylesheet_directory() . '/parts/footer.html';
			case 'header':
					return $this->change_stylesheet_directory() . '/parts/header.html';
			case '404':
					return $this->change_stylesheet_directory() . '/templates/404.html';
			case 'about':
					return $this->change_stylesheet_directory() . '/templates/about.html';
			case 'archive':
					return $this->change_stylesheet_directory() . '/templates/archive.html';
			case 'blank-canvas':
					return $this->change_stylesheet_directory() . '/templates/blank-canvas.html';
			case 'blog':
					return $this->change_stylesheet_directory() . '/templates/blog.html';
			case 'contact':
					return $this->change_stylesheet_directory() . '/templates/contact.html';
			case 'faq':
					return $this->change_stylesheet_directory() . '/templates/faq.html';
			case 'front-page':
					return $this->change_stylesheet_directory() . '/templates/front-page.html';
			case 'index':
					return $this->change_stylesheet_directory() . '/templates/index.html';
			case 'page':
					return $this->change_stylesheet_directory() . '/templates/page.html';
			case 'search':
					return $this->change_stylesheet_directory() . '/templates/search.html';
			case 'services':
					return $this->change_stylesheet_directory() . '/templates/services.html';
			case 'single':
					return $this->change_stylesheet_directory() . '/templates/single.html';
			case 'template-basic':
					return $this->change_stylesheet_directory() . '/templates/template-basic.html';
		}

		return $template_file;
	}

	/**
	 * Register Block Pattern.
	 */
	public function register_block_patterns() {
		new Block_Patterns();
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function dashboard_scripts() {
		$screen = get_current_screen();
		wp_enqueue_script('wp-api-fetch');

		if ( is_admin() ) {
			// enqueue css.
			wp_enqueue_style(
				'echelon-fse-dashboard',
				ECHELON_FSE_URI . 'assets/css/theme-dashboard.css',
				array(),
				ECHELON_FSE_VERSION
			);

			// enqueue js.
			wp_enqueue_script(
				'echelon-fse-dashboard',
				ECHELON_FSE_URI . 'assets/js/theme-dashboard.js',
				array( 'wp-api-fetch' ),
				ECHELON_FSE_VERSION,
				true
			);

			wp_localize_script( 'echelon-fse-dashboard', 'GutenThemeConfig', $this->theme_config() );
		}
	}

	/**
	 * Check if plugin is installed.
	 *
	 * @param string $plugin_slug plugin slug.
	 * 
	 * @return boolean
	 */
	public function is_installed( $plugin_slug ) {
		$all_plugins = get_plugins();
		foreach ( $all_plugins as $plugin_file => $plugin_data ) {
			$plugin_dir = dirname($plugin_file);

			if ($plugin_dir === $plugin_slug) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Register static data to be used in theme's js file
	 */
	public function theme_config() {
		$active_plugins = get_option( 'active_plugins' );
		$plugins = array();
		foreach( $active_plugins as $active ) {
			$plugins[] = explode( '/', $active)[0];
		}

		$config = array(
			'home_url'     => home_url(),
			'version'      => ECHELON_FSE_VERSION,
			'images'       => ECHELON_FSE_URI . 'assets/img/',
			'title'        => esc_html__( 'Echelon FSE', 'echelon-fse' ),
			'description'  => esc_html__( 'Echelon FSE is a modern and responsive style theme template for WordPress fullsite editing and fully compatible with Gutenverse plugin. Echelon FSE created especially for Corporate, Company Profile, Business Agency, Consulting, and all other Business websites. The templates includes both core version and Gutenverse plugin version, also has core and Gutenverse block patterns ready so you can start mix and match your template parts as you desire. The templates is built ready so you don\'t need to build it from scratch. We want to make your experience using WordPress fullsite editor more convenient.', 'echelon-fse' ),
			'pluginTitle'  => esc_html__( 'Plugin Requirement', 'echelon-fse' ),
			'pluginDesc'   => esc_html__( 'This theme require some plugins. Please make sure all the plugin below are installed and activated.', 'echelon-fse' ),
			'note'         => esc_html__( '', 'echelon-fse' ),
			'note2'        => esc_html__( '', 'echelon-fse' ),
			'demo'         => esc_html__( '', 'echelon-fse' ),
			'demoUrl'      => esc_url( 'https://gutenverse.com/demo?name=echelon-fse' ),
			'install'      => '',
			'installText'  => esc_html__( 'Install Gutenverse Plugin', 'echelon-fse' ),
			'activateText' => esc_html__( 'Activate Gutenverse Plugin', 'echelon-fse' ),
			'doneText'     => esc_html__( 'Gutenverse Plugin Installed', 'echelon-fse' ),
			'dashboardPage'=> admin_url( 'themes.php?page=echelon-fse-dashboard' ),
			'logo'         => false,
			'slug'         => 'echelon-fse',
			'upgradePro'   => 'https://gutenverse.com/pro',
			'ratingLink'   => 'https://themeforest.net/downloads',
			'supportLink'  => 'https://support.jegtheme.com/forums/forum/fse-themes/',
			'libraryApi'   => 'https://gutenverse.com//wp-json/gutenverse-server/v1',
			'docsLink'     => 'https://support.jegtheme.com/theme/fse-themes/',
			'pages'        => array(
				'page-0' => ECHELON_FSE_URI . 'assets/img/ss-echelon-home.webp',
				'page-1' => ECHELON_FSE_URI . 'assets/img/ss-echelon-about.webp',
				'page-2' => ECHELON_FSE_URI . 'assets/img/ss-echelon-service.webp',
				'page-3' => ECHELON_FSE_URI . 'assets/img/ss-echelon-faq.webp',
				'page-4' => ECHELON_FSE_URI . 'assets/img/ss-echelon-contact.webp'
			),
			'plugins'      => array(
				array(
					'slug'       		=> 'gutenverse',
					'title'      		=> 'Gutenverse',
					'short_desc' 		=> 'GUTENVERSE – GUTENBERG BLOCKS AND WEBSITE BUILDER FOR SITE EDITOR, TEMPLATE LIBRARY, POPUP BUILDER, ADVANCED ANIMATION EFFECTS, 45+ FREE USER-FRIENDLY BLOCKS',
					'active'    		=> in_array( 'gutenverse', $plugins, true ),
					'installed'  		=> $this->is_installed( 'gutenverse' ),
					'icons'      		=> array (
  '1x' => 'https://ps.w.org/gutenverse/assets/icon-128x128.gif?rev=3132408',
  '2x' => 'https://ps.w.org/gutenverse/assets/icon-256x256.gif?rev=3132408',
),
					'download_url'      => '',
				),
				array(
					'slug'       		=> 'gutenverse-form',
					'title'      		=> 'Gutenverse Form',
					'short_desc' 		=> 'GUTENVERSE FORM – FORM BUILDER FOR GUTENBERG BLOCK EDITOR, MULTI-STEP FORMS, CONDITIONAL LOGIC, PAYMENT, CALCULATION, 15+ FREE USER-FRIENDLY FORM BLOCKS',
					'active'    		=> in_array( 'gutenverse-form', $plugins, true ),
					'installed'  		=> $this->is_installed( 'gutenverse-form' ),
					'icons'      		=> array (
  '1x' => 'https://ps.w.org/gutenverse-form/assets/icon-128x128.png?rev=3135966',
),
					'download_url'      => '',
				)
			),
			'assign'       => array(
				
			),
			'dashboardData'=> array(
				
			),
			
		);

		if ( isset( $config['assign'] ) && $config['assign'] ) {
			$assign = $config['assign'];
			foreach ( $assign as $key => $value ) {
				$query = new \WP_Query(
					array(
						'post_type'      => 'page',
						'post_status'    => 'publish',
						'title'          => '' !== $value['page'] ? $value['page'] : $value['title'],
						'posts_per_page' => 1,
					)
				);

				if ( $query->have_posts() ) {
					$post                     = $query->posts[0];
					$page_template            = get_page_template_slug( $post->ID );
					$assign[ $key ]['status'] = array(
						'exists'         => true,
						'using_template' => $page_template === $value['slug'],
					);

				} else {
					$assign[ $key ]['status'] = array(
						'exists'         => false,
						'using_template' => false,
					);
				}

				wp_reset_postdata();
			}
			$config['assign'] = $assign;
		}

		return $config;
	}

	/**
	 * Add Menu
	 */
	public function admin_menu() {
		add_theme_page(
			'Echelon FSE Dashboard',
			'Echelon FSE Dashboard',
			'manage_options',
			'echelon-fse-dashboard',
			array( $this, 'load_dashboard' ),
			1
		);
	}

	/**
	 * Template page
	 */
	public function load_dashboard() {
		?>
			<div id="gutenverse-theme-dashboard">
			</div>
		<?php
	}
}
