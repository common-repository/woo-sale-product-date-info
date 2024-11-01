<?php
/*
Plugin Name: WooCommerce sale product date info
Description: Display a customizable sale date tag for WooCommerce products.
Version: 1.0.10
Author: Motpr355
Text Domain: woo-sale-product-date-info
Domain Path: /languages/
License: GPLv2 or later
WC requires at least: 3.4.3
WC tested up to: 5.4.1
*/

class Motpr355_Woo_Sale_Product_Date_Info {

	private $configuration_saved;
	private $settings;
	private $settings_enum;
	private $activation_enum;
	private $font_weight_enum;
	private $text_transform_enum;
	private $display_enum;

	public function __construct() {

		$this->init_data();

		register_activation_hook( __FILE__, array( 'Motpr355_Woo_Sale_Product_Date_Info', 'install' ) );

		add_action( 'activated_plugin', array( $this, 'installed' ) );

		register_uninstall_hook( __FILE__, array( 'Motpr355_Woo_Sale_Product_Date_Info', 'uninstall' ) );

		add_filter( 'auto_update_plugin', array( $this, 'update_plugin' ), 20, 2 );

		add_action( 'admin_menu', array( $this, 'get_admin_menu' ) );

		add_action( 'plugins_loaded', array( $this, 'get_languages' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'get_admin_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'get_admin_styles' ) );

		if ( function_exists( 'plugin_basename' ) ) {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_add_settings_link' ) );
		}

		$this->init_website_content();

	}

	public function plugin_add_settings_link( $links ) {
		$links[] = '<a href="edit.php?post_type=product&page=motpr355_woo_sale_product_date_info" id="motpr355_woo_sale_product_date_info">' . __( 'Settings' ) . '</a>';
		return $links;
	}

	private function init_data() {
		$this->settings_enum = ( object ) array(
			'nonce' => 'nonce',
			'activation' => 'activation',
			'background_color' => 'background_color',
			'color' => 'color',
			'font_weight' => 'font_weight',
			'text_transform' => 'text_transform',
			'display' => 'display'
		);
		$this->activation_enum = ( object ) array(
			'actived' => 'actived',
			'deactived' => 'deactived'
		);
		$this->font_weight_enum = ( object ) array(
			'normal' => 'normal',
			'bold' => 'bold'
		);
		$this->text_transform_enum = ( object ) array(
			'none' => 'none',
			'lowercase' => 'lowercase',
			'uppercase' => 'uppercase'
		);
		$this->display_enum = ( object ) array(
			'start_date' => 'start_date',
			'end_date' => 'end_date',
			'start_end_date' => 'start_end_date'
		);

		$this->configuration_saved = false;
		$this->settings = $this->get_settings();
	}

	private function init_website_content() {
		if ( isset ( $this->settings->{$this->settings_enum->activation} ) ) {
			if ( $this->settings->{$this->settings_enum->activation} === $this->activation_enum->actived ) {
				add_action( 'wp_head', array( $this, 'display_website_style' ) );
				add_action( 'woocommerce_shop_loop_item_title', array( $this, 'display_website_info' ), 11 );
				add_action( 'woocommerce_single_product_summary', array( $this, 'display_website_info' ), 6 );
			}
		}
	}

	public function display_website_info() {
		echo $this->get_display_info_content();
	}

	public function display_website_style() {
		if ( isset ( $this->settings->{$this->settings_enum->background_color},
			$this->settings->{$this->settings_enum->color},
			$this->settings->{$this->settings_enum->font_weight},
			$this->settings->{$this->settings_enum->text_transform},
			$this->settings->{$this->settings_enum->display} ) ) {
			include plugin_dir_path( __FILE__ ) . 'css/website.php';
		}
	}

	private function get_display_info_content() {
		global $product;
		if ( method_exists( $product, 'is_on_sale' ) ) {
			if ( $product->is_on_sale() ) {
				if ( function_exists( 'get_post_meta' ) && function_exists( 'get_the_ID' ) && function_exists( 'date_i18n' ) ) {
					$date_format = get_option( 'date_format' );
					if ( $date_format !== false ) {
						if ( method_exists( $product, 'get_date_on_sale_from' ) && method_exists( $product, 'get_date_on_sale_to' ) ) {
							$begin_date_display = false;
							$begin_date = $product->get_date_on_sale_from( 'edit' );
							if ( $begin_date && method_exists( $begin_date, 'getOffsetTimestamp' ) ) {
								$begin_date_display = date_i18n( $date_format, $begin_date->getOffsetTimestamp() );
							}
							$end_date_display = false;
							$end_date = $product->get_date_on_sale_to( 'edit' );
							if ( $end_date && method_exists( $end_date, 'getOffsetTimestamp' ) ) {
								$end_date_display = date_i18n( $date_format, $end_date->getOffsetTimestamp() );
							}
							if ( $begin_date_display || $end_date_display ) {
								if ( isset ( $this->settings->{$this->settings_enum->background_color},
									$this->settings->{$this->settings_enum->color},
									$this->settings->{$this->settings_enum->font_weight},
									$this->settings->{$this->settings_enum->text_transform},
									$this->settings->{$this->settings_enum->display}) ) {
									$display_option = $this->settings->{$this->settings_enum->display};
									$text = false;
									if ( $begin_date_display && $end_date_display ) {
										if ( $display_option === $this->display_enum->start_date ) {
											$text = sprintf( __( 'On sale from %s', 'woo-sale-product-date-info' ), $begin_date_display );
										} else if ( $display_option === $this->display_enum->end_date ) {
											$text = sprintf( __( 'On sale until %s', 'woo-sale-product-date-info' ), $end_date_display );
										} else if ( $display_option === $this->display_enum->start_end_date ) {
											$text = sprintf( __( 'On sale from %s to %s', 'woo-sale-product-date-info' ), $begin_date_display, $end_date_display );
										}
									} else if ( $begin_date_display && ! $end_date_display ) {
										if ( $display_option === $this->display_enum->start_date || $display_option === $this->display_enum->start_end_date ) {
											$text = sprintf( __( 'On sale from %s', 'woo-sale-product-date-info' ), $begin_date_display );
										}
									} else if ( ! $begin_date_display && $end_date_display ) {
										if ( $display_option === $this->display_enum->end_date || $display_option === $this->display_enum->start_end_date ) {
											$text = sprintf( __( 'On sale until %s', 'woo-sale-product-date-info' ), $end_date_display );
										}
									}
									if ( $text !== false ) {
										if ( isset ( $this->display_info_content ) ) {
											return str_replace( 'CONTENT', $text, $this->display_info_content );
										}
										if ( function_exists( 'ob_start' ) && function_exists( 'ob_get_clean' ) ) {
											ob_start();
											include plugin_dir_path( __FILE__ ) . 'html/website.php';
											$this->display_info_content = ob_get_clean();
											return str_replace( 'CONTENT', $text, $this->display_info_content );
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return '';
	}

	public static function install() {
		$nonce = 'motpr355_woo_sale_product_date_info';
		$chars = array_merge(
			range( 'A','Z' ),
			range( 'a','z' ),
			range( '0','9' )
		);
		$max = count( $chars ) - 1;
		for ( $i = 0; $i < 25; $i++ ) {
			$rand = mt_rand( 0, $max );
			$nonce .= $chars[ $rand ];
		}
		$settings = array(
			'nonce' => $nonce,
			'activation' => 'deactived',
			'background_color' => '#000000',
			'color' => '#ffffff',
			'font_weight' => 'bold',
			'text_transform' => 'none',
			'display' => 'end_date'
		);
		update_option( 'motpr355_woo_sale_product_date_info_settings', $settings );
	}

	public function installed( $plugin ) {
		if( $plugin === plugin_basename( __FILE__ ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			exit( wp_redirect( admin_url( 'edit.php?post_type=product&page=motpr355_woo_sale_product_date_info' ) ) );
		}
	}

	public static function uninstall() {
		delete_option( 'motpr355_woo_sale_product_date_info_settings' );
	}

	public function update_plugin( $update, $item ) {
		$plugins = array(
			'woo-sale-product-date-info/woo-sale-product-date-info.php',
			'woo-sale-product-date-info',
			'woo-sale-product-date-info.php'
		);
		if ( isset( $item->slug ) && in_array( $item->slug, $plugins ) ) {
			return true;
		} else {
			return $update;
		}
	}

	public function get_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=product',
			__( 'Sale product date info', 'woo-sale-product-date-info' ),
			__( 'Sale product date info', 'woo-sale-product-date-info' ),
			'administrator',
			'motpr355_woo_sale_product_date_info',
			array( $this, 'plugin_content' )
		);
	}

	public function get_languages() {
		load_plugin_textdomain(
			'woo-sale-product-date-info',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	public function get_admin_scripts() {
		$is_on_admin_plugin_page = $this->is_on_admin_plugin_page();
		$is_on_admin_extensions_page = $this->is_on_admin_extensions_page();
		if ( $is_on_admin_plugin_page || $is_on_admin_extensions_page ) {
			if ( $is_on_admin_plugin_page ) {
				$this->save_configuration();
				wp_enqueue_style( 'wp-color-picker' );
				wp_register_script(
					'motpr355_woo_sale_product_date_info_script',
					plugins_url( 'js/admin.js', __FILE__ ),
					array( 'wp-color-picker' ),
					'1.0.10',
					true
				);
				wp_localize_script(
					'motpr355_woo_sale_product_date_info_script',
					'motpr355_woo_sale_product_date_info_settings',
					( array ) $this->settings
				);
			} else {
				wp_register_script(
					'motpr355_woo_sale_product_date_info_script',
					plugins_url( 'js/admin-extensions.js', __FILE__ ),
					array(),
					'1.0.10',
					true
				);
				wp_localize_script(
					'motpr355_woo_sale_product_date_info_script',
					'motpr355_woo_sale_product_date_info_translations',
					array(
						'woo_inactive' => __(
							'WooCommerce must be actived',
							'woo-sale-product-date-info'
						),
						'woo_not_found' => __(
							'WooCommerce must be installed',
							'woo-sale-product-date-info'
						)
					)
				);
			}
			wp_enqueue_script( 'motpr355_woo_sale_product_date_info_script' );
		}
	}

	public function get_admin_styles() {
		if ( $this->is_on_admin_plugin_page() ) {
			wp_register_style(
				'motpr355_woo_sale_product_date_info_style',
				plugins_url( 'css/admin.css', __FILE__ ),
				array(),
				'1.0.10'
			);
			wp_enqueue_style( 'motpr355_woo_sale_product_date_info_style' );
		}
	}

	private function is_on_admin_plugin_page() {
		return $this->is_on_admin_page( 'product_page_motpr355_woo_sale_product_date_info' );
	}

	private function is_on_admin_extensions_page() {
		return $this->is_on_admin_page( 'plugins' );
	}

	private function is_on_admin_page( $page ) {
		if ( function_exists( 'get_current_screen' ) ) {
			$current_screen = get_current_screen();
			if ( isset( $current_screen->base ) ) {
				return is_admin() && $current_screen->base === $page;
			}
		}
		return false;
	}

	private function save_configuration() {
		if ( isset ( $_POST[ 'nonce' ], $_POST[ 'activation' ], $_POST[ 'background_color' ],
			$_POST[ 'color' ], $_POST[ 'font_weight' ], $_POST[ 'text_transform' ], $_POST[ 'display' ] ) ) {
			$nonce = $this->get_cleaned_posted_data( $_POST[ 'nonce' ] );
			$saved_nonce = $this->settings->{$this->settings_enum->nonce};
			if ( wp_verify_nonce( $nonce, $saved_nonce ) ) {
				$activation = $this->get_cleaned_activation( $_POST[ 'activation' ] );
				$background_color = $this->get_cleaned_background_color( $_POST[ 'background_color' ] );
				$color = $this->get_cleaned_color( $_POST[ 'color' ] );
				$font_weight = $this->get_cleaned_font_weight( $_POST[ 'font_weight' ] );
				$text_transform = $this->get_cleaned_text_transform( $_POST[ 'text_transform' ] );
				$display = $this->get_cleaned_display( $_POST[ 'display' ] );
				if ( $activation !== false && $background_color !== false && $color !== false
					&& $font_weight !== false && $text_transform !== false && $display !== false ) {
					$settings = array(
						'nonce' => $saved_nonce,
						'activation' => $activation,
						'background_color' => $background_color,
						'color' => $color,
						'font_weight' => $font_weight,
						'text_transform' => $text_transform,
						'display' => $display
					);
					update_option( 'motpr355_woo_sale_product_date_info_settings', $settings );
					$this->configuration_saved = true;
					$this->settings = $this->get_settings();
				}
			}
		}
	}

	private function get_cleaned_activation( $activation ) {
		$activation = $this->get_cleaned_posted_data( $activation );
		return in_array( $activation, ( array ) $this->activation_enum ) ? $activation : false;
	}

	private function get_cleaned_background_color( $background_color ) {
		$background_color = $this->get_cleaned_posted_color_data( $background_color );
		return $this->check_color_format( $background_color ) ? $background_color : false;
	}

	private function get_cleaned_color( $color ) {
		$color = $this->get_cleaned_posted_color_data( $color );
		return $this->check_color_format( $color ) ? $color : false;
	}

	private function get_cleaned_font_weight( $font_weight ) {
		$font_weight = $this->get_cleaned_posted_data( $font_weight );
		return in_array( $font_weight, ( array ) $this->font_weight_enum ) ? $font_weight : false;
	}

	private function get_cleaned_text_transform( $text_transform ) {
		$text_transform = $this->get_cleaned_posted_data( $text_transform );
		return in_array( $text_transform, ( array ) $this->text_transform_enum ) ? $text_transform : false;
	}

	private function get_cleaned_display( $display ) {
		$display = $this->get_cleaned_posted_data( $display );
		return in_array( $display, ( array ) $this->display_enum ) ? $display : false;
	}

	private function get_cleaned_posted_data( $data ) {
		return stripslashes_deep( sanitize_text_field( $data ) );
	}

	private function get_cleaned_posted_color_data( $data ) {
		return stripslashes_deep( sanitize_hex_color( $data ) );
	}

	private function check_color_format( $color ) {
		if ( ( strlen ( $color ) !== 4 && strlen ( $color ) !== 7 )
			|| substr( $color, 0, 1 ) !== '#' ) {
			return false;
		}
		return ctype_xdigit( substr( $color, 1 ) );
	}

	private function get_settings() {
		$settings = ( object ) get_option( 'motpr355_woo_sale_product_date_info_settings', array() );
		if ( ! isset ( $settings->display ) ) {
			$settings->display = $this->display_enum->end_date;
		}
		return $settings;
	}

	public function plugin_content() {
		include_once plugin_dir_path( __FILE__ ) . 'html/admin.php';
	}

}

if ( defined( 'ABSPATH' ) ) {
	new Motpr355_Woo_Sale_Product_Date_Info();
}
