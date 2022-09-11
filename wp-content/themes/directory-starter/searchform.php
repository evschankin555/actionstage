<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) );?>">
	<?php
		echo aui()->input(
			array(
				'name'			=> 's',
				'no_wrap'       => true,
				'type'			=> 'search',
				'value'         => get_search_query(),
				'label'         => _x( 'Search for:', 'label','directory-starter' ),
				'placeholder'	=> esc_attr_x( 'Search &hellip;', 'placeholder','directory-starter' ),
				'input_group_right' => aui()->button(
						array(
							'type'		=> 'submit',
							'class'		=> 'search-submit btn btn-primary',
							'content'	=>  esc_attr_x( 'Search', 'submit button','directory-starter' )
						)
					)
			)
		);
	?>
</form>