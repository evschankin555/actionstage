<?php

if ( ! class_exists( 'TLPTeamTemplate' ) ):

	/**
	 *
	 */
	class TLPTeamTemplate {

		function __construct() {
			add_filter( 'template_include', array( $this, 'template_loader' ) );
		}

		public static function template_loader( $template ) {
			$find = array();
			$file = null;
			if ( is_single() && get_post_type() == TLPTeam()->post_type ) {

				$file   = 'single-team.php';
				$find[] = $file;
				$find[] = TLPTeam()->templatesPath . $file;

			}

			if ( $file ) {

				$template = locate_template( array_unique( $find ) );
				if ( ! $template ) {
					$template = TLPTeam()->templatesPath . $file;
				}
			}

			return $template;
		}

	}

endif;
