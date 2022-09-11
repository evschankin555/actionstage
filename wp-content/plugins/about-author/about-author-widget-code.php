<?php
/**
 * Adds widget.
 */
class About_Author extends WP_Widget
{
	public function __construct() {
		parent::__construct(
			'about_me', 		// Base ID
			'About Author', 	// Name
			array( 
				'description' => 'An efficient way to display author information on your WordPress blog.', 'WEBLIZAR_ABOUT_DOMAIN'  
			) // Args
		);
	}
	
	/*
	* Front-end display of widget.
	*/
	public function widget( $args, $instance ) {
		$Title =  apply_filters( 'Weblizar_widget_title', $instance['Title'] );
		echo wp_kses_post($args['before_widget']);

		$WeblizarId	=   apply_filters( 'weblizar_widget_shortcode', $instance['Shortcode'] );

		if(is_numeric($WeblizarId)) {
			if ( ! empty( $instance['Title'] ) ) {
				echo wp_kses_post($args['before_title'] . apply_filters( 'widget_title', $instance['Title'] ). $args['after_title']);
			}
			echo do_shortcode( '[Weblizar id='.$WeblizarId.']' );
		} else {
			echo "<p>". esc_html_e("Sorry! No About Author Shortcode Found.", 'WEBLIZAR_ABOUT_DOMAIN')."</p>";
		}
		echo wp_kses_post($args['after_widget']);
		wp_reset_query();
	}

	/**
	* Back-end widget form.
	*/
	public function form( $instance ) {

		if ( isset( $instance[ 'Title' ] ) ) {
			$Title = $instance[ 'Title' ];
		} else {
			$Title = "About Author";
		}

		if ( isset( $instance[ 'Shortcode' ] ) ) {
			$Shortcode = $instance[ 'Shortcode' ];
		} else {
			$Shortcode = "Select Any About Author Shortcode";
		}
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'Title' )); ?>"><?php esc_html_e( 'About Author Widget Title','WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'Title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'Title' )); ?>" type="text" value="<?php echo esc_attr( $Title ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'Shortcode' )); ?>"><?php esc_html_e( 'Select Any','WEBLIZAR_ABOUT_DOMAIN' ); ?> <?php esc_html_e( '(Required)','WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
			<?php
			/**
			 * Get All about_author Shortcode Custom Post Type
			 */
			$Weblizar_CPT_Name = "about_author";
			$Weblizar_All_Posts = wp_count_posts( $Weblizar_CPT_Name )->publish;
			global $All_Weblizar;
			$All_Weblizar = array('post_type' => $Weblizar_CPT_Name, 'orderby' => 'ASC', 'posts_per_page' => $Weblizar_All_Posts);
			$All_Weblizar = new WP_Query( $All_Weblizar );
			?>
			<select id="<?php echo esc_attr($this->get_field_id( 'Shortcode' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'Shortcode' )); ?>" style="width: 100%;">
				<option value="Select Any About Author Shortcode" <?php if($Shortcode == "Select Any About Author Shortcode") echo 'selected="selected"'; ?>><?php esc_html_e( 'Select Any About Author Shortcode', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
				<?php
				if( $All_Weblizar->have_posts() ) {	 ?>
				<?php while ( $All_Weblizar->have_posts() ) : $All_Weblizar->the_post();
				$PostId = get_the_ID();
				$PostTitle = get_the_title($PostId);
				?>
				<option value="<?php echo esc_attr($PostId); ?>" <?php if($Shortcode == $PostId) echo 'selected="selected"'; ?>><?php if($PostTitle) esc_html_e($PostTitle); else esc_html_e("No Title", 'WEBLIZAR_ABOUT_DOMAIN'); ?></option>
				<?php endwhile; ?>
				<?php
				} else {
					echo "<option>". esc_html_e("Sorry! No About Author Shortcode Found.", 'WEBLIZAR_ABOUT_DOMAIN') ."</option>";
				}
				?>
			</select>
		</p>

		<?php
	}
	public  function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['Title'] = ( ! empty( $new_instance['Title'] ) ) ? strip_tags( $new_instance['Title'] ) : '';
		$instance['Shortcode'] = ( ! empty( $new_instance['Shortcode'] ) ) ? strip_tags( $new_instance['Shortcode'] ) : 'Select Any About Author Shortcode';
		return $instance;
	}
} // end of class Instagram Shortcode Pro Widget Class

// Register About Author Widget
add_action( 'widgets_init', 'register_About_Me' );
function register_About_Me() {
	register_widget( 'About_Author' );
}
?>