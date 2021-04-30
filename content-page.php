<?php
/**
 * Template Name: Page-breadcrumbs
 *
 * @package Story
 */
?>




<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="entry-wrapper">
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php custom_breadcrumbs(); ?>
		</header><!-- .entry-header -->
		
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
		
		<?php edit_post_link( __( 'Edit', 'story' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
	</div><!-- .entry-wrapper -->

</article><!-- #post-## -->


