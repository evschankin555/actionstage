<?php
function directory_theme_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
		?>
		<nav class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'directory-starter' ); ?></h2>
			<div class="nav-links">
				<?php
				if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'directory-starter' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;

				if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'directory-starter' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;
				?>
			</div><!-- .nav-links -->
		</nav><!-- .comment-navigation -->
		<?php
	endif;
}


add_filter( 'comment_form_defaults', 'ds_comment_form_defaults' );
function ds_comment_form_defaults($defaults){
	

	// comment field
	$defaults['comment_field'] = aui()->textarea(array(
		'name'       => 'comment',
		'class'      => '',
		'id'         => 'comment',
		'placeholder'=> esc_html__( "Enter your review comments here (required)...", 'directory-starter'),
		'required'   => true,
		'label'      => esc_html__( "Review text", 'directory-starter'),
		'rows'      => 8,
	));

	// author name
	$defaults['fields']['author'] = aui()->input(
		array(
			'id'                => 'author',
			'name'              => 'author',
			'required'          => true,
			'label'              => esc_html__( "Name", 'directory-starter'),
			'type'              => 'text',
			'placeholder'       => esc_html__( "Name (required)" , 'directory-starter'),
			'extra_attributes'  => array(
				'maxlength' => "245"
			)
		)
	);

	// author email
	$defaults['fields']['email'] = aui()->input(
		array(
			'id'                => 'email',
			'name'              => 'email',
			'required'          => true,
			'label'              => esc_html__( "Email", 'directory-starter'),
			'type'              => 'email',
			'placeholder'       => esc_html__( "Email (required)" , 'directory-starter'),
			'extra_attributes'  => array(
				'maxlength' => "100"
			)
		)
	);

	// website url
	$defaults['fields']['url'] = aui()->input(
		array(
			'id'                => 'url',
			'name'              => 'url',
			'required'          => true,
			'label'              => esc_html__( "Website", 'directory-starter'),
			'type'              => 'url',
			'placeholder'       => esc_html__( "Website" , 'directory-starter'),
			'extra_attributes'  => array(
				'maxlength' => "200"
			)
		)
	);

	// website url
	$defaults['fields']['cookies'] = aui()->input(
		array(
			'id'                => 'wp-comment-cookies-consent',
			'name'              => 'wp-comment-cookies-consent',
			'required'          => true,
			'value'             => 'yes',
			'label'              => esc_html__( "Save my name, email, and website in this browser for the next time I comment.", 'directory-starter'),
			'type'              => 'checkbox',
		)
	);

	// logged in as
	$defaults['logged_in_as'] = str_replace("logged-in-as","logged-in-as mb-3",$defaults['logged_in_as'] );

	// logged out notes
	$defaults['comment_notes_before'] = aui()->alert(array(
			'type'=> 'info',
			'content'=> __("Your email address will not be published.","directory-starter")
		)
	);

	$defaults['class_submit'] .= " btn btn-primary form-control text-white";

	$defaults['submit_field'] = '<div class="form-submit form-group">%1$s %2$s</div>';

	$defaults['class_submit'] .= " btn btn-primary form-control text-white";

	$defaults['submit_field'] = '<div class="form-submit form-group">%1$s %2$s</div>';

	return $defaults;
}