<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GeoDir_Admin_Install Class.
 */
class DS_theme_downgrade {

	/**
	 * Setup class.
	 *
	 * @since 2.1
	 */
	public function __construct() {
		
		// Downgrade notice
		add_action( 'admin_notices', array( $this, 'notice_downgrade' ) );

		// filter package for downgrade
		add_filter('upgrader_package_options',array( $this, 'maybe_downgrade'));
		
	}

	/**
	 * Used to allow downgrade if a user installs v2 but is not ready to convert yet.
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	public static function maybe_downgrade($options){

		// Directory Starter
		if(
			!empty($_REQUEST['ds_downgrade'])
			&& !empty($options['package'])
			&& strpos( $options['package'], "https://downloads.wordpress.org/theme/directory-starter.2" ) === 0
		){
			$options['package'] = "https://downloads.wordpress.org/theme/directory-starter.2.0.0.10.zip";
			$options['clear_destination'] = 1;
			$options['abort_if_destination_exists'] = 0;
		}

		// Supreme Directory
		if(
			!empty($_REQUEST['ds_downgrade'])
			&& !empty($options['package'])
			&& strpos( $options['package'], "https://downloads.wordpress.org/theme/supreme-directory.2" ) === 0
		){
			$options['package'] = "https://downloads.wordpress.org/theme/supreme-directory.2.0.0.14.zip";
			$options['clear_destination'] = 1;
			$options['abort_if_destination_exists'] = 0;
		}

		// Whoop
		if(
			!empty($_REQUEST['ds_downgrade'])
			&& !empty($options['package'])
			&& strpos( $options['package'], "https://downloads.wordpress.org/theme/whoop.2" ) === 0
		){
			$options['package'] = "https://downloads.wordpress.org/theme/whoop.2.0.4.zip";
			$options['clear_destination'] = 1;
			$options['abort_if_destination_exists'] = 0;
		}

		return $options;
	}


	/**
	 * Show the downgrade admin notice.
	 */
	public function notice_downgrade(){

		// maybe set to remove notice
		if( !empty($_REQUEST['ds_remove_downgrade_notice'])){
			update_option("ds_no_downgrade",true);
		}

		if( empty( $_REQUEST['ds_downgrade'] ) && !get_option("ds_no_downgrade",false) ){


			if(defined('SD_VERSION') && version_compare(SD_VERSION,"2.1",">=")){
				// Supreme Directory
				$this->sd_notice();
			}elseif(defined('WHOOP_VERSION') && version_compare(WHOOP_VERSION,"2.1",">=")){
				// Whoop
				$this->whoop_notice();
			}else{
				// Directory Starter
				$this->ds_notice();
			}

		}

	}

	/**
	 * Whoop notice.
	 */
	public function whoop_notice(){

		$action = 'install-theme';
		$slug = 'whoop';
		$install_url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'theme' => $slug,
					'ds_downgrade' => 1
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);

		$remove_notice_url = admin_url( 'index.php?ds_remove_downgrade_notice=1' );

		$class = 'notice notice-error';
		$title = __("Immediate attention required","directory-starter");
		$message = __( 'Whoop theme 2.1+ has some huge design improvements, if you are not ready for these or do not want them please downgrade to keep things as they were.', 'directory-starter' );

		$buttons = 	'<p>';
		$buttons .= '<a onclick="return confirm(\''.__("This will downgrade Whoop theme to the latest version 2.0","directory-starter").'\');" class="button button-primary" href="'.$install_url.'" target="_parent"><i class="fas fa-undo-alt"></i> '.__("Downgrade to latest v2.0","directory-starter").'</a></strong>';
		$buttons .= ' <a class="button button-secondary" href="'.$remove_notice_url.'" target="_parent">'.__("I'm OK with this, Remove Notice","directory-starter").'</a></strong>';
		$buttons .= '</p>';

		printf( '<div class="%1$s"><h2 style="font-size: 22px;font-weight: bold;color: red;">%2$s</h2><p><b>%3$s</b></p>%4$s</div>', esc_attr( $class ), esc_attr( $title ), esc_html( $message ),$buttons );

	}

	/**
	 * Supreme Directory notice.
	 */
	public function sd_notice(){

		$action = 'install-theme';
		$slug = 'supreme-directory';
		$install_url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'theme' => $slug,
					'ds_downgrade' => 1
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);

		$remove_notice_url = admin_url( 'index.php?ds_remove_downgrade_notice=1' );

		$class = 'notice notice-error';
		$title = __("Immediate attention required","directory-starter");
		$message = __( 'Supreme Directory 2.1+ has some huge design improvements, if you are not ready for these or do not want them please downgrade to keep things as they were.', 'directory-starter' );

		$buttons = 	'<p>';
		$buttons .= '<a onclick="return confirm(\''.__("This will downgrade Supreme Directory theme to the latest version 2.0","directory-starter").'\');" class="button button-primary" href="'.$install_url.'" target="_parent"><i class="fas fa-undo-alt"></i> '.__("Downgrade to latest v2.0","directory-starter").'</a></strong>';
		$buttons .= ' <a class="button button-secondary" href="'.$remove_notice_url.'" target="_parent">'.__("I'm OK with this, Remove Notice","directory-starter").'</a></strong>';
		$buttons .= '</p>';

		printf( '<div class="%1$s"><h2 style="font-size: 22px;font-weight: bold;color: red;">%2$s</h2><p><b>%3$s</b></p>%4$s</div>', esc_attr( $class ), esc_attr( $title ), esc_html( $message ),$buttons );

	}

	/**
	 * Directory Starter notice.
	 */
	public function ds_notice(){

		$action = 'install-theme';
		$slug = 'directory-starter';
		$install_url = wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'theme' => $slug,
					'ds_downgrade' => 1
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);

		$remove_notice_url = admin_url( 'index.php?ds_remove_downgrade_notice=1' );

		$class = 'notice notice-error';
		$title = __("Immediate attention required","directory-starter");
		$message = __( 'Directory Starter 2.1+ has some huge design improvements, if you are not ready for these or do not want them please downgrade to keep things as they were.', 'directory-starter' );

		$buttons = 	'<p>';
		$buttons .= '<a onclick="return confirm(\''.__("This will downgrade Directory Starter theme to the latest version 2.0","directory-starter").'\');" class="button button-primary" href="'.$install_url.'" target="_parent"><i class="fas fa-undo-alt"></i> '.__("Downgrade to latest v2.0","directory-starter").'</a></strong>';
		$buttons .= ' <a class="button button-secondary" href="'.$remove_notice_url.'" target="_parent">'.__("I'm OK with this, Remove Notice","directory-starter").'</a></strong>';
		$buttons .= '</p>';

		printf( '<div class="%1$s"><h2 style="font-size: 22px;font-weight: bold;color: red;">%2$s</h2><p><b>%3$s</b></p>%4$s</div>', esc_attr( $class ), esc_attr( $title ), esc_html( $message ),$buttons );

	}
}
new DS_theme_downgrade();