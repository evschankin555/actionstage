<?php

if ( ! class_exists( 'TLPTeamWidget' ) ):


	/**
	 *
	 */
	class TLPTeamWidget extends WP_Widget {

		/**
		 * TLP TEAM widget setup
		 */
		function __construct() {

			$widget_ops = array(
				'classname'   => 'widget_tlpTeam',
				'description' => __( 'Display the Team.', "tlp-team" )
			);
			parent::__construct( 'widget_tlpTeam', __( 'TPL Team', "tlp-team" ), $widget_ops );

		}

		/**
		 * display the widgets on the screen.
		 */
		function widget( $args, $instance ) {
			extract( $args );
			$member  = ! empty( $instance['member'] ) ? (int) $instance['member'] : null;
			$layout  = ! empty( $instance['layout'] ) ? (int) $instance['layout'] : 1;
			$col     = ! empty( $instance['col'] ) ? (int) $instance['col'] : 3;
			$orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : null;
			$order   = ! empty( $instance['order'] ) ? $instance['order'] : null;

			echo $before_widget;
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			echo do_shortcode( "[tlpteam layout='{$layout}' member='{$member}' col='{$col}' orderby='{$orderby}' order='{$order}' ]" );

			echo $after_widget;
		}

		function form( $instance ) {

			$defaults = array(
				'title'   => '',
				'member'  => 4,
				'layout'  => 1,
				'col'     => 3,
				'orderby' => null,
				'order'   => null,
			);

			$instance = wp_parse_args( (array) $instance, $defaults );
			?>
            <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:',
						"tlp-team" ); ?></label>
                <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"
                       style="width:100%;"/></p>

            <p><label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php _e( 'Select a layout',
						"tlp-team" ); ?></label>
                <select id="<?php echo $this->get_field_id( 'layout' ); ?>"
                        name="<?php echo $this->get_field_name( 'layout' ); ?>">
                    <option value="">Select one</option>
					<?php
					$layouts = TLPTeam()->scLayouts();
					foreach ( $layouts as $key => $layout ) {
						$selected = ( $key == $instance['layout'] ? "selected" : null );
						echo "<option value='{$key}' {$selected}>{$layout}</option>";
					}
					?>
                </select></p>
            <p><label for="<?php echo $this->get_field_id( 'col' ); ?>"><?php _e( 'Select a column',
						"tlp-team" ); ?></label>
                <select id="<?php echo $this->get_field_id( 'col' ); ?>"
                        name="<?php echo $this->get_field_name( 'col' ); ?>">
                    <option value="">Select one</option>
					<?php
					$cols = TLPTeam()->scColumns();
					foreach ( $cols as $key => $col ) {
						$selected = ( $key == $instance['col'] ? "selected" : null );
						echo "<option value='{$key}' {$selected}>{$col}</option>";
					}
					?>
                </select></p>

            <p><label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order by',
						"tlp-team" ); ?></label>
                <select id="<?php echo $this->get_field_id( 'orderby' ); ?>"
                        name="<?php echo $this->get_field_name( 'orderby' ); ?>">
                    <option value="">Select one</option>
					<?php
					$obs = TLPTeam()->scOrderBy();
					foreach ( $obs as $key => $ob ) {
						$selected = ( $key == $instance['orderby'] ? "selected" : null );
						echo "<option value='{$key}' {$selected}>{$ob}</option>";
					}
					?>
                </select></p>
            <p><label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order by',
						"tlp-team" ); ?></label>
                <select id="<?php echo $this->get_field_id( 'order' ); ?>"
                        name="<?php echo $this->get_field_name( 'order' ); ?>">
                    <option value="">Select one</option>
					<?php
					$orders = TLPTeam()->scOrder();
					foreach ( $orders as $key => $order ) {
						$selected = ( $key == $instance['order'] ? "selected" : null );
						echo "<option value='{$key}' {$selected}>{$order}</option>";
					}
					?>
                </select></p>
            <p><label for="<?php echo $this->get_field_id( 'member' ); ?>"><?php _e( 'Number of member to show:',
						"tlp-team" ); ?></label>
                <input type="text" size="2" id="<?php echo $this->get_field_id( 'member' ); ?>"
                       name="<?php echo $this->get_field_name( 'member' ); ?>"
                       value="<?php echo $instance['member']; ?>"/></p>


			<?php
		}

		public function update( $new_instance, $old_instance ) {

			$instance           = array();
			$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['member'] = ( ! empty( $new_instance['member'] ) ) ? (int) ( $new_instance['member'] ) : '';
			$instance['layout'] = ( ! empty( $new_instance['layout'] ) ) ? (int) ( $new_instance['layout'] ) : '';
			$instance['col'] = ( ! empty( $new_instance['col'] ) ) ? (int) ( $new_instance['col'] ) : '';
			$instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? ( $new_instance['orderby'] ) : '';
			$instance['order'] = ( ! empty( $new_instance['order'] ) ) ? ( $new_instance['order'] ) : '';

			return $instance;
		}


	}


endif;