<?php
/**
 * A custom WordPress comment walker class to implement the Bootstrap 4 Media object in wordpress comment list.
 *
 * @package     WP Bootstrap 4 Comment Walker
 * @version     1.0.0
 * @author      Aymene Bourafai <bourafai.a@gmail.com> <www.aymenebourafai.com>
 * @license     http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link        https://github.com/bourafai/wp-bootstrap-4-comment-walker
 * @link        https://v4-alpha.getbootstrap.com/layout/media-object/
 */

class Bootstrap_Comment_Walker extends Walker_Comment {
	/**
	 * Output a comment in the HTML5 format.
	 *
	 * @since 1.0.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param object $comment the comments list.
	 * @param int    $depth   Depth of comments.
	 * @param array  $args    An array of arguments.
	 */
	protected function html5_comment( $comment, $depth, $args ) {

		$tag = ( $args['style'] === 'div' ) ? 'div' : 'li';
		$main_class = empty($comment->comment_parent) ? '' : 'mr-2';
		?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'has-children media' : ' media' ); ?>>


	<div class="media-body card mt-3 <?php echo DT_CONTENT_SHADOW; ?> mb-3 <?php echo $main_class;?>" id="div-comment-<?php comment_ID(); ?>">
		<div class="card-header border-bottom bg-white d-flex justify-content-between align-items-center">
			<div class="flex-grow-1 d-flex align-items-center">
				<?php if ( $args['avatar_size'] != 0  ): ?>
					<a href="<?php echo get_comment_author_url(); ?>" class="media-object float-left">
						<?php echo get_avatar( $comment, $args['avatar_size'],'mm','', array('class'=>"comment_avatar rounded-circle") ); ?>
					</a>
				<?php endif; ?>
				<h4 class="media-heading pl-2 m-0 h5"><?php echo get_comment_author_link() ?></h4>
			</div>
			<a class="hidden-xs-down text-muted text-right mr-2" href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
				<time class="chip timeago" datetime="<?php comment_time( 'c' ); ?>">
					<?php comment_date() ?>,
					<?php comment_time() ?>
				</time>
			</a>
			<div class="comment-links text-right">
				<?php
				edit_comment_link( __( 'Edit', 'directory-starter' ), '<span class="edit-link btn btn-link">', '</span>' );
				?>
				<span class="reply-link">
						<?php $reply_link = get_comment_reply_link( array_merge( $args, array(
							'reply_text' => __( 'Reply', 'directory-starter' ),
							'depth'      => $depth,
							'max_depth'  => $args['max_depth']
						) ) );
						echo str_replace("comment-reply-link","comment-reply-link btn btn-primary",$reply_link);
						?>
					</span>
			</div>
		</div>
		<div class="card-block warning-color p-4">
		<?php if ( '0' == $comment->comment_approved ) : ?>
			<p class="card-text comment-awaiting-moderation label label-info text-muted small"><?php _e( 'Your comment is awaiting moderation.','directory-starter' ); ?></p>
		<?php endif; ?>

		<div class="comment-content card-text">
			<?php comment_text(); ?>
		</div><!-- .comment-content -->


		</div>


		<?php


	}
}