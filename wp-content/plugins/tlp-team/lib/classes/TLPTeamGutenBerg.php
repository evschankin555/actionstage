<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'TLPTeamGutenBerg' ) ):

	class TLPTeamGutenBerg {
		protected $version;

		function __construct() {
			$this->version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : TLP_TEAM_VERSION;
			add_action( 'enqueue_block_assets', array( $this, 'block_assets' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ) );
			if ( function_exists( 'register_block_type' ) ) {
				register_block_type( 'radiustheme/tlp-team', array(
					'render_callback' => array( $this, 'render_shortcode' ),
				) );
				register_block_type('rttpg/tlp-team-pro', array(
					'render_callback' => array($this,'render_shortcode_pro'),
				));
			}
		}

		static function render_shortcode_pro( $atts ){
			if(!empty($atts['gridId']) && $id = absint($atts['gridId'])){
				return do_shortcode( '[tlpteam id="' . $id . '"]' );
			}
		}

		static function render_shortcode( $atts ) {
			$shortcode = '[tlpteam';
			if ( isset( $atts['layout'] ) && ! empty( $atts['layout'] ) ) {
				$shortcode .= ' layout="' . $atts['layout'] . '"';
			}
			if ( isset( $atts['col'] ) && ! empty( $atts['col'] ) ) {
				$shortcode .= ' col="' . $atts['col'] . '"';
			}
			if ( isset( $atts['orderby'] ) && ! empty( $atts['orderby'] ) ) {
				$shortcode .= ' orderby="' . $atts['orderby'] . '"';
			}
			if ( isset( $atts['order'] ) && ! empty( $atts['order'] ) ) {
				$shortcode .= ' order="' . $atts['order'] . '"';
			}
			if ( isset( $atts['member'] ) && ! empty( $atts['member'] ) ) {
				$shortcode .= ' member="' . $atts['member'] . '"';
			}
			if ( isset( $atts['id'] ) && ! empty( $atts['id'] ) ) {
				$shortcode .= ' id="' . $atts['id'] . '"';
			}
			if ( isset( $atts['nameColor'] ) && ! empty( $atts['nameColor'] ) ) {
				$shortcode .= ' name-color="' . $atts['nameColor'] . '"';
			}
			if ( isset( $atts['designationColor'] ) && ! empty( $atts['designationColor'] ) ) {
				$shortcode .= ' designation-color="' . $atts['designationColor'] . '"';
			}
			if ( isset( $atts['sdColor'] ) && ! empty( $atts['sdColor'] ) ) {
				$shortcode .= ' sd-color="' . $atts['sdColor'] . '"';
			}
			if ( isset( $atts['wrapperClass'] ) && ! empty( $atts['wrapperClass'] ) ) {
				$shortcode .= ' class="' . $atts['wrapperClass'] . '"';
			}
			$shortcode .= ']';
			return do_shortcode( $shortcode );
		}


		function block_assets() {
			wp_enqueue_style( 'wp-blocks' );
		}

		function block_editor_assets() {
			// Scripts.
			wp_enqueue_script(
				'rt-tlp-team-gb-block-js',
				TLPTeam()->assetsUrl . "js/tlp-team-blocks.min.js",
				array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
				$this->version,
				true
			);
			wp_localize_script( 'rt-tlp-team-gb-block-js', 'tlpTeam', array(
				'layout'      => TLPTeam()->oldScLayouts(),
				'column'      => TLPTeam()->scColumns(),
				'orderby'     => TLPTeam()->scOrderBy(),
				'order'       => TLPTeam()->scOrder(),
				'short_codes' => TLPTeam()->getTTPShortCodeList(),
				'icon'        => TLPTeam()->assetsUrl . 'images/team.png',
			) );
			wp_enqueue_style( 'wp-edit-blocks' );
		}
	}

endif;