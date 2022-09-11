<?php
if ( ! class_exists( 'TLPTeamOptions' ) ) :

	class TLPTeamOptions {

		function get_sc_layout_settings_meta_fields() {
			return array(
				"layout"                        => array(
					"label"   => __( 'Layout', 'tlp-team' ),
					"type"    => "select",
					"class"   => "tlp-select",
					"options" => $this->scLayouts()
				),
				'ttp_column'                    => array(
					'type'    => 'multiple_options',
					'label'   => __( 'Column', 'tlp-team' ),
					'options' => array(
						"desktop" => array(
							'type'    => 'select',
							'class'   => 'tlp-select',
							'label'   => __( 'Desktop', 'tlp-team' ),
							'options' => $this->scColumns(),
							'default' => 4
						),
						"tab"     => array(
							'type'    => 'select',
							'class'   => 'tlp-select',
							'label'   => __( 'Tab', 'tlp-team' ),
							'options' => $this->scColumns(),
							'default' => 2
						),
						"mobile"  => array(
							'type'    => 'select',
							'class'   => 'tlp-select',
							'label'   => __( 'Mobile', 'tlp-team' ),
							'options' => $this->scColumns(),
							'default' => 1
						)
					)
				),
				'ttp_carousel_speed'            => array(
					"label"       => __( "Speed", 'tlp-team' ),
					"holderClass" => "tlp-hidden tlp-carousel-item",
					"type"        => "number",
					'default'     => 250,
					"description" => __( 'Auto play Speed in milliseconds', 'tlp-team' ),
				),
				'ttp_carousel_options'          => array(
					"label"       => __( "Carousel Options", 'tlp-team' ),
					"holderClass" => "tlp-hidden tlp-carousel-item",
					"type"        => "checkbox",
					"multiple"    => true,
					"alignment"   => "vertical",
					"options"     => $this->owlProperty(),
					"default"     => array( 'autoplay', 'arrows', 'dots', 'responsive', 'infinite' ),
				),
				'ttp_carousel_autoplay_timeout' => array(
					"label"       => __( "Autoplay timeout", 'tlp-team' ),
					"holderClass" => "tlp-hidden tlp-carousel-item tlp-carousel-auto-play-timeout",
					"type"        => "number",
					'default'     => 5000,
					"description" => __( 'Autoplay interval timeout', 'tlp-team' ),
				),
				'ttp_pagination'                => array(
					"type"        => "checkbox",
					"label"       => __( "Pagination", 'tlp-team' ),
					'holderClass' => "tlp-pagination-item pagination tlp-hidden",
					"optionLabel" => __( 'Enable', 'tlp-team' ),
					"option"      => 1
				),
				'ttp_posts_per_page'            => array(
					"type"        => "number",
					"label"       => __( "Display per page", 'tlp-team' ),
					'holderClass' => "tlp-pagination-item tlp-hidden",
					"default"     => 5,
					"description" => __( "If value of Limit setting is not blank (empty), this value should be smaller than Limit value.",
						'tlp-team' )
				),
				'ttp_image'                     => array(
					"type"        => "checkbox",
					"label"       => __( "Feature Image", 'tlp-team' ),
					"optionLabel" => __( 'Disable', 'tlp-team' ),
					"option"      => 1
				),
				'ttp_image_size'                => array(
					"type"        => "select",
					"label"       => __( "Image Size", 'tlp-team' ),
					"class"       => "tlp-select",
					'holderClass' => "tlp-feature-image-option",
					"options"     => TLPTeam()->get_image_sizes()
				),
				'ttp_custom_image_size'         => array(
					"type"        => "image_size",
					"label"       => __( "Custom Image Size", 'tlp-team' ),
					'holderClass' => "tlp-feature-image-option tlp-hidden",
				),
				'character_limit'               => array(
					"type"        => "number",
					"class"       => 'small-text',
					"label"       => __( "Short description limit", 'tlp-team' ),
					"description" => __( "Short description limit only integer number is allowed, Leave it blank for full text.<br> <span style='color: red;'>Also HTML TAGS will not work if you use limit.</span>",
						'tlp-team' )
				),
				'disable_detail_page_link'      => array(
					"type"        => "checkbox",
					"label"       => __( "Detail page link", 'tlp-team' ),
					"optionLabel" => __( "Disable", 'tlp-team' ),
					"option"      => 1
				),
			);
		}

		function get_sc_query_filter_meta_fields() {

			return array(
				'ttp_post__in'     => array(
					"label"       => __( "Include only", 'tlp-team' ),
					"type"        => "select",
					"class"       => "tlp-select",
					"description" => __( 'Select the member you want to display',
						'tlp-team' ),
					"multiple"    => true,
					"options"     => TLPTeam()->getMemberList()
				),
				'ttp_post__not_in' => array(
					"label"       => __( "Exclude", 'tlp-team' ),
					"type"        => "select",
					"class"       => "tlp-select",
					"description" => __( 'Select the member you want to hide',
						'tlp-team' ),
					"multiple"    => true,
					"options"     => TLPTeam()->getMemberList()
				),
				'ttp_limit'        => array(
					"label"       => __( "Limit", 'tlp-team' ),
					"type"        => "number",
					"description" => __( 'The number of posts to show. Set empty to show all found posts.',
						'tlp-team' )
				),
				'order_by'         => array(
					"label"   => __( "Order By", 'tlp-team' ),
					"type"    => "select",
					"class"   => "tlp-select",
					"default" => "date",
					"options" => $this->scOrderBy()
				),
				'order'            => array(
					"label"     => __( "Order", 'tlp-team' ),
					"type"      => "radio",
					"options"   => $this->scOrder(),
					"default"   => "DESC",
					"alignment" => "vertical",
				),
			);
		}

		function get_sc_field_style_meta() {
			return array(
				'ttp_parent_class' => array(
					"type"        => "text",
					"label"       => "Parent class",
					"class"       => "medium-text",
					"description" => "Parent class for adding custom css"
				),
				'primary_color'    => array(
					"type"    => "text",
					"label"   => "Primary Color",
					"class"   => "tlp-color",
					"default" => "rgba(3,103,191,0.8)",
					"alpha"   => true
				),
				'ttp_button_style' => array(
					"type"    => "multiple_options",
					"label"   => "Button color",
					"options" => array(
						'bg'         => array(
							'type'  => 'color',
							'label' => 'Background'
						),
						'hover_bg'   => array(
							'type'  => 'color',
							'label' => 'Hover background'
						),
						'active_bg'  => array(
							'type'  => 'color',
							'label' => 'Active background'
						),
						'text'       => array(
							'type'  => 'color',
							'label' => 'Text'
						),
						'hover_text' => array(
							'type'  => 'color',
							'label' => 'Hover text'
						),
						'border'     => array(
							'type'  => 'color',
							'label' => 'Border'
						)
					)
				),
				'name'             => array(
					'type'    => 'multiple_options',
					'label'   => __( 'Name', 'tlp-team' ),
					'options' => $this->scStyleOptions()
				),
				'designation'      => array(
					'type'    => 'multiple_options',
					'label'   => __( 'Designation', 'tlp-team' ),
					'options' => $this->scStyleOptions()
				),
				'short_bio'        => array(
					'type'    => 'multiple_options',
					'label'   => __( 'Short biography', 'tlp-team' ),
					'options' => $this->scStyleOptions()
				)
			);
		}

		function opacity() {
			return array(
				'0.1' => "10 %",
				'0.2' => "20 %",
				'0.3' => "30 %",
				'0.4' => "40 %",
				'0.5' => "50 %",
				'0.6' => "60 %",
				'0.7' => "70 %",
				'0.8' => "80 %",
				'0.9' => "90 %",
			);
		}

		function imageCropType() {
			return array(
				'soft' => __( "Soft Crop", "tlp-team" ),
				'hard' => __( "Hard Crop", "tlp-team" )
			);
		}

		private function scStyleOptions( $items = array( 'color', 'hover_color', 'size', 'weight', 'align' ) ) {
			$fields = array();
			if ( in_array( 'color', $items ) ) {
				$fields['color'] = array(
					'type'     => 'color',
					'col_size' => 4,
					'label'    => __( 'Color', 'tlp-team' ),
				);
			}
			if ( in_array( 'hover_color', $items ) ) {
				$fields['hover_color'] = array(
					'type'     => 'color',
					'col_size' => 4,
					'label'    => __( 'Hover color', 'tlp-team' ),
				);
			}
			if ( in_array( 'size', $items ) ) {
				$fields['size'] = array(
					'type'     => 'select',
					'label'    => __( 'Font size', 'tlp-team' ),
					'col_size' => 4,
					'class'    => 'tlp-select',
					'blank'    => __( 'Default', 'tlp-team' ),
					'options'  => $this->scFontSize()
				);
			}
			if ( in_array( 'weight', $items ) ) {
				$fields['weight'] = array(
					'type'     => 'select',
					'label'    => __( 'Weight', 'tlp-team' ),
					'col_size' => 4,
					'class'    => 'tlp-select',
					'blank'    => __( 'Default', 'tlp-team' ),
					'options'  => $this->scTextWeight()
				);
			}
			if ( in_array( 'align', $items ) ) {
				$fields['align'] = array(
					'type'     => 'select',
					'label'    => __( 'Alignment', 'tlp-team' ),
					'col_size' => 4,
					'blank'    => __( 'Default', 'tlp-team' ),
					'class'    => 'tlp-select',
					'options'  => $this->scAlignment()
				);
			}

			return $fields;
		}

		function socialLink() {
			return array(
				'facebook'    => __( 'Facebook', "tlp-team" ),
				'twitter'     => __( 'Twitter', "tlp-team" ),
				'linkedin'    => __( 'LinkedIn', "tlp-team" ),
				'youtube'     => __( 'Youtube', "tlp-team" ),
				'vimeo'       => __( 'Vimeo', "tlp-team" ),
				'google-plus' => __( 'Google+', "tlp-team" ),
				'instagram'   => __( 'Instagram', "tlp-team" )
			);
		}

		private function scFontSize() {
			$num = array();
			for ( $i = 10; $i <= 60; $i ++ ) {
				$num[ $i ] = $i . "px";
			}

			return $num;
		}

		private function scTextWeight() {
			return array(
				'normal'  => "Normal",
				'bold'    => "Bold",
				'bolder'  => "Bolder",
				'lighter' => "Lighter",
				'inherit' => "Inherit",
				'initial' => "Initial",
				'unset'   => "Unset",
				100       => '100',
				200       => '200',
				300       => '300',
				400       => '400',
				500       => '500',
				600       => '600',
				700       => '700',
				800       => '800',
				900       => '900',
			);
		}

		private function scAlignment() {
			return array(
				'left'    => "Left",
				'right'   => "Right",
				'center'  => "Center",
				'justify' => "Justify"
			);
		}

		function scColumns() {
			return array(
				1 => "1 Column",
				2 => "2 Column",
				3 => "3 Column",
				4 => "4 Column",
				5 => "5 Column",
				6 => "6 Column",
			);
		}

		function owlProperty() {
			return array(
				'loop'               => __( 'Loop', 'tlp-team' ),
				'autoplay'           => __( 'Auto Play', 'tlp-team' ),
				'autoplayHoverPause' => __( 'Pause on mouse hover', 'tlp-team' ),
				'nav'                => __( 'Nav Button', 'tlp-team' ),
				'dots'               => __( 'Pagination', 'tlp-team' ),
				'autoHeight'         => __( 'Auto Height', 'tlp-team' ),
				'lazyLoad'           => __( 'Lazy Load', 'tlp-team' ),
				'rtl'                => __( 'Left to Right (RTL)', 'tlp-team' )
			);
		}

		function scLayouts() {
			return array(
				"layout1"   => "Layout 1",
				"layout2"   => "Layout 2",
				"layout3"   => "Layout 3",
				"layout4"   => "Layout 4",
				'isotope1'  => "Isotope Layout",
				'carousel1' => "Carousel Slider Layout",
			);
		}

		function oldScLayouts() {
			return array(
				1          => "Layout 1",
				2          => "Layout 2",
				3          => "Layout 3",
				4          => "Layout 4",
				'isotope'  => "Isotope Layout",
				'carousel' => "Carousel Slider Layout",
			);
		}

		function scOrderBy() {
			return array(
				'menu_order' => "Menu Order",
				'title'      => "Name",
				'ID'         => "ID",
				'date'       => "Date"
			);
		}

		function scOrder() {
			return array(
				'ASC'  => __( "Ascending", "tlp-team" ),
				'DESC' => __( "Descending", "tlp-team" ),
			);
		}

		function tlp_filter_list() {
			return array(
				'_taxonomy_filter' => 'Taxonomy filter',
				'_order_by'        => 'Order - Sort retrieved posts by parameter',
				'_sort_order'      => 'Sort Order - Designates the ascending or descending order of the "orderby" parameter',
				'_search'          => "Search filter",
			);
		}

		function pro_features_list() {
			$html = '
			<div class="rt-features-wrap">
                <h2 class="item-title">TLP Team<span>Pro</span>Features</h2>
                <ul class="list-item">
                    <li>Full Responsive and Mobile Friendly.</li>
                    <li>33 Layouts (Grid, Table, Isotope & Carousel).</li>
                    <li>100+ Layout variation.</li>
                    <li>Square / Rounded Image Style.</li>
                    <li>Social icon, color size and background color control.</li>
                    <li>Fully translatable (POT files included (/languages/))</li>
                    <li>Detail Page Field control (New).</li>
                    <li>All 14 layouts now can turn as Grid or Filter (New V 2.0).</li>
                    <li>Improve Code & AJAX functionality (New V 2.0).</li>
                    <li>Now Filter as Button or Drop down (New V 2.0).</li>
                    <li>Layout Preview on Shortcode Generator(New V 2.0).</li>
                    <li>Short by & Ordering option (New V 2.0).</li>
                    <li>Gutter or Padding Control (New V 2.0).</li>
                    <li>GrayScale option added (New V 2.0).</li>
                    <li>Single Member Popup (New).</li>
                    <li>Multiple Designation (New).</li>
                    <li>Added additional image for gallery (New).</li>
                    <li>Skill fields with progress bar.</li>
                    <li>Pagination (You can set how many show per page)</li>
                    <li>Assign member as user (New).</li>
                    <li>Member Latest post show in detail/popup page (New).</li>
                    <li>Order by Random (New).</li>
                    <li>Dynamic Image Re-size added (New V 2.0).</li>
                    <li>Taxonomy Ordering ie Department, Designation added (New V 2.0).</li>
                </ul>
                <a href="https://radiustheme.com/tlp-team-pro-for-wordpress/" class="rt-admin-btn button button-primary"
                      target="_blank">Get Pro Version</a>
            </div>';

			return $html;
		}

	}
endif;
