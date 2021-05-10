<?php
/**
 * Template Name: Home Menu
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * 
 * 
 *
 */


get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	
	<div class="entry-wrapper">
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php lk_custom_breadcrumbs(); ?>
		</header><!-- .entry-header -->
		<div class="entry-content">
			<?php the_content(); ?>

		<?php lk_list_child_pages(); ?>
		</div><!-- .entry-content -->
		
		<?php edit_post_link( __( 'Edit', 'story' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>

	
	</div><!-- .entry-wrapper -->
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
